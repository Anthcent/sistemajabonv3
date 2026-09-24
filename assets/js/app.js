
document.addEventListener('alpine:init', () => {

    // Helper for SweetAlert
    const notify = (icon, title, text = '') => {
        Swal.fire({
            icon: icon,
            title: title,
            text: text,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            background: '#1e293b',
            color: '#fff'
        });
    };

    const confirmAction = async (title, text) => {
        const result = await Swal.fire({
            title: title,
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#0ea5e9',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, continuar',
            cancelButtonText: 'Cancelar',
            background: '#0f172a',
            color: '#fff'
        });
        return result.isConfirmed;
    };

    // Inventory Management Component
    Alpine.data('inventory', (mode = 'all') => ({
        mode: mode,
        products: [],
        categories: [],
        cloneResults: [],
        cloneSearch: '',
        formMode: 'create', // create | edit
        margin: 0,

        // Filters
        search: '',
        filterCategory: '',

        // Searchable Category Dropdown State
        catSearch: '',
        showCatDropdown: false,
        selectedCatName: '',

        product: {
            id: null,
            sku: '',
            name: '',
            category_id: '',
            brand: '',
            is_liquid: true,
            is_liquid: true,
            is_raw_material: false,
            use_special_rate: false,
            display_unit: 'Litro',
            cost_price: '',
            price: '',
            stock: '',
            min_stock: 10,
            barcode: '',
            image: null,
            stock_adjust: ''
        },
        imagePreview: null,
        // Camera state removed

        async init() {
            await this.fetchProducts();
            await this.fetchCategories();
            await this.fetchCategories();
            await this.fetchSettings();
            this.checkUrlForEdit();
        },

        config: {},
        async fetchSettings() {
            const res = await fetch('api/settings.php');
            const json = await res.json();
            if (json.status === 'success') {
                this.config = {
                    ...json.data,
                    exchange_rate_global: parseFloat(json.data.exchange_rate_global),
                    exchange_rate_special: parseFloat(json.data.exchange_rate_special)
                };
            }
        },

        get convertedPrice() {
            if (this.config.main_currency !== 'USD') return '';
            const price = parseFloat(this.product.price) || 0;
            const rate = this.product.use_special_rate ? this.config.exchange_rate_special : this.config.exchange_rate_global;
            const converted = price * rate;
            return `Bs ${converted.toLocaleString('es-VE', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
        },

        checkUrlForEdit() {
            const urlParams = new URLSearchParams(window.location.search);
            const editId = urlParams.get('edit');
            if (editId) {
                // Wait for products to load, then find
                const interval = setInterval(() => {
                    if (this.products.length > 0) {
                        const item = this.products.find(p => p.id == editId);
                        if (item) {
                            this.loadForEdit(item);
                            // Clean URL
                            window.history.replaceState({}, document.title, window.location.pathname);
                        }
                        clearInterval(interval);
                    }
                }, 100);
            }
        },

        // Filter categories for the custom dropdown
        get filteredCategories() {
            if (!this.catSearch) return this.categories;
            return this.categories.filter(c => c.name.toLowerCase().includes(this.catSearch.toLowerCase()));
        },

        selectCategory(cat) {
            this.product.category_id = cat.id;
            this.selectedCatName = cat.name;
            this.showCatDropdown = false;
            this.catSearch = '';
        },

        async fetchProducts() {
            // mode can be 'all', 'for_sale', 'raw_material'
            const res = await fetch(`api/products.php?action=list&type=${this.mode}`);
            const json = await res.json();
            if (json.status === 'success') this.products = json.data;
        },

        async fetchCategories() {
            const res = await fetch('api/categories.php?action=list');
            const json = await res.json();
            if (json.status === 'success') this.categories = json.data;
        },

        get filteredProducts() {
            let result = this.products;

            // Text Search
            if (this.search) {
                const term = this.search.toLowerCase();
                result = result.filter(p =>
                    p.name.toLowerCase().includes(term) ||
                    (p.sku && p.sku.toLowerCase().includes(term))
                );
            }

            // Category Filter
            if (this.filterCategory) {
                result = result.filter(p => p.category_id == this.filterCategory);
            }

            return result;
        },

        setLiquid(val) {
            this.product.is_liquid = val;
            if (val) this.product.display_unit = 'Litro';
            else this.product.display_unit = 'Unidad';
        },

        addStock(qty) {
            let current = parseFloat(this.product.stock) || 0;
            this.product.stock = (current + parseFloat(qty)).toFixed(3);
        },

        calculateMargin() {
            const cost = parseFloat(this.product.cost_price) || 0;
            const price = parseFloat(this.product.price) || 0;
            if (price > 0 && cost > 0) {
                // Formula: ((Price - Cost) / Price) * 100
                // This gives Profit Margin %.
                // If you want Markup %, it would be ((Price - Cost) / Cost) * 100
                // Let's stick to Profit Margin as requested.
                this.margin = (((price - cost) / price) * 100).toFixed(1);
            } else {
                this.margin = 0;
            }
        },

        handleImage(event) {
            const file = event.target.files[0];
            if (file) {
                // Validación de tamaño (Max 4MB)
                const maxSize = 4 * 1024 * 1024; // 4MB en bytes
                if (file.size > maxSize) {
                    notify('error', 'Imagen muy pesada', 'La imagen no debe superar los 4MB.');
                    event.target.value = ''; // Limpiar input
                    return;
                }

                this.product.image = file;
                this.imagePreview = URL.createObjectURL(file);
            }
        },

        // Métodos de cámara eliminados para usar cámara nativa
        startCamera() {
            // Deprecated
        },
        stopCamera() {
            // Deprecated
        },
        capturePhoto() {
            // Deprecated
        },

        // Clean Reset
        resetForm() {
            this.formMode = 'create';
            this.product = {
                id: null,
                sku: '',
                name: '',
                category_id: '',
                category_id: '',
                brand: '',
                is_liquid: true,
                is_liquid: true,
                is_raw_material: this.mode === 'raw_material',
                use_special_rate: false,
                display_unit: 'Litro',
                cost_price: '',
                price: '',
                stock: '',
                min_stock: 10,
                barcode: '',
                image: null,
                stock_adjust: ''
            };
            this.imagePreview = null;
            this.margin = 0;
            this.cloneSearch = '';
            this.selectedCatName = '';
            // Reset file input value
            const input = document.getElementById('fileInput');
            if (input) input.value = '';
            const camInput = document.getElementById('cameraInput');
            if (camInput) camInput.value = '';
        },

        searchForClone() {
            if (this.cloneSearch.length < 2) {
                this.cloneResults = [];
                return;
            }
            const term = this.cloneSearch.toLowerCase();
            this.cloneResults = this.products.filter(p =>
                p.name.toLowerCase().includes(term) ||
                (p.sku && p.sku.toLowerCase().includes(term))
            ).slice(0, 5);
        },

        fillForm(item) {
            // When cloning, we want to allow new image upload.
            // We set imagePreview to null so the "Click to upload" placeholder appears,
            // OR we can show the old one but allow click.
            // The user complained "no me deja agregar imagen" (won't let me add image).
            // This suggests the upload click handler might be blocked or the input not resetting.

            this.product = {
                ...item,
                id: null, // New ID
                name: item.name + ' (Copia)',
                stock: '',
                category_id: item.category_id || '',
                is_liquid: item.is_liquid == 1,
                is_raw_material: item.is_raw_material == 1,
                use_special_rate: item.use_special_rate == 1,
                image: null, // New file object is null
                barcode: '',
                sku: '' // Clear SKU to avoid duplicate
            };

            // Set preview to old image just for visual reference, BUT ensure file input is clear
            // Actually, to make it super clear "add new image", maybe we shouldn't show the old preview?
            // User request usually implies they want to register a SIMILAR product but likely valid to change image.
            // Let's keep the preview but ensure the DIV CLICK works.
            this.imagePreview = item.image_path ? item.image_path : null;

            // Fix Margin
            this.calculateMargin();
            this.selectedCatName = item.category_name || '';

            this.formMode = 'create';
            this.cloneResults = [];
            this.cloneSearch = '';

            // Ensure File Input is reset
            const input = document.getElementById('fileInput');
            if (input) input.value = '';

            Swal.fire({ icon: 'info', title: 'Datos Copiados', text: 'Puedes editar y agregar la nueva imagen.', toast: true, position: 'top-end', timer: 3000, showConfirmButton: false });
        },

        loadForEdit(item) {
            this.product = {
                ...item,
                is_liquid: item.is_liquid == 1,
                is_raw_material: item.is_raw_material == 1,
                use_special_rate: item.use_special_rate == 1,
                category_id: item.category_id || '',
                image: null // Reset file input
            };
            this.selectedCatName = item.category_name || ''; // Update UI text
            this.product.stock = parseFloat(item.stock_quantity).toFixed(2);
            this.product.min_stock = parseFloat(item.min_stock); // Remove trailing zeros
            this.imagePreview = item.image_path ? item.image_path : null;
            this.calculateMargin();
            this.formMode = 'edit';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },

        async saveProduct() {
            if (!this.product.name || !this.product.price) {
                notify('error', 'Faltan datos');
                return;
            }

            const formData = new FormData();
            formData.append('action', 'save');
            formData.append('id', this.product.id || '');
            formData.append('sku', this.product.sku);
            formData.append('name', this.product.name);
            formData.append('brand', this.product.brand);
            formData.append('category_id', this.product.category_id || '');
            formData.append('is_liquid', this.product.is_liquid ? 1 : 0);
            formData.append('is_raw_material', this.product.is_raw_material ? 1 : 0);
            formData.append('use_special_rate', this.product.use_special_rate ? 1 : 0);
            formData.append('display_unit', this.product.display_unit);
            formData.append('cost_price', this.product.cost_price);
            formData.append('price', this.product.price);

            formData.append('min_stock', this.product.min_stock);
            formData.append('barcode', this.product.barcode || '');

            if (this.product.image) {
                formData.append('image', this.product.image);
            }

            if (this.formMode === 'create') {
                formData.append('stock', this.product.stock || 0);
            }

            const res = await fetch('api/products.php', { method: 'POST', body: formData });
            const json = await res.json();

            if (json.status === 'success') {
                notify('success', json.message);
                this.fetchProducts();
                if (this.formMode === 'create') this.resetForm();
            } else {
                notify('error', 'Error', json.message);
            }
        },

        async quickStock(product) {
            const { value: amount } = await Swal.fire({
                title: `Agregar Stock`,
                text: `Ingresa la cantidad a agregar en ${product.display_unit}`,
                input: 'number',
                inputAttributes: { step: '0.01' },
                showCancelButton: true,
                background: '#1e293b',
                color: '#fff'
            });

            if (amount) {
                const formData = new FormData();
                formData.append('action', 'update_stock');
                formData.append('id', product.id);
                formData.append('amount', amount);

                const res = await fetch('api/products.php', { method: 'POST', body: formData });
                const json = await res.json();

                if (json.status === 'success') {
                    notify('success', 'Stock actualizado');
                    this.fetchProducts();
                } else {
                    notify('error', json.message);
                }
            }
        },

        // Manual Stock Adjustment from Edit Form
        async adjustStock(type) {
            // type: 'add' | 'subtract'
            if (!this.product.stock_adjust || parseFloat(this.product.stock_adjust) <= 0) {
                notify('error', 'Ingresa una cantidad válida');
                return;
            }

            const amount = parseFloat(this.product.stock_adjust);
            const finalAmount = type === 'subtract' ? -amount : amount;

            const formData = new FormData();
            formData.append('action', 'update_stock');
            formData.append('id', this.product.id);
            formData.append('amount', finalAmount);

            try {
                const res = await fetch('api/products.php', { method: 'POST', body: formData });
                const json = await res.json();

                if (json.status === 'success') {
                    notify('success', type === 'subtract' ? 'Stock reducido' : 'Stock agregado');
                    // Reload product data to reflect new stock
                    this.product.stock_adjust = ''; // Clear input

                    // We need to refresh the list AND the current product stock display
                    await this.fetchProducts();
                    const updated = this.products.find(p => p.id == this.product.id);
                    if (updated) {
                        this.product.stock = parseFloat(updated.stock_quantity).toFixed(2);
                    }
                } else {
                    notify('error', 'Error', json.message);
                }
            } catch (e) {
                notify('error', 'Error de conexión');
            }
        },

        async deleteProduct(id) {
            if (await confirmAction('¿Eliminar producto?', 'Esta acción no se puede deshacer.')) {
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('id', id);
                await fetch('api/products.php', { method: 'POST', body: formData });
                notify('success', 'Producto eliminado');
                this.fetchProducts();
            }
        }
    }));

    // Settings Component
    Alpine.data('settings', () => ({
        config: {
            company_name: '',
            nit_ruc_nif: '',
            address: '',
            phone: '',
            main_currency: 'VES',
            exchange_rate_global: 1,
            exchange_rate_special: 1,
            backup_path: 'backups/',
            backup_frequency: 24
        },
        backupProcessing: false,
        backups: [],
        restoreUploading: false,

        formatDate(d) {
            if (!d) return '';
            return new Date(d).toLocaleString('es-VE');
        },

        async init() {
            await this.fetchSettings();
            await this.fetchBackups();
        },

        async fetchBackups() {
            const res = await fetch('api/backup.php?action=list');
            const json = await res.json();
            if (json.status === 'success') {
                this.backups = json.data;
            }
        },

        async manualBackup() {
            this.backupProcessing = true;
            try {
                const res = await fetch('api/backup.php?action=manual', { method: 'POST' });
                const json = await res.json();
                if (json.status === 'success') {
                    notify('success', 'Respaldo Completado', json.full ? 'Respaldo Completo (BD + Imágenes)' : 'Respaldo Base de Datos');
                    this.fetchSettings();
                    this.fetchBackups();
                } else {
                    notify('error', 'Error', json.message);
                }
            } catch (e) {
                notify('error', 'Error de conexión');
            } finally {
                this.backupProcessing = false;
            }
        },

        async deleteBackup(filename) {
            if (!await confirmAction('¿Eliminar Respaldo?', 'Esta acción no se puede deshacer.')) return;

            const formData = new FormData();
            formData.append('action', 'delete');
            formData.append('filename', filename);

            const res = await fetch('api/backup.php', { method: 'POST', body: formData });
            const json = await res.json();
            if (json.status === 'success') {
                notify('success', 'Eliminado');
                this.fetchBackups();
            } else {
                notify('error', json.message);
            }
        },

        async restoreBackup(filename) {
            if (!await confirmAction('¿Restaurar Sistema?', 'Se reemplazarán los datos actuales por esta copia. Se recomienda hacer un respaldo actual antes.')) return;

            const formData = new FormData();
            formData.append('action', 'restore');
            formData.append('filename', filename);

            // Show loading or blocking UI
            let loading = Swal.fire({ title: 'Restaurando...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

            try {
                const res = await fetch('api/backup.php', { method: 'POST', body: formData });
                const json = await res.json();
                if (json.status === 'success') {
                    notify('success', 'Sistema Restaurado');
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    notify('error', 'Error', json.message);
                }
            } catch (e) {
                notify('error', 'Error crítico al restaurar');
            }
        },

        async uploadRestore(event) {
            const file = event.target.files[0];
            if (!file) return;

            if (!await confirmAction('¿Importar y Restaurar?', 'Se reemplazarán los datos con el archivo subido (' + file.name + ').')) {
                event.target.value = ''; // Reset
                return;
            }

            this.restoreUploading = true;
            const formData = new FormData();
            formData.append('action', 'restore');
            formData.append('backup_file', file);

            try {
                const res = await fetch('api/backup.php', { method: 'POST', body: formData });
                const json = await res.json();
                if (json.status === 'success') {
                    notify('success', 'Restauración Completada');
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    notify('error', json.message);
                }
            } catch (e) {
                notify('error', 'Error al subir/restaurar');
            } finally {
                this.restoreUploading = false;
                event.target.value = '';
            }
        },


        async fetchSettings() {
            const res = await fetch('api/settings.php');
            const json = await res.json();
            if (json.status === 'success') {
                this.config = {
                    ...json.data,
                    // Ensure numbers for inputs
                    exchange_rate_global: parseFloat(json.data.exchange_rate_global),
                    exchange_rate_special: parseFloat(json.data.exchange_rate_special)
                };
            }
        },

        async saveSettings() {
            const formData = new FormData();
            formData.append('action', 'save');
            // spread config into formdata
            for (const key in this.config) {
                formData.append(key, this.config[key]);
            }

            const res = await fetch('api/settings.php', { method: 'POST', body: formData });
            const json = await res.json();

            if (json.status === 'success') {
                notify('success', 'Configuración Guardada');
            } else {
                notify('error', 'Error', json.message);
            }
        }
    }));

    // POS Component
    Alpine.data('pos', () => ({
        products: [],
        cart: [],
        search: '',

        // Calculator State
        showCalculator: false,
        calcProduct: {},
        calcAmount: '', // Money
        calcQty: '',    // Units

        async init() {
            await this.fetchSettings();
            await this.fetchProducts();
        },

        config: {},
        async fetchSettings() {
            const res = await fetch('api/settings.php');
            const json = await res.json();
            if (json.status === 'success') {
                this.config = {
                    ...json.data,
                    exchange_rate_global: parseFloat(json.data.exchange_rate_global),
                    exchange_rate_special: parseFloat(json.data.exchange_rate_special)
                };
            }
        },

        formatPrice(price, useSpecial = false) {
            const val = parseFloat(price);
            if (this.config.main_currency === 'USD') {
                // Return $X.XX (Bs X,XXX.XX)
                const rate = useSpecial ? this.config.exchange_rate_special : this.config.exchange_rate_global;
                const bs = val * rate;
                return `$${val.toFixed(2)} (Bs ${bs.toLocaleString('es-VE', { minimumFractionDigits: 2, maximumFractionDigits: 2 })})`;
            } else {
                return `Bs ${val.toFixed(2)}`;
            }
        },

        async fetchProducts() {
            const res = await fetch('api/products.php?action=list&type=for_sale');
            const json = await res.json();
            if (json.status === 'success') this.products = json.data;
        },

        get filteredProducts() {
            if (!this.search) return this.products;
            return this.products.filter(p => p.name.toLowerCase().includes(this.search.toLowerCase()));
        },

        addToCart(product) {
            this.addFinalToCart(product, 1, parseFloat(product.price));
            notify('success', 'Agregado', `1 ${product.display_unit} de ${product.name}`);
        },

        addFinalToCart(product, qty, subtotal) {
            const existing = this.cart.find(i => i.id === product.id);
            if (existing) {
                // Update existing
                let newQty = parseFloat(existing.quantity_to_sell) + parseFloat(qty);
                existing.quantity_to_sell = newQty.toFixed(4);
                existing.subtotal = (parseFloat(existing.subtotal) + parseFloat(subtotal)).toFixed(2);
            } else {
                // Add new
                this.cart.push({
                    ...product,
                    quantity_to_sell: parseFloat(qty).toFixed(4),
                    subtotal: parseFloat(subtotal).toFixed(2)
                });
            }
        },

        removeFromCart(index) {
            this.cart.splice(index, 1);
        },

        get cartTotal() {
            return this.cart.reduce((sum, item) => sum + parseFloat(item.subtotal || 0), 0).toFixed(2);
        },

        get cartTotalBs() {
            return this.cart.reduce((sum, item) => {
                const rate = (item.use_special_rate == 1 || item.use_special_rate === true)
                    ? this.config.exchange_rate_special
                    : this.config.exchange_rate_global;
                return sum + (parseFloat(item.subtotal || 0) * rate);
            }, 0).toLocaleString('es-VE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },

        // --- Calculator Logic ---

        openCalculator(product) {
            this.calcProduct = product;
            this.calcQty = 1;
            this.calcAmount = parseFloat(product.price).toFixed(2);
            this.showCalculator = true;
        },

        updateCalcByAmount() {
            // User types Money -> Calc Qty
            let price = parseFloat(this.calcProduct.price);
            if (price > 0 && this.calcAmount) {
                this.calcQty = (parseFloat(this.calcAmount) / price).toFixed(4);
            }
        },

        updateCalcByQty() {
            // User types Qty -> Calc Money
            let price = parseFloat(this.calcProduct.price);
            if (price > 0 && this.calcQty) {
                this.calcAmount = (parseFloat(this.calcQty) * price).toFixed(2);
            }
        },

        setFraction(fraction) {
            this.calcQty = fraction;
            this.updateCalcByQty();
        },

        addToCartFromCalc() {
            // Validate
            if (parseFloat(this.calcAmount) <= 0 || parseFloat(this.calcQty) <= 0) {
                notify('error', 'Valor Inválido');
                return;
            }

            this.addFinalToCart(this.calcProduct, this.calcQty, this.calcAmount);
            this.showCalculator = false;
            notify('success', 'Agregado', `Bs ${this.calcAmount} de ${this.calcProduct.name}`);
        },

        decrementItem(index) {
            const item = this.cart[index];
            let currentQty = parseFloat(item.quantity_to_sell);
            let unitPrice = parseFloat(item.price);

            if (currentQty > 1) {
                // If more than 1, decrement by 1
                let newQty = currentQty - 1;
                item.quantity_to_sell = newQty.toFixed(4);
                item.subtotal = (newQty * unitPrice).toFixed(2);
            } else {
                this.removeFromCart(index);
            }
        },

        incrementItem(index) {
            const item = this.cart[index];
            let currentQty = parseFloat(item.quantity_to_sell);
            let unitPrice = parseFloat(item.price);

            // Increment by 1
            let newQty = currentQty + 1;
            item.quantity_to_sell = newQty.toFixed(4);
            item.subtotal = (newQty * unitPrice).toFixed(2);
        },

        updateByAmount(index, amount) {
            const item = this.cart[index];
            const price = parseFloat(item.price);
            if (price > 0) {
                item.quantity_to_sell = (parseFloat(amount) / price).toFixed(4);
                item.subtotal = amount;
            }
        },

        async checkout() {
            if (this.cart.length === 0) return;

            // Final Stock Check
            for (let item of this.cart) {
                if (parseFloat(item.quantity_to_sell) > parseFloat(item.stock_quantity)) {
                    Swal.fire({ icon: 'error', title: 'Stock Insuficiente', text: `Solo tienes ${item.stock_quantity} ${item.display_unit} de ${item.name}` });
                    return;
                }
            }

            if (await confirmAction('¿Procesar Venta?', `Total: Bs ${this.cartTotal}`)) {
                const res = await fetch('api/sales.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ cart: this.cart })
                });
                const json = await res.json();

                if (json.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Venta Exitosa!',
                        text: `ID Venta: #${json.sale_id}`,
                        background: '#1e293b',
                        color: '#fff'
                    });
                    this.cart = [];
                    this.fetchProducts();
                } else {
                    notify('error', 'Error', json.message);
                }
            }
        }
    }));

    // Categories Component (Separate Page logic)
    Alpine.data('categories', () => ({
        categories: [],
        showModal: false,
        current: { id: null, name: '', description: '' },

        init() { this.fetchCats(); },

        async fetchCats() {
            const res = await fetch('api/categories.php?action=list');
            const json = await res.json();
            if (json.status === 'success') this.categories = json.data;
        },

        openModal() {
            this.current = { id: null, name: '', description: '' };
            this.showModal = true;
        },

        edit(cat) {
            this.current = { ...cat };
            this.showModal = true;
        },

        async save() {
            const formData = new FormData();
            formData.append('action', 'save');
            if (this.current.id) formData.append('id', this.current.id);
            formData.append('name', this.current.name);
            formData.append('description', this.current.description);

            const res = await fetch('api/categories.php', { method: 'POST', body: formData });
            const json = await res.json();

            if (json.status === 'success') {
                Swal.fire({ icon: 'success', title: 'Guardado', toast: true, position: 'top-end', timer: 2000, showConfirmButton: false });
                this.fetchCats();
                this.showModal = false;
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: json.message });
            }
        },

        async remove(id) {
            if ((await Swal.fire({ title: '¿Eliminar?', icon: 'warning', showCancelButton: true })).isConfirmed) {
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('id', id);
                await fetch('api/categories.php', { method: 'POST', body: formData });
                this.fetchCats();
            }
        }
    }));
});

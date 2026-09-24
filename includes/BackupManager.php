<?php

class BackupManager {
    private $pdo;
    private $baseDir;

    public function __construct($pdo, $baseDir = __DIR__ . '/../') {
        $this->pdo = $pdo;
        $this->baseDir = $baseDir; // Root project dir
    }

    public function getSettings() {
        $stmt = $this->pdo->query("SELECT * FROM system_settings LIMIT 1");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function ensureDirectory($path) {
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        // Protect with .htaccess if possible
        if (!file_exists($path . '/.htaccess')) {
            file_put_contents($path . '/.htaccess', "Deny from all");
        }
    }

    /**
     * Backup Database to SQL File (PHP Implementation)
     */
    public function backupDatabase($targetDir) {
        $this->ensureDirectory($targetDir);
        
        $tables = [];
        $query = $this->pdo->query('SHOW TABLES');
        while($row = $query->fetch(PDO::FETCH_NUM)) {
            $tables[] = $row[0];
        }

        $sqlScript = "-- Auto Backup \n-- Date: " . date('Y-m-d H:i:s') . "\n\n";
        
        foreach($tables as $table) {
            $result = $this->pdo->query('SELECT * FROM ' . $table);
            $numFields = $result->columnCount();
            
            $sqlScript .= "DROP TABLE IF EXISTS `$table`;\n";
            $row2 = $this->pdo->query('SHOW CREATE TABLE ' . $table)->fetch(PDO::FETCH_NUM);
            $sqlScript .= "\n" . $row2[1] . ";\n\n";

            for ($i = 0; $i < $numFields; $i++) {
                while($row = $result->fetch(PDO::FETCH_NUM)) {
                    $sqlScript .= "INSERT INTO `$table` VALUES(";
                    for($j=0; $j < $numFields; $j++) {
                        $row[$j] = addslashes($row[$j]);
                        $row[$j] = str_replace("\n","\\n",$row[$j]);
                        if (isset($row[$j])) {
                            $sqlScript .= '"' . $row[$j] . '"' ; 
                        } else {
                            $sqlScript .= '""';
                        }
                        if ($j < ($numFields - 1)) { 
                            $sqlScript .= ','; 
                        }
                    }
                    $sqlScript .= ");\n";
                }
            }
            $sqlScript .= "\n\n\n";
        }

        $filename = 'backup_db_' . date('Y-m-d_H-i-s') . '.sql';
        $fullPath = $targetDir . '/' . $filename;
        
        file_put_contents($fullPath, $sqlScript);
        
        return $filename;
    }

    /**
     * Backup Images (Assets/Img + Uploads)
     */
    public function backupImages($targetDir) {
        $this->ensureDirectory($targetDir);

        $date = date('Y-m-d_H-i-s');
        $filename = 'backup_img_' . $date . '.zip';
        $fullPath = $targetDir . '/' . $filename;

        $zip = new ZipArchive();
        if ($zip->open($fullPath, ZipArchive::CREATE) !== TRUE) {
            throw new Exception("No se pudo crear el archivo ZIP");
        }

        // Add assets/img (System images)
        if (is_dir($this->baseDir . 'assets/img')) {
            $this->addFolderToZip($this->baseDir . 'assets/img', $zip, 'assets/img');
        }

        // Add uploads (Product images)
        if (is_dir($this->baseDir . 'uploads')) {
            $this->addFolderToZip($this->baseDir . 'uploads', $zip, 'uploads');
        }
        
        $zip->close();
        
        return $filename;
    }

    private function addFolderToZip($dir, $zipArchive, $zipDir) {
        // Ensure dir ends with slash
        if (substr($dir, -1) !== '/' && substr($dir, -1) !== '\\') {
            $dir .= '/';
        }

        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                // Add empty dir
                $zipArchive->addEmptyDir($zipDir);
                while (($file = readdir($dh)) !== false) {
                    if (($file !== ".") && ($file !== "..")) {
                        if (is_file($dir . $file)) {
                            $zipArchive->addFile($dir . $file, $zipDir . "/" . $file);
                        } else {
                            $this->addFolderToZip($dir . $file, $zipArchive, $zipDir . "/" . $file);
                        }
                    }
                }
                closedir($dh);
            }
        }
    }

    /**
     * Run Manual or Auto Backup
     */
    public function runBackup($type = 'AUTO') {
        $settings = $this->getSettings();
        $targetDir = realpath($this->baseDir) . '/' . trim($settings['backup_path'], '/\\');
        
        // Ensure path isn't crazy
        if (!$settings['backup_path']) $targetDir = $this->baseDir . '/backups';

        // Decisions
        $doDB = true;
        // Check Smart logic for Images
        // If last_image_change is newer than last_full_backup -> Do Zip
        $doImg = false;
        
        if ($type === 'MANUAL') {
            $doImg = true; // Always full on manual? Or ask user. Default full.
        } else {
            // Auto
            $lastFull = $settings['last_full_backup'];
            $lastImgChange = $settings['last_image_change'];
            
            if (!$lastFull || ($lastImgChange > $lastFull)) {
                $doImg = true;
            }
        }

        $files = [];
        // Run DB Backup
        $dbFile = $this->backupDatabase($targetDir);
        $files['db'] = $dbFile;
        // Update DB Time
        $this->pdo->exec("UPDATE system_settings SET last_db_backup = NOW()");

        // Run Img Backup
        if ($doImg) {
            $imgFile = $this->backupImages($targetDir);
            $files['img'] = $imgFile;
            // Update Full Time
            $this->pdo->exec("UPDATE system_settings SET last_full_backup = NOW()");
        }

        // Log It
        $desc = ($type === 'MANUAL') ? "Respaldo Manual" : "Respaldo Automático";
        if ($doImg) $desc .= " (Completo)"; else $desc .= " (Solo BD)";

        if (function_exists('log_movement')) {
            log_movement($this->pdo, 'BACKUP', $desc, $files);
        }

        return ['status' => 'success', 'files' => $files, 'type' => $type, 'full' => $doImg];
    }
    
    public function checkAutoTrigger() {
        $settings = $this->getSettings();
        $freqHours = $settings['backup_frequency'] ?? 24;
        $lastDB = $settings['last_db_backup'];

        if (!$lastDB) return $this->runBackup('AUTO'); // First time

        $lastDate = new DateTime($lastDB);
        $now = new DateTime();
        $diff = $now->diff($lastDate);
        $hours = ($diff->days * 24) + $diff->h;

        if ($hours >= $freqHours) {
            return $this->runBackup('AUTO');
        }

        return ['status' => 'skipped', 'message' => 'Not time yet'];
    }

    public function getBackupList() {
        $settings = $this->getSettings();
        $targetDir = realpath($this->baseDir) . '/' . trim($settings['backup_path'], '/\\');
        
        $files = [];
        if (is_dir($targetDir)) {
             $scanned = scandir($targetDir);
             foreach($scanned as $file) {
                 if($file !== '.' && $file !== '..') {
                     $path = $targetDir . '/' . $file;
                     $files[] = [
                         'name' => $file,
                         'size' => filesize($path),
                         'date' => filemtime($path),
                         'type' => (strpos($file, '.sql') !== false) ? 'db' : 'img'
                     ];
                 }
             }
        }
        // Sort by date desc
        usort($files, function($a, $b) {
             return $b['date'] - $a['date'];
        });
        return $files;
    }

    public function deleteBackup($filename) {
        $settings = $this->getSettings();
        $targetDir = realpath($this->baseDir) . '/' . trim($settings['backup_path'], '/\\');
        $path = $targetDir . '/' . $filename;
        if (file_exists($path)) {
            unlink($path);
            return true;
        }
        return false;
    }

    public function restoreDatabase($filepath) {
        if (!file_exists($filepath)) throw new Exception("Archivo no encontrado");

        // Temporary: Read entire file (Warning: memory intensive for huge DBs)
        // For larger DBs, stream reading is better.
        $sql = file_get_contents($filepath);
        if (!$sql) throw new Exception("Archivo vacío o ilegible");

        try {
            $this->pdo->exec($sql);
        } catch (PDOException $e) {
            // Fallback: Split commands if single exec fails (unlikely for mysqldump style, but possible)
             throw new Exception("Error SQL: " . $e->getMessage());
        }
    }

    public function restoreImages($filepath) {
        if (!file_exists($filepath)) throw new Exception("Archivo no encontrado");

        $zip = new ZipArchive;
        if ($zip->open($filepath) === TRUE) {
            // Extract to root
            $zip->extractTo($this->baseDir);
            $zip->close();
        } else {
            throw new Exception("No se pudo abrir el ZIP");
        }
    }
}
?>

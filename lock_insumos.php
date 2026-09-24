<?php
session_start();
// Remove the insumos unlock flag
unset($_SESSION['insumos_unlocked']);
// Redirect to index
header("Location: index.php");
exit;
?>

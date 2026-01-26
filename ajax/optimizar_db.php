<?php
session_start();
if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) {
    exit;
}

require_once("../config/db.php");
require_once("../config/conexion.php");

// Obtener todas las tablas
$alltables = mysqli_query($con, "SHOW TABLES");
$count = 0;

while ($table = mysqli_fetch_row($alltables)) {
    $tableName = $table[0];
    // Ejecutar optimización
    mysqli_query($con, "OPTIMIZE TABLE $tableName");
    $count++;
}

echo "Mantenimiento completado: $count tablas optimizadas correctamente.";
?>
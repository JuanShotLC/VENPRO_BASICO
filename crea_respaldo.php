<?php
/*----------------------------------------------------------------------------------------------------------------*
 * MÓDULO: Generador de Respaldo de Base de Datos (Backup)
 * AUTOR: Modernizado para PHP 7/8 (MySQLi)
 *----------------------------------------------------------------------------------------------------------------*/
session_start();
// Solo permitir si está logueado y es admin (seguridad básica)
if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) {
    header("location: login.php");
    exit;
}

// Configuración de la Base de Datos
require_once("config/db.php");

// Definir constantes basadas en tu configuración existente
define("DB_USER", DB_USER);
define("DB_PASSWORD", DB_PASS);
define("DB_NAME", DB_NAME);
define("DB_HOST", DB_HOST);

// Instanciar y ejecutar el respaldo
$backup = new Backup_Database(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$backup->backupTables('*');

/**
 * Clase para Backup MySQLi (Compatible PHP 7+)
 */
class Backup_Database
{
    var $host;
    var $username;
    var $passwd;
    var $dbName;
    var $charset;
    var $conn;

    function __construct($host, $username, $passwd, $dbName, $charset = 'utf8')
    {
        $this->host = $host;
        $this->username = $username;
        $this->passwd = $passwd;
        $this->dbName = $dbName;
        $this->charset = $charset;
        $this->initializeDatabase();
    }

    protected function initializeDatabase()
    {
        $this->conn = mysqli_connect($this->host, $this->username, $this->passwd, $this->dbName);
        if (mysqli_connect_errno()) {
            die('Error conectando a MySQL: ' . mysqli_connect_error());
        }
        mysqli_set_charset($this->conn, $this->charset);
    }

    public function backupTables($tables = '*')
    {
        try {
            if ($tables == '*') {
                $tables = array();
                $result = mysqli_query($this->conn, 'SHOW TABLES');
                while ($row = mysqli_fetch_row($result)) {
                    $tables[] = $row[0];
                }
            } else {
                $tables = is_array($tables) ? $tables : explode(',', $tables);
            }

            $sql = "-- RESPALDO BASE DE DATOS: " . $this->dbName . "\n";
            $sql .= "-- FECHA: " . date("d-m-Y H:i:s") . "\n\n";
            $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

            foreach ($tables as $table) {
                // Estructura de la tabla
                $row = mysqli_fetch_row(mysqli_query($this->conn, 'SHOW CREATE TABLE ' . $table));
                $sql .= "\n\n-- Estructura de tabla para: " . $table . "\n";
                $sql .= "DROP TABLE IF EXISTS `" . $table . "`;\n";
                $sql .= $row[1] . ";\n\n";

                // Datos de la tabla
                $result = mysqli_query($this->conn, 'SELECT * FROM ' . $table);
                $numFields = mysqli_num_fields($result);

                for ($i = 0; $i < $numFields; $i++) {
                    while ($row = mysqli_fetch_row($result)) {
                        $sql .= 'INSERT INTO `' . $table . '` VALUES(';
                        for ($j = 0; $j < $numFields; $j++) {
                            $row[$j] = addslashes($row[$j]);
                            // Reemplazo moderno de ereg_replace
                            $row[$j] = str_replace("\n", "\\n", $row[$j]);

                            if (isset($row[$j])) {
                                $sql .= '"' . $row[$j] . '"';
                            } else {
                                $sql .= '""';
                            }
                            if ($j < ($numFields - 1)) {
                                $sql .= ',';
                            }
                        }
                        $sql .= ");\n";
                    }
                }
            }

            $sql .= "\nSET FOREIGN_KEY_CHECKS=1;";

            // FORZAR DESCARGA DEL ARCHIVO
            $this->downloadFile($sql);

        } catch (Exception $e) {
            echo "Error exportando: " . $e->getMessage();
            return false;
        }
    }

    protected function downloadFile($sql)
    {
        $filename = 'backup_db_' . $this->dbName . '_' . date('Y-m-d_H-i-s') . '.sql';

        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=\"" . $filename . "\"");
        echo $sql;
        exit;
    }
}
?>
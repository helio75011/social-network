<?php
session_start();

define("SQL_HOST", "localhost");
define("SQL_USER", "root");
define("SQL_PASS", "");
define("SQL_DBNAME", "deterdb");

try {
    $db = new PDO("mysql:host=" . SQL_HOST . ";dbname=" . SQL_DBNAME . ";charset=utf8", SQL_USER, SQL_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Erreur : ' . $e->getMessage());
}
?>

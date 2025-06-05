<?php
$dbhost = "localhost";
$dbuser= "root";
$dbpass = "cs3319";
$dbname = "assign2db";
try {
    $connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
} catch (mysqli_sql_exception $e) {
    die("Database connection failed: " . $e->getMessage());
}

<?php
function getDbConnection() {
    $host = "localhost";  
    $port = "8889";
    $dbname = "ppe1_test"; 
    $username = "root";  
    $password = "root";  

    try {
        $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }
}
?>
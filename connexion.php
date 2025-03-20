<?php
include 'config.php';

$conn = getDbConnection(); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM utilisateur WHERE Email = :Email");
    $stmt->bindParam(':Email', $email);
    $stmt->execute();
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['Mot_de_passe'])) {
        header("location: index.html");
        exit;
        
    } else {
        echo "Email ou mot de passe incorrect";
    }
}
?>
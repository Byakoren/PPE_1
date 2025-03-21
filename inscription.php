<?php
include 'config.php';

$conn = getDbConnection();




if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO utilisateur (Nom, Email, Mot_de_passe) VALUES (:Nom, :Email, :Mot_de_passe)");
    $stmt->bindParam(':Nom', $nom);
    $stmt->bindParam(':Email', $email);
    $stmt->bindParam(':Mot_de_passe', $password);

    if ($stmt->execute()) {
        header("location: connexion.html");
        exit;
    } else {
        echo "Erreur lors de l'inscription";
    }
}
?>
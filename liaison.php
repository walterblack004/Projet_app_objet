

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "projet_transversal_objet";


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connexion réussie";
?>
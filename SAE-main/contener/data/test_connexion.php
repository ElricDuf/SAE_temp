<?php
$servername = "mysql";
$username = "user";
$password = "pass";
$dbname = "tp";


if (!function_exists('mysqli_init') && !extension_loaded('mysqli')) {
    echo 'We don\'t have mysqli!!!';
}

$conn = new \mysqli($servername, $username, $password);

if($conn->connect_error){
    die('Erreur : ' .$conn->connect_error);
}
echo 'Connexion réussie en natif';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully";
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

?>

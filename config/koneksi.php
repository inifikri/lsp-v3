<?php
$server = "localhost";
$username = "root";
$password = "";
$database = "lsp_db";
// Create connection
$conn = new mysqli($server, $username, $password, $database);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// try {
//     $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
//     // set the PDO error mode to exception
//     $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
//     // prepare sql and bind parameters
//     $stmt = $conn->prepare('SELECT * FROM users WHERE username = :username AND password = :password');
//     $stmt->bindParam(':username', $username);                   
//     $stmt->bindParam(':password', $password);
//     $stmt->execute();
//   } catch(PDOException $e) {
//     echo "Error: " . $e->getMessage();
//   }
//   $conn = null;
?>

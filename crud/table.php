<?php
session_start();
$host = "localhost";
$username = "root";
$password = "";
$dbname = "crud";
$sql = "";
$email = "";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if(isset($_SESSION["role"])) {
    if($_SESSION["role"] != "admin") {
        echo "You are not admin.";
        return;
    }
} else {
    echo "You are not logged in.";
    return;
}


$query = "SELECT * FROM users";
$result = mysqli_query($conn, $query);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "id: " . $row["id"]. " - email: " . $row["email"]. " - role: " . $row["role"] . ' <a href="edit.php?id=' . $row["id"] . '">Edit</a><br>';
    }
}
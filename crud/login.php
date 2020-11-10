<?php
session_start();
$host = "localhost";
$username = "root";
$password = "";
$dbname = "crud";
$sql = "";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if(isset($_POST["login_user"])) {
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $pass = mysqli_real_escape_string($conn, $_POST["password"]);
    if(!empty($email) && !empty($pass)) {
        $pass = md5($pass);
        $query = "SELECT * FROM users WHERE email = '$email' AND password = '$pass'";
        $result = mysqli_query($conn, $query);
        if(mysqli_num_rows($result) == 1) {
            $row = $result->fetch_array();
            $_SESSION["id"] = $row["id"];
            $_SESSION["email"] = $row["email"];
            $_SESSION["role"] = $row["role"];
            header("Location: index.php");
        } else {
            echo "user does not exists.";
        }
    } else {
        echo "email or password is not inserted.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>login</title>
</head>
<body>
<h2>Login</h2>

<form method="post" action="login.php">
    <label>Email</label>
    <input type="email" name="email">
    <label>Password</label>
    <input type="password" name="password">
    <button type="submit" class="btn" name="login_user">Login</button>
</form>
</body>
</html>

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

if(isset($_POST["reg_user"])) {
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $pass = mysqli_real_escape_string($conn, $_POST["password"]);
    if(empty($email) || empty($pass)) {
        echo "All fields have to be filled.";
        return;
    }
    $user_check = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $user_check);
    $user = mysqli_fetch_assoc($result);

    if(!$user) {
        $pass = md5($pass);
        if(isset($_POST["role"]))
            $role = mysqli_real_escape_string($conn, $_POST["role"]);
        else
            $role = "user";

        $query = "INSERT INTO users (email, password, role) VALUES ('$email', '$password', '$role')";
        mysqli_query($conn, $query);
        header("Location: index.php");
    } else {
        echo "user with this email already exists";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>registration</title>
</head>
<body>
<h2>Register</h2>

<form method="post" action="register.php">
        <label>Email</label>
        <input type="email" name="email">
        <label>Password</label>
        <input type="password" name="password">
        <?php
        if(isset($_SESSION["role"])) {
            if($_SESSION["role"] == "admin") {
                echo '<label>Role</label>';
                echo '<input type="text" name="role">';
            }
        }
        ?>
        <button type="submit" class="btn" name="reg_user">Register</button>
</form>
</body>
</html>
<?php
session_start();
$host = "localhost";
$username = "root";
$password = "";
$dbname = "crud";
$sql = "";

$email = "";
$pass = "";
$role = "";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if(isset($_SESSION["id"])) {
    if(isset($_GET["id"]))
        $id = $_GET["id"];
    else
        $id = $_SESSION["id"];
} else {
    echo "You are not allowed to do that";
    return;
}

if($id != $_SESSION["id"] && $_SESSION["role"] != "admin") {
    echo "You are not allowed to do that";
    return;
}

$query = "SELECT * FROM users WHERE id = '$id'";
$result = mysqli_query($conn, $query);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
         $email = $row["email"];
         $role = $row["role"];
    }
}

if(isset($_POST["edit_user"])) {
    if(isset($_POST["email"])) {
        $email = mysqli_real_escape_string($conn, $_POST["email"]);
        if(!empty($email)) {
            $query = "UPDATE users SET email = '$email' WHERE id = '$id'";
            mysqli_query($conn, $query);
        }
    }

    if(isset($_POST["password"])) {
        $pass = mysqli_real_escape_string($conn, $_POST["password"]);
        if(!empty($pass)) {
            $pass = md5($pass);
            $query = "UPDATE users SET password = '$pass' WHERE id = '$id'";
            mysqli_query($conn, $query);
        }
    }

    if(isset($_POST["role"])) {
        $role = mysqli_real_escape_string($conn, $_POST["role"]);
        if(!empty($role)) {
            $query = "UPDATE users SET role = '$role' WHERE id = '$id'";
            mysqli_query($conn, $query);
        }
    }

    echo "ok";
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>edit</title>
</head>
<body>
<h2>Edit</h2>

<form method="post" action="edit.php?id=<?php echo $id ?>">
    <label>Email</label>
    <input type="email" name="email" value="<?php echo $email;?>">
    <label>Password</label>
    <input type="password" name="password">
    <?php
    if($_SESSION["role"] == "admin") {
        echo '<label>Role</label>';
        echo '<input type="text" name="role" value="' . $role . '">';
    }
    ?>
    <button type="submit" class="btn" name="edit_user">Edit</button>
</form>
</body>
</html>
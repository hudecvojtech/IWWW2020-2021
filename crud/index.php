<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>index</title>
</head>
<body>
<h2>Menu</h2>
<nav>
    <ul>
        <?php
        if(!isset($_SESSION["role"])) {
            echo '<li><a href="login.php">Login</li>';
            echo '<li><a href="register.php">Register</li>';
        } else {
            echo '<li><a href="logout.php">Logout</li>';
            echo '<li><a href="edit.php?id=' . $_SESSION["id"] . '">Edit</li>';
            if($_SESSION["role"] == "admin")
            echo '<li><a href="table.php">Table</li>';
        }
        ?>
    </ul>
</nav>

</body>
</html>

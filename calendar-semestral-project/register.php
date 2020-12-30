<?php
/**
 * @var $pdo
 */

session_start();
require_once "connection.php";
require_once "User.php";

$user = new User($pdo);

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $err = NULL;
    if(empty(trim($_POST["firstname"]))) {
        $err .= "Please enter your firstname.<br>";
    } else {
        $firstname = trim($_POST["firstname"]);
    }

    if(empty(trim($_POST["lastname"]))) {
        $err .= "Please enter your lastname.<br>";
    } else {
        $lastname = trim($_POST["lastname"]);
    }

    if(empty(trim($_POST["email"]))) {
        $err .= "Please enter your email.<br>";
    } else {
        $email = trim($_POST["email"]);
    }

    if(empty(trim($_POST["password1"]))) {
        $err .= "Please enter your password.<br>";
    } else {
        $password1 = trim($_POST["password1"]);
    }

    if(empty(trim($_POST["password2"]))) {
        $err .= "Please enter your password again.<br>";
    } else {
        $password2 = trim($_POST["password2"]);
        if($password1 != $password2) {
            $err .= "Your passwords does not match.<br>";
        }
    }

    if(empty($err)) {
        // avatarId 1 - default
        if(!$user->register($email, $password1, $firstname, $lastname, "user", 1)) {
            $err .= "This email address is already taken.<br>";
        }
    }
}

?>

<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">

    <title>Register</title>
    <meta name="description" content="Free calendar app">
    <meta name="author" content="Vojtěch Hudec">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
<header>
    <div class="logo">
        <h1><a href="index.php">Calendar</a></h1>
    </div>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="login.php">Log in</a></li>
        </ul>
    </nav>
</header>
<div class="container">
    <?php
    if(!empty($err)) {
        echo '<div class="error">';
        echo '<p>' . $err . '</p>';
        echo '</div>';
    }
    ?>
    <form action="register.php" method="post">
        <label for="firstname">First name:</label>
        <input type="text" name="firstname" value="<?php if(!empty($firstname)) echo $firstname ?>">
        <label for="lastname">Last name:</label>
        <input type="text" name="lastname" value="<?php if(!empty($lastname)) echo $lastname ?>">
        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php if(!empty($email)) echo $email ?>">
        <label for="password1">Password:</label>
        <input type="password" name="password1">
        <label for="password2">Password again:</label>
        <input type="password" name="password2">
        <input type="submit" value="Sing up">
    </form>
    <p>You already have account? <a href="login.php">Log in</a> now!</p>
</div>
<footer>
    <p>2020 Vojtěch Hudec</p>
</footer>
</body>
</html>


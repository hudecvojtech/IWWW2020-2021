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
    if(empty(trim($_POST["email"]))) {
        $err .= "Please enter email.<br>";
    } else {
        $email = trim($_POST["email"]);
    }

    if(empty(trim($_POST["password"]))) {
        $err .= "Please enter password.<br>";
    } else {
        $password = trim($_POST["password"]);
    }

    if(empty($err)) {
        if(!$user->login($email, $password)) {
            $err .= "Email or password is not valid.<br>";
        }
    }
}

?>

<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">

    <title>Login</title>
    <meta name="description" content="Free calendar app">
    <meta name="author" content="Vojtěch Hudec">

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
            <li><a href="register.php">Sing up</a></li>
            <li><a href="index.php">Home</a></li>
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
    <form action="login.php" method="post">
        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php if(!empty($email)) echo $email ?>">
        <label for="password">Password:</label>
        <input type="password" name="password">
        <input type="submit" value="Log in">
    </form>
    <p>You do not have account? <a href="register.php">Click here</a> and start using Calendar today!</p>
</div>
<footer>
    <p>2020 Vojtěch Hudec</p>
</footer>
</body>
</html>

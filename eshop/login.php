<?php
/**
 * @var $pdo
 */

session_start();
require_once "connection.php";
require_once "User.php";

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $err = NULL;
    if(empty(trim($_POST["login"]))) {
        $err .= "Please enter login.<br>";
    } else {
        $login = trim($_POST["login"]);
    }

    if(empty(trim($_POST["password"]))) {
        $err .= "Please enter password.<br>";
    } else {
        $password = trim($_POST["password"]);
    }

    if(empty($err)) {
        if(!User::login($login, $password, $pdo)) {
            $err .= "Email or password is not valid.<br>";
        }
    }
}

?>

<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">
</head>

<body>
    <?php
    if(!empty($err)) {
        echo '<div class="error">';
        echo '<p>' . $err . '</p>';
        echo '</div>';
    }
    ?>
    <form action="login.php" method="post">
        <label for="login">Login:</label>
        <input type="text" name="login" value="<?php if(!empty($login)) echo $login ?>">
        <label for="password">Password:</label>
        <input type="password" name="password">
        <input type="submit" value="Log in">
    </form>
</body>
</html>


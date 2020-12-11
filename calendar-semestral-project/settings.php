<?php
/**
 * @var $pdo
 */

session_start();
require_once "connection.php";
require_once "User.php";
require_once "Avatar.php";

$user = new User($pdo);

if(!isset($_SESSION["role"])) {
    header("Location: index.php");
}

if($_SESSION["role"] == "admin" && isset($_GET["id"])) {
    $id = $_GET["id"];
} else {
    $id = $_SESSION["id"];
}

$u = $user->getUser($id);

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $err = NULL;
    if(!empty(trim($_POST["firstname"]))) {
        $firstname = $_POST["firstname"];
    }

    if(!empty(trim($_POST["lastname"]))) {
        $lastname = $_POST["lastname"];
    }

    if(!empty(trim($_POST["email"]))) {
        $email = $_POST["email"];
    }

    if(!empty(trim($_POST["password1"]))) {
        if(!empty(trim($_POST["password2"]))) {
            if($_POST["password1"] != $_POST["password2"]) {
                echo "nejsou stejný hesla";
            } else {
                $password = $_POST["password1"];
            }
        } else {
            echo "chyba hesla vyplnit obě";
        }
    }

    $avat = new Avatar($pdo);
    $avatarId = $u["AVATAR_id_avatar"];
    $prevAvatarId = "";
    if(isset($_POST["avatarUpload"])) {
        $filename   = uniqid() . "-" . time();
        $extension  = pathinfo( $_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION );
        $basename   = $filename . "." . $extension;
        move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], "img/" . $basename);
        $avat->insert($basename);
        $prevAvatarId = $avatarId;
        $avatarId = $avat->getId($basename);
    }

    $role = "user";
    if(isset($_POST["role"])) {
        $role = $_POST["role"];
    }

    $user->update($id, $firstname, $lastname, $email, $password, $role, $avatarId);

    if(!empty($prevAvatarId)) {
        if($prevAvatarId != 1) {
            $avat = new Avatar($pdo);
            $path = $avat->getPath($prevAvatarId);
            $path = "img/" . $path;
            $avat->delete($prevAvatarId);
            unlink($path);
        }
    }

    $u = $user->getUser($id);
}

?>

<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">

    <title>Settings</title>
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
            <?php
            if($_SESSION["role"] == "admin") {
                echo '<li><a href="admin.php">Admin</a></li>';
            }
            ?>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="logout.php">Log out</a></li>
        </ul>
    </nav>
</header>
<div class="container">
    <form action="settings.php?id=<?php echo $id; ?>" method="post" class="settingsform" enctype="multipart/form-data">
        <img src="img/<?php $avat = new Avatar($pdo); echo $avat->getPath($u["AVATAR_id_avatar"]); ?>" class="settingsavatar">
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Upload new image" name="avatarUpload">
        <label for="firstname">First name:</label>
        <input type="text" name="firstname" value="<?php echo $u["firstname"]; ?>">
        <label for="lastname">Last name:</label>
        <input type="text" name="lastname" value="<?php echo $u["lastname"]; ?>">
        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo $u["email"]; ?>">
        <label for="password1">Password:</label>
        <input type="password" name="password1">
        <label for="password2">Password again:</label>
        <input type="password" name="password2">
        <?php
        if($_SESSION["role"] == "admin") {
            echo '<label for="role">Role: </label>
        <select name="role">
            <option value="admin">Admin</option>
            <option value="user" ';
            if($u["role"] == "user") echo 'selected';
            echo '>User</option>
        </select>';
        }
        ?>
        
        <input type="submit" value="Edit">
    </form>
</div>
<footer>
    <p>2020 Vojtěch Hudec</p>
</footer>
</body>
</html>


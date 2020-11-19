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
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="logout.php">Log out</a></li>
        </ul>
    </nav>
</header>
<div class="container">
    <form action="settings.php" method="post" class="settingsform">
        <img src="img/default-avatar.png" class="settingsavatar">
        <input type="submit" value="Upload new image">
        <label for="firstname">First name:</label>
        <input type="text" name="firstname">
        <label for="lastname">Last name:</label>
        <input type="text" name="lastname">
        <label for="email">Email:</label>
        <input type="email" name="email">
        <label for="password1">Password:</label>
        <input type="password" name="password1">
        <label for="password2">Password again:</label>
        <input type="password" name="password2">
        <input type="submit" value="Edit">
    </form>
</div>
<footer>
    <p>2020 Vojtěch Hudec</p>
</footer>
</body>
</html>


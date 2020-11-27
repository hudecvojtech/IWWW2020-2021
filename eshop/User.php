<?php

class user
{

    public static function login($login, $password, $pdo)
    {
        $query = "SELECT * FROM users WHERE login = '$login'";
        $stmt = $pdo->query($query);
        $count = $stmt->rowCount();
        $row = $stmt->fetch();
        if ($count == 1) {
            if (!password_verify($password, $row["password"]))
                return false;

            $_SESSION["id"] = $row["id"];
            $_SESSION["login"] = $row["login"];

            header("location: index.php");
        } else {
            return false;
        }
    }

    public static function register($login, $password, $pdo)
    {
        $query = $query = "SELECT * FROM users WHERE login = '$login'";
        $stmt = $pdo->query($query);
        $count = $stmt->rowCount();
        if ($count == 0) {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO users(login, password) VALUES('$login', '$passwordHash')";
            $pdo->query($query);
            User::login($login, $password, $pdo);
        } else {
            return false;
        }
    }

}
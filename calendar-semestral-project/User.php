<?php

require_once "DatabaseQueries.php";

class user
{

    public static function login($email, $password, $pdo)
    {
        $query = DatabaseQueries::userFindByEmail($email);
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $count = $stmt->rowCount();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($count == 1) {
            if (!password_verify($password, $row["password"]))
                return false;

            $_SESSION["id"] = $row["id_user"];
            $_SESSION["email"] = $row["email"];
            $_SESSION["firstname"] = $row["firstname"];
            $_SESSION["lastname"] = $row["lastname"];

            $roleId = $row["ROLE_id_role"];
            $avatarId = $row["AVATAR_id_avatar"];

            $query = DatabaseQueries::roleNameById($roleId);
            $result = $pdo->query($query);
            $result =  $result->fetch();
            $_SESSION["role"] = $result[0];

            $query = DatabaseQueries::avatarPathById($avatarId);
            $result = $pdo->query($query);
            $result =  $result->fetch();
            $_SESSION["avatar"] = $result[0];

            header("location: dashboard.php");
        } else {
            return false;
        }
    }

    public static function register($email, $password, $firstname, $lastname, $roleId, $avatarId, $pdo)
    {
        $query = DatabaseQueries::userFindByEmail($email);
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $count = $stmt->rowCount();
        if ($count == 0) {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $query = DatabaseQueries::userInsert($email, $passwordHash, $firstname, $lastname, $roleId, $avatarId);
            $pdo->query($query);
            User::login($email, $password, $pdo);
        } else {
            return false;
        }
    }

    public static function updateFirstName($firstname, $userId, $pdo) {
        $query = DatabaseQueries::updateFirstName($firstname, $userId);
        $pdo->query($query);
        $_SESSION["firstname"] = $firstname;
    }

    public static function updateLastName($lastname, $userId, $pdo) {
        $query = DatabaseQueries::updateLastName($lastname, $userId);
        $pdo->query($query);
        $_SESSION["lastname"] = $lastname;
    }

    public static function updateEmail($email, $userId, $pdo) {
        $query = DatabaseQueries::updateEmail($email, $userId);
        $pdo->query($query);
        $_SESSION["email"] = $email;
    }

    public static function updatePassword($password, $userId, $pdo) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $query = DatabaseQueries::updatePassword($passwordHash, $userId);
        $pdo->query($query);
    }

    public static function insertAvatar($path, $pdo) {
        $query = DatabaseQueries::insertAvatar($path);
        $pdo->query($query);
    }

    public static function selectAvatarByPath($path, $pdo) {
        $query = DatabaseQueries::selectAvatarIdByPath($path);
        $result = $pdo->query($query);
        $result = $result->fetch();
        return $result[0];

    }

    public static function updateAvatar($avatarId, $userId, $pdo) {
        $query = DatabaseQueries::updateAvatar($avatarId, $userId);
        $pdo->query($query);
    }

    public static function deleteAvatar($avatarId, $pdo) {
        $query = DatabaseQueries::deleteAvatar($avatarId);
        $pdo->query($query);
    }

}
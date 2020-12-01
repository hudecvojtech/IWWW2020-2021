<?php

class User
{
    private $conn;

    public function __construct(\PDO $pdo) {
        $this->conn = $pdo;
    }

    public function login($email, $password)
    {
        $query = "SELECT * FROM users WHERE email = '$email'";
        $stmt = $this->conn->query($query);
        $count = $stmt->rowCount();
        $row = $stmt->fetch();
        if ($count == 1) {
            if (!password_verify($password, $row["password"]))
                return false;

            $_SESSION["id"] = $row["id_user"];
            $_SESSION["email"] = $row["email"];
            $_SESSION["firstname"] = $row["firstname"];
            $_SESSION["lastname"] = $row["lastname"];
            $_SESSION["role"] = $row["role"];

            $avatarId = $row["AVATAR_id_avatar"];
            $query = "SELECT path FROM avatar WHERE id_avatar = '$avatarId'";
            $result = $this->conn->query($query);
            $result =  $result->fetch();
            $_SESSION["avatar"] = $result[0];

            header("location: dashboard.php");
        } else {
            return false;
        }
    }

    public function register($email, $password, $firstname, $lastname, $role, $avatarId)
    {
        $query = "SELECT * FROM users WHERE email = '$email'";
        $stmt = $this->conn->query($query);
        $count = $stmt->rowCount();
        if ($count == 0) {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO `users` (`email`, `password`, `firstname`, `lastname`, `role`, `AVATAR_id_avatar`)
 VALUES ('$email', '$passwordHash', '$firstname', '$lastname', '$role', '$avatarId')";
            var_dump($query);
            $this->conn->query($query);
            $this->login($email, $password);
        } else {
            return false;
        }
    }

    public function update($userId, $firstname, $lastname, $email, $password, $role, $avatarId) {
        $count = 0;
        $query = "UPDATE users ";
        if($firstname != $_SESSION["firstname"] && !empty($firstname)) {
            $_SESSION["firstname"] = $firstname;
            $query .= "SET firstname='$firstname'";
            $count++;
        }
        if($lastname != $_SESSION["lastname"] && !empty($lastname)) {
            $_SESSION["lastname"] = $lastname;
            $query .= "SET lastname='$lastname'";
            $count++;
        }
        if($email != $_SESSION["email"] && !empty($email)) {
            $_SESSION["email"] = $email;
            $query .= "SET email='$email'";
            $count++;
        }
        if(!empty($password)) {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $query .= "SET password='$passwordHash'";
            $count++;
        }
        if($role != $_SESSION["role"] && !empty($role)) {
            $_SESSION["role"] = $role;
            $query .= "SET role='$role'";
            $count++;
        }
        if($avatarId != $_SESSION["avatar"] && !empty($avatarId)) {
            $avat = new Avatar($this->conn);
            $_SESSION["avatar"] = $avat->getPath($avatarId);
            $query .= "SET AVATAR_id_avatar='$avatarId'";
            $count++;
        }

        $query .= " WHERE id_user = '$userId'";
        if($count > 0) {
            $this->conn->query($query);
            return true;
        }
        return false;
    }

}
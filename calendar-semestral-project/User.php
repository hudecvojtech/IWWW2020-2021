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
        $u = $this->getUser($userId);
        $count = 0;
        $query = "UPDATE users SET";
        if($firstname != $u["firstname"] && !empty($firstname)) {
            $query .= " firstname='$firstname',";
            $count++;
        }
        if($lastname != $u["lastname"] && !empty($lastname)) {
            $query .= " lastname='$lastname',";
            $count++;
        }
        if($email != $u["email"] && !empty($email)) {
            $query .= " email='$email',";
            $count++;
        }
        if(!empty($password)) {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $query .= " password='$passwordHash',";
            $count++;
        }

        if(!empty($avatarId)) {
            $avat = new Avatar($this->conn);
            if($avatarId != $u["AVATAR_id_avatar"]) {
                $query .= " AVATAR_id_avatar='$avatarId',";
                $count++;
            }
        }

        if(!empty($role)) {
            $query .= " role='$role'";
            $count++;
        }

        $query .= " WHERE id_user = '$userId'";
        if($count > 0) {
            $this->conn->query($query);
            return true;
        }
        return false;
    }

    public function getUsers() {
        $query = "SELECT * FROM users";
        $result = $this->conn->query($query);
        return $result->fetchAll();
    }

    public function getUser($id) {
        $query = "SELECT * FROM users WHERE id_user = '$id'";
        $result = $this->conn->query($query);
        return $result->fetch();
    }

    public function getUserByEmail($email) {
        $query = "SELECT * FROM users WHERE email = '$email'";
        $result = $this->conn->query($query);
        return $result->fetch()[0];
    }

    public function deleteUser($id)
    {
        $query = "DELETE FROM users WHERE id_user = '$id'";
        $this->conn->query($query);
    }

}
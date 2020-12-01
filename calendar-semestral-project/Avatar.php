<?php


class Avatar
{
    private $conn;

    public function __construct(\PDO $pdo) {
        $this->conn = $pdo;
    }

    public function insert($path) {
        $query = "INSERT INTO avatar(path) VALUES ('$path')";
        $this->conn->query($query);
    }

    public function getId($path) {
        $query = "SELECT id_avatar FROM avatar WHERE path = '$path'";
        $result = $this->conn->query($query);
        $result = $result->fetch();
        return $result[0];

    }

    public function getPath($id) {
        $query = "SELECT path FROM avatar WHERE id_avatar = '$id'";
        $result = $this->conn->query($query);
        $result = $result->fetch();
        return $result[0];
    }

    public function delete($avatarId) {
        $query = "DELETE FROM avatar WHERE id_avatar = '$avatarId'";
        $this->conn->query($query);
    }
}
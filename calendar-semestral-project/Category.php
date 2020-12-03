<?php

class Category
{
    private $conn;

    public function __construct(\PDO $pdo) {
        $this->conn = $pdo;
    }

    public function getCategories() {
        $query = "SELECT * FROM category";
        $result = $this->conn->query($query);
        return $result->fetchAll();
    }

    public function getCategoryId($name) {
        $query = "SELECT id_category FROM category WHERE name = '$name'";
        return $this->conn->query($query)->fetch()["id_category"];
    }

    public function getCategoryName($id) {
        $query = "SELECT name FROM category WHERE id_category = '$id'";
        return $this->conn->query($query)->fetch()["name"];
    }

    public function insertCategory($name) {
        $query = "INSERT INTO category(name) VALUES('$name')";
        $this->conn->query($query);
    }

    public function deleteCategory($id) {
        $query = "DELETE FROM category WHERE id_category = '$id'";
        $this->conn->query($query);
    }
}
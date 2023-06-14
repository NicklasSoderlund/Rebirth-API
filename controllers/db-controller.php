<?php

class DBController {

    protected $pdo;

    public function __construct($pdo) 
    {
        $this->pdo = $pdo;
    }

    public function getAll($table) 
    {
        $query = "SELECT * FROM $table";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($table, $id) 
    {
        $query = "SELECT * FROM $table WHERE id = $id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function customFetch($query) {

        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


  
}


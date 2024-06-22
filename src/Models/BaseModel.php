<?php

namespace App\Models;

use PDO;
use Exception;

abstract class BaseModel {
    protected $pdo;
    protected $table;
    protected $columns;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    protected function mapData($data) {
        foreach ($data as $column => $value) {
            if (property_exists($this, $column)) {
                $this->$column = $value;
            }
        }
    }

    public function save() {
        if (isset($this->id)) {
            $columns = [];
            $params = [];
            foreach ($this->columns as $column) {
                if ($column != 'id' || $column != 'created') {
                    $columns[] = "$column = :$column";
                    $params[$column] = $this->$column;
                }
            }
            $params['id'] = $this->id;
            $sql = "UPDATE $this->table SET " . implode(', ', $columns) . " WHERE id = :id";
        } else {
            $columns = [];
            $placeholders = [];
            $params = [];
            foreach ($this->columns as $column) {

                switch ($column){
                    case 'id':
                    case 'created':
                        break;
                    default:
                        $columns[] = $column;
                        $placeholders[] = ":$column";
                        $params[$column] = $this->$column;
                        break;
                }
            }
            $sql = "INSERT INTO $this->table (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        if (!isset($this->id)) {
            $this->id = $this->pdo->lastInsertId();
        }
    }

    public function delete() {
        if (isset($this->id)) {
            $sql = "DELETE FROM $this->table WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['id' => $this->id]);
        }
    }

    public static function all($pdo) {
        $instance = new static($pdo);
        $sql = "SELECT * FROM $instance->table";
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $models = [];
        foreach ($results as $result) {
            $model = new static($pdo);
            $model->mapData($result);
            $models[] = $model;
        }
        return $models;
    }

    public static function find($pdo, $id) {
        $instance = new static($pdo);
        $sql = "SELECT * FROM $instance->table WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $instance->mapData($result);
            return $instance;
        }
        return null;
    }
}
?>

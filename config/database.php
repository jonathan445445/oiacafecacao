<?php
/**
 * Classe de connexion à la base de données (Singleton)
 */

class Database {
    private static $instance = null;
    private $pdo;
    
    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";charset=" . DB_CHARSET;
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);
            
            // Sélectionner la base de données si elle existe
            try {
                $this->pdo->exec("USE `".DB_NAME."`");
            } catch (PDOException $e) {
                // Ne pas interrompre si la DB n'existe pas encore
            }
        } catch (PDOException $e) {
            die("Erreur de connexion à MySQL : " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->pdo;
    }
    
    public function query($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
    
    public function fetchAll($sql, $params = []) {
        try {
            $stmt = $this->query($sql, $params);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }
    
    public function fetchOne($sql, $params = []) {
        try {
            $stmt = $this->query($sql, $params);
            return $stmt->fetch();
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function insert($table, $data) {
        if (empty($data)) return false;
        
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $sql = "INSERT INTO `$table` ($columns) VALUES ($placeholders)";
        
        $this->query($sql, $data);
        return $this->pdo->lastInsertId();
    }
    
    public function update($table, $data, $where, $whereId = null) {
        if (empty($data)) return false;
        
        $set = [];
        foreach (array_keys($data) as $column) {
            $set[] = "`$column` = :$column";
        }
        $setClause = implode(', ', $set);
        
        if ($whereId !== null) {
            $sql = "UPDATE `$table` SET $setClause WHERE `id` = :where_id";
            $params = array_merge($data, ['where_id' => $whereId]);
        } else {
            $sql = "UPDATE `$table` SET $setClause WHERE $where";
            $params = $data;
        }
        
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }
    
    public function delete($table, $where, $params = []) {
        $sql = "DELETE FROM `$table` WHERE $where";
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }
}

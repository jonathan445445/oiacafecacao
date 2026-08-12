<?php
class Category {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function findAll() {
        try {
            $sql = "SELECT * FROM categories ORDER BY sort_order ASC, name ASC";
            return $this->db->fetchAll($sql);
        } catch (Exception $e) {
            return [];
        }
    }
    
    public function findById($id) {
        try {
            $sql = "SELECT * FROM categories WHERE id = ?";
            return $this->db->fetchOne($sql, [$id]);
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function findBySlug($slug) {
        try {
            $sql = "SELECT * FROM categories WHERE slug = ?";
            return $this->db->fetchOne($sql, [$slug]);
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function create($data) {
        try {
            if (!isset($data['slug']) && isset($data['name'])) {
                $data['slug'] = slugify($data['name']);
            }
            return $this->db->insert('categories', $data);
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function update($id, $data) {
        try {
            if (isset($data['name']) && !isset($data['slug'])) {
                $data['slug'] = slugify($data['name']);
            }
            return $this->db->update('categories', $data, '', $id);
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function delete($id) {
        try {
            return $this->db->delete('categories', 'id = ?', [$id]);
        } catch (Exception $e) {
            return false;
        }
    }

    public function count() {
        try {
            $result = $this->db->fetchOne("SELECT COUNT(*) AS total FROM categories");
            return $result ? intval($result['total']) : 0;
        } catch (Exception $e) {
            return 0;
        }
    }
}

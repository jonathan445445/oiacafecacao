<?php
class Comment {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function findByArticleId($articleId, $approvedOnly = true) {
        try {
            $sql = "SELECT * FROM article_comments WHERE article_id = ?";
            if ($approvedOnly) {
                $sql .= " AND is_approved = 1";
            }
            $sql .= " ORDER BY created_at DESC";
            return $this->db->fetchAll($sql, [$articleId]);
        } catch (Exception $e) {
            return [];
        }
    }
    
    public function create($data) {
        try {
            return $this->db->insert('article_comments', $data);
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function countByArticleId($articleId, $approvedOnly = true) {
        try {
            $sql = "SELECT COUNT(*) as total FROM article_comments WHERE article_id = ?";
            if ($approvedOnly) {
                $sql .= " AND is_approved = 1";
            }
            $result = $this->db->fetchOne($sql, [$articleId]);
            return $result ? $result['total'] : 0;
        } catch (Exception $e) {
            return 0;
        }
    }
}

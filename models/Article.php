<?php
class Article {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    private function decodeData($data) {
        if (is_array($data)) {
            foreach ($data as &$item) {
                if (is_array($item)) {
                    $item = $this->decodeData($item);
                } else if (is_string($item)) {
                    $item = html_entity_decode($item, ENT_QUOTES, 'UTF-8');
                }
            }
        } elseif (is_string($data)) {
            $data = html_entity_decode($data, ENT_QUOTES, 'UTF-8');
        }
        return $data;
    }
    
    public function findAll($page = 1, $per_page = 10, $status = 'published') {
        try {
            $offset = ($page - 1) * $per_page;
            $sql = "SELECT a.*, CONCAT(u.first_name, ' ', u.last_name) as author_name, 
                           c.name as category_name, c.slug as category_slug
                    FROM articles a 
                    JOIN users u ON a.author_id = u.id 
                    LEFT JOIN categories c ON a.category_id = c.id
                    WHERE a.status = ? 
                    ORDER BY a.published_at DESC, a.created_at DESC 
                    LIMIT ? OFFSET ?";
            $result = $this->db->fetchAll($sql, [$status, $per_page, $offset]);
            return $this->decodeData($result);
        } catch (Exception $e) {
            return [];
        }
    }
    
    public function findAllAdmin($page = 1, $per_page = 10) {
        try {
            $offset = ($page - 1) * $per_page;
            $sql = "SELECT a.*, CONCAT(u.first_name, ' ', u.last_name) as author_name,
                           c.name as category_name
                    FROM articles a 
                    JOIN users u ON a.author_id = u.id 
                    LEFT JOIN categories c ON a.category_id = c.id
                    ORDER BY a.created_at DESC 
                    LIMIT ? OFFSET ?";
            $result = $this->db->fetchAll($sql, [$per_page, $offset]);
            return $this->decodeData($result);
        } catch (Exception $e) {
            return [];
        }
    }
    
    public function findById($id) {
        try {
            $sql = "SELECT a.*, CONCAT(u.first_name, ' ', u.last_name) as author_name,
                           c.name as category_name, c.slug as category_slug
                    FROM articles a 
                    JOIN users u ON a.author_id = u.id 
                    LEFT JOIN categories c ON a.category_id = c.id
                    WHERE a.id = ?";
            $result = $this->db->fetchOne($sql, [$id]);
            return $this->decodeData($result);
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function findBySlug($slug) {
        try {
            $sql = "SELECT a.*, CONCAT(u.first_name, ' ', u.last_name) as author_name,
                           c.name as category_name, c.slug as category_slug
                    FROM articles a 
                    JOIN users u ON a.author_id = u.id 
                    LEFT JOIN categories c ON a.category_id = c.id
                    WHERE a.slug = ? AND a.status = 'published'";
            $result = $this->db->fetchOne($sql, [$slug]);
            return $this->decodeData($result);
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function findFeatured($limit = 4) {
        try {
            $sql = "SELECT a.*, CONCAT(u.first_name, ' ', u.last_name) as author_name,
                           c.name as category_name, c.slug as category_slug
                    FROM articles a 
                    JOIN users u ON a.author_id = u.id 
                    LEFT JOIN categories c ON a.category_id = c.id
                    WHERE a.status = 'published' AND a.is_featured = 1 
                    ORDER BY a.published_at DESC 
                    LIMIT ?";
            $result = $this->db->fetchAll($sql, [$limit]);
            return $this->decodeData($result);
        } catch (Exception $e) {
            return [];
        }
    }
    
    public function search($query, $page = 1, $per_page = 10) {
        try {
            $offset = ($page - 1) * $per_page;
            $search = "%$query%";
            $sql = "SELECT a.*, CONCAT(u.first_name, ' ', u.last_name) as author_name,
                           c.name as category_name, c.slug as category_slug
                    FROM articles a 
                    JOIN users u ON a.author_id = u.id 
                    LEFT JOIN categories c ON a.category_id = c.id
                    WHERE a.status = 'published' 
                    AND (a.title LIKE ? OR a.content LIKE ? OR a.excerpt LIKE ?) 
                    ORDER BY a.published_at DESC 
                    LIMIT ? OFFSET ?";
            $result = $this->db->fetchAll($sql, [$search, $search, $search, $per_page, $offset]);
            return $this->decodeData($result);
        } catch (Exception $e) {
            return [];
        }
    }

    public function searchCount($query) {
        try {
            $search = "%$query%";
            $sql = "SELECT COUNT(*) as total FROM articles a WHERE a.status = 'published' AND (a.title LIKE ? OR a.content LIKE ? OR a.excerpt LIKE ?)";
            $result = $this->db->fetchOne($sql, [$search, $search, $search]);
            return $result ? (int)$result['total'] : 0;
        } catch (Exception $e) {
            return 0;
        }
    }
    
    public function create($data) {
        try {
            if (!isset($data['slug']) && isset($data['title'])) {
                $data['slug'] = slugify($data['title']);
            }
            // S'assurer que category_id est NULL si non défini ou vide
            if (!isset($data['category_id']) || empty($data['category_id'])) {
                $data['category_id'] = null;
            }
            return $this->db->insert('articles', $data);
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function update($id, $data) {
        try {
            if (isset($data['title']) && !isset($data['slug'])) {
                $data['slug'] = slugify($data['title']);
            }
            // S'assurer que category_id est NULL si non défini ou vide
            if (!isset($data['category_id']) || empty($data['category_id'])) {
                $data['category_id'] = null;
            }
            return $this->db->update('articles', $data, '', $id);
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function delete($id) {
        try {
            return $this->db->delete('articles', 'id = ?', [$id]);
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function incrementViews($id) {
        try {
            $this->db->query("UPDATE articles SET views = views + 1 WHERE id = ?", [$id]);
        } catch (Exception $e) {
            // Ne rien faire
        }
    }
    
    public function count($status = 'published') {
        try {
            $result = $this->db->fetchOne("SELECT COUNT(*) as total FROM articles WHERE status = ?", [$status]);
            return $result ? $result['total'] : 0;
        } catch (Exception $e) {
            return 0;
        }
    }
    
    public function countAll() {
        try {
            $result = $this->db->fetchOne("SELECT COUNT(*) as total FROM articles");
            return $result ? $result['total'] : 0;
        } catch (Exception $e) {
            return 0;
        }
    }
    
    public function getLatest($limit = 5) {
        try {
            $sql = "SELECT a.*, CONCAT(u.first_name, ' ', u.last_name) as author_name,
                           c.name as category_name, c.slug as category_slug
                    FROM articles a 
                    JOIN users u ON a.author_id = u.id 
                    LEFT JOIN categories c ON a.category_id = c.id
                    WHERE a.status = 'published' 
                    ORDER BY a.created_at DESC 
                    LIMIT ?";
            $result = $this->db->fetchAll($sql, [$limit]);
            return $this->decodeData($result);
        } catch (Exception $e) {
            return [];
        }
    }
}

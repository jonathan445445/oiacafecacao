<?php
class Banner {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function findAll($page = 1, $perPage = 20, $onlyPublished = true) {
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT * FROM banners";
        $params = [];
        $conditions = [];

        if ($onlyPublished) {
            $conditions[] = "is_published = 1";
        }

        if (!empty($conditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $sql .= ' ORDER BY sort_order ASC, created_at DESC LIMIT ?, ?';
        $params[] = $offset;
        $params[] = $perPage;

        return $this->db->fetchAll($sql, $params);
    }

    public function findById($id) {
        return $this->db->fetchOne("SELECT * FROM banners WHERE id = ?", [$id]);
    }

    public function create($data) {
        return $this->db->insert('banners', $data);
    }

    public function update($id, $data) {
        return $this->db->update('banners', $data, 'id = :where_id', $id);
    }

    public function delete($id) {
        return $this->db->delete('banners', 'id = ?', [$id]);
    }
}

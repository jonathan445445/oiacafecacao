<?php
class Filiere {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function findAll($page = 1, $per_page = 12, $published = true) {
        try {
            $offset = ($page - 1) * $per_page;
            $sql = "SELECT * FROM filieres";
            $params = [];
            if ($published) {
                $sql .= " WHERE is_published = 1";
            }
            $sql .= " ORDER BY sort_order ASC, created_at DESC LIMIT ? OFFSET ?";
            $params[] = $per_page;
            $params[] = $offset;

            return $this->db->fetchAll($sql, $params);
        } catch (Exception $e) {
            return [];
        }
    }

    public function findAllAdmin($page = 1, $per_page = 20) {
        return $this->findAll($page, $per_page, false);
    }

    public function findBySlug($slug, $published = true) {
        try {
            $sql = "SELECT * FROM filieres WHERE slug = ?";
            $params = [$slug];
            if ($published) {
                $sql .= " AND is_published = 1";
            }
            return $this->db->fetchOne($sql, $params);
        } catch (Exception $e) {
            return false;
        }
    }

    public function findById($id) {
        try {
            return $this->db->fetchOne("SELECT * FROM filieres WHERE id = ?", [$id]);
        } catch (Exception $e) {
            return false;
        }
    }

    public function create($data) {
        try {
            if ((empty($data['slug']) || !isset($data['slug'])) && !empty($data['name'])) {
                $data['slug'] = $this->generateUniqueSlug($data['name']);
            }
            return $this->db->insert('filieres', $data);
        } catch (Exception $e) {
            return false;
        }
    }

    public function update($id, $data) {
        try {
            if ((empty($data['slug']) || !isset($data['slug'])) && !empty($data['name'])) {
                $data['slug'] = $this->generateUniqueSlug($data['name'], $id);
            }
            return $this->db->update('filieres', $data, '', $id);
        } catch (Exception $e) {
            return false;
        }
    }

    public function delete($id) {
        try {
            return $this->db->delete('filieres', 'id = ?', [$id]);
        } catch (Exception $e) {
            return false;
        }
    }

    public function count($published = true) {
        try {
            $sql = "SELECT COUNT(*) AS total FROM filieres";
            $params = [];
            if ($published) {
                $sql .= " WHERE is_published = 1";
            }
            $result = $this->db->fetchOne($sql, $params);
            return $result ? $result['total'] : 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    private function generateUniqueSlug($name, $currentId = null) {
        $slug = slugify($name);
        $baseSlug = $slug;
        $counter = 1;

        while (true) {
            $existing = $this->findBySlug($slug, false);
            if (!$existing || ($currentId !== null && $existing['id'] == $currentId)) {
                break;
            }
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}

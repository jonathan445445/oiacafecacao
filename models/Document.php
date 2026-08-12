<?php
class Document {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function findAll($page = 1, $per_page = 12, $published = true) {
        try {
            $offset = ($page - 1) * $per_page;
            $sql = "SELECT * FROM documents";
            $params = [];

            if ($published) {
                $sql .= " WHERE is_published = 1";
            }

            $sql .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";
            $params[] = $per_page;
            $params[] = $offset;

            return $this->db->fetchAll($sql, $params);
        } catch (Exception $e) {
            return [];
        }
    }

    public function findAllAdmin($page = 1, $per_page = 20) {
        try {
            $offset = ($page - 1) * $per_page;
            $sql = "SELECT * FROM documents ORDER BY created_at DESC LIMIT ? OFFSET ?";
            return $this->db->fetchAll($sql, [$per_page, $offset]);
        } catch (Exception $e) {
            return [];
        }
    }

    public function findById($id) {
        try {
            return $this->db->fetchOne("SELECT * FROM documents WHERE id = ?", [$id]);
        } catch (Exception $e) {
            return false;
        }
    }

    public function findBySlug($slug) {
        try {
            return $this->db->fetchOne("SELECT * FROM documents WHERE slug = ? AND is_published = 1", [$slug]);
        } catch (Exception $e) {
            return false;
        }
    }

    public function findBySlugAnyStatus($slug) {
        try {
            return $this->db->fetchOne("SELECT * FROM documents WHERE slug = ?", [$slug]);
        } catch (Exception $e) {
            return false;
        }
    }

    public function create($data) {
        try {
            if (!isset($data['slug']) && isset($data['title'])) {
                $data['slug'] = $this->generateUniqueSlug($data['title']);
            }

            return $this->db->insert('documents', $data);
        } catch (Exception $e) {
            return false;
        }
    }

    public function update($id, $data) {
        try {
            if (isset($data['title']) && !isset($data['slug'])) {
                $data['slug'] = $this->generateUniqueSlug($data['title'], $id);
            }
            return $this->db->update('documents', $data, '', $id);
        } catch (Exception $e) {
            return false;
        }
    }

    public function delete($id) {
        try {
            return $this->db->delete('documents', 'id = ?', [$id]);
        } catch (Exception $e) {
            return false;
        }
    }

    public function count($published = true) {
        try {
            $sql = "SELECT COUNT(*) as total FROM documents";
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

    private function generateUniqueSlug($title, $currentId = null) {
        $slug = slugify($title);
        $baseSlug = $slug;
        $counter = 1;

        while (true) {
            $existing = $this->findBySlugAnyStatus($slug);
            if (!$existing || ($currentId !== null && $existing['id'] == $currentId)) {
                break;
            }
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}

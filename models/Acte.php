<?php
class Acte {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    private function decodeData($data) {
        if (is_array($data)) {
            foreach ($data as &$item) {
                if (is_array($item)) {
                    $item = $this->decodeData($item);
                } elseif (is_string($item)) {
                    $item = html_entity_decode($item, ENT_QUOTES, 'UTF-8');
                }
            }
        } elseif (is_string($data)) {
            $data = html_entity_decode($data, ENT_QUOTES, 'UTF-8');
        }
        return $data;
    }

    public function findAll($page = 1, $per_page = 12, $published = true) {
        try {
            $offset = ($page - 1) * $per_page;
            $sql = "SELECT * FROM actes_oia";
            $params = [];
            
            if ($published) {
                $sql .= " WHERE is_published = 1";
            }
            
            $sql .= " ORDER BY date_pub DESC, created_at DESC LIMIT ? OFFSET ?";
            $params[] = $per_page;
            $params[] = $offset;

            $result = $this->db->fetchAll($sql, $params);
            return $this->decodeData($result);
        } catch (Exception $e) {
            return [];
        }
    }

    public function findAllAdmin($page = 1, $per_page = 20) {
        try {
            $offset = ($page - 1) * $per_page;
            $sql = "SELECT * FROM actes_oia ORDER BY date_pub DESC, created_at DESC LIMIT ? OFFSET ?";
            return $this->db->fetchAll($sql, [$per_page, $offset]);
        } catch (Exception $e) {
            return [];
        }
    }

    public function findById($id, $published = true) {
        try {
            $sql = "SELECT * FROM actes_oia WHERE id = ?";
            $params = [$id];
            if ($published) {
                $sql .= " AND is_published = 1";
            }
            $result = $this->db->fetchOne($sql, $params);
            return $this->decodeData($result);
        } catch (Exception $e) {
            return false;
        }
    }

    public function findBySlug($slug, $published = true) {
        try {
            $sql = "SELECT * FROM actes_oia WHERE slug = ?";
            $params = [$slug];
            if ($published) {
                $sql .= " AND is_published = 1";
            }
            $result = $this->db->fetchOne($sql, $params);
            return $this->decodeData($result);
        } catch (Exception $e) {
            return false;
        }
    }

    public function create($data) {
        try {
            if (empty($data['slug']) && !empty($data['title'])) {
                $data['slug'] = $this->generateUniqueSlug($data['title']);
            } else {
                $data['slug'] = $this->generateUniqueSlug($data['slug'] ?? $data['title'] ?? '');
            }
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
            return $this->db->insert('actes_oia', $data);
        } catch (Exception $e) {
            return false;
        }
    }

    public function update($id, $data) {
        try {
            if (isset($data['title']) && empty($data['slug'])) {
                $data['slug'] = $this->generateUniqueSlug($data['title'], $id);
            }
            $data['updated_at'] = date('Y-m-d H:i:s');
            return $this->db->update('actes_oia', $data, '', $id);
        } catch (Exception $e) {
            return false;
        }
    }

    public function delete($id) {
        try {
            return $this->db->delete('actes_oia', 'id = ?', [$id]);
        } catch (Exception $e) {
            return false;
        }
    }

    private function generateUniqueSlug($base, $currentId = null) {
        $slug = slugify($base);
        if (empty($slug)) {
            $slug = 'acte-oia';
        }

        $original = $slug;
        $counter = 1;

        while (true) {
            $existing = $this->findBySlugAnyStatus($slug);
            if (!$existing || ($currentId !== null && $existing['id'] == $currentId)) {
                break;
            }
            $slug = $original . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    public function findBySlugAnyStatus($slug) {
        try {
            return $this->db->fetchOne("SELECT * FROM actes_oia WHERE slug = ?", [$slug]);
        } catch (Exception $e) {
            return false;
        }
    }
}

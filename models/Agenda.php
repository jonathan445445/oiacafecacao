<?php
class Agenda {
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

    public function findAll($page = 1, $per_page = 12, $published = true, $filters = []) {
        try {
            $offset = ($page - 1) * $per_page;
            $sql = "SELECT * FROM agenda";
            $conditions = [];
            $params = [];

            if ($published) {
                $conditions[] = "is_published = 1";
            }

            if (!empty($filters['q'])) {
                $search = '%' . $filters['q'] . '%';
                $conditions[] = "(title LIKE ? OR description LIKE ? OR location LIKE ?)";
                $params = array_merge($params, [$search, $search, $search]);
            }

            if (!empty($filters['month']) && preg_match('/^\d{4}-\d{2}$/', $filters['month'])) {
                $conditions[] = "DATE_FORMAT(start_date, '%Y-%m') = ?";
                $params[] = $filters['month'];
            }

            if (!empty($conditions)) {
                $sql .= ' WHERE ' . implode(' AND ', $conditions);
            }

            $sql .= " ORDER BY start_date ASC, start_time ASC, created_at DESC LIMIT ? OFFSET ?";
            $params[] = $per_page;
            $params[] = $offset;

            $result = $this->db->fetchAll($sql, $params);
            return $this->decodeData($result);
        } catch (Exception $e) {
            return [];
        }
    }

    public function findUpcoming($limit = 10) {
        try {
            $today = date('Y-m-d');
            $sql = "SELECT * FROM agenda WHERE is_published = 1 AND (DATE(start_date) >= ? OR (end_date IS NOT NULL AND DATE(end_date) >= ?)) ORDER BY start_date ASC, start_time ASC LIMIT ?";
            $result = $this->db->fetchAll($sql, [$today, $today, $limit]);
            return $this->decodeData($result);
        } catch (Exception $e) {
            return [];
        }
    }

    public function findAllFuture($page = 1, $per_page = 12) {
        try {
            $offset = ($page - 1) * $per_page;
            $today = date('Y-m-d');
            $sql = "SELECT * FROM agenda WHERE is_published = 1 AND (DATE(start_date) >= ? OR (end_date IS NOT NULL AND DATE(end_date) >= ?)) ORDER BY start_date ASC, start_time ASC LIMIT ? OFFSET ?";
            $result = $this->db->fetchAll($sql, [$today, $today, $per_page, $offset]);
            return $this->decodeData($result);
        } catch (Exception $e) {
            return [];
        }
    }

    public function findAllAdmin($page = 1, $per_page = 20) {
        try {
            $offset = ($page - 1) * $per_page;
            $sql = "SELECT * FROM agenda ORDER BY start_date DESC, created_at DESC LIMIT ? OFFSET ?";
            return $this->db->fetchAll($sql, [$per_page, $offset]);
        } catch (Exception $e) {
            return [];
        }
    }

    public function findById($id, $published = true) {
        try {
            $sql = "SELECT * FROM agenda WHERE id = ?";
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
            $sql = "SELECT * FROM agenda WHERE slug = ?";
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

    public function getMonths() {
        try {
            $result = $this->db->fetchAll("SELECT DISTINCT DATE_FORMAT(start_date, '%Y-%m') AS month, DATE_FORMAT(start_date, '%Y') AS year, DATE_FORMAT(start_date, '%m') AS month_num FROM agenda WHERE is_published = 1 ORDER BY month DESC");
            return $result;
        } catch (Exception $e) {
            return [];
        }
    }

    public function create($data) {
        try {
            if (empty($data['slug']) && !empty($data['title'])) {
                $data['slug'] = $this->generateUniqueSlug($data['title']);
            }
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
            return $this->db->insert('agenda', $data);
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
            return $this->db->update('agenda', $data, '', $id);
        } catch (Exception $e) {
            return false;
        }
    }

    public function delete($id) {
        try {
            return $this->db->delete('agenda', 'id = ?', [$id]);
        } catch (Exception $e) {
            return false;
        }
    }

    private function generateUniqueSlug($base, $currentId = null) {
        $slug = slugify($base);
        if (empty($slug)) {
            $slug = 'agenda-event';
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
            return $this->db->fetchOne("SELECT * FROM agenda WHERE slug = ?", [$slug]);
        } catch (Exception $e) {
            return false;
        }
    }
}

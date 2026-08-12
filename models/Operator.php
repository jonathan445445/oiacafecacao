<?php
class Operator {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    private function buildBaseQuery($published = true) {
        $sql = "SELECT o.*, 
            GROUP_CONCAT(DISTINCT f.id ORDER BY f.name SEPARATOR ',') AS filiere_ids,
            GROUP_CONCAT(DISTINCT f.name ORDER BY f.name SEPARATOR ', ') AS filiere_names,
            GROUP_CONCAT(DISTINCT f.slug ORDER BY f.name SEPARATOR ',') AS filiere_slugs,
            MIN(f.name) AS filiere_name,
            MIN(f.slug) AS filiere_slug
            FROM operators o
            LEFT JOIN operator_filieres ofp ON o.id = ofp.operator_id
            LEFT JOIN filieres f ON ofp.filiere_id = f.id";

        if ($published) {
            $sql .= " WHERE o.is_published = 1";
        }

        return $sql;
    }

    public function findAll($page = 1, $per_page = 12, $published = true) {
        try {
            $offset = ($page - 1) * $per_page;
            $sql = $this->buildBaseQuery($published);
            $sql .= " GROUP BY o.id ORDER BY o.sort_order ASC, o.created_at DESC LIMIT ? OFFSET ?";

            return $this->db->fetchAll($sql, [$per_page, $offset]);
        } catch (Exception $e) {
            return [];
        }
    }

    public function findAllAdmin($page = 1, $per_page = 20) {
        return $this->findAll($page, $per_page, false);
    }

    public function findByType($type, $page = 1, $per_page = 12, $published = true) {
        if (!in_array($type, ['acheteur', 'operateur'], true)) {
            return [];
        }

        try {
            $offset = ($page - 1) * $per_page;
            $sql = $this->buildBaseQuery(false) . " WHERE o.type = ?";
            $params = [$type];
            if ($published) {
                $sql .= " AND o.is_published = 1";
            }
            $sql .= " GROUP BY o.id ORDER BY o.sort_order ASC, o.created_at DESC LIMIT ? OFFSET ?";
            $params[] = $per_page;
            $params[] = $offset;

            return $this->db->fetchAll($sql, $params);
        } catch (Exception $e) {
            return [];
        }
    }

    public function findBySlug($slug, $published = true) {
        try {
            $sql = $this->buildBaseQuery(false) . " WHERE o.slug = ?";
            $params = [$slug];
            if ($published) {
                $sql .= " AND o.is_published = 1";
            }
            $sql .= " GROUP BY o.id";
            return $this->db->fetchOne($sql, $params);
        } catch (Exception $e) {
            return false;
        }
    }

    public function findById($id) {
        try {
            $sql = $this->buildBaseQuery(false) . " WHERE o.id = ? GROUP BY o.id";
            return $this->db->fetchOne($sql, [$id]);
        } catch (Exception $e) {
            return false;
        }
    }

    public function getFilieresByOperatorId($operatorId) {
        try {
            return $this->db->fetchAll(
                "SELECT f.* FROM operator_filieres ofp JOIN filieres f ON ofp.filiere_id = f.id WHERE ofp.operator_id = ? ORDER BY f.name ASC",
                [$operatorId]
            );
        } catch (Exception $e) {
            return [];
        }
    }

    public function syncFilieres($operatorId, array $filiereIds) {
        try {
            $this->db->query("DELETE FROM operator_filieres WHERE operator_id = ?", [$operatorId]);
            $uniqueIds = array_unique(array_filter(array_map('intval', $filiereIds), fn($id) => $id > 0));
            foreach ($uniqueIds as $filiereId) {
                $this->db->insert('operator_filieres', [
                    'operator_id' => $operatorId,
                    'filiere_id' => $filiereId
                ]);
            }
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function create($data) {
        try {
            if ((empty($data['slug']) || !isset($data['slug'])) && !empty($data['name'])) {
                $data['slug'] = $this->generateUniqueSlug($data['name']);
            }

            $filiereIds = $data['filiere_ids'] ?? [];
            unset($data['filiere_ids']);

            if (!empty($filiereIds) && is_array($filiereIds)) {
                $data['filiere_id'] = intval($filiereIds[0]);
            } else {
                $data['filiere_id'] = null;
            }

            $operatorId = $this->db->insert('operators', $data);
            if ($operatorId && !empty($filiereIds)) {
                $this->syncFilieres($operatorId, $filiereIds);
            }

            return $operatorId;
        } catch (Exception $e) {
            return false;
        }
    }

    public function update($id, $data) {
        try {
            if ((empty($data['slug']) || !isset($data['slug'])) && !empty($data['name'])) {
                $data['slug'] = $this->generateUniqueSlug($data['name'], $id);
            }

            $filiereIds = $data['filiere_ids'] ?? [];
            unset($data['filiere_ids']);

            if (!empty($filiereIds) && is_array($filiereIds)) {
                $data['filiere_id'] = intval($filiereIds[0]);
            } else {
                $data['filiere_id'] = null;
            }

            $result = $this->db->update('operators', $data, '', $id);
            if ($result !== false) {
                $this->syncFilieres($id, $filiereIds);
            }

            return $result;
        } catch (Exception $e) {
            return false;
        }
    }

    public function delete($id) {
        try {
            $this->db->query("DELETE FROM operator_filieres WHERE operator_id = ?", [$id]);
            return $this->db->delete('operators', 'id = ?', [$id]);
        } catch (Exception $e) {
            return false;
        }
    }

    public function count($type = null, $published = true) {
        try {
            $sql = "SELECT COUNT(*) AS total FROM operators";
            $params = [];

            if ($type !== null) {
                $sql .= " WHERE type = ?";
                $params[] = $type;
                if ($published) {
                    $sql .= " AND is_published = 1";
                }
            } elseif ($published) {
                $sql .= " WHERE is_published = 1";
            }

            $result = $this->db->fetchOne($sql, $params);
            return $result ? intval($result['total']) : 0;
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

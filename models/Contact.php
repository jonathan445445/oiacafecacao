<?php

class Contact {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    // ==================== COORDONNEES ====================
    
    public function getAllCoordonnees($only_active = true) {
        $sql = "SELECT * FROM contact_coordonnees";
        $params = [];
        if ($only_active) {
            $sql .= " WHERE statut = 1";
        }
        $sql .= " ORDER BY ordre_affichage ASC, id ASC";
        return $this->db->fetchAll($sql, $params);
    }
    
    public function getCoordonneeById($id) {
        $sql = "SELECT * FROM contact_coordonnees WHERE id = ?";
        return $this->db->fetchOne($sql, [$id]);
    }
    
    public function addCoordonnee($data) {
        return $this->db->insert('contact_coordonnees', [
            'type' => $data['type'],
            'valeur' => $data['valeur'],
            'titre' => $data['titre'] ?? null,
            'icone' => $data['icone'] ?? null,
            'lien' => $data['lien'] ?? null,
            'ordre_affichage' => $data['ordre_affichage'] ?? 0,
            'statut' => $data['statut'] ?? 1
        ]);
    }
    
    public function updateCoordonnee($id, $data) {
        return $this->db->update('contact_coordonnees', $data, '', $id);
    }
    
    public function deleteCoordonnee($id) {
        return $this->db->delete('contact_coordonnees', 'id = ?', [$id]);
    }
    
    // ==================== ADRESSES ====================
    
    public function getAllAdresses($only_active = true) {
        $sql = "SELECT * FROM contact_adresses";
        $params = [];
        if ($only_active) {
            $sql .= " WHERE statut = 1";
        }
        $sql .= " ORDER BY ordre_affichage ASC, id ASC";
        return $this->db->fetchAll($sql, $params);
    }
    
    public function getAdresseById($id) {
        $sql = "SELECT * FROM contact_adresses WHERE id = ?";
        return $this->db->fetchOne($sql, [$id]);
    }
    
    public function addAdresse($data) {
        return $this->db->insert('contact_adresses', [
            'titre' => $data['titre'],
            'adresse' => $data['adresse'],
            'latitude' => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
            'google_maps_url' => $data['google_maps_url'] ?? null,
            'zoom_level' => $data['zoom_level'] ?? 15,
            'ordre_affichage' => $data['ordre_affichage'] ?? 0,
            'statut' => $data['statut'] ?? 1
        ]);
    }
    
    public function updateAdresse($id, $data) {
        return $this->db->update('contact_adresses', $data, '', $id);
    }
    
    public function deleteAdresse($id) {
        return $this->db->delete('contact_adresses', 'id = ?', [$id]);
    }
    
    // ==================== MESSAGES ====================
    
    public function getAllMessages($page = 1, $per_page = 10, $status = null, $search = '') {
        $offset = ($page - 1) * $per_page;
        $sql = "SELECT * FROM contact_messages WHERE 1=1";
        $params = [];

        if ($status) {
            $sql .= " AND status = ?";
            $params[] = $status;
        }

        if (!empty($search)) {
            $sql .= " AND (nom LIKE ? OR email LIKE ? OR objet LIKE ? OR message LIKE ?)";
            $searchTerm = "%{$search}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        $sql .= " ORDER BY date_add DESC LIMIT ? OFFSET ?";
        $params[] = $per_page;
        $params[] = $offset;

        return $this->db->fetchAll($sql, $params);
    }
    
    public function getTotalMessages($status = null, $search = '') {
        $sql = "SELECT COUNT(*) AS total FROM contact_messages WHERE 1=1";
        $params = [];

        if ($status) {
            $sql .= " AND status = ?";
            $params[] = $status;
        }

        if (!empty($search)) {
            $sql .= " AND (nom LIKE ? OR email LIKE ? OR objet LIKE ? OR message LIKE ?)";
            $searchTerm = "%{$search}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        $result = $this->db->fetchOne($sql, $params);
        return $result ? $result['total'] : 0;
    }
    
    public function getMessageById($id) {
        $sql = "SELECT * FROM contact_messages WHERE id = ?";
        return $this->db->fetchOne($sql, [$id]);
    }
    
    public function addMessage($data) {
        Logger::contact('Tentative d\'enregistrement de message', [
            'nom' => $data['nom'],
            'email' => $data['email']
        ]);

        $messageId = $this->db->insert('contact_messages', [
            'nom' => $data['nom'],
            'telephone' => $data['telephone'] ?? null,
            'email' => $data['email'],
            'objet' => $data['objet'] ?? null,
            'service' => $data['service'] ?? null,
            'message' => $data['message'],
            'ip_visiteur' => $_SERVER['REMOTE_ADDR'] ?? null,
            'pays' => $data['pays'] ?? null,
            'ville' => $data['ville'] ?? null,
            'navigateur' => $_SERVER['HTTP_USER_AGENT'] ?? null,
            'is_read' => 0,
            'status' => 'nouveau'
        ]);

        Logger::contact('Message enregistré avec succès', ['id' => $messageId]);
        return $messageId;
    }
    
    public function updateMessage($id, $data) {
        return $this->db->update('contact_messages', $data, '', $id);
    }
    
    public function markAsRead($id) {
        return $this->db->update('contact_messages', ['is_read' => 1], '', $id);
    }
    
    public function markAsUnread($id) {
        return $this->db->update('contact_messages', ['is_read' => 0], '', $id);
    }
    
    public function updateMessageStatus($id, $status) {
        return $this->db->update('contact_messages', ['status' => $status], '', $id);
    }
    
    public function deleteMessage($id) {
        // Supprimer les réponses associées d'abord
        $this->db->delete('contact_replies', 'message_id = ?', [$id]);
        return $this->db->delete('contact_messages', 'id = ?', [$id]);
    }
    
    // ==================== REPONSES ====================
    
    public function getRepliesByMessageId($message_id) {
        $sql = "SELECT r.*, u.name AS user_name FROM contact_replies r 
                LEFT JOIN users u ON r.user_id = u.id 
                WHERE r.message_id = ? 
                ORDER BY r.date_add ASC";
        return $this->db->fetchAll($sql, [$message_id]);
    }
    
    public function addReply($message_id, $content, $user_id = null) {
        $replyId = $this->db->insert('contact_replies', [
            'message_id' => $message_id,
            'user_id' => $user_id,
            'content' => $content
        ]);

        // Mettre à jour le statut du message à "traité"
        $this->updateMessageStatus($message_id, 'traite');
        $this->markAsRead($message_id);

        Logger::contact('Réponse ajoutée', ['id' => $replyId, 'message_id' => $message_id]);
        return $replyId;
    }
    
    public function deleteReply($reply_id) {
        return $this->db->delete('contact_replies', 'id = ?', [$reply_id]);
    }
    
    // ==================== STATISTIQUES ====================
    
    public function getDashboardStats() {
        $today = date('Y-m-d 00:00:00');
        $monthStart = date('Y-m-01 00:00:00');
        $now = date('Y-m-d H:i:s');

        return [
            'total' => $this->countMessages(),
            'unread' => $this->countUnreadMessages(),
            'nouveau' => $this->countMessagesByStatus('nouveau'),
            'en_cours' => $this->countMessagesByStatus('en_cours'),
            'traite' => $this->countMessagesByStatus('traite'),
            'archive' => $this->countMessagesByStatus('archive'),
            'today' => $this->countMessagesByPeriod($today, $now),
            'month' => $this->countMessagesByPeriod($monthStart, $now)
        ];
    }

    private function countMessages() {
        try {
            $sql = "SELECT COUNT(*) AS total FROM contact_messages";
            $result = $this->db->fetchOne($sql);
            return $result ? $result['total'] : 0;
        } catch (Exception $e) {
            Logger::error('Erreur countMessages', ['exception' => $e->getMessage()]);
            return 0;
        }
    }

    private function countUnreadMessages() {
        try {
            $sql = "SELECT COUNT(*) AS total FROM contact_messages WHERE is_read = 0";
            $result = $this->db->fetchOne($sql);
            return $result ? $result['total'] : 0;
        } catch (Exception $e) {
            Logger::error('Erreur countUnreadMessages', ['exception' => $e->getMessage()]);
            return 0;
        }
    }

    private function countMessagesByStatus($status) {
        try {
            $sql = "SELECT COUNT(*) AS total FROM contact_messages WHERE status = ?";
            $result = $this->db->fetchOne($sql, [$status]);
            return $result ? $result['total'] : 0;
        } catch (Exception $e) {
            Logger::error('Erreur countMessagesByStatus', ['exception' => $e->getMessage()]);
            return 0;
        }
    }

    private function countMessagesByPeriod($start_date, $end_date) {
        try {
            $sql = "SELECT COUNT(*) AS total FROM contact_messages 
                    WHERE date_add BETWEEN ? AND ?";
            $result = $this->db->fetchOne($sql, [$start_date, $end_date]);
            return $result ? $result['total'] : 0;
        } catch (Exception $e) {
            Logger::error('Erreur countMessagesByPeriod', ['exception' => $e->getMessage()]);
            return 0;
        }
    }
}

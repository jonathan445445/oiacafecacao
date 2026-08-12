<?php

class Newsletter {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Inscrire un abonné depuis le site public
     */
    public function subscribePublic($email, $firstName = null, $lastName = null) {
        // Vérifier si l'email existe déjà
        $existing = $this->getSubscriberByEmail($email);
        if ($existing) {
            if ($existing['is_confirmed']) {
                throw new Exception('Cet email est déjà abonné et confirmé.');
            } else {
                // Ré-envoyer l'email de confirmation (non bloquant)
                try {
                    $this->sendConfirmationEmail($existing);
                } catch (Exception $e) {
                    // Log l'erreur mais continue
                    error_log("Newsletter email error: " . $e->getMessage());
                }
                return ['id' => $existing['id'], 'resent' => true, 'email_sent' => true];
            }
        }
        
        // Générer un token unique
        $token = bin2hex(random_bytes(32));
        
        // Créer l'abonné
        $id = $this->db->insert('newsletter_subscribers', [
            'email' => $email,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'token' => $token,
            'is_active' => 1,
            'is_confirmed' => 0,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null
        ]);
        
        // Récupérer l'abonné pour envoyer l'email (non bloquant)
        $subscriber = $this->getSubscriberById($id);
        $email_sent = true;
        try {
            $this->sendConfirmationEmail($subscriber);
        } catch (Exception $e) {
            $email_sent = false;
            error_log("Newsletter email error: " . $e->getMessage());
        }
        
        return ['id' => $id, 'resent' => false, 'email_sent' => $email_sent];
    }
    
    /**
     * Envoyer l'email de confirmation
     */
    private function sendConfirmationEmail($subscriber) {
        $confirmUrl = url('', ['confirm' => $subscriber['token']]);
        $siteName = get_setting('site_name', 'OIA Café-Cacao');
        $siteEmail = get_setting('site_email', 'contact@oia-cafecacao.ci');
        
        $subject = "Confirmez votre inscription à la newsletter {$siteName}";
        
        $message = "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Confirmation d'inscription</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #8a4e00; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9f9f9; }
        .button { display: inline-block; background: #8a4e00; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .footer { text-align: center; color: #666; font-size: 12px; padding: 20px; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h2>{$siteName}</h2>
        </div>
        <div class='content'>
            <p>Bonjour" . ($subscriber['first_name'] ? " " . e($subscriber['first_name']) : "") . ",</p>
            <p>Merci de votre inscription à notre newsletter !</p>
            <p>Veuillez confirmer votre email en cliquant sur le bouton ci-dessous :</p>
            <p style='text-align: center;'>
                <a href='{$confirmUrl}' class='button'>Confirmer mon inscription</a>
            </p>
            <p>Si le bouton ne fonctionne pas, copiez et collez ce lien dans votre navigateur :</p>
            <p style='word-break: break-all; color: #666;'>{$confirmUrl}</p>
            <p>Cordialement,<br>L'équipe {$siteName}</p>
        </div>
        <div class='footer'>
            <p>Si vous n'avez pas demandé cette inscription, ignorez simplement cet email.</p>
        </div>
    </div>
</body>
</html>";
        
        $headers = "From: {$siteName} <{$siteEmail}>\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        
        // Vérifier si la fonction mail existe avant de l'appeler
        if (!function_exists('mail')) {
            throw new Exception("Fonction mail() non disponible sur ce serveur.");
        }
        
        $result = mail($subscriber['email'], $subject, $message, $headers);
        
        if (!$result) {
            throw new Exception("Échec de l'envoi de l'email.");
        }
        
        return $result;
    }
    
    /**
     * Confirmer un abonné via son token
     */
    public function confirmSubscription($token) {
        $subscriber = $this->db->fetchOne("SELECT * FROM newsletter_subscribers WHERE token = ?", [$token]);
        
        if (!$subscriber) {
            throw new Exception('Token invalide ou expiré.');
        }
        
        if ($subscriber['is_confirmed']) {
            return ['already_confirmed' => true];
        }
        
        $this->db->update('newsletter_subscribers', [
            'is_confirmed' => 1,
            'confirmed_at' => date('Y-m-d H:i:s')
        ], '', $subscriber['id']);
        
        // Envoyer un email de bienvenue (non bloquant)
        try {
            $this->sendWelcomeEmail($this->getSubscriberById($subscriber['id']));
        } catch (Exception $e) {
            error_log("Newsletter welcome email error: " . $e->getMessage());
        }
        
        return ['already_confirmed' => false];
    }
    
    /**
     * Envoyer l'email de bienvenue
     */
    private function sendWelcomeEmail($subscriber) {
        $siteName = get_setting('site_name', 'OIA Café-Cacao');
        $siteEmail = get_setting('site_email', 'contact@oia-cafecacao.ci');
        
        $subject = "Bienvenue dans la communauté {$siteName} !";
        
        $message = "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Bienvenue !</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #8a4e00; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9f9f9; }
        .footer { text-align: center; color: #666; font-size: 12px; padding: 20px; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h2>{$siteName}</h2>
        </div>
        <div class='content'>
            <p>Bonjour" . ($subscriber['first_name'] ? " " . e($subscriber['first_name']) : "") . ",</p>
            <p>Félicitations ! Votre inscription à notre newsletter est confirmée.</p>
            <p>Vous allez maintenant recevoir nos dernières actualités et informations sur la filière café-cacao.</p>
            <p>Cordialement,<br>L'équipe {$siteName}</p>
        </div>
        <div class='footer'>
            <p>Pour vous désinscrire, cliquez sur le lien de désinscription présent dans chaque email.</p>
        </div>
    </div>
</body>
</html>";
        
        $headers = "From: {$siteName} <{$siteEmail}>\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        
        if (!function_exists('mail')) {
            throw new Exception("Fonction mail() non disponible sur ce serveur.");
        }
        
        $result = mail($subscriber['email'], $subject, $message, $headers);
        
        if (!$result) {
            throw new Exception("Échec de l'envoi de l'email de bienvenue.");
        }
        
        return $result;
    }
    
    // ==================== SUBSCRIBERS ====================
    
    public function getAllSubscribers($page = 1, $perPage = 20, $search = '', $status = null) {
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT * FROM newsletter_subscribers WHERE 1=1";
        $params = [];
        
        if ($search) {
            $sql .= " AND (email LIKE ? OR first_name LIKE ? OR last_name LIKE ?)";
            $searchTerm = "%$search%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        if ($status !== null) {
            $sql .= " AND is_active = ?";
            $params[] = $status ? 1 : 0;
        }
        
        $sql .= " ORDER BY subscribed_at DESC LIMIT ? OFFSET ?";
        $params[] = $perPage;
        $params[] = $offset;
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function getTotalSubscribers($search = '', $status = null) {
        $sql = "SELECT COUNT(*) as total FROM newsletter_subscribers WHERE 1=1";
        $params = [];
        
        if ($search) {
            $sql .= " AND (email LIKE ? OR first_name LIKE ? OR last_name LIKE ?)";
            $searchTerm = "%$search%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        if ($status !== null) {
            $sql .= " AND is_active = ?";
            $params[] = $status ? 1 : 0;
        }
        
        $result = $this->db->fetchOne($sql, $params);
        return $result['total'] ?? 0;
    }
    
    public function getSubscriberById($id) {
        $sql = "SELECT * FROM newsletter_subscribers WHERE id = ?";
        return $this->db->fetchOne($sql, [$id]);
    }
    
    public function getSubscriberByEmail($email) {
        $sql = "SELECT * FROM newsletter_subscribers WHERE email = ?";
        return $this->db->fetchOne($sql, [$email]);
    }
    
    public function addSubscriber($data) {
        return $this->db->insert('newsletter_subscribers', [
            'email' => $data['email'],
            'first_name' => $data['first_name'] ?? null,
            'last_name' => $data['last_name'] ?? null,
            'token' => $data['token'] ?? bin2hex(random_bytes(32)),
            'is_active' => $data['is_active'] ?? 1,
            'is_confirmed' => $data['is_confirmed'] ?? 0,
            'ip_address' => $data['ip_address'] ?? $_SERVER['REMOTE_ADDR'] ?? null
        ]);
    }
    
    public function updateSubscriber($id, $data) {
        $updateData = [];
        $allowedFields = ['email', 'first_name', 'last_name', 'is_active', 'is_confirmed'];
        
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $updateData[$field] = $data[$field];
            }
        }
        
        return $this->db->update('newsletter_subscribers', $updateData, '', $id);
    }
    
    public function deleteSubscriber($id) {
        return $this->db->delete('newsletter_subscribers', 'id = ?', [$id]);
    }
    
    public function toggleSubscriberStatus($id) {
        $subscriber = $this->getSubscriberById($id);
        if (!$subscriber) return false;
        
        $newStatus = $subscriber['is_active'] ? 0 : 1;
        return $this->db->update('newsletter_subscribers', ['is_active' => $newStatus], '', $id);
    }
    
    /**
     * Confirmer manuellement un abonné depuis l'admin
     */
    public function confirmSubscriber($id) {
        $subscriber = $this->getSubscriberById($id);
        if (!$subscriber) {
            throw new Exception('Abonné non trouvé.');
        }
        
        if ($subscriber['is_confirmed']) {
            return ['already_confirmed' => true];
        }
        
        $this->db->update('newsletter_subscribers', [
            'is_confirmed' => 1,
            'confirmed_at' => date('Y-m-d H:i:s')
        ], '', $id);
        
        return ['already_confirmed' => false];
    }
    
    // ==================== CAMPAIGNS ====================
    
    public function getAllCampaigns($page = 1, $perPage = 10, $status = null) {
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT nc.*, u.first_name as creator_first, u.last_name as creator_last 
                FROM newsletter_campaigns nc 
                LEFT JOIN users u ON nc.created_by = u.id 
                WHERE 1=1";
        $params = [];
        
        if ($status) {
            $sql .= " AND nc.status = ?";
            $params[] = $status;
        }
        
        $sql .= " ORDER BY nc.created_at DESC LIMIT ? OFFSET ?";
        $params[] = $perPage;
        $params[] = $offset;
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function getTotalCampaigns($status = null) {
        $sql = "SELECT COUNT(*) as total FROM newsletter_campaigns WHERE 1=1";
        $params = [];
        
        if ($status) {
            $sql .= " AND status = ?";
            $params[] = $status;
        }
        
        $result = $this->db->fetchOne($sql, $params);
        return $result['total'] ?? 0;
    }
    
    public function getCampaignById($id) {
        $sql = "SELECT * FROM newsletter_campaigns WHERE id = ?";
        return $this->db->fetchOne($sql, [$id]);
    }
    
    public function addCampaign($data) {
        return $this->db->insert('newsletter_campaigns', [
            'title' => $data['title'],
            'subject' => $data['subject'],
            'content' => $data['content'],
            'status' => $data['status'] ?? 'draft',
            'created_by' => $data['created_by'] ?? null
        ]);
    }
    
    public function updateCampaign($id, $data) {
        $updateData = [];
        $allowedFields = ['title', 'subject', 'content', 'status'];
        
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $updateData[$field] = $data[$field];
            }
        }
        
        return $this->db->update('newsletter_campaigns', $updateData, '', $id);
    }
    
    public function deleteCampaign($id) {
        $this->db->delete('newsletter_logs', 'campaign_id = ?', [$id]);
        return $this->db->delete('newsletter_campaigns', 'id = ?', [$id]);
    }
    
    public function sendCampaign($campaignId) {
        $campaign = $this->getCampaignById($campaignId);
        if (!$campaign || $campaign['status'] !== 'draft') return false;
        
        $this->updateCampaign($campaignId, ['status' => 'sending']);
        
        $subscribers = $this->getActiveSubscribers();
        
        foreach ($subscribers as $subscriber) {
            try {
                $this->sendEmail($subscriber['email'], $campaign['subject'], $campaign['content'], $subscriber);
                $this->logEmail($campaignId, $subscriber['id'], $subscriber['email'], 'sent');
            } catch (Exception $e) {
                $this->logEmail($campaignId, $subscriber['id'], $subscriber['email'], 'failed', $e->getMessage());
            }
        }
        
        $this->updateCampaign($campaignId, [
            'status' => 'sent',
            'sent_at' => date('Y-m-d H:i:s')
        ]);
        
        return true;
    }
    
    private function sendEmail($to, $subject, $content, $subscriber) {
        $unsubscribeLink = url('', ['unsubscribe' => $subscriber['token']]);
        $content = str_replace('{UNSUBSCRIBE_LINK}', $unsubscribeLink, $content);
        $content = str_replace('{FIRST_NAME}', $subscriber['first_name'] ?? '', $content);
        $content = str_replace('{LAST_NAME}', $subscriber['last_name'] ?? '', $content);
        
        $headers = "From: OIA Café-Cacao <noreply@oia-cafecacao.ci>\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        
        return mail($to, $subject, $content, $headers);
    }
    
    private function logEmail($campaignId, $subscriberId, $email, $status, $error = null) {
        return $this->db->insert('newsletter_logs', [
            'campaign_id' => $campaignId,
            'subscriber_id' => $subscriberId,
            'email' => $email,
            'status' => $status,
            'error_message' => $error
        ]);
    }
    
    public function getActiveSubscribers() {
        $sql = "SELECT * FROM newsletter_subscribers WHERE is_active = 1 AND is_confirmed = 1";
        return $this->db->fetchAll($sql);
    }
    
    // ==================== TEMPLATES ====================
    
    public function getAllTemplates($onlyActive = true) {
        $sql = "SELECT * FROM newsletter_templates";
        $params = [];
        
        if ($onlyActive) {
            $sql .= " WHERE is_active = 1";
        }
        
        $sql .= " ORDER BY created_at DESC";
        return $this->db->fetchAll($sql, $params);
    }
    
    public function getTemplateById($id) {
        $sql = "SELECT * FROM newsletter_templates WHERE id = ?";
        return $this->db->fetchOne($sql, [$id]);
    }
    
    public function addTemplate($data) {
        return $this->db->insert('newsletter_templates', [
            'name' => $data['name'],
            'content' => $data['content'],
            'is_active' => $data['is_active'] ?? 1
        ]);
    }
    
    public function updateTemplate($id, $data) {
        $updateData = [];
        $allowedFields = ['name', 'content', 'is_active'];
        
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $updateData[$field] = $data[$field];
            }
        }
        
        return $this->db->update('newsletter_templates', $updateData, '', $id);
    }
    
    public function deleteTemplate($id) {
        return $this->db->delete('newsletter_templates', 'id = ?', [$id]);
    }
    
    // ==================== STATS ====================
    
    public function getDashboardStats() {
        return [
            'total_subscribers' => $this->getTotalSubscribers(),
            'active_subscribers' => $this->getTotalSubscribers('', true),
            'total_campaigns' => $this->getTotalCampaigns(),
            'sent_campaigns' => $this->getTotalCampaigns('sent'),
            'today_subscribers' => $this->countSubscribersByPeriod(date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')),
            'month_subscribers' => $this->countSubscribersByPeriod(date('Y-m-01 00:00:00'), date('Y-m-t 23:59:59'))
        ];
    }
    
    private function countSubscribersByPeriod($start, $end) {
        $sql = "SELECT COUNT(*) as total FROM newsletter_subscribers WHERE subscribed_at BETWEEN ? AND ?";
        $result = $this->db->fetchOne($sql, [$start, $end]);
        return $result['total'] ?? 0;
    }
    
    public function getCampaignStats($campaignId) {
        $sql = "SELECT status, COUNT(*) as count FROM newsletter_logs WHERE campaign_id = ? GROUP BY status";
        $rows = $this->db->fetchAll($sql, [$campaignId]);
        
        $stats = ['sent' => 0, 'failed' => 0, 'opened' => 0, 'clicked' => 0];
        foreach ($rows as $row) {
            $stats[$row['status']] = $row['count'];
        }
        
        return $stats;
    }
}

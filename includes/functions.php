<?php
/**
 * Fonctions utilitaires
 */

function url($path = '', $params = []) {
    // Si le path contient déjà index.php?p=, ne pas rajouter
    if (strpos($path, 'index.php?p=') !== false) {
        return APP_URL . '/' . ltrim($path, '/');
    }
    
    // Séparer le path et les paramètres
    $pathParts = explode('?', $path);
    $pathOnly = $pathParts[0];
    $queryString = isset($pathParts[1]) ? $pathParts[1] : '';
    
    $pathOnly = ltrim($pathOnly, '/');
    
    // Fusionner les paramètres
    $finalParams = [];
    if ($queryString) {
        parse_str($queryString, $finalParams);
    }
    $finalParams = array_merge($finalParams, $params);
    
    if (empty($pathOnly)) {
        if (empty($finalParams)) {
            return APP_URL . '/';
        }
        return APP_URL . '/index.php?' . http_build_query($finalParams);
    }
    
    // Toujours utiliser le paramètre ?p= pour éviter les problèmes de mod_rewrite
    $encodedPath = implode('/', array_map('rawurlencode', explode('/', $pathOnly)));
    $url = APP_URL . '/index.php?p=' . $encodedPath;
    
    if (!empty($finalParams)) {
        $url .= '&' . http_build_query($finalParams);
    }
    
    return $url;
}

function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    return $data;
}

function e($string) {
    return htmlspecialchars((string)$string, ENT_QUOTES, 'UTF-8');
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function require_login() {
    if (!is_logged_in()) {
        redirect(APP_URL . '/admin/login.php');
    }
}

function generate_csrf_token() {
    if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    }
    return $_SESSION[CSRF_TOKEN_NAME];
}

function verify_csrf_token($token) {
    return isset($_SESSION[CSRF_TOKEN_NAME]) && hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
}

function slugify($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    return $text;
}

function get_video_mime_type($file_path) {
    $extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
    switch ($extension) {
        case 'webm':
            return 'video/webm';
        case 'ogg':
        case 'ogv':
            return 'video/ogg';
        case 'mp4':
        default:
            return 'video/mp4';
    }
}

function asset_url($path) {
    if (empty($path)) {
        return '';
    }

    if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, 'data:')) {
        return $path;
    }

    return APP_URL . '/' . ltrim($path, '/');
}

function asset_file_exists($path) {
    if (empty($path)) {
        return false;
    }

    if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, 'data:')) {
        return true;
    }

    $file_path = BASE_PATH . '/' . ltrim($path, '/');
    return file_exists($file_path);
}

function get_video_thumbnail_url($video) {
    // Si c'est une vidéo YouTube, générer la miniature automatiquement
    if (!empty($video['video_type']) && $video['video_type'] === 'youtube' && !empty($video['youtube_id'])) {
        // Essayer la version maxres d'abord, sinon hqdefault, sinon mqdefault, sinon default
        return 'https://i.ytimg.com/vi/' . $video['youtube_id'] . '/hqdefault.jpg';
    }

    // Si une miniature est définie
    if (!empty($video['thumbnail'])) {
        // Si c'est une URL (http/https/data), la retourner directement
        if (str_starts_with($video['thumbnail'], 'http://') || str_starts_with($video['thumbnail'], 'https://') || str_starts_with($video['thumbnail'], 'data:')) {
            return $video['thumbnail'];
        }
        // Sinon, vérifier si c'est un fichier local
        if (asset_file_exists($video['thumbnail'])) {
            return asset_url($video['thumbnail']);
        }
    }

    // Miniature par défaut
    return 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="400" height="300" viewBox="0 0 400 300"><rect fill="#5b2d00" width="400" height="300" rx="10"/><circle fill="#8a4e00" cx="200" cy="150" r="50"/><path fill="white" d="M180 120 L230 150 L180 180 Z"/><text x="50%" y="270" font-family="Arial" font-size="16" text-anchor="middle" fill="white">Vidéo</text></svg>');
}

function get_video_source_url($video) {
    if ($video['video_type'] === 'youtube' && !empty($video['youtube_id'])) {
        return 'https://www.youtube.com/embed/' . $video['youtube_id'];
    }

    if (!empty($video['file_path']) && asset_file_exists($video['file_path'])) {
        return asset_url($video['file_path']);
    }

    return '';
}

function format_date($date, $format = 'd/m/Y H:i') {
    return date($format, strtotime($date));
}

function log_activity($action, $model = null, $model_id = null, $details = null) {
    try {
        $db = Database::getInstance();
        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        $ip = $_SERVER['REMOTE_ADDR'] ?? null;
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? null;
        
        $db->insert('activity_log', [
            'user_id' => $user_id,
            'action' => $action,
            'model' => $model,
            'model_id' => $model_id,
            'details' => $details,
            'ip_address' => $ip,
            'user_agent' => $user_agent
        ]);
    } catch (Exception $e) {
        // Ne rien faire si la DB n'existe pas
    }
}

function get_setting($key, $default = '') {
    static $settings_cache = null;
    
    $defaults = [
        'site_name' => 'Organisation Interprofessionnelle Agricole Café-Cacao',
        'site_description' => 'Un acteur clé pour une filière équitable, durable et prospère au service des producteurs.',
        'site_email' => 'contact@oia-cafecacao.ci',
        'site_phone' => '(+225) 27 20 00 00 00',
        'site_address' => 'Yamoussoukro, Côte d\'Ivoire',
        'facebook_url' => '',
        'twitter_url' => '',
        'instagram_url' => '',
        'youtube_url' => ''
    ];
    
    try {
        $db = Database::getInstance();
        $result = $db->fetchOne("SELECT value FROM settings WHERE key_name = ?", [$key]);
        return $result ? $result['value'] : ($defaults[$key] ?? $default);
    } catch (Exception $e) {
        return $defaults[$key] ?? $default;
    }
}

function compress_image($source, $destination, $quality = 80) {
    $info = getimagesize($source);
    if ($info['mime'] == 'image/jpeg') {
        $image = imagecreatefromjpeg($source);
        imagejpeg($image, $destination, $quality);
    } elseif ($info['mime'] == 'image/png') {
        $image = imagecreatefrompng($source);
        imagepng($image, $destination, round(9 * $quality / 100));
    } elseif ($info['mime'] == 'image/webp') {
        $image = imagecreatefromwebp($source);
        imagewebp($image, $destination, $quality);
    }
    if (isset($image)) {
        imagedestroy($image);
    }
}

function get_project_image_url($project) {
    if (!empty($project['image_path'])) {
        if (str_starts_with($project['image_path'], 'http://') || str_starts_with($project['image_path'], 'https://') || str_starts_with($project['image_path'], 'data:')) {
            return $project['image_path'];
        }
        if (asset_file_exists($project['image_path'])) {
            return asset_url($project['image_path']);
        }
    }
    // Image SVG par défaut thème cacao/café
    $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="800" height="600" viewBox="0 0 800 600">
        <defs>
            <linearGradient id="bgGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#5b2d00;stop-opacity:1" />
                <stop offset="100%" style="stop-color:#3a1d00;stop-opacity:1" />
            </linearGradient>
            <linearGradient id="beanGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#d4a574;stop-opacity:1" />
                <stop offset="100%" style="stop-color:#8b4513;stop-opacity:1" />
            </linearGradient>
        </defs>
        <rect width="800" height="600" fill="url(#bgGrad)" rx="0"/>
        <g transform="translate(400, 300)">
            <ellipse cx="0" cy="0" rx="100" ry="60" fill="url(#beanGrad)" transform="rotate(-20)"/>
            <ellipse cx="-30" cy="-10" rx="25" ry="15" fill="none" stroke="#f5e6d3" stroke-width="4" transform="rotate(-20)"/>
            <g transform="translate(-180, -80)">
                <ellipse cx="0" cy="0" rx="70" ry="42" fill="url(#beanGrad)" transform="rotate(15)"/>
                <ellipse cx="-20" cy="-6" rx="17" ry="10" fill="none" stroke="#d4b896" stroke-width="3" transform="rotate(15)"/>
            </g>
            <g transform="translate(180, 80)">
                <ellipse cx="0" cy="0" rx="70" ry="42" fill="url(#beanGrad)" transform="rotate(-10)"/>
                <ellipse cx="-20" cy="-6" rx="17" ry="10" fill="none" stroke="#d4b896" stroke-width="3" transform="rotate(-10)"/>
            </g>
            <circle cx="-160" cy="0" r="20" fill="#f5e6d3" opacity="0.3"/>
            <circle cx="160" cy="-40" r="15" fill="#f5e6d3" opacity="0.2"/>
            <circle cx="140" cy="40" r="10" fill="#f5e6d3" opacity="0.25"/>
        </g>
        <text x="400" y="450" font-family="Arial, sans-serif" font-size="42" font-weight="bold" text-anchor="middle" fill="#f5e6d3">
            Projet
        </text>
        <text x="400" y="500" font-family="Arial, sans-serif" font-size="24" text-anchor="middle" fill="#d4b896">
            OIA Café-Cacao
        </text>
    </svg>';
    return 'data:image/svg+xml;base64,' . base64_encode($svg);
}

function get_organisation_image_url($organisation) {
    if (!empty($organisation['image_path'])) {
        if (str_starts_with($organisation['image_path'], 'http://') || str_starts_with($organisation['image_path'], 'https://') || str_starts_with($organisation['image_path'], 'data:')) {
            return $organisation['image_path'];
        }
        if (asset_file_exists($organisation['image_path'])) {
            return asset_url($organisation['image_path']);
        }
    }
    // Image SVG par défaut thème cacao/café pour organisations
    $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="800" height="600" viewBox="0 0 800 600">
        <defs>
            <linearGradient id="orgBgGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#5b2d00;stop-opacity:1" />
                <stop offset="100%" style="stop-color:#3a1d00;stop-opacity:1" />
            </linearGradient>
        </defs>
        <rect width="800" height="600" fill="url(#orgBgGrad)" rx="0"/>
        <g transform="translate(400, 280)">
            <rect x="-120" y="-80" width="240" height="160" fill="#d4a574" rx="20"/>
            <rect x="-100" y="-60" width="200" height="120" fill="#f5e6d3" rx="10"/>
            <rect x="-80" y="-40" width="40" height="60" fill="#8b4513" rx="5"/>
            <rect x="-20" y="-40" width="40" height="60" fill="#8b4513" rx="5"/>
            <rect x="40" y="-40" width="40" height="60" fill="#8b4513" rx="5"/>
            <rect x="-10" y="-20" width="20" height="40" fill="#5b2d00" rx="3"/>
            <circle cx="8" cy="0" r="3" fill="#d4a574"/>
        </g>
        <text x="400" y="450" font-family="Arial, sans-serif" font-size="40" font-weight="bold" text-anchor="middle" fill="#f5e6d3">
            Organisation
        </text>
        <text x="400" y="500" font-family="Arial, sans-serif" font-size="22" text-anchor="middle" fill="#d4b896">
            OIA Café-Cacao
        </text>
    </svg>';
    return 'data:image/svg+xml;base64,' . base64_encode($svg);
}

function send_json_response($data, $status_code = 200) {
    http_response_code($status_code);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit();
}

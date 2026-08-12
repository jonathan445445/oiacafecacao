<?php
/**
 * Fichier de configuration principal
 */

// Rapport d'erreurs - ACTIVER TOUT
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/php-error.log');

// Configuration de l'environnement
define('APP_ENV', 'development');
define('APP_DEBUG', true);

// Déterminer l'URL de base dynamiquement
// Calculer le chemin de base de l'application (racine du projet)
$project_root = dirname(__DIR__); // chemin physique vers la racine
$doc_root = $_SERVER['DOCUMENT_ROOT'];
$relative_path = trim(str_replace(str_replace('\\', '/', $doc_root), '', str_replace('\\', '/', $project_root)), '/');

$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$base_url = $protocol . '://' . $host;
if (!empty($relative_path)) {
    $base_url .= '/' . $relative_path;
}
define('APP_URL', rtrim($base_url, '/'));

// Chemin de base
define('BASE_PATH', __DIR__ . '/..');
define('PUBLIC_PATH', BASE_PATH);
define('UPLOAD_PATH', BASE_PATH . '/uploads');
define('LOG_PATH', BASE_PATH . '/logs');

// Configuration de la base de données
// Utiliser les variables d'environnement si elles sont définies, sinon utiliser les valeurs locales par défaut.
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'oiacafec_');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_CHARSET', 'utf8mb4');

// Configuration de sécurité
define('SECRET_KEY', 'votre_cle_secrete_ici_changez_cette_valeur');
define('CSRF_TOKEN_NAME', 'csrf_token');
define('SESSION_LIFETIME', 7200); // 2 heures

// Configuration d'upload
define('MAX_UPLOAD_SIZE', 10 * 1024 * 1024); // 10 Mo
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif', 'webp']);
define('ALLOWED_FILE_TYPES', ['pdf', 'doc', 'docx', 'xls', 'xlsx']);

// Configuration PWA
define('PWA_NAME', 'OIA Café-Cacao');
define('PWA_SHORT_NAME', 'OIA');
define('PWA_THEME_COLOR', '#5b2d00');
define('PWA_BACKGROUND_COLOR', '#ffffff');

// Configuration AI (OpenAI ou Hugging Face)
// Pour OpenAI: Obtenez une clé sur https://platform.openai.com/api-keys
// Pour Hugging Face (gratuit): https://huggingface.co/settings/tokens
define('AI_API_KEY', ''); // Remplacez par votre clé API
define('AI_PROVIDER', 'openai'); // 'openai' ou 'huggingface'
define('AI_MODEL', 'gpt-3.5-turbo'); // Pour OpenAI: gpt-3.5-turbo, gpt-4; Pour Hugging Face: mistralai/Mistral-7B-Instruct-v0.3
define('AI_SYSTEM_PROMPT', "Tu es l'assistant virtuel de l'Organisation Interprofessionnelle Agricole Café-Cacao (OIA) de Côte d'Ivoire. Tu réponds poliment aux questions des visiteurs, en donnant des informations utiles sur l'OIA, ses filières (café et cacao), ses collèges (Producteurs, Transformateurs Industriels, Exportateurs, Acheteurs, Négoce, Transformateurs Artisanaux), ses actualités et ses services. Tu dois être concis et professionnel. Si tu ne connais pas la réponse, invite la personne à contacter l'OIA directement.");

// Fuseau horaire
date_default_timezone_set('Africa/Abidjan');

// Démarrage de la session - simplifié
if (session_status() == PHP_SESSION_NONE) {
    @session_start();
}

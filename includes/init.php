<?php
/**
 * Fichier d'initialisation
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/functions.php';

// Autoloader pour les helpers
spl_autoload_register(function ($class_name) {
    $file = __DIR__ . '/../models/' . $class_name . '.php';
    if (file_exists($file)) {
        require_once $file;
        return;
    }
    
    $file = __DIR__ . '/../controllers/' . $class_name . '.php';
    if (file_exists($file)) {
        require_once $file;
        return;
    }
    
    $file = __DIR__ . '/../helpers/' . $class_name . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

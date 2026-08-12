<?php
require_once __DIR__ . '/../includes/init.php';

$user = new User();
$user->logout();

header('Location: ' . APP_URL . '/admin/login.php');
exit;

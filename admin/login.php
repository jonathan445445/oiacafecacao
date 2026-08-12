<?php
require_once __DIR__ . '/../includes/init.php';

$error = '';
$db_available = true;

try {
    // Tester la connexion DB
    $db = Database::getInstance();
} catch (Exception $e) {
    $db_available = false;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = clean_input($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (!$db_available) {
        $error = 'La base de données n\'est pas installée. Veuillez utiliser le install.php d\'abord.';
    } else {
        try {
            $user = new User();
            if ($user->login($email, $password)) {
                log_activity('Connexion administrateur');
                redirect('index.php');
            } else {
                $error = 'Identifiants incorrects.';
            }
        } catch (Exception $e) {
            $error = 'Erreur : ' . $e->getMessage() . '. Veuillez installer la base de données d\'abord.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - OIA Café-Cacao</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #5b2d00 0%, #3a1d00 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            max-width: 400px;
            width: 100%;
            border-radius: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card login-card shadow-lg">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <h3 class="fw-bold">OIA Café-Cacao</h3>
                    <p class="text-muted">Connexion à l'administration</p>
                </div>
                
                <?php if (!$db_available): ?>
                    <div class="alert alert-warning">
                        <p><strong>⚠️ Base de données non installée</strong></p>
                        <a href="../install.php" class="btn btn-success w-100">Installer la base de données</a>
                    </div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= e($error) ?></div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control" value="admin@oia-cafecacao.ci" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Mot de passe</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 btn-lg" style="background: #5b2d00; border-color: #5b2d00;" <?= !$db_available ? 'disabled' : '' ?>>
                        Se connecter
                    </button>
                </form>
                
                <div class="text-center mt-4">
                    <small class="text-muted">Compte par défaut : admin@oia-cafecacao.ci / password</small>
                </div>
            </div>
        </div>
        <div class="text-center py-3">
            <a href="../install.php" class="text-light text-decoration-none">Installer la DB</a>
            <span class="text-light mx-2">•</span>
            <a href="../index.php" class="text-light text-decoration-none">← Retour au site</a>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

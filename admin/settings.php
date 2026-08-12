<?php
require_once __DIR__ . '/../includes/init.php';
require_login();

$page_title = 'Parametres';
$db = Database::getInstance();

function save_admin_setting($db, $key, $value, $group = 'general', $type = 'text', $description = '') {
    $exists = $db->fetchOne("SELECT id FROM settings WHERE key_name = ?", [$key]);
    if ($exists) {
        $db->query("UPDATE settings SET value = ?, group_name = ?, type = ?, description = ? WHERE key_name = ?", [$value, $group, $type, $description, $key]);
        return;
    }

    $db->insert('settings', [
        'key_name' => $key,
        'value' => $value,
        'group_name' => $group,
        'type' => $type,
        'description' => $description
    ]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Jeton CSRF invalide.';
            redirect(url('admin/settings'));
        }

        $action = $_POST['action'] ?? '';

        if ($action === 'general') {
            save_admin_setting($db, 'site_name', clean_input($_POST['site_name'] ?? ''), 'general', 'text', 'Nom du site');
            save_admin_setting($db, 'site_description', clean_input($_POST['site_description'] ?? ''), 'general', 'textarea', 'Description du site');
            save_admin_setting($db, 'site_email', clean_input($_POST['site_email'] ?? ''), 'general', 'email', 'Email de contact');
            save_admin_setting($db, 'site_phone', clean_input($_POST['site_phone'] ?? ''), 'general', 'text', 'Telephone');
            save_admin_setting($db, 'site_address', clean_input($_POST['site_address'] ?? ''), 'general', 'textarea', 'Adresse');
            $_SESSION['success'] = 'Informations generales enregistrees.';
        } elseif ($action === 'social') {
            save_admin_setting($db, 'facebook_url', clean_input($_POST['facebook_url'] ?? ''), 'social', 'url', 'URL Facebook');
            save_admin_setting($db, 'twitter_url', clean_input($_POST['twitter_url'] ?? ''), 'social', 'url', 'URL Twitter');
            save_admin_setting($db, 'instagram_url', clean_input($_POST['instagram_url'] ?? ''), 'social', 'url', 'URL Instagram');
            save_admin_setting($db, 'youtube_url', clean_input($_POST['youtube_url'] ?? ''), 'social', 'url', 'URL YouTube');
            $_SESSION['success'] = 'Reseaux sociaux enregistres.';
        }

        redirect(url('admin/settings'));
    } catch (Exception $e) {
        $_SESSION['error'] = 'Erreur: ' . $e->getMessage();
        redirect(url('admin/settings'));
    }
}

require_once __DIR__ . '/layouts/header.php';
?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= e($_SESSION['success']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= e($_SESSION['error']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Informations generales</h6>
            </div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
                    <input type="hidden" name="action" value="general">
                    <div class="mb-3">
                        <label class="form-label">Nom du site</label>
                        <input type="text" name="site_name" class="form-control" value="<?= e(get_setting('site_name')) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="site_description" class="form-control" rows="3"><?= e(get_setting('site_description')) ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="site_email" class="form-control" value="<?= e(get_setting('site_email')) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Telephone</label>
                        <input type="text" name="site_phone" class="form-control" value="<?= e(get_setting('site_phone')) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Adresse</label>
                        <textarea name="site_address" class="form-control" rows="2"><?= e(get_setting('site_address')) ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Reseaux sociaux</h6>
            </div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
                    <input type="hidden" name="action" value="social">
                    <div class="mb-3">
                        <label class="form-label">Facebook</label>
                        <input type="url" name="facebook_url" class="form-control" value="<?= e(get_setting('facebook_url')) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Twitter</label>
                        <input type="url" name="twitter_url" class="form-control" value="<?= e(get_setting('twitter_url')) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Instagram</label>
                        <input type="url" name="instagram_url" class="form-control" value="<?= e(get_setting('instagram_url')) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">YouTube</label>
                        <input type="url" name="youtube_url" class="form-control" value="<?= e(get_setting('youtube_url')) ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </form>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Sauvegarde</h6>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Sauvegardez votre base de donnees et vos fichiers.</p>
                <button class="btn btn-outline-secondary" type="button"><i class="bi bi-download"></i> Sauvegarder maintenant</button>
            </div>
        </div>
    </div>
</div>


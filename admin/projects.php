<?php
require_once __DIR__ . '/../includes/init.php';
require_login();

$page_title = 'Projets';
require_once __DIR__ . '/layouts/header.php';

$db = Database::getInstance();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $show = isset($_POST['show_projects_menu']) ? '1' : '0';
    $label = clean_input($_POST['projects_menu_label'] ?? 'Projets');
    $slug = clean_input($_POST['projects_menu_slug'] ?? 'projets');
    $content = $_POST['projects_page_content'] ?? '';

    $settings = [
        'show_projects_menu' => $show,
        'projects_menu_label' => $label,
        'projects_menu_slug' => $slug,
        'projects_page_content' => $content
    ];

    foreach ($settings as $k => $v) {
        $exists = $db->fetchOne("SELECT id FROM settings WHERE key_name = ?", [$k]);
        if ($exists) {
            $db->query("UPDATE settings SET value = ? WHERE key_name = ?", [$v, $k]);
        } else {
            $type = strlen($v) > 255 ? 'textarea' : 'text';
            $db->insert('settings', [
                'key_name' => $k,
                'value' => $v,
                'group_name' => 'projects',
                'type' => $type,
                'description' => 'Paramètre projet: ' . $k
            ]);
        }
    }

    $_SESSION['success'] = 'Paramètres projets enregistrés.';
    redirect(APP_URL . '/admin/projects.php');
}

$show = get_setting('show_projects_menu', '1');
$label = get_setting('projects_menu_label', 'Projets');
$slug = get_setting('projects_menu_slug', 'projets');
$content = get_setting('projects_page_content', '');
?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Configuration du menu Projets</h6>
    </div>
    <div class="card-body">
        <form method="POST">
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="show_projects_menu" name="show_projects_menu" value="1" <?= $show === '1' ? 'checked' : '' ?> />
                <label class="form-check-label" for="show_projects_menu">Afficher le menu "Projets"</label>
            </div>

            <div class="mb-3">
                <label class="form-label">Libellé du menu</label>
                <input type="text" name="projects_menu_label" class="form-control" value="<?= e($label) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Slug/URL</label>
                <input type="text" name="projects_menu_slug" class="form-control" value="<?= e($slug) ?>">
                <small class="form-text text-muted">Ex: projets -> <?= APP_URL ?>/index.php?p=projets</small>
            </div>

            <div class="mb-3">
                <label class="form-label">Contenu de la page Projets</label>
                <textarea name="projects_page_content" class="form-control" rows="6"><?= e($content) ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>

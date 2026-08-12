<?php
require_once __DIR__ . '/../includes/init.php';
require_login();

$page_title = 'Paramètres de présentation';
$db = Database::getInstance();

$presentationTab = $_GET['tab'] ?? 'presentation';
if (!in_array($presentationTab, ['presentation', 'president'])) {
    $presentationTab = 'presentation';
}

function save_admin_setting($db, $key, $value, $group = 'presentation', $type = 'text', $description = '') {
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
            redirect(url('admin/settings-presentation'));
        }

        $action = $_POST['action'] ?? '';

        if ($action === 'presentation') {
            save_admin_setting($db, 'presentation_subtitle', clean_input($_POST['presentation_subtitle'] ?? ''), 'presentation', 'textarea', 'Sous-titre de la presentation generale');
            save_admin_setting($db, 'presentation_hero_text', clean_input($_POST['presentation_hero_text'] ?? ''), 'presentation', 'textarea', 'Texte principal de la presentation generale');
            save_admin_setting($db, 'presentation_section_text', clean_input($_POST['presentation_section_text'] ?? ''), 'presentation', 'textarea', 'Texte de section');
            save_admin_setting($db, 'presentation_history_text', clean_input($_POST['presentation_history_text'] ?? ''), 'presentation', 'textarea', 'Texte bloc historique');
            save_admin_setting($db, 'presentation_mission_text', clean_input($_POST['presentation_mission_text'] ?? ''), 'presentation', 'textarea', 'Texte bloc mission');
            save_admin_setting($db, 'presentation_vision_text', clean_input($_POST['presentation_vision_text'] ?? ''), 'presentation', 'textarea', 'Texte bloc vision');
            save_admin_setting($db, 'presentation_vision_items', clean_input($_POST['presentation_vision_items'] ?? ''), 'presentation', 'textarea', 'Liste des points vision (une ligne par point)');
            save_admin_setting($db, 'presentation_objective_text', clean_input($_POST['presentation_objective_text'] ?? ''), 'presentation', 'textarea', 'Texte bloc objectif');
            save_admin_setting($db, 'presentation_objective_items', clean_input($_POST['presentation_objective_items'] ?? ''), 'presentation', 'textarea', 'Liste des points objectif (une ligne par point)');
            save_admin_setting($db, 'presentation_structure_text', clean_input($_POST['presentation_structure_text'] ?? ''), 'presentation', 'textarea', 'Texte de présentation de la structure');
            save_admin_setting($db, 'presentation_structure_secondary_text', clean_input($_POST['presentation_structure_secondary_text'] ?? ''), 'presentation', 'textarea', 'Texte secondaire de la structure');
            save_admin_setting($db, 'presentation_structure_mission_items', clean_input($_POST['presentation_structure_mission_items'] ?? ''), 'presentation', 'textarea', 'Liste des missions de la structure (séparées par des lignes)');
            $_SESSION['success'] = 'Présentation générale enregistrée.';
            redirect(url('admin/settings-presentation', ['tab' => 'presentation']));
        } elseif ($action === 'presentation_president') {
            if (!empty($_FILES['president_photo']['tmp_name']) && is_uploaded_file($_FILES['president_photo']['tmp_name'])) {
                $uploadDir = BASE_PATH . '/uploads/president';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $originalName = $_FILES['president_photo']['name'];
                $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
                if (in_array($extension, $allowedExtensions, true)) {
                    $filename = 'president_photo_' . time() . '.' . $extension;
                    $destination = $uploadDir . '/' . $filename;
                    if (move_uploaded_file($_FILES['president_photo']['tmp_name'], $destination)) {
                        save_admin_setting($db, 'president_photo', 'uploads/president/' . $filename, 'presentation', 'text', 'Photo du président');
                    }
                }
            }
            save_admin_setting($db, 'president_hero_subtitle', clean_input($_POST['president_hero_subtitle'] ?? ''), 'presentation', 'textarea', 'Sous-titre du bloc président');
            save_admin_setting($db, 'president_hero_text', clean_input($_POST['president_hero_text'] ?? ''), 'presentation', 'textarea', 'Texte du bloc président');
            save_admin_setting($db, 'president_name', clean_input($_POST['president_name'] ?? ''), 'presentation', 'text', 'Nom du président');
            save_admin_setting($db, 'president_role', clean_input($_POST['president_role'] ?? ''), 'presentation', 'text', 'Rôle du président');
            save_admin_setting($db, 'president_bio', clean_input($_POST['president_bio'] ?? ''), 'presentation', 'textarea', 'Biographie courte du président');
            save_admin_setting($db, 'president_vision_text', clean_input($_POST['president_vision_text'] ?? ''), 'presentation', 'textarea', 'Texte de la vision du président');
            save_admin_setting($db, 'president_vision_items', clean_input($_POST['president_vision_items'] ?? ''), 'presentation', 'textarea', 'Points de la vision du président');
            save_admin_setting($db, 'president_engagements_text', clean_input($_POST['president_engagements_text'] ?? ''), 'presentation', 'textarea', 'Texte des engagements du président');
            save_admin_setting($db, 'president_engagements_items', clean_input($_POST['president_engagements_items'] ?? ''), 'presentation', 'textarea', 'Points des engagements du président');
            save_admin_setting($db, 'president_quote_text', clean_input($_POST['president_quote_text'] ?? ''), 'presentation', 'textarea', 'Citation du président');
            $_SESSION['success'] = 'Présentation du président enregistrée.';
            redirect(url('admin/settings-presentation', ['tab' => 'president']));
        }

        redirect(url('admin/settings-presentation'));
    } catch (Exception $e) {
        $_SESSION['error'] = 'Erreur: ' . $e->getMessage();
        redirect(url('admin/settings-presentation'));
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

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Configuration des présentations</h6>
    </div>
    <div class="card-body">
        <ul class="nav nav-pills mb-4">
            <li class="nav-item">
                <a class="nav-link <?= $presentationTab === 'presentation' ? 'active' : '' ?>" href="<?= url('admin/settings-presentation', ['tab' => 'presentation']) ?>">Présentation générale</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $presentationTab === 'president' ? 'active' : '' ?>" href="<?= url('admin/settings-presentation', ['tab' => 'president']) ?>">Présentation du président</a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade <?= $presentationTab === 'presentation' ? 'show active' : '' ?>" id="tab-presentation">
                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
                    <input type="hidden" name="action" value="presentation">

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Sous-titre</label>
                            <textarea name="presentation_subtitle" class="form-control" rows="2"><?= e(get_setting('presentation_subtitle', "Presentation generale de l'Organisation Interprofessionnelle Agricole Cafe-Cacao.")) ?></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Texte principal</label>
                            <textarea name="presentation_hero_text" class="form-control" rows="3"><?= e(get_setting('presentation_hero_text', "L'Organisation Interprofessionnelle Agricole Cafe-Cacao (OIA) regroupe les acteurs de la filiere pour promouvoir une gouvernance inclusive, durable et performante.")) ?></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Texte de section</label>
                            <textarea name="presentation_section_text" class="form-control" rows="3"><?= e(get_setting('presentation_section_text', "L'OIA Cafe-Cacao travaille a la cohesion des acteurs, a l'amelioration des conditions de production, et a la valorisation des produits sur les marches locaux et internationaux.")) ?></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Texte Historique</label>
                            <textarea name="presentation_history_text" class="form-control" rows="4"><?= e(get_setting('presentation_history_text', "L'OIA Café-Cacao s'est construite autour d'une ambition commune : structurer la filière, renforcer la cohésion entre acteurs et accompagner son évolution.")) ?></textarea>
                        </div>

                        <div class="col-12 mt-4">
                            <h5 class="fw-bold">Mission</h5>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Texte Mission</label>
                            <textarea name="presentation_mission_text" class="form-control" rows="4"><?= e(get_setting('presentation_mission_text', "Promouvoir une filière café-cacao compétitive, durable et inclusive au service des producteurs, des opérateurs et des consommateurs.")) ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Missions Structure (une par ligne)</label>
                            <textarea name="presentation_structure_mission_items" class="form-control" rows="4"><?= e(get_setting('presentation_structure_mission_items', "Assurer la concertation permanente entre les différents collèges professionnels.
Représenter et défendre les intérêts économiques et institutionnels de la filière.
Contribuer à la régulation, à la modernisation et à la qualité de la filière.")) ?></textarea>
                        </div>

                        <div class="col-12 mt-4">
                            <h5 class="fw-bold">Vision</h5>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Texte Vision</label>
                            <textarea name="presentation_vision_text" class="form-control" rows="4"><?= e(get_setting('presentation_vision_text', "Devenir un acteur de référence dans la gouvernance, la transformation et la valorisation de la filière café-cacao.")) ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Points Vision (une ligne par point)</label>
                            <textarea name="presentation_vision_items" class="form-control" rows="4"><?= e(get_setting('presentation_vision_items', "Vision stratégique
Filière de référence
Innovation et durabilité")) ?></textarea>
                        </div>

                        <div class="col-12 mt-4">
                            <h5 class="fw-bold">Objectif</h5>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Texte Objectif</label>
                            <textarea name="presentation_objective_text" class="form-control" rows="4"><?= e(get_setting('presentation_objective_text', "Consolider la cohésion des acteurs, améliorer la performance économique et renforcer la visibilité de la filière sur les marchés.")) ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Points Objectif (une ligne par point)</label>
                            <textarea name="presentation_objective_items" class="form-control" rows="4"><?= e(get_setting('presentation_objective_items', "Renforcer la cohésion des acteurs
Améliorer la performance économique
Valoriser la filière sur les marchés")) ?></textarea>
                        </div>

                        <div class="col-12 mt-4">
                            <h5 class="fw-bold">Structure</h5>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Texte Structure</label>
                            <textarea name="presentation_structure_text" class="form-control" rows="3"><?= e(get_setting('presentation_structure_text', "Reconnue par l’État ivoirien, l’OIA réunit l’ensemble des collèges de la chaîne de valeur et assure la coordination entre les acteurs du café et du cacao.")) ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Texte secondaire Structure</label>
                            <textarea name="presentation_structure_secondary_text" class="form-control" rows="3"><?= e(get_setting('presentation_structure_secondary_text', "Elle constitue une interface de dialogue entre les producteurs, les transformateurs, les commerçants et les pouvoirs publics pour renforcer la gouvernance de la filière.")) ?></textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Enregistrer</button>
                </form>
            </div>
            <div class="tab-pane fade <?= $presentationTab === 'president' ? 'show active' : '' ?>" id="tab-president">
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
                    <input type="hidden" name="action" value="presentation_president">

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Photo du président</label>
                            <input type="file" name="president_photo" class="form-control" accept="image/*">
                            <?php $currentPhoto = get_setting('president_photo'); ?>
                            <?php if (!empty($currentPhoto) && asset_file_exists($currentPhoto)): ?>
                                <div class="mt-3">
                                    <img src="<?= asset_url($currentPhoto) ?>" alt="Photo du président" class="img-fluid rounded" style="max-width: 220px;">
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nom du président</label>
                            <input type="text" name="president_name" class="form-control" value="<?= e(get_setting('president_name', 'M. Siaka DIAKITÉ')) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fonction</label>
                            <input type="text" name="president_role" class="form-control" value="<?= e(get_setting('president_role', 'Président du Conseil d\'Administration')) ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Sous-titre</label>
                            <textarea name="president_hero_subtitle" class="form-control" rows="3"><?= e(get_setting('president_hero_subtitle', 'Découvrez la vision et l’engagement de M. Siaka DIAKITÉ pour une filière café-cacao forte, durable et inclusive.')) ?></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Texte principal</label>
                            <textarea name="president_hero_text" class="form-control" rows="4"><?= e(get_setting('president_hero_text', 'Avec une vision claire, il porte l’ambition d’une filière plus forte, plus juste et plus durable.')) ?></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Biographie courte</label>
                            <textarea name="president_bio" class="form-control" rows="4"><?= e(get_setting('president_bio', 'Président du Conseil d\'Administration de l\'OIA Café-Cacao, il porte une vision stratégique pour la filière.')) ?></textarea>
                        </div>
                        <div class="col-12 mt-4">
                            <h5 class="fw-bold">Vision</h5>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Texte Vision</label>
                            <textarea name="president_vision_text" class="form-control" rows="4"><?= e(get_setting('president_vision_text', 'Instaurer une gouvernance partagée, renforcer la compétitivité et assurer la durabilité de la filière café-cacao ivoirienne.')) ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Points Vision (une ligne par point)</label>
                            <textarea name="president_vision_items" class="form-control" rows="4"><?= e(get_setting('president_vision_items', 'Cohésion des acteurs
Gouvernance transparente')) ?></textarea>
                        </div>
                        <div class="col-12 mt-4">
                            <h5 class="fw-bold">Engagements</h5>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Texte Engagements</label>
                            <textarea name="president_engagements_text" class="form-control" rows="4"><?= e(get_setting('president_engagements_text', 'Au cœur de son action, il place l’organisation, la cohésion et l’innovation pour la filière café-cacao.')) ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Points Engagements (une ligne par point)</label>
                            <textarea name="president_engagements_items" class="form-control" rows="4"><?= e(get_setting('president_engagements_items', 'Renforcer la transparence de la gouvernance
Soutenir le développement économique des acteurs
Encourager l’adoption de bonnes pratiques durables
Promouvoir la qualité et l’exportation des produits')) ?></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Citation</label>
                            <textarea name="president_quote_text" class="form-control" rows="3"><?= e(get_setting('president_quote_text', 'Nous œuvrons pour renforcer la cohésion, la transparence et la compétitivité de notre filière café-cacao, pilier de l’économie ivoirienne.')) ?></textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>

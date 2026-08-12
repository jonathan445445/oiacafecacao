<?php
require_once __DIR__ . '/../includes/init.php';
require_login();

$page_title = 'Gestion des Bannières';

$bannerModel = new Banner();
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$banners = $bannerModel->findAll($page, 200, false);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Jeton CSRF invalide.';
            redirect(url('admin/banners'));
        }

        if (isset($_POST['action']) && $_POST['action'] === 'delete') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id) {
                $banner = $bannerModel->findById($id);
                if ($banner && !empty($banner['image_path'])) {
                    $oldFile = BASE_PATH . '/' . ltrim($banner['image_path'], '/');
                    if (file_exists($oldFile)) {
                        @unlink($oldFile);
                    }
                }
                $bannerModel->delete($id);
                $_SESSION['success'] = 'Bannière supprimée avec succès.';
            }
            redirect(url('admin/banners'));
        }

        $id = (int)($_POST['id'] ?? 0);
        $title = clean_input($_POST['title'] ?? '');
        $link = clean_input($_POST['link'] ?? '');
        $button_text = clean_input($_POST['button_text'] ?? '');
        $sort_order = isset($_POST['sort_order']) ? intval($_POST['sort_order']) : 0;
        $is_published = isset($_POST['is_published']) ? 1 : 0;

        if (empty($title)) {
            throw new Exception('Le titre de la bannière est requis.');
        }

        $data = [
            'title' => $title,
            'link' => $link,
            'button_text' => $button_text,
            'sort_order' => $sort_order,
            'is_published' => $is_published,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = UPLOAD_PATH . '/banners/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $originalName = pathinfo($_FILES['image']['name'], PATHINFO_FILENAME);
            $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $safeName = slugify($originalName) ?: 'banner';
            $fileName = $safeName . '-' . time() . '.' . $extension;
            $destination = $uploadDir . $fileName;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                $data['image_path'] = 'uploads/banners/' . $fileName;
            }
        }

        if ($id) {
            $existing = $bannerModel->findById($id);
            if ($existing) {
                if (empty($data['image_path'])) {
                    $data['image_path'] = $existing['image_path'];
                } elseif (!empty($existing['image_path']) && $existing['image_path'] !== $data['image_path']) {
                    $oldFile = BASE_PATH . '/' . ltrim($existing['image_path'], '/');
                    if (file_exists($oldFile)) {
                        @unlink($oldFile);
                    }
                }
            }
            $bannerModel->update($id, $data);
            $_SESSION['success'] = 'Bannière mise à jour avec succès.';
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            $bannerModel->create($data);
            $_SESSION['success'] = 'Bannière créée avec succès.';
        }

        redirect(url('admin/banners'));
    } catch (Exception $e) {
        $_SESSION['error'] = 'Erreur : ' . $e->getMessage();
        redirect(url('admin/banners'));
    }
}

require_once __DIR__ . '/layouts/header.php';
?>

<div class="row g-4">
    <div class="col-lg-5">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= e($_SESSION['success']); unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= e($_SESSION['error']); unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Ajouter / Modifier une bannière</h6>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data" id="bannerForm">
                    <input type="hidden" name="id" id="banner-id" value="">
                    <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">

                    <div class="mb-3">
                        <label class="form-label">Titre</label>
                        <input type="text" name="title" id="banner-title" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Texte du bouton</label>
                        <input type="text" name="button_text" id="banner-button-text" class="form-control" placeholder="En savoir plus">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Lien</label>
                        <input type="url" name="link" id="banner-link" class="form-control" placeholder="https://example.com">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Image</label>
                        <input type="file" name="image" id="banner-image" class="form-control" accept="image/*">
                        <div class="mt-3" id="banner-image-preview" style="display:none;">
                            <p class="mb-2"><strong>Image actuelle :</strong></p>
                            <img src="" alt="Aperçu bannière" class="img-fluid rounded" style="max-height: 180px; object-fit: cover; width: 100%;">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Ordre</label>
                            <input type="number" name="sort_order" id="banner-sort-order" class="form-control" value="0">
                        </div>
                        <div class="col-md-6">
                            <div class="form-check pt-4">
                                <input class="form-check-input" type="checkbox" name="is_published" id="banner-is-published" value="1" checked>
                                <label class="form-check-label" for="banner-is-published">Publié</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                        <button type="button" class="btn btn-secondary" id="banner-reset">Réinitialiser</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Liste des bannières</h6>
                <span class="badge bg-success"><?= count($banners) ?></span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Publié</th>
                                <th>Ordre</th>
                                <th style="width: 180px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($banners): ?>
                                <?php foreach ($banners as $banner): ?>
                                    <tr>
                                        <td>
                                            <?php if (!empty($banner['image_path'])): ?>
                                                <img src="<?= asset_url($banner['image_path']) ?>" alt="<?= e($banner['title']) ?>" style="max-height:40px; object-fit:cover; margin-right:8px;">
                                            <?php endif; ?>
                                            <?= e($banner['title']) ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?= $banner['is_published'] ? 'success' : 'secondary' ?>">
                                                <?= $banner['is_published'] ? 'Oui' : 'Non' ?>
                                            </span>
                                        </td>
                                        <td><?= e($banner['sort_order']) ?></td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="editBanner(<?= $banner['id'] ?>)">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <form method="POST" style="display:inline-block;" onsubmit="return confirm('Confirmer la suppression ?')">
                                                <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
                                                <input type="hidden" name="id" value="<?= $banner['id'] ?>">
                                                <input type="hidden" name="action" value="delete">
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-5">Aucune bannière enregistrée.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const banners = <?= json_encode($banners, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;

function editBanner(id) {
    const banner = banners.find(item => item.id === id);
    if (!banner) return;

    document.getElementById('banner-id').value = banner.id;
    document.getElementById('banner-title').value = banner.title;
    document.getElementById('banner-button-text').value = banner.button_text || '';
    document.getElementById('banner-link').value = banner.link || '';
    document.getElementById('banner-sort-order').value = banner.sort_order || 0;
    document.getElementById('banner-is-published').checked = banner.is_published == 1;

    if (banner.image_path) {
        const preview = document.getElementById('banner-image-preview');
        preview.style.display = 'block';
        preview.querySelector('img').src = '<?= APP_URL ?>/' + banner.image_path.replace(/^\/+/, '');
    }
}

function resetBannerForm() {
    document.getElementById('bannerForm').reset();
    document.getElementById('banner-id').value = '';
    const preview = document.getElementById('banner-image-preview');
    preview.style.display = 'none';
    preview.querySelector('img').src = '';
}

window.addEventListener('DOMContentLoaded', () => {
    document.getElementById('banner-reset').addEventListener('click', resetBannerForm);
});
</script>

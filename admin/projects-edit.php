<?php
require_once __DIR__ . '/../includes/init.php';
require_login();

$page_title = 'Projets - Édition';
require_once __DIR__ . '/layouts/header.php';

$projectModel = new Project();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$project = $id ? $projectModel->findById($id) : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $_SESSION['error'] = 'Jeton CSRF invalide.';
        redirect(url('admin/projects-list'));
    }

    $filePath = $id && $project ? $project['file_path'] : '';
    $imagePath = $id && $project ? $project['image_path'] : '';
    
    // Traitement de l'image du projet
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = __DIR__ . '/../uploads/projects/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $file = $_FILES['image'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($ext, $allowed) && $file['size'] <= MAX_UPLOAD_SIZE) {
            $filename = 'project_' . time() . '_' . md5($file['name']) . '.' . $ext;
            $imagePath = 'uploads/projects/' . $filename;
            move_uploaded_file($file['tmp_name'], $uploadDir . $filename);
        } else {
            $_SESSION['error'] = 'Image invalide ou trop volumineuse.';
        }
    }
    
    // Traitement du fichier uploadé (PDF)
    if (!empty($_FILES['file']['name'])) {
        $uploadDir = __DIR__ . '/../uploads/projects/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $file = $_FILES['file'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['pdf'];
        
        if (in_array($ext, $allowed) && $file['size'] <= MAX_UPLOAD_SIZE) {
            $filename = 'project_file_' . time() . '_' . md5($file['name']) . '.' . $ext;
            $filePath = 'uploads/projects/' . $filename;
            move_uploaded_file($file['tmp_name'], $uploadDir . $filename);
        } else {
            $_SESSION['error'] = 'Fichier PDF invalide ou trop volumineux.';
        }
    }

    $data = [
        'title' => clean_input($_POST['title'] ?? ''),
        'slug' => slugify($_POST['slug'] ?? ($_POST['title'] ?? '')),
        'summary' => clean_input($_POST['summary'] ?? ''),
        'image_path' => $imagePath,
        'start_date' => !empty($_POST['start_date']) ? $_POST['start_date'] : null,
        'end_date' => !empty($_POST['end_date']) ? $_POST['end_date'] : null,
        'file_path' => $filePath,
        'status' => in_array($_POST['status'] ?? 'draft', ['draft','published']) ? $_POST['status'] : 'draft',
        'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
        'sort_order' => (int)($_POST['sort_order'] ?? 0)
    ];

    if ($id) {
        $projectModel->update($id, $data);
        $_SESSION['success'] = 'Projet mis à jour.';
    } else {
        $newId = $projectModel->create($data);
        $_SESSION['success'] = 'Projet créé.';
        $id = $newId;
    }

    redirect(url('admin/projects-edit', ['id' => $id]));
}

?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><?= $id ? 'Modifier le projet' : 'Nouveau projet' ?></h6>
    </div>
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
            <div class="mb-3">
                <label class="form-label">Titre</label>
                <input type="text" name="title" class="form-control" value="<?= e($project['title'] ?? '') ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Slug</label>
                <input type="text" name="slug" class="form-control" value="<?= e($project['slug'] ?? '') ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Résumé/Description</label>
                <textarea name="summary" class="form-control" rows="3"><?= e($project['summary'] ?? '') ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Image du projet</label>
                <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png,.gif,.webp">
                <small class="form-text text-muted">Formats acceptés: JPG, PNG, GIF, WEBP (Max: 10 Mo)</small>
                <?php if (isset($project['image_path']) && !empty($project['image_path'])): ?>
                    <br><small>Image actuelle:</small>
                    <br><img src="<?= asset_url($project['image_path']) ?>" class="img-thumbnail mt-2" style="max-width: 200px;">
                <?php endif; ?>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Date de début</label>
                    <input type="date" name="start_date" class="form-control" value="<?= e($project['start_date'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Date de fin</label>
                    <input type="date" name="end_date" class="form-control" value="<?= e($project['end_date'] ?? '') ?>">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Fichier du projet (PDF)</label>
                <input type="file" name="file" class="form-control" accept=".pdf">
                <small class="form-text text-muted">Formats acceptés: PDF (Max: 10 Mo)</small>
                <?php if (isset($project['file_path']) && !empty($project['file_path'])): ?>
                    <br><small>Fichier actuel: <a href="<?= asset_url($project['file_path']) ?>" target="_blank"><?= e(basename($project['file_path'])) ?></a></small>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Statut</label>
                <select name="status" class="form-select">
                    <option value="draft" <?= (isset($project['status']) && $project['status'] === 'draft') ? 'selected' : '' ?>>Brouillon</option>
                    <option value="published" <?= (isset($project['status']) && $project['status'] === 'published') ? 'selected' : '' ?>>Publié</option>
                </select>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" name="is_featured" class="form-check-input" id="is_featured" <?= (isset($project['is_featured']) && $project['is_featured']) ? 'checked' : '' ?> />
                <label class="form-check-label" for="is_featured">À la une</label>
            </div>
            <div class="mb-3">
                <label class="form-label">Ordre</label>
                <input type="number" name="sort_order" class="form-control" value="<?= e($project['sort_order'] ?? 0) ?>">
            </div>

            <div class="d-flex gap-2">
                <button class="btn btn-primary btn-lg" type="submit">
                    <i class="bi bi-save me-1"></i> Enregistrer
                </button>
                <a class="btn btn-secondary btn-lg" href="<?= url('admin/projects-list') ?>">
                    <i class="bi bi-arrow-left me-1"></i> Retour
                </a>
            </div>
        </form>
    </div>
</div>

<div style="height: 100px;"></div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>

<?php
require_once __DIR__ . '/../includes/init.php';
require_login();

$acteModel = new Acte();

$flash_success = $_SESSION['flash_success'] ?? null;
$flash_error = $_SESSION['flash_error'] ?? null;
unset($_SESSION['flash_success'], $_SESSION['flash_error']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $acteId = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $title = clean_input($_POST['title'] ?? '');
    $description = clean_input($_POST['description'] ?? '');
    $date_pub = clean_input($_POST['date_pub'] ?? '') ?: null;
    $is_published = isset($_POST['is_published']) && $_POST['is_published'] == '1' ? 1 : 0;

    switch ($action) {
        case 'create_acte':
        case 'update_acte':
            if (empty($title)) {
                $_SESSION['flash_error'] = 'Le titre de l\'acte est requis.';
                header('Location: ' . url('admin/actes'));
                exit;
            }

            $data = [
                'title' => $title,
                'date_pub' => $date_pub,
                'is_published' => $is_published,
                'created_by' => $_SESSION['user_id'] ?? null
            ];

            // Handle PDF upload
            if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = BASE_PATH . '/uploads/actes/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $fileExt = strtolower(pathinfo($_FILES['pdf_file']['name'], PATHINFO_EXTENSION));
                $allowedExts = ['pdf'];
                
                if (in_array($fileExt, $allowedExts)) {
                    $fileName = uniqid('acte_') . '_' . time() . '.' . $fileExt;
                    $destination = $uploadDir . $fileName;
                    
                    if (move_uploaded_file($_FILES['pdf_file']['tmp_name'], $destination)) {
                        $data['pdf_file'] = 'uploads/actes/' . $fileName;
                        
                        // Delete old PDF if updating
                        if ($action === 'update_acte' && $acteId > 0) {
                            $oldActe = $acteModel->findById($acteId, false);
                            if ($oldActe && !empty($oldActe['pdf_file'])) {
                                $oldFilePath = BASE_PATH . '/' . ltrim($oldActe['pdf_file'], '/');
                                if (file_exists($oldFilePath)) {
                                    @unlink($oldFilePath);
                                }
                            }
                        }
                    }
                }
            }

            // Check that we have a PDF (required for create)
            if ($action === 'create_acte' && empty($data['pdf_file'])) {
                $_SESSION['flash_error'] = 'Un fichier PDF est requis.';
                header('Location: ' . url('admin/actes'));
                exit;
            }

            if ($action === 'create_acte') {
                $result = $acteModel->create($data);
                if ($result) {
                    $_SESSION['flash_success'] = 'Acte ajouté avec succès.';
                } else {
                    $_SESSION['flash_error'] = 'Impossible de créer l\'acte.';
                }
            } else {
                $existing = $acteModel->findById($acteId, false);
                if ($existing) {
                    $result = $acteModel->update($acteId, $data);
                    if ($result !== false) {
                        $_SESSION['flash_success'] = 'Acte mis à jour avec succès.';
                    } else {
                        $_SESSION['flash_error'] = 'Impossible de mettre à jour l\'acte.';
                    }
                } else {
                    $_SESSION['flash_error'] = 'Acte introuvable.';
                }
            }

            header('Location: ' . url('admin/actes'));
            exit;

        case 'delete_acte':
            if ($acteId > 0) {
                $acte = $acteModel->findById($acteId, false);
                if ($acte) {
                    // Delete PDF if exists
                    if (!empty($acte['pdf_file'])) {
                        $filePath = BASE_PATH . '/' . ltrim($acte['pdf_file'], '/');
                        if (file_exists($filePath)) {
                            @unlink($filePath);
                        }
                    }
                    if ($acteModel->delete($acteId)) {
                        $_SESSION['flash_success'] = 'Acte supprimé avec succès.';
                    } else {
                        $_SESSION['flash_error'] = 'Impossible de supprimer l\'acte.';
                    }
                }
            }
            header('Location: ' . url('admin/actes'));
            exit;
    }
}

$editId = isset($_GET['edit_id']) ? intval($_GET['edit_id']) : 0;
$editActe = $editId ? $acteModel->findById($editId, false) : null;
$actes = $acteModel->findAllAdmin(1, 100);

$page_title = 'Gestion des Actes de l\'OIA';
require_once __DIR__ . '/layouts/header.php';
?>

<?php if ($flash_success): ?>
    <div class="alert alert-success"><?= e($flash_success) ?></div>
<?php endif; ?>
<?php if ($flash_error): ?>
    <div class="alert alert-danger"><?= e($flash_error) ?></div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-xl-5">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><?= $editActe ? 'Modifier un Acte' : 'Ajouter un Acte' ?></h6>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="<?= $editActe ? 'update_acte' : 'create_acte' ?>">
                    <input type="hidden" name="id" value="<?= $editActe ? intval($editActe['id']) : 0 ?>">

                    <div class="mb-3">
                        <label class="form-label">Titre *</label>
                        <input type="text" name="title" class="form-control" value="<?= e($editActe['title'] ?? '') ?>" required>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Date de publication</label>
                            <input type="date" name="date_pub" class="form-control" value="<?= e($editActe['date_pub'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fichier PDF <?= $editActe ? '' : '*' ?></label>
                        <?php if ($editActe && !empty($editActe['pdf_file'])): ?>
                            <div class="mb-2">
                                <a href="<?= asset_url($editActe['pdf_file']) ?>" target="_blank" class="text-decoration-none">
                                    <i class="bi bi-file-pdf"></i> Voir le fichier actuel
                                </a>
                            </div>
                        <?php endif; ?>
                        <input type="file" name="pdf_file" class="form-control" accept=".pdf">
                        <small class="text-muted">Uniquement des fichiers PDF</small>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_published" class="form-check-input" value="1" <?= isset($editActe['is_published']) && $editActe['is_published'] == 1 ? 'checked' : '' ?>>
                            <label class="form-check-label">Publier immédiatement</label>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success"><?= $editActe ? 'Mettre à jour' : 'Créer l\'Acte' ?></button>
                        <?php if ($editActe): ?>
                            <a href="<?= url('admin/actes') ?>" class="btn btn-secondary">Nouvel Acte</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-xl-7">
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Actes de l'OIA</h6>
                <span class="badge bg-success"><?= count($actes) ?></span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Titre</th>
                                <th>Date</th>
                                <th>Statut</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($actes)): ?>
                                <?php foreach ($actes as $acte): ?>
                                    <tr>
                                        <td><?= intval($acte['id']) ?></td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="bi bi-file-pdf text-danger"></i>
                                                <span><?= e($acte['title']) ?></span>
                                            </div>
                                        </td>
                                        <td><?= e($acte['date_pub'] ?: '-') ?></td>
                                        <td>
                                            <span class="badge bg-<?= $acte['is_published'] == 1 ? 'success' : 'secondary' ?>">
                                                <?= $acte['is_published'] == 1 ? 'Publié' : 'Brouillon' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="<?= asset_url($acte['pdf_file']) ?>" target="_blank" class="btn btn-sm btn-outline-primary me-1">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="<?= url('admin/actes', ['edit_id' => $acte['id']]) ?>" class="btn btn-sm btn-warning me-1">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form method="POST" class="d-inline">
                                                <input type="hidden" name="action" value="delete_acte">
                                                <input type="hidden" name="id" value="<?= intval($acte['id']) ?>">
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cet acte ?');">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="5" class="text-center">Aucun acte trouvé.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>

<?php
require_once __DIR__ . '/../includes/init.php';
require_login();

$page_title = 'Gestion des Projets';

$projectModel = new Project();

// Traitement POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Jeton CSRF invalide.';
            redirect(url('admin/projects-list'));
        }
        $id = (int)($_POST['id'] ?? 0);
        if ($id) {
            $projectModel->delete($id);
            $_SESSION['success'] = 'Projet supprimé.';
        }
        redirect(url('admin/projects-list'));
    } else {
        // Création/édition
        if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Jeton CSRF invalide.';
            redirect(url('admin/projects-list'));
        }

        $id = (int)($_POST['id'] ?? 0);
        $filePath = '';
        $imagePath = '';
        
        // Récupérer les fichiers précédents si on édite
        if ($id) {
            $existing = $projectModel->findById($id);
            $filePath = $existing['file_path'] ?? '';
            $imagePath = $existing['image_path'] ?? '';
        }
        
        // Traitement fichier principal uploadé
        if (!empty($_FILES['file']['name'])) {
            $uploadDir = __DIR__ . '/../uploads/projects/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $file = $_FILES['file'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowed = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'webp'];
            
            if (in_array($ext, $allowed) && $file['size'] <= MAX_UPLOAD_SIZE) {
                $filename = 'project_' . time() . '_' . md5($file['name']) . '.' . $ext;
                $filePath = 'uploads/projects/' . $filename;
                move_uploaded_file($file['tmp_name'], $uploadDir . $filename);
                
                // Si c'est une image, la sauvegarder aussi en tant qu'image
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $imagePath = $filePath;
                }
            } else {
                $_SESSION['error'] = 'Fichier invalide ou trop volumineux.';
                redirect(url('admin/projects-list'));
            }
        }
        
        // Traitement image uploadée
        if (!empty($_FILES['image']['name'])) {
            $uploadDir = __DIR__ . '/../uploads/projects/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $file = $_FILES['image'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            
            if (in_array($ext, $allowed) && $file['size'] <= MAX_UPLOAD_SIZE) {
                $filename = 'project_img_' . time() . '_' . md5($file['name']) . '.' . $ext;
                $imagePath = 'uploads/projects/' . $filename;
                move_uploaded_file($file['tmp_name'], $uploadDir . $filename);
            } else {
                $_SESSION['error'] = 'Image invalide ou trop volumineuse.';
                redirect(url('admin/projects-list'));
            }
        }

        $data = [
            'title' => clean_input($_POST['title'] ?? ''),
            'slug' => slugify($_POST['title'] ?? ''),
            'summary' => clean_input($_POST['summary'] ?? ''),
            'start_date' => !empty($_POST['start_date']) ? $_POST['start_date'] : null,
            'end_date' => !empty($_POST['end_date']) ? $_POST['end_date'] : null,
            'status' => in_array($_POST['status'] ?? 'draft', ['draft','published']) ? $_POST['status'] : 'draft',
            'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
        ];
        
        if (!empty($filePath)) {
            $data['file_path'] = $filePath;
        }
        
        if (!empty($imagePath)) {
            $data['image_path'] = $imagePath;
        }

        if ($id) {
            $projectModel->update($id, $data);
            $_SESSION['success'] = 'Projet mis à jour.';
        } else {
            $projectModel->create($data);
            $_SESSION['success'] = 'Projet créé.';
        }

        redirect(url('admin/projects-list'));
    }
}

require_once __DIR__ . '/layouts/header.php';

$projects = $projectModel->findAll(1, 200);
$editProject = null;

// Si édition, charger les données du projet
if (isset($_GET['edit'])) {
    $editProject = $projectModel->findById((int)$_GET['edit']);
    if (!$editProject) {
        $_SESSION['error'] = 'Projet non trouvé.';
        redirect(url('admin/projects-list'));
    }
}

?>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <h6 class="m-0 font-weight-bold" style="color: #5b2d00;">
            <i class="bi bi-kanban me-2"></i>
            Tous les projets
        </h6>
        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#projectModal" onclick="resetProjectForm()">
            <i class="bi bi-plus"></i> Nouveau projet
        </button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>ID</th>
                        <th>Titre</th>
                        <th>Date de début</th>
                        <th>Statut</th>
                        <th>À la une</th>
                        <th>Image</th>
                        <th>Fichier</th>
                        <th style="width: 180px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($projects): ?>
                        <?php foreach ($projects as $p): ?>
                            <tr>
                                <td><?= $p['id'] ?></td>
                                <td class="fw-semibold"><?= e($p['title']) ?></td>
                                <td>
                                    <?= !empty($p['start_date']) ? format_date($p['start_date'], 'd/m/Y') : '-' ?>
                                    <?php if (!empty($p['end_date'])): ?>
                                        au <?= format_date($p['end_date'], 'd/m/Y') ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge rounded-pill bg-<?= $p['status'] === 'published' ? 'success' : 'warning' ?>">
                                        <?= ucfirst($p['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge rounded-pill bg-<?= $p['is_featured'] ? 'primary' : 'secondary' ?>">
                                        <?= $p['is_featured'] ? 'Oui' : 'Non' ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if (!empty($p['image_path'])): ?>
                                        <i class="bi bi-image text-success"></i>
                                    <?php else: ?>
                                        <i class="bi bi-image text-muted"></i>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($p['file_path'])): ?>
                                        <i class="bi bi-file-earmark-check text-success"></i>
                                    <?php else: ?>
                                        <i class="bi bi-file-earmark-x text-muted"></i>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#projectModal" onclick="editProject(<?= $p['id'] ?>)">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form method="POST" style="display:inline-block;" onsubmit="return confirm('Confirmer la suppression ?')">
                                        <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-kanban display-1 mb-3 d-block"></i>
                                Aucun projet pour le moment
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Project Modal (Add/Edit) -->
<div class="modal fade" id="projectModal" tabindex="-1" aria-hidden="true" style="z-index: 9999;">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-header" style="z-index: 10;">
                    <h5 class="modal-title" id="modalTitle">Nouveau projet</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                    <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
                    <input type="hidden" name="id" id="project_id" value="">
                    
                    <div class="mb-3">
                        <label class="form-label">Titre <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="project_title" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="summary" id="project_summary" class="form-control" rows="3" placeholder="Résumé court du projet"></textarea>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Date de début</label>
                            <input type="date" name="start_date" id="project_start_date" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date de fin</label>
                            <input type="date" name="end_date" id="project_end_date" class="form-control">
                        </div>
                    </div>
                    
                    <div class="row g-3 mt-1">
                        <div class="col-md-6">
                            <label class="form-label">Statut</label>
                            <select name="status" id="project_status" class="form-select">
                                <option value="draft">Brouillon</option>
                                <option value="published">Publié</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check pt-4">
                                <input type="checkbox" name="is_featured" id="project_featured" class="form-check-input">
                                <label class="form-check-label" for="project_featured">À la une</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Image du projet</label>
                        <input type="file" name="image" id="project_image" class="form-control" accept=".jpg,.jpeg,.png,.gif,.webp">
                        <small class="form-text text-muted d-block mt-1">Formats: JPG, PNG, GIF, WEBP (Max 10 Mo)</small>
                        <div id="imageInfo" class="mt-2"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Fichier (PDF ou Image)</label>
                        <input type="file" name="file" id="project_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.gif,.webp">
                        <small class="form-text text-muted d-block mt-1">Formats: PDF, JPG, PNG, GIF, WEBP (Max 10 Mo)</small>
                        <div id="fileInfo" class="mt-2"></div>
                    </div>
                </div>
                <div class="modal-footer" style="z-index: 10;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function resetProjectForm() {
    document.getElementById('modalTitle').textContent = 'Nouveau projet';
    document.getElementById('project_id').value = '';
    document.getElementById('project_title').value = '';
    document.getElementById('project_summary').value = '';
    document.getElementById('project_start_date').value = '';
    document.getElementById('project_end_date').value = '';
    document.getElementById('project_status').value = 'draft';
    document.getElementById('project_featured').checked = false;
    document.getElementById('imageInfo').innerHTML = '';
    document.getElementById('fileInfo').innerHTML = '';
}

function editProject(id) {
    // Charger les données du projet
    const projectData = <?= json_encode($projects) ?>;
    const project = projectData.find(p => p.id == id);
    
    if (!project) return;
    
    document.getElementById('modalTitle').textContent = 'Modifier le projet';
    document.getElementById('project_id').value = project.id;
    document.getElementById('project_title').value = project.title;
    document.getElementById('project_summary').value = project.summary || '';
    document.getElementById('project_start_date').value = project.start_date || '';
    document.getElementById('project_end_date').value = project.end_date || '';
    document.getElementById('project_status').value = project.status;
    document.getElementById('project_featured').checked = project.is_featured;
    
    if (project.image_path) {
        document.getElementById('imageInfo').innerHTML = `<small>Image actuelle: <img src="<?= APP_URL ?>/${project.image_path}" style="max-width:100px; max-height:100px; border-radius:4px;" alt="Thumbnail"></small>`;
    } else {
        document.getElementById('imageInfo').innerHTML = '';
    }
    
    if (project.file_path) {
        document.getElementById('fileInfo').innerHTML = `<small>Fichier actuel: <a href="<?= APP_URL ?>/${project.file_path}" target="_blank">${project.file_path.split('/').pop()}</a></small>`;
    } else {
        document.getElementById('fileInfo').innerHTML = '';
    }
}

// Réinitialiser le modal à l'ouverture pour création
const projectModal = document.getElementById('projectModal');
projectModal.addEventListener('show.bs.modal', function(e) {
    if (!e.relatedTarget?.classList.contains('btn-outline-primary')) {
        // C'est un bouton "+ Nouveau projet"
        resetProjectForm();
    }
});
</script>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>

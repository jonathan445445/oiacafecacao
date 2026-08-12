<?php
require_once __DIR__ . '/../includes/init.php';
require_login();

$filiereModel = new Filiere();
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$filieres = $filiereModel->findAllAdmin($page, 20);

if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    if ($delete_id > 0 && $filiereModel->delete($delete_id)) {
        $_SESSION['flash_success'] = 'Filière supprimée avec succès.';
    } else {
        $_SESSION['flash_error'] = 'Impossible de supprimer la filière.';
    }

    header('Location: ' . url('admin/filieres'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'create';
    $name = clean_input($_POST['name'] ?? '');
    $slug = clean_input($_POST['slug'] ?? '');
    $description = clean_input($_POST['description'] ?? '');
    $content = clean_input($_POST['content'] ?? '');
    $actions = clean_input($_POST['actions'] ?? '');
    $production_share = clean_input($_POST['production_share'] ?? '');
    $producers_count = clean_input($_POST['producers_count'] ?? '');
    $tonnes_per_year = clean_input($_POST['tonnes_per_year'] ?? '');
    $is_published = isset($_POST['is_published']) ? 1 : 0;
    $sort_order = isset($_POST['sort_order']) ? intval($_POST['sort_order']) : 0;

    $data = [
        'name' => $name,
        'slug' => $slug,
        'description' => $description,
        'content' => $content,
        'actions' => $actions,
        'production_share' => $production_share,
        'producers_count' => $producers_count,
        'tonnes_per_year' => $tonnes_per_year,
        'is_published' => $is_published,
        'sort_order' => $sort_order,
        'updated_at' => date('Y-m-d H:i:s')
    ];

    // Cover photo upload
    if (isset($_FILES['cover_photo']) && $_FILES['cover_photo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = UPLOAD_PATH . '/filieres/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $originalName = pathinfo($_FILES['cover_photo']['name'], PATHINFO_FILENAME);
        $extension = strtolower(pathinfo($_FILES['cover_photo']['name'], PATHINFO_EXTENSION));
        $safeName = slugify($originalName) ?: 'filiere-image';
        $fileName = $safeName . '-' . time() . '.' . $extension;
        $destination = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['cover_photo']['tmp_name'], $destination)) {
            $data['cover_photo'] = 'uploads/filieres/' . $fileName;
        }
    }

    if (empty($data['name'])) {
        $_SESSION['flash_error'] = 'Le nom de la filière est requis.';
        header('Location: ' . url('admin/filieres'));
        exit;
    }

    if ($action === 'create') {
        $data['created_at'] = date('Y-m-d H:i:s');
        if ($filiereModel->create($data)) {
            $_SESSION['flash_success'] = 'Filière créée avec succès.';
        } else {
            $_SESSION['flash_error'] = 'Impossible de créer la filière.';
        }
    } elseif ($action === 'update') {
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $existing = $filiereModel->findById($id);

        if (isset($data['cover_photo']) && $existing && !empty($existing['cover_photo'])) {
            $oldCover = BASE_PATH . '/' . ltrim($existing['cover_photo'], '/');
            if (file_exists($oldCover)) {
                @unlink($oldCover);
            }
        }

        if ($filiereModel->update($id, $data)) {
            $_SESSION['flash_success'] = 'Filière mise à jour avec succès.';
        } else {
            $_SESSION['flash_error'] = 'Impossible de mettre à jour la filière.';
        }
    }

    header('Location: ' . url('admin/filieres'));
    exit;
}

$page_title = 'Gestion des Filières';
require_once __DIR__ . '/layouts/header.php';
?>

<div class="row g-4">
    <div class="col-lg-5">
        <?php if (isset($_SESSION['flash_success'])): ?>
            <div class="alert alert-success"><?php echo e($_SESSION['flash_success']); unset($_SESSION['flash_success']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['flash_error'])): ?>
            <div class="alert alert-danger"><?php echo e($_SESSION['flash_error']); unset($_SESSION['flash_error']); ?></div>
        <?php endif; ?>

        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Ajouter / Modifier une filière</h6>
            </div>
            <div class="card-body">
                <form method="POST" id="filiereForm" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="create" id="filiere-action">
                    <input type="hidden" name="id" value="" id="filiere-id">

                    <div class="mb-3">
                        <label class="form-label">Nom</label>
                        <input type="text" name="name" id="filiere-name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" id="filiere-slug" class="form-control">
                        <small class="text-muted">Si vide, le slug sera généré automatiquement.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Image de couverture</label>
                        <input type="file" name="cover_photo" id="filiere-cover-photo" class="form-control" accept="image/*">
                        <div class="mt-3" id="filiere-cover-preview" style="display:none;">
                            <p class="mb-2"><strong>Image actuelle :</strong></p>
                            <img src="" alt="Aperçu" class="img-fluid rounded" style="max-height: 180px; object-fit: cover;">
                        </div>
                        <small class="text-muted">Formats : JPG, PNG, GIF, WebP.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description courte</label>
                        <textarea name="description" id="filiere-description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contenu détaillé</label>
                        <textarea name="content" id="filiere-content" class="form-control" rows="6"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nos actions pour la filière</label>
                        <div id="filiere-actions-list" class="mb-2"></div>
                        <button type="button" class="btn btn-sm btn-outline-primary mb-3" id="add-filiere-action">Ajouter une action</button>
                        <textarea name="actions" id="filiere-actions" class="form-control d-none"></textarea>
                        <small class="text-muted">Ajoutez une action par ligne. Chaque action est ajoutée individuellement.</small>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Producteurs</label>
                            <input type="text" name="producers_count" id="filiere-producers-count" class="form-control" placeholder="Ex: 2M+">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tonnes par an</label>
                            <input type="text" name="tonnes_per_year" id="filiere-tonnes-per-year" class="form-control" placeholder="Ex: 2.2M+">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Production mondiale</label>
                        <input type="text" name="production_share" id="filiere-production-share" class="form-control" placeholder="Ex: 40%">
                        <small class="text-muted">Indiquez le taux de production, par exemple 40%.</small>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Ordre</label>
                            <input type="number" name="sort_order" id="filiere-sort-order" class="form-control" value="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Publié</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_published" id="filiere-is-published" value="1" checked>
                                <label class="form-check-label" for="filiere-is-published">Oui</label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                        <button type="button" class="btn btn-secondary" id="filiere-reset">Réinitialiser</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Liste des filières</h6>
                <span class="badge bg-success"><?= count($filieres) ?></span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Slug</th>
                                <th>Publié</th>
                                <th>Ordre</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($filieres): ?>
                                <?php foreach ($filieres as $filiere): ?>
                                    <tr>
                                        <td>
                                            <?php if (!empty($filiere['cover_photo'])): ?>
                                                <img src="<?= APP_URL . '/' . e($filiere['cover_photo']) ?>" alt="<?= e($filiere['name']) ?>" style="max-height: 60px; object-fit: cover; border-radius: 6px; margin-right: 8px;">
                                            <?php endif; ?>
                                            <?= e($filiere['name']) ?>
                                        </td>
                                        <td><?= e($filiere['slug']) ?></td>
                                        <td>
                                            <?php if ($filiere['is_published']): ?>
                                                <span class="badge bg-success">Oui</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Non</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= e($filiere['sort_order']) ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-warning edit-filiere" data-id="<?= $filiere['id'] ?>" data-name="<?= e($filiere['name']) ?>" data-slug="<?= e($filiere['slug']) ?>" data-description="<?= e($filiere['description']) ?>" data-content="<?= e($filiere['content']) ?>" data-actions="<?= e(base64_encode($filiere['actions'] ?? '')) ?>" data-production-share="<?= e($filiere['production_share'] ?? '') ?>" data-producers-count="<?= e($filiere['producers_count'] ?? '') ?>" data-tonnes-per-year="<?= e($filiere['tonnes_per_year'] ?? '') ?>" data-cover-photo="<?= e($filiere['cover_photo']) ?>" data-is-published="<?= $filiere['is_published'] ?>" data-sort-order="<?= e($filiere['sort_order']) ?>">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <a href="<?= url('admin/filieres') ?>&delete_id=<?= $filiere['id'] ?>" class="btn btn-sm btn-danger delete-filiere" onclick="return confirm('Voulez-vous supprimer cette filière ?');">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="5" class="text-center">Aucune filière disponible</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const actionsList = document.getElementById('filiere-actions-list');
const actionsField = document.getElementById('filiere-actions');
const addActionBtn = document.getElementById('add-filiere-action');

function createActionInput(value = '') {
    const wrapper = document.createElement('div');
    wrapper.className = 'input-group mb-2';

    const input = document.createElement('input');
    input.type = 'text';
    input.className = 'form-control filiere-action-item';
    input.value = value;
    input.placeholder = 'Action pour la filière';

    const buttonWrapper = document.createElement('button');
    buttonWrapper.type = 'button';
    buttonWrapper.className = 'btn btn-outline-danger';
    buttonWrapper.textContent = 'Supprimer';
    buttonWrapper.addEventListener('click', () => wrapper.remove());

    wrapper.appendChild(input);
    wrapper.appendChild(buttonWrapper);
    actionsList.appendChild(wrapper);
    return input;
}

function populateActions(actions) {
    actionsList.innerHTML = '';
    if (!actions) {
        createActionInput('');
        return;
    }

    const items = actions.split(/\r?\n/).filter(item => item.trim() !== '');
    if (items.length === 0) {
        createActionInput('');
        return;
    }

    items.forEach(item => createActionInput(item));
}

function collectActions() {
    const values = Array.from(document.querySelectorAll('.filiere-action-item'))
        .map(input => input.value.trim())
        .filter(value => value !== '');
    actionsField.value = values.join('\n');
}

function decodeBase64Unicode(str) {
    try {
        const bytes = atob(str);
        const percentEncoded = Array.prototype.map.call(bytes, function (c) {
            return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
        }).join('');
        return decodeURIComponent(percentEncoded);
    } catch (error) {
        return '';
    }
}

document.querySelectorAll('.edit-filiere').forEach(btn => {
    btn.addEventListener('click', () => {
        document.getElementById('filiere-action').value = 'update';
        document.getElementById('filiere-id').value = btn.dataset.id;
        document.getElementById('filiere-name').value = btn.dataset.name;
        document.getElementById('filiere-slug').value = btn.dataset.slug;
        document.getElementById('filiere-description').value = btn.dataset.description;
        document.getElementById('filiere-content').value = btn.dataset.content;
        document.getElementById('filiere-production-share').value = btn.dataset.productionShare;
        document.getElementById('filiere-producers-count').value = btn.dataset.producersCount;
        document.getElementById('filiere-tonnes-per-year').value = btn.dataset.tonnesPerYear;
        document.getElementById('filiere-sort-order').value = btn.dataset.sortOrder;
        document.getElementById('filiere-is-published').checked = btn.dataset.isPublished === '1';
        const decodedActions = btn.dataset.actions ? decodeBase64Unicode(btn.dataset.actions) : '';
        populateActions(decodedActions);

        const coverPreview = document.getElementById('filiere-cover-preview');
        const coverPreviewImg = coverPreview.querySelector('img');
        if (btn.dataset.coverPhoto) {
            coverPreview.style.display = 'block';
            coverPreviewImg.src = '<?= APP_URL ?>/' + btn.dataset.coverPhoto;
        } else {
            coverPreview.style.display = 'none';
            coverPreviewImg.src = '';
        }

        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
});

addActionBtn.addEventListener('click', () => {
    createActionInput('');
});

document.getElementById('filiereForm').addEventListener('submit', () => {
    collectActions();
});

document.getElementById('filiere-reset').addEventListener('click', () => {
    document.getElementById('filiereForm').reset();
    document.getElementById('filiere-action').value = 'create';
    document.getElementById('filiere-id').value = '';
    populateActions('');
});

populateActions('');
</script>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>

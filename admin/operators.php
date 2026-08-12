<?php
require_once __DIR__ . '/../includes/init.php';
require_login();

$page_title = 'Gestion des Acheteurs / Opérateurs';

$operatorModel = new Operator();
$filiereModel = new Filiere();

$filieres = $filiereModel->findAll(1, 200, false);
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$operators = $operatorModel->findAllAdmin($page, 20);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Jeton CSRF invalide.';
            redirect(url('admin/operators'));
        }

        if (isset($_POST['action']) && $_POST['action'] === 'delete') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id) {
                $operatorModel->delete($id);
                $_SESSION['success'] = 'Opérateur/Acheteur supprimé avec succès.';
            }
            redirect(url('admin/operators'));
        }

        $id = (int)($_POST['id'] ?? 0);
        $filiereIds = array_values(array_filter(array_map('intval', (array)($_POST['filiere_ids'] ?? [])), fn($value) => $value > 0));
        $data = [
            'type' => in_array($_POST['type'] ?? '', ['acheteur', 'operateur'], true) ? $_POST['type'] : 'operateur',
            'filiere_ids' => $filiereIds,
            'name' => clean_input($_POST['name'] ?? ''),
            'slug' => clean_input($_POST['slug'] ?? ''),
            'description' => clean_input($_POST['description'] ?? ''),
            'contact_person' => clean_input($_POST['contact_person'] ?? ''),
            'email' => clean_input($_POST['email'] ?? ''),
            'phone' => clean_input($_POST['phone'] ?? ''),
            'address' => clean_input($_POST['address'] ?? ''),
            'website' => clean_input($_POST['website'] ?? ''),
            'is_published' => isset($_POST['is_published']) ? 1 : 0,
            'sort_order' => isset($_POST['sort_order']) ? intval($_POST['sort_order']) : 0,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = UPLOAD_PATH . '/operators/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $originalName = pathinfo($_FILES['logo']['name'], PATHINFO_FILENAME);
            $extension = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
            $safeName = slugify($originalName) ?: 'operator-logo';
            $fileName = $safeName . '-' . time() . '.' . $extension;
            $destination = $uploadDir . $fileName;
            if (move_uploaded_file($_FILES['logo']['tmp_name'], $destination)) {
                $data['logo'] = 'uploads/operators/' . $fileName;
            }
        }

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = UPLOAD_PATH . '/operators/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $originalName = pathinfo($_FILES['image']['name'], PATHINFO_FILENAME);
            $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $safeName = slugify($originalName) ?: 'operator-image';
            $fileName = $safeName . '-' . time() . '.' . $extension;
            $destination = $uploadDir . $fileName;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                $data['image'] = 'uploads/operators/' . $fileName;
            }
        }

        if (empty($data['name'])) {
            $_SESSION['error'] = 'Le nom est requis.';
            redirect(url('admin/operators'));
        }

        if ($id) {
            $existing = $operatorModel->findById($id);
            if (!empty($data['logo']) && $existing && !empty($existing['logo'])) {
                $oldLogo = BASE_PATH . '/' . ltrim($existing['logo'], '/');
                if (file_exists($oldLogo)) {
                    @unlink($oldLogo);
                }
            }
            if (!empty($data['image']) && $existing && !empty($existing['image'])) {
                $oldImage = BASE_PATH . '/' . ltrim($existing['image'], '/');
                if (file_exists($oldImage)) {
                    @unlink($oldImage);
                }
            }
            $operatorModel->update($id, $data);
            $_SESSION['success'] = 'Opérateur/Acheteur mis à jour avec succès.';
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            $operatorModel->create($data);
            $_SESSION['success'] = 'Opérateur/Acheteur créé avec succès.';
        }

        redirect(url('admin/operators'));
    } catch (Exception $e) {
        $_SESSION['error'] = 'Erreur : ' . $e->getMessage();
        redirect(url('admin/operators'));
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
                <h6 class="m-0 font-weight-bold text-primary">Ajouter / Modifier un acheteur ou opérateur</h6>
            </div>
            <div class="card-body">
                <form method="POST" id="operatorForm" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="" id="operator-id">
                    <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">

                    <div class="mb-3">
                        <label class="form-label">Type</label>
                        <select name="type" id="operator-type" class="form-control">
                            <option value="acheteur">Acheteur</option>
                            <option value="operateur">Opérateur</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Filières associées</label>
                        <div id="operator-filieres" class="row gx-2">
                            <?php foreach ($filieres as $filiere): ?>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="filiere_ids[]" value="<?= e($filiere['id']) ?>" id="operator-filiere-<?= e($filiere['id']) ?>">
                                        <label class="form-check-label" for="operator-filiere-<?= e($filiere['id']) ?>"><?= e($filiere['name']) ?></label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <small class="text-muted">Cochez une ou plusieurs filières.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nom</label>
                        <input type="text" name="name" id="operator-name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" id="operator-slug" class="form-control">
                        <small class="text-muted">Laisser vide pour générer automatiquement.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" id="operator-description" class="form-control" rows="4"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Personne de contact</label>
                        <input type="text" name="contact_person" id="operator-contact-person" class="form-control">
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" id="operator-email" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Téléphone</label>
                            <input type="text" name="phone" id="operator-phone" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Adresse</label>
                        <textarea name="address" id="operator-address" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Site web</label>
                        <input type="url" name="website" id="operator-website" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Logo</label>
                        <input type="file" name="logo" id="operator-logo" class="form-control" accept="image/*">
                        <div class="mt-3" id="operator-logo-preview" style="display:none;">
                            <p class="mb-2"><strong>Logo actuel :</strong></p>
                            <img src="" alt="Logo" class="img-fluid rounded" style="max-height: 120px; object-fit: cover;">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Image</label>
                        <input type="file" name="image" id="operator-image" class="form-control" accept="image/*">
                        <div class="mt-3" id="operator-image-preview" style="display:none;">
                            <p class="mb-2"><strong>Image actuelle :</strong></p>
                            <img src="" alt="Image" class="img-fluid rounded" style="max-height: 200px; object-fit: cover;">
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Ordre</label>
                            <input type="number" name="sort_order" id="operator-sort-order" class="form-control" value="0">
                        </div>
                        <div class="col-md-6">
                            <div class="form-check pt-4">
                                <input class="form-check-input" type="checkbox" name="is_published" id="operator-is-published" value="1" checked>
                                <label class="form-check-label" for="operator-is-published">Publié</label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                        <button type="button" class="btn btn-secondary" id="operator-reset">Réinitialiser</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Liste des Acheteurs / Opérateurs</h6>
                <span class="badge bg-success"><?= count($operators) ?></span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Type</th>
                                <th>Filière</th>
                                <th>Publié</th>
                                <th>Ordre</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($operators): ?>
                                <?php foreach ($operators as $operator): ?>
                                    <tr>
                                        <td>
                                            <?php if (!empty($operator['logo'])): ?>
                                                <img src="<?= APP_URL . '/' . e($operator['logo']) ?>" alt="<?= e($operator['name']) ?>" style="max-height: 50px; object-fit: cover; border-radius: 6px; margin-right: 8px;">
                                            <?php endif; ?>
                                            <?= e($operator['name']) ?>
                                        </td>
                                        <td><?= e($operator['type'] === 'acheteur' ? 'Acheteur' : 'Opérateur') ?></td>
                                        <td><?= e($operator['filiere_names'] ?? ($operator['filiere_name'] ?? '-')) ?></td>
                                        <td>
                                            <?php if ($operator['is_published']): ?>
                                                <span class="badge bg-success">Oui</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Non</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= e($operator['sort_order']) ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-warning edit-operator" data-id="<?= $operator['id'] ?>" data-type="<?= e($operator['type']) ?>" data-filiere-ids="<?= e($operator['filiere_ids'] ?? '') ?>" data-name="<?= e($operator['name']) ?>" data-slug="<?= e($operator['slug']) ?>" data-description="<?= e(base64_encode($operator['description'] ?? '')) ?>" data-contact-person="<?= e($operator['contact_person'] ?? '') ?>" data-email="<?= e($operator['email'] ?? '') ?>" data-phone="<?= e($operator['phone'] ?? '') ?>" data-address="<?= e(base64_encode($operator['address'] ?? '')) ?>" data-website="<?= e($operator['website'] ?? '') ?>" data-logo="<?= e($operator['logo'] ?? '') ?>" data-image="<?= e($operator['image'] ?? '') ?>" data-is-published="<?= $operator['is_published'] ?>" data-sort-order="<?= e($operator['sort_order']) ?>">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <form method="POST" style="display:inline-block;" onsubmit="return confirm('Voulez-vous supprimer cet élément ?')">
                                                <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
                                                <input type="hidden" name="id" value="<?= $operator['id'] ?>">
                                                <input type="hidden" name="action" value="delete">
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="6" class="text-center">Aucun acheteur ou opérateur trouvé</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
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

function editOperator(button) {
    document.getElementById('operator-id').value = button.dataset.id;
    document.getElementById('operator-type').value = button.dataset.type;
    const filiereIds = (button.dataset.filiereIds || '').split(',').filter(Boolean);
    document.querySelectorAll('input[name="filiere_ids[]"]').forEach(checkbox => {
        checkbox.checked = filiereIds.includes(checkbox.value);
    });
    document.getElementById('operator-name').value = button.dataset.name;
    document.getElementById('operator-slug').value = button.dataset.slug;
    document.getElementById('operator-description').value = decodeBase64Unicode(button.dataset.description || '');
    document.getElementById('operator-contact-person').value = button.dataset.contactPerson || '';
    document.getElementById('operator-email').value = button.dataset.email || '';
    document.getElementById('operator-phone').value = button.dataset.phone || '';
    document.getElementById('operator-address').value = decodeBase64Unicode(button.dataset.address || '');
    document.getElementById('operator-website').value = button.dataset.website || '';
    document.getElementById('operator-sort-order').value = button.dataset.sortOrder || 0;
    document.getElementById('operator-is-published').checked = button.dataset.isPublished === '1';

    const logoPreview = document.getElementById('operator-logo-preview');
    const logoImg = logoPreview.querySelector('img');
    if (button.dataset.logo) {
        logoPreview.style.display = 'block';
        logoImg.src = '<?= APP_URL ?>/' + button.dataset.logo;
    } else {
        logoPreview.style.display = 'none';
        logoImg.src = '';
    }

    const imagePreview = document.getElementById('operator-image-preview');
    const imageImg = imagePreview.querySelector('img');
    if (button.dataset.image) {
        imagePreview.style.display = 'block';
        imageImg.src = '<?= APP_URL ?>/' + button.dataset.image;
    } else {
        imagePreview.style.display = 'none';
        imageImg.src = '';
    }

    window.scrollTo({ top: 0, behavior: 'smooth' });
}

document.querySelectorAll('.edit-operator').forEach(btn => {
    btn.addEventListener('click', () => editOperator(btn));
});

document.getElementById('operator-reset').addEventListener('click', () => {
    document.getElementById('operatorForm').reset();
    document.getElementById('operator-id').value = '';
    document.getElementById('operator-type').value = 'acheteur';
    document.querySelectorAll('input[name="filiere_ids[]"]').forEach(checkbox => checkbox.checked = false);
    document.getElementById('operator-logo-preview').style.display = 'none';
    document.getElementById('operator-image-preview').style.display = 'none';
});
</script>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>

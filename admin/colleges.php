<?php
require_once __DIR__ . '/../includes/init.php';
require_login();

$collegeModel = new College();
$colleges = $collegeModel->findAll(1, 100, false);

if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    if ($delete_id > 0 && $collegeModel->delete($delete_id)) {
        $_SESSION['flash_success'] = 'Collège supprimé avec succès.';
    } else {
        $_SESSION['flash_error'] = 'Impossible de supprimer le collège.';
    }

    header('Location: ' . url('admin/colleges'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'create';
    $name = clean_input($_POST['name'] ?? '');
    $slug = clean_input($_POST['slug'] ?? '');
    $icon_class = clean_input($_POST['icon_class'] ?? '');
    $short_description = clean_input($_POST['short_description'] ?? '');
    $description = clean_input($_POST['description'] ?? '');
    $contact_person = clean_input($_POST['contact_person'] ?? '');
    $logo = '';
    $contact_email = clean_input($_POST['contact_email'] ?? '');
    $contact_phone = clean_input($_POST['contact_phone'] ?? '');
    $contact_address = clean_input($_POST['contact_address'] ?? '');
    $contact_website = clean_input($_POST['contact_website'] ?? '');
    $is_published = isset($_POST['is_published']) ? 1 : 0;
    $sort_order = isset($_POST['sort_order']) ? intval($_POST['sort_order']) : 0;

    $data = [
        'name' => $name,
        'slug' => $slug,
        'icon_class' => $icon_class,
        'short_description' => $short_description,
        'description' => $description,
        'logo' => $logo,
        'contact_person' => $contact_person,
        'contact_email' => $contact_email,
        'contact_phone' => $contact_phone,
        'contact_address' => $contact_address,
        'contact_website' => $contact_website,
        'is_published' => $is_published,
        'sort_order' => $sort_order,
        'updated_at' => date('Y-m-d H:i:s')
    ];

    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = UPLOAD_PATH . '/colleges/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $originalName = pathinfo($_FILES['logo']['name'], PATHINFO_FILENAME);
        $extension = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
        $safeName = slugify($originalName) ?: 'college-logo';
        $fileName = $safeName . '-logo-' . time() . '.' . $extension;
        $destination = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['logo']['tmp_name'], $destination)) {
            $data['logo'] = 'uploads/colleges/' . $fileName;
        }
    }

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = UPLOAD_PATH . '/colleges/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $originalName = pathinfo($_FILES['image']['name'], PATHINFO_FILENAME);
        $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $safeName = slugify($originalName) ?: 'college-image';
        $fileName = $safeName . '-' . time() . '.' . $extension;
        $destination = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
            $data['image'] = 'uploads/colleges/' . $fileName;
        }
    }

    if (empty($data['name'])) {
        $_SESSION['flash_error'] = 'Le nom du collège est requis.';
        header('Location: ' . url('admin/colleges'));
        exit;
    }

    if ($action === 'create') {
        $data['created_at'] = date('Y-m-d H:i:s');
        if ($collegeModel->create($data)) {
            $_SESSION['flash_success'] = 'Collège créé avec succès.';
        } else {
            $_SESSION['flash_error'] = 'Impossible de créer le collège.';
        }
    } elseif ($action === 'update') {
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $existing = $collegeModel->findById($id);

        if ($existing) {
            if (empty($data['logo'])) {
                $data['logo'] = $existing['logo'];
            } elseif (!empty($existing['logo']) && $existing['logo'] !== $data['logo']) {
                $oldLogo = BASE_PATH . '/' . ltrim($existing['logo'], '/');
                if (file_exists($oldLogo)) {
                    @unlink($oldLogo);
                }
            }

            if (empty($data['image'])) {
                $data['image'] = $existing['image'];
            } elseif (!empty($existing['image']) && $existing['image'] !== $data['image']) {
                $oldImage = BASE_PATH . '/' . ltrim($existing['image'], '/');
                if (file_exists($oldImage)) {
                    @unlink($oldImage);
                }
            }

            if ($collegeModel->update($id, $data)) {
                $_SESSION['flash_success'] = 'Collège mis à jour avec succès.';
            } else {
                $_SESSION['flash_error'] = 'Impossible de mettre à jour le collège.';
            }
        } else {
            $_SESSION['flash_error'] = 'Collège introuvable.';
        }
    }

    header('Location: ' . url('admin/colleges'));
    exit;
}

$page_title = 'Gestion des Collèges';
require_once __DIR__ . '/layouts/header.php';
?>

<div class="row g-4">
    <div class="col-lg-5">
        <?php if (isset($_SESSION['flash_success'])): ?>
            <div class="alert alert-success"><?= e($_SESSION['flash_success']); unset($_SESSION['flash_success']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['flash_error'])): ?>
            <div class="alert alert-danger"><?= e($_SESSION['flash_error']); unset($_SESSION['flash_error']); ?></div>
        <?php endif; ?>

        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Ajouter / Modifier un collège</h6>
            </div>
            <div class="card-body">
                <form method="POST" id="collegeForm" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="create" id="college-action">
                    <input type="hidden" name="id" value="" id="college-id">

                    <div class="mb-3">
                        <label class="form-label">Nom</label>
                        <input type="text" name="name" id="college-name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" id="college-slug" class="form-control">
                        <small class="text-muted">Si vide, le slug sera généré automatiquement.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Icône Bootstrap</label>
                        <input type="text" name="icon_class" id="college-icon-class" class="form-control" placeholder="bi-people, bi-building...">
                        <small class="text-muted">Classe d'icône Bootstrap Icons.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Logo</label>
                        <input type="file" name="logo" id="college-logo" class="form-control" accept="image/*">
                        <div class="mt-3" id="college-logo-preview" style="display:none;">
                            <p class="mb-2"><strong>Logo actuel :</strong></p>
                            <img src="" alt="Aperçu logo" class="img-fluid rounded" style="max-height: 120px; object-fit: contain; width: auto;">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Image</label>
                        <input type="file" name="image" id="college-image" class="form-control" accept="image/*">
                        <div class="mt-3" id="college-image-preview" style="display:none;">
                            <p class="mb-2"><strong>Image actuelle :</strong></p>
                            <img src="" alt="Aperçu" class="img-fluid rounded" style="max-height: 180px; object-fit: cover; width: 100%;">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description courte</label>
                        <textarea name="short_description" id="college-short-description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description détaillée</label>
                        <textarea name="description" id="college-description" class="form-control" rows="5"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contact</label>
                        <input type="text" name="contact_person" id="college-contact-person" class="form-control mb-2" placeholder="Personne de contact">
                        <input type="email" name="contact_email" id="college-contact-email" class="form-control mb-2" placeholder="Email de contact">
                        <input type="text" name="contact_phone" id="college-contact-phone" class="form-control mb-2" placeholder="Téléphone">
                        <input type="text" name="contact_address" id="college-contact-address" class="form-control mb-2" placeholder="Adresse">
                        <input type="url" name="contact_website" id="college-contact-website" class="form-control" placeholder="Site web">
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Ordre</label>
                            <input type="number" name="sort_order" id="college-sort-order" class="form-control" value="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Publié</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_published" id="college-is-published" value="1" checked>
                                <label class="form-check-label" for="college-is-published">Oui</label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                        <button type="button" class="btn btn-secondary" id="college-reset">Réinitialiser</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Liste des collèges</h6>
                <span class="badge bg-success"><?= count($colleges) ?></span>
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
                            <?php if ($colleges): ?>
                                <?php foreach ($colleges as $college): ?>
                                    <tr>
                                        <td>
                                            <?php if (!empty($college['image'])): ?>
                                                <img src="<?= APP_URL . '/' . e($college['image']) ?>" alt="<?= e($college['name']) ?>" style="max-height: 60px; object-fit: cover; border-radius: 6px; margin-right: 8px;">
                                            <?php endif; ?>
                                            <?= e($college['name']) ?>
                                        </td>
                                        <td><?= e($college['slug']) ?></td>
                                        <td>
                                            <?php if ($college['is_published']): ?>
                                                <span class="badge bg-success">Oui</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Non</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= e($college['sort_order']) ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-warning edit-college" data-id="<?= $college['id'] ?>" data-name="<?= e($college['name']) ?>" data-slug="<?= e($college['slug']) ?>" data-icon-class="<?= e($college['icon_class']) ?>" data-short-description="<?= e(base64_encode($college['short_description'] ?? '')) ?>" data-description="<?= e(base64_encode($college['description'] ?? '')) ?>" data-contact-person="<?= e($college['contact_person'] ?? '') ?>" data-contact-email="<?= e($college['contact_email'] ?? '') ?>" data-contact-phone="<?= e($college['contact_phone'] ?? '') ?>" data-contact-address="<?= e($college['contact_address'] ?? '') ?>" data-contact-website="<?= e($college['contact_website'] ?? '') ?>" data-logo="<?= e($college['logo'] ?? '') ?>" data-image="<?= e($college['image'] ?? '') ?>" data-is-published="<?= $college['is_published'] ?>" data-sort-order="<?= e($college['sort_order']) ?>">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <a href="<?= url('admin/colleges') ?>&delete_id=<?= $college['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Voulez-vous supprimer ce collège ?');">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="5" class="text-center">Aucun collège disponible</td></tr>
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

function setCollegeForm(data) {
    document.getElementById('college-action').value = 'update';
    document.getElementById('college-id').value = data.id || '';
    document.getElementById('college-name').value = data.name || '';
    document.getElementById('college-slug').value = data.slug || '';
    document.getElementById('college-icon-class').value = data.iconClass || '';
    document.getElementById('college-short-description').value = data.shortDescription || '';
    document.getElementById('college-description').value = data.description || '';
    document.getElementById('college-contact-person').value = data.contactPerson || '';
    document.getElementById('college-contact-email').value = data.contactEmail || '';
    document.getElementById('college-contact-phone').value = data.contactPhone || '';
    document.getElementById('college-contact-address').value = data.contactAddress || '';
    document.getElementById('college-contact-website').value = data.contactWebsite || '';
    document.getElementById('college-sort-order').value = data.sortOrder || 0;
    document.getElementById('college-is-published').checked = data.isPublished === '1' || data.isPublished === 1;

    const logoPreview = document.getElementById('college-logo-preview');
    const logoPreviewImg = logoPreview.querySelector('img');
    if (data.logo) {
        logoPreview.style.display = 'block';
        logoPreviewImg.src = '<?= APP_URL ?>/' + data.logo;
    } else {
        logoPreview.style.display = 'none';
        logoPreviewImg.src = '';
    }

    const preview = document.getElementById('college-image-preview');
    const previewImg = preview.querySelector('img');
    if (data.image) {
        preview.style.display = 'block';
        previewImg.src = '<?= APP_URL ?>/' + data.image;
    } else {
        preview.style.display = 'none';
        previewImg.src = '';
    }
}

document.querySelectorAll('.edit-college').forEach(btn => {
    btn.addEventListener('click', () => {
        const data = {
            id: btn.dataset.id,
            name: btn.dataset.name,
            slug: btn.dataset.slug,
            iconClass: btn.dataset.iconClass,
            shortDescription: decodeBase64Unicode(btn.dataset.shortDescription),
            description: decodeBase64Unicode(btn.dataset.description),
            contactPerson: btn.dataset.contactPerson,
            contactEmail: btn.dataset.contactEmail,
            contactPhone: btn.dataset.contactPhone,
            contactAddress: btn.dataset.contactAddress,
            contactWebsite: btn.dataset.contactWebsite,
            logo: btn.dataset.logo,
            image: btn.dataset.image,
            isPublished: btn.dataset.isPublished,
            sortOrder: btn.dataset.sortOrder
        };
        setCollegeForm(data);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
});

document.getElementById('college-reset').addEventListener('click', () => {
    document.getElementById('collegeForm').reset();
    document.getElementById('college-action').value = 'create';
    document.getElementById('college-id').value = '';
    document.getElementById('college-image-preview').style.display = 'none';
});
</script>

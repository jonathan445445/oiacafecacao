<?php
require_once __DIR__ . '/../includes/init.php';
require_login();

$page_title = 'Gestion des Partenaires';

$partnerModel = new Partner();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Jeton CSRF invalide.';
            redirect(url('admin/partners'));
        }

        if (isset($_POST['action']) && $_POST['action'] === 'delete') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id) {
                $partnerModel->delete($id);
                $_SESSION['success'] = 'Partenaire supprimé avec succès.';
            }
            redirect(url('admin/partners'));
        }

        $id = (int)($_POST['id'] ?? 0);
        $name = clean_input($_POST['name'] ?? '');
        $slug = clean_input($_POST['slug'] ?? '');
        $type = in_array($_POST['type'] ?? '', ['institutionnel', 'prive'], true) ? $_POST['type'] : 'prive';
        $description = clean_input($_POST['description'] ?? '');
        $website = clean_input($_POST['website'] ?? '');
        $contact_name = clean_input($_POST['contact_name'] ?? '');
        $contact_email = clean_input($_POST['contact_email'] ?? '');
        $contact_phone = clean_input($_POST['contact_phone'] ?? '');
        $is_published = isset($_POST['is_published']) ? 1 : 0;
        $sort_order = isset($_POST['sort_order']) ? intval($_POST['sort_order']) : 0;

        if (empty($name)) {
            throw new Exception('Le nom du partenaire est requis.');
        }

        $data = [
            'name' => $name,
            'slug' => $slug ?: slugify($name),
            'type' => $type,
            'description' => $description,
            'website' => $website,
            'contact_name' => $contact_name,
            'contact_email' => $contact_email,
            'contact_phone' => $contact_phone,
            'is_published' => $is_published,
            'sort_order' => $sort_order,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = UPLOAD_PATH . '/partners/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $originalName = pathinfo($_FILES['logo']['name'], PATHINFO_FILENAME);
            $extension = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
            $safeName = slugify($originalName) ?: 'partner-logo';
            $fileName = $safeName . '-' . time() . '.' . $extension;
            $destination = $uploadDir . $fileName;
            if (move_uploaded_file($_FILES['logo']['tmp_name'], $destination)) {
                $data['logo'] = 'uploads/partners/' . $fileName;
            }
        }

        if ($id) {
            $existing = $partnerModel->findById($id);
            if ($existing) {
                if (empty($data['logo'])) {
                    $data['logo'] = $existing['logo'];
                } elseif (!empty($existing['logo']) && $existing['logo'] !== $data['logo']) {
                    $oldLogo = BASE_PATH . '/' . ltrim($existing['logo'], '/');
                    if (file_exists($oldLogo)) {
                        @unlink($oldLogo);
                    }
                }
            }

            $partnerModel->update($id, $data);
            $_SESSION['success'] = 'Partenaire mis à jour avec succès.';
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            $partnerModel->create($data);
            $_SESSION['success'] = 'Partenaire créé avec succès.';
        }

        redirect(url('admin/partners'));
    } catch (Exception $e) {
        $_SESSION['error'] = 'Erreur : ' . $e->getMessage();
        redirect(url('admin/partners'));
    }
}

$partners = $partnerModel->findAll(1, 200, false);

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
                <h6 class="m-0 font-weight-bold text-primary">Ajouter / Modifier un partenaire</h6>
            </div>
            <div class="card-body">
                <form method="POST" id="partnerForm" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="partner-id" value="">
                    <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">

                    <div class="mb-3">
                        <label class="form-label">Type</label>
                        <select name="type" id="partner-type" class="form-control">
                            <option value="institutionnel">Institutionnel</option>
                            <option value="prive">Privé</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nom</label>
                        <input type="text" name="name" id="partner-name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" id="partner-slug" class="form-control">
                        <small class="text-muted">Laisser vide pour générer automatiquement.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Logo</label>
                        <input type="file" name="logo" id="partner-logo" class="form-control" accept="image/*">
                        <div class="mt-3" id="partner-logo-preview" style="display:none;">
                            <p class="mb-2"><strong>Logo actuel :</strong></p>
                            <img src="" alt="Aperçu logo" class="img-fluid rounded" style="max-height: 120px; object-fit: contain; width: auto;">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" id="partner-description" class="form-control" rows="4"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Site web</label>
                        <input type="url" name="website" id="partner-website" class="form-control" placeholder="https://example.com">
                    </div>

                    

                    <div class="mb-3">
                        <label class="form-label">Contact</label>
                        <input type="text" name="contact_name" id="partner-contact-name" class="form-control mb-2" placeholder="Personne de contact">
                        <input type="email" name="contact_email" id="partner-contact-email" class="form-control mb-2" placeholder="Email de contact">
                        <input type="text" name="contact_phone" id="partner-contact-phone" class="form-control" placeholder="Téléphone">
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Ordre</label>
                            <input type="number" name="sort_order" id="partner-sort-order" class="form-control" value="0">
                        </div>
                        <div class="col-md-6">
                            <div class="form-check pt-4">
                                <input class="form-check-input" type="checkbox" name="is_published" id="partner-is-published" value="1" checked>
                                <label class="form-check-label" for="partner-is-published">Publié</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                        <button type="button" class="btn btn-secondary" id="partner-reset">Réinitialiser</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Liste des partenaires</h6>
                <span class="badge bg-success"><?= count($partners) ?></span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Type</th>
                                <th>Email</th>
                                <th>Publiée</th>
                                <th>Ordre</th>
                                <th style="width: 160px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($partners): ?>
                                <?php foreach ($partners as $partner): ?>
                                    <tr>
                                        <td>
                                            <?php if (!empty($partner['logo'])): ?>
                                                <img src="<?= asset_url($partner['logo']) ?>" alt="<?= e($partner['name']) ?>" style="max-height: 40px; object-fit: contain; margin-right: 8px;">
                                            <?php endif; ?>
                                            <?= e($partner['name']) ?>
                                        </td>
                                        <td><?= e($partner['type'] === 'institutionnel' ? 'Institutionnel' : 'Privé') ?></td>
                                        <td><?= e($partner['contact_email'] ?: '-') ?></td>
                                        <td>
                                            <span class="badge bg-<?= $partner['is_published'] ? 'success' : 'secondary' ?>">
                                                <?= $partner['is_published'] ? 'Oui' : 'Non' ?>
                                            </span>
                                        </td>
                                        <td><?= $partner['sort_order'] ?></td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="editPartnerInline(<?= $partner['id'] ?>)">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                            <form method="POST" style="display:inline-block;" onsubmit="return confirm('Confirmer la suppression ?')">
                                                <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
                                                <input type="hidden" name="id" value="<?= $partner['id'] ?>">
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
                                    <td colspan="6" class="text-center text-muted py-5">
                                        Aucun partenaire enregistré pour le moment.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal removed: editing is done inline using the left form -->

<script>
const partnerData = <?= json_encode($partners, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;

function resetPartnerForm() {
    document.getElementById('partner-id').value = '';
    document.getElementById('partner-type').value = 'prive';
    document.getElementById('partner-name').value = '';
    document.getElementById('partner-slug').value = '';
    document.getElementById('partner-description').value = '';
    document.getElementById('partner-website').value = '';
    document.getElementById('partner-contact-name').value = '';
    document.getElementById('partner-contact-email').value = '';
    document.getElementById('partner-contact-phone').value = '';
    document.getElementById('partner-sort-order').value = '0';
    document.getElementById('partner-is-published').checked = true;
    var preview = document.getElementById('partner-logo-preview');
    if (preview) {
        preview.style.display = 'none';
        var img = preview.querySelector('img'); if (img) img.src = '';
    }
    var fileInput = document.getElementById('partner-logo'); if (fileInput) fileInput.value = '';
}

function editPartnerInline(id) {
    const partner = partnerData.find(p => p.id == id);
    if (!partner) return;

    document.getElementById('partner-id').value = partner.id;
    document.getElementById('partner-type').value = partner.type;
    document.getElementById('partner-name').value = partner.name;
    document.getElementById('partner-slug').value = partner.slug;
    document.getElementById('partner-description').value = partner.description;
    document.getElementById('partner-contact-name').value = partner.contact_name;
    document.getElementById('partner-contact-email').value = partner.contact_email;
    document.getElementById('partner-contact-phone').value = partner.contact_phone;
    document.getElementById('partner-website').value = partner.website || '';
    document.getElementById('partner-sort-order').value = partner.sort_order ?? 0;
    document.getElementById('partner-is-published').checked = partner.is_published == 1;

    var preview = document.getElementById('partner-logo-preview');
    if (partner.logo && preview) {
        preview.style.display = 'block';
        var img = preview.querySelector('img');
        if (img) img.src = '<?= APP_URL ?>/' + partner.logo.replace(/^\/+/, '');
    } else if (preview) {
        preview.style.display = 'none';
    }

    // scroll to top where the form is
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

document.getElementById('partner-reset').addEventListener('click', resetPartnerForm);
</script>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>

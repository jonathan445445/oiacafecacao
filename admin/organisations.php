<?php
require_once __DIR__ . '/../includes/init.php';
require_login();

$page_title = 'Gestion des Organisations';

$organisationModel = new Organisation();

// Traitement POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Jeton CSRF invalide.';
            redirect(url('admin/organisations'));
        }

        if (isset($_POST['action']) && $_POST['action'] === 'delete') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id) {
                $organisationModel->delete($id);
                $_SESSION['success'] = 'Organisation supprimee.';
            }
            redirect(url('admin/organisations'));
        }

        $id = (int)($_POST['id'] ?? 0);
        $data = [
            'name' => clean_input($_POST['name'] ?? ''),
            'slug' => slugify($_POST['name'] ?? ''),
            'description' => clean_input($_POST['description'] ?? ''),
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
            'is_published' => isset($_POST['is_published']) ? 1 : 0,
            'responsable' => clean_input($_POST['responsable'] ?? ''),
            'telephone' => clean_input($_POST['telephone'] ?? ''),
            'email' => clean_input($_POST['email'] ?? ''),
            'personnes' => $_POST['personnes'] ?? '',
        ];

        if ($id) {
            $organisationModel->update($id, $data);
            $_SESSION['success'] = 'Organisation mise a jour.';
        } else {
            $organisationModel->create($data);
            $_SESSION['success'] = 'Organisation creee.';
        }

        redirect(url('admin/organisations'));
    } catch (Exception $e) {
        $_SESSION['error'] = 'Erreur: ' . $e->getMessage();
        redirect(url('admin/organisations'));
    }
}
require_once __DIR__ . '/layouts/header.php';

// CSS pour s'assurer que le modal est au-dessus
?>
<style>
    .modal-backdrop {
        z-index: 1059 !important;
    }
    .modal {
        z-index: 1060 !important;
    }
    #organisationModal .modal-dialog {
        max-height: calc(100vh - 2rem);
    }
    #organisationModal .modal-content,
    #organisationModal form {
        max-height: calc(100vh - 2rem);
        display: flex;
        flex-direction: column;
    }
    #organisationModal .modal-body {
        overflow-y: auto;
        flex: 1 1 auto;
        min-height: 0;
    }
    #organisationModal .modal-footer {
        flex-shrink: 0;
        background: #fff;
        border-top: 1px solid #dee2e6;
        position: sticky;
        bottom: 0;
        z-index: 2;
    }

    @media (max-width: 575.98px) {
        #organisationModal .modal-dialog {
            margin: .5rem;
            max-height: calc(100vh - 1rem);
        }
        #organisationModal .modal-content,
        #organisationModal form {
            max-height: calc(100vh - 1rem);
        }
    }
</style>
<?php

$organisations = $organisationModel->findAll(1, 200);

// Afficher les messages de succes/erreur
if (isset($_SESSION['success'])) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
        ' . e($_SESSION['success']) . '
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>';
    unset($_SESSION['success']);
}
if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        ' . e($_SESSION['error']) . '
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>';
    unset($_SESSION['error']);
}
?>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <h6 class="m-0 font-weight-bold" style="color: #5b2d00;">
            <i class="bi bi-building me-2"></i>
            Toutes les Organisations
        </h6>
        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#organisationModal" onclick="resetOrganisationForm()">
            <i class="bi bi-plus"></i> Nouvelle Organisation
        </button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Responsable</th>
                        <th>Email</th>
                        <th>Ordre</th>
                        <th>Publie</th>
                        <th style="width: 180px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($organisations): ?>
                        <?php foreach ($organisations as $org): ?>
                            <tr>
                                <td><?= $org['id'] ?></td>
                                <td class="fw-semibold"><?= e($org['name']) ?></td>
                                <td><?= e($org['responsable'] ?? '-') ?></td>
                                <td><?= e($org['email'] ?? '-') ?></td>
                                <td><?= $org['sort_order'] ?></td>
                                <td>
                                    <span class="badge rounded-pill bg-<?= $org['is_published'] ? 'success' : 'warning' ?>">
                                        <?= $org['is_published'] ? 'Oui' : 'Non' ?>
                                    </span>
                                </td>

                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#organisationModal" onclick="editOrganisation(<?= $org['id'] ?>)">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form method="POST" style="display:inline-block;" onsubmit="return confirm('Confirmer la suppression ?')">
                                        <input type="hidden" name="id" value="<?= $org['id'] ?>">
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
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-building display-1 mb-3 d-block"></i>
                                Aucune organisation pour le moment
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Organisation Modal (Add/Edit) -->
<div class="modal fade" id="organisationModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" style="z-index: 1060;">
    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="orgModalTitle">Nouvelle Organisation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
                    <input type="hidden" name="id" id="organisation_id" value="">
                    
                    <div class="mb-3">
                        <label class="form-label">Nom <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="organisation_name" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description courte</label>
                        <textarea name="description" id="organisation_description" class="form-control" rows="2"></textarea>
                    </div>
                    
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Responsable</label>
                            <input type="text" name="responsable" id="organisation_responsable" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Telephone</label>
                            <input type="text" name="telephone" id="organisation_telephone" class="form-control">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" id="organisation_email" class="form-control">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Liste des personnes</label>
                        <textarea name="personnes" id="organisation_personnes" class="form-control" rows="4" placeholder="Entrez les personnes, une par ligne"></textarea>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Ordre d'affichage</label>
                            <input type="number" name="sort_order" id="organisation_sort_order" class="form-control" value="0">
                        </div>
                        <div class="col-md-6">
                            <div class="form-check pt-4">
                                <input type="checkbox" name="is_published" id="organisation_is_published" class="form-check-input" checked>
                                <label class="form-check-label" for="organisation_is_published">Publie</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const organisationData = <?= json_encode($organisations, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;

function resetOrganisationForm() {
    document.getElementById('orgModalTitle').textContent = 'Nouvelle Organisation';
    document.getElementById('organisation_id').value = '';
    document.getElementById('organisation_name').value = '';
    document.getElementById('organisation_description').value = '';
    document.getElementById('organisation_responsable').value = '';
    document.getElementById('organisation_telephone').value = '';
    document.getElementById('organisation_email').value = '';
    document.getElementById('organisation_personnes').value = '';
    document.getElementById('organisation_sort_order').value = '0';
    document.getElementById('organisation_is_published').checked = true;
}

function editOrganisation(id) {
    const org = organisationData.find(o => o.id == id);
    if (!org) return;
    
    document.getElementById('orgModalTitle').textContent = 'Modifier l\'Organisation';
    document.getElementById('organisation_id').value = org.id;
    document.getElementById('organisation_name').value = org.name;
    document.getElementById('organisation_description').value = org.description || '';
    document.getElementById('organisation_responsable').value = org.responsable || '';
    document.getElementById('organisation_telephone').value = org.telephone || '';
    document.getElementById('organisation_email').value = org.email || '';
    document.getElementById('organisation_personnes').value = org.personnes || '';
    document.getElementById('organisation_sort_order').value = org.sort_order || 0;
    document.getElementById('organisation_is_published').checked = org.is_published == 1;
}
</script>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>

<?php
require_once __DIR__ . '/../includes/init.php';
require_login();

$documentModel = new Document();
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$documents = $documentModel->findAllAdmin($page, 20);

$page_title = 'Gestion des Documents';
require_once __DIR__ . '/layouts/header.php';
?>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <h6 class="m-0 font-weight-bold" style="color: #5b2d00;">
            <i class="bi bi-file-earmark-text me-2"></i>
            Documents
        </h6>
        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#documentModal" data-mode="add">
            <i class="bi bi-plus"></i> Nouveau Document
        </button>
    </div>
    <div class="card-body">
        <?php if ($documents): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Fichier</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($documents as $document): ?>
                            <tr data-id="<?= $document['id'] ?>">
                                <td><?= e($document['title']) ?></td>
                                <td><?= e($document['file_name']) ?></td>
                                <td>
                                    <?php if ($document['is_published']): ?>
                                        <span class="badge bg-success">Publié</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Brouillon</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= format_date($document['created_at']) ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm edit-document" data-id="<?= $document['id'] ?>">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm delete-document" data-id="<?= $document['id'] ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="bi bi-file-earmark-text display-1 text-muted mb-3"></i>
                <h5 class="text-muted">Aucun document pour le moment</h5>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Document -->
<div class="modal fade" id="documentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #5b2d00, #8a4e00); color: white;">
                <h5 class="modal-title" id="documentModalTitle">Nouveau Document</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="documentForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="subaction" id="document-subaction" value="create">
                    <input type="hidden" name="id" id="document-id" value="">

                    <div class="mb-3">
                        <label class="form-label">Titre <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="document-title" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" id="document-description" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Fichier <span class="text-danger">*</span></label>
                        <input type="file" name="document_file" id="document-file" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx">
                        <small class="text-muted">Formats supportés : PDF, DOC, DOCX, XLS, XLSX</small>
                    </div>

                    <div class="form-check mb-3">
                        <input type="checkbox" name="is_published" id="document-is-published" class="form-check-input" value="1" checked>
                        <label class="form-check-label" for="document-is-published">Publier immédiatement</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success" id="document-submit">
                        <i class="bi bi-check"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const documentModalEl = document.getElementById('documentModal');
const documentForm = document.getElementById('documentForm');
const documentModalTitle = document.getElementById('documentModalTitle');

const documentsData = <?= json_encode($documents) ?>;

function resetDocumentForm() {
    documentForm.reset();
    document.getElementById('document-subaction').value = 'create';
    document.getElementById('document-id').value = '';
    documentModalTitle.textContent = 'Nouveau Document';
}

documentModalEl?.addEventListener('show.bs.modal', function(e) {
    const mode = e.relatedTarget?.getAttribute('data-mode');
    const id = e.relatedTarget?.getAttribute('data-id');

    if (mode === 'edit' && id) {
        const documentItem = documentsData.find(d => d.id == id);
        if (documentItem) {
            documentModalTitle.textContent = 'Modifier le document';
            document.getElementById('document-subaction').value = 'update';
            document.getElementById('document-id').value = documentItem.id;
            document.getElementById('document-title').value = documentItem.title;
            document.getElementById('document-description').value = documentItem.description || '';
            document.getElementById('document-is-published').checked = documentItem.is_published == 1;
        }
    } else {
        resetDocumentForm();
    }
});

documentForm?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    try {
        const response = await fetch('<?= url('index.php?p=ajax/document') ?>', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();

        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Succès',
                text: data.message,
                confirmButtonColor: '#5b2d00'
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: data.message,
                confirmButtonColor: '#5b2d00'
            });
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Erreur',
            text: 'Une erreur est survenue. Veuillez réessayer.',
            confirmButtonColor: '#5b2d00'
        });
    }
});

document.querySelectorAll('.edit-document').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        const trigger = document.createElement('button');
        trigger.setAttribute('data-bs-toggle', 'modal');
        trigger.setAttribute('data-bs-target', '#documentModal');
        trigger.setAttribute('data-mode', 'edit');
        trigger.setAttribute('data-id', id);
        trigger.style.display = 'none';
        document.body.appendChild(trigger);
        trigger.click();
        document.body.removeChild(trigger);
    });
});

document.querySelectorAll('.delete-document').forEach(btn => {
    btn.addEventListener('click', async function() {
        const id = this.getAttribute('data-id');
        const result = await Swal.fire({
            title: 'Êtes-vous sûr ?',
            text: 'Ce document sera supprimé définitivement !',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Oui, supprimer !',
            cancelButtonText: 'Annuler'
        });

        if (result.isConfirmed) {
            try {
                const formData = new FormData();
                formData.append('subaction', 'delete');
                formData.append('id', id);

                const response = await fetch('<?= url('index.php?p=ajax/document') ?>', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Succès',
                        text: data.message,
                        confirmButtonColor: '#5b2d00'
                    }).then(() => {
                        location.reload();
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Une erreur est survenue. Veuillez réessayer.',
                    confirmButtonColor: '#5b2d00'
                });
            }
        }
    });
});
</script>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>

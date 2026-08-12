<?php
require_once __DIR__ . '/../includes/init.php';
require_login();

$article = new Article();
$category = new Category();

$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$articles = $article->findAllAdmin($page, 10);
$categories = $category->findAll();

$page_title = 'Gestion des Actualités';
require_once __DIR__ . '/layouts/header.php';
?>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <h6 class="m-0 font-weight-bold" style="color: #5b2d00;">
            <i class="bi bi-newspaper me-2"></i>
            Tous les articles
        </h6>
        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#articleModal" data-mode="add">
            <i class="bi bi-plus"></i> Nouvel article
        </button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Titre</th>
                        <th>Statut</th>
                        <th>À la une</th>
                        <th>Date</th>
                        <th style="width: 200px;">Actions</th>
                    </tr>
                </thead>
                <tbody id="articles-tbody">
                    <?php if ($articles): ?>
                        <?php foreach ($articles as $art): ?>
                            <tr class="article-row" data-id="<?= $art['id'] ?>">
                                <td><?= $art['id'] ?></td>
                                <td>
                                    <?php if ($art['featured_image']): ?>
                                        <img src="<?= APP_URL ?>/<?= $art['featured_image'] ?>" alt="" style="width: 80px; height: 50px; object-fit: cover; border-radius: 5px;">
                                    <?php else: ?>
                                        <span class="text-muted">Aucune</span>
                                    <?php endif; ?>
                                </td>
                                <td class="fw-semibold"><?= e($art['title']) ?></td>
                                <td>
                                    <span class="badge rounded-pill bg-<?= $art['status'] === 'published' ? 'success' : 'warning' ?>">
                                        <?= ucfirst($art['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge rounded-pill bg-<?= $art['is_featured'] ? 'primary' : 'secondary' ?>">
                                        <?= $art['is_featured'] ? 'Oui' : 'Non' ?>
                                    </span>
                                </td>
                                <td><?= format_date($art['created_at']) ?></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-info view-article-btn" data-id="<?= $art['id'] ?>" title="Voir">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary edit-article-btn" data-id="<?= $art['id'] ?>" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger delete-article-btn" data-id="<?= $art['id'] ?>" title="Supprimer">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-newspaper display-1 mb-3 d-block"></i>
                                Aucun article pour le moment
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Article Modal (Add/Edit) -->
<div class="modal fade" id="articleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <form id="articleForm" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="articleModalTitle">Nouvel article</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                    <input type="hidden" name="id" id="article-id">
                    <div class="row g-4">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Titre <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="article-title" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Catégorie</label>
                                <select name="category_id" id="article-category_id" class="form-select">
                                    <option value="">Sélectionner une catégorie...</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>"><?= e($cat['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Statut</label>
                                <select name="status" id="article-status" class="form-select">
                                    <option value="draft">Brouillon</option>
                                    <option value="published">Publié</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row g-4">
                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="checkbox" name="is_featured" id="article-is_featured" class="form-check-input">
                                <label class="form-check-label" for="article-is_featured">Mettre à la une</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Extrait</label>
                        <textarea name="excerpt" id="article-excerpt" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contenu <span class="text-danger">*</span></label>
                        <textarea name="content" id="article-content" class="form-control" rows="10" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Image à la une</label>
                        <input type="file" name="image" id="article-image" class="form-control" accept="image/*">
                        <div class="mt-2" id="image-preview-container">
                            <small class="text-muted">Vous pouvez aussi entrer une URL d'image ci-dessous</small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ou URL d'image</label>
                        <input type="url" name="featured_image" id="article-featured_image" class="form-control" placeholder="https://...">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success" id="article-submit-btn">
                        <i class="bi bi-check-lg me-1"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Article Modal -->
<div class="modal fade" id="viewArticleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewArticleTitle">Aperçu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height: 70vh; overflow-y: auto;" id="viewArticleContent">
                <div class="text-center py-4">
                    <div class="spinner-border text-success" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
const articlesData = <?= json_encode($articles ?? []) ?>;

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function showToast(title, text, icon) {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
    Toast.fire({ icon, title, text });
}

document.addEventListener('DOMContentLoaded', function() {
    const articleModalEl = document.getElementById('articleModal');
    const articleModal = new bootstrap.Modal(articleModalEl);

    // Add/Edit article
    articleModalEl?.addEventListener('show.bs.modal', function(e) {
        const mode = e.relatedTarget?.getAttribute('data-mode');
        const id = e.relatedTarget?.getAttribute('data-id');
        
        if (mode === 'edit' && id) {
            const art = articlesData.find(a => a.id == id);
            if (art) {
                document.getElementById('articleModalTitle').textContent = 'Modifier l\'article';
                document.getElementById('article-id').value = art.id;
                document.getElementById('article-title').value = art.title;
                document.getElementById('article-category_id').value = art.category_id || '';
                document.getElementById('article-status').value = art.status;
                document.getElementById('article-excerpt').value = art.excerpt || '';
                document.getElementById('article-content').value = art.content || '';
                document.getElementById('article-featured_image').value = art.featured_image || '';
                document.getElementById('article-is_featured').checked = art.is_featured;
            }
        } else {
            document.getElementById('articleModalTitle').textContent = 'Nouvel article';
            document.getElementById('articleForm').reset();
        }
    });

    document.getElementById('articleForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        console.log('Form submitted');
        
        const id = document.getElementById('article-id').value;
        const formData = new FormData(this);
        formData.append('action', id ? 'update_article' : 'add_article');
        if (id) formData.append('id', id);
        formData.append('author_id', '<?= $_SESSION['user_id'] ?? 1 ?>');

        // Debug: log form data
        console.log('Form data:');
        for (let [key, value] of formData.entries()) {
            console.log(key, value);
        }

        try {
            const response = await fetch('<?= APP_URL ?>/index.php?p=ajax/article', { 
                method: 'POST', 
                body: formData 
            });
            
            console.log('Response status:', response.status);
            
            let data;
            try {
                data = await response.json();
                console.log('Response data:', data);
            } catch (parseError) {
                console.error('Parse error:', parseError);
                const text = await response.text();
                console.error('Response text:', text);
                showToast('Erreur', 'Réponse invalide du serveur: ' + text.substring(0, 200), 'error');
                return;
            }
            
            if (data.success) {
                showToast('Succès', data.message, 'success');
                articleModal.hide();
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast('Erreur', data.message, 'error');
            }
        } catch (err) {
            console.error('Fetch error:', err);
            showToast('Erreur', 'Une erreur est survenue: ' + err.message, 'error');
        }
    });

    // View article
    document.querySelectorAll('.view-article-btn')?.forEach(btn => {
        btn.addEventListener('click', async function(e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            const viewModal = new bootstrap.Modal(document.getElementById('viewArticleModal'));
            viewModal.show();
            
            const formData = new FormData();
            formData.append('action', 'get_article');
            formData.append('id', id);
            
            try {
                const response = await fetch('<?= APP_URL ?>/index.php?p=ajax/article', { 
                    method: 'POST', 
                    body: formData 
                });
                const data = await response.json();
                if (data.success && data.article) {
                    const art = data.article;
                    document.getElementById('viewArticleTitle').textContent = escapeHtml(art.title);
                    document.getElementById('viewArticleContent').innerHTML = `
                        <div class="mb-3">
                            <p class="text-muted mb-0">
                                <i class="bi bi-person"></i> ${escapeHtml(art.author_name || 'OIA')} • 
                                <i class="bi bi-calendar"></i> ${new Date(art.published_at || art.created_at).toLocaleDateString('fr-FR')}
                            </p>
                        </div>
                        ${art.featured_image ? `<img src="${art.featured_image.startsWith('http') ? art.featured_image : '<?= APP_URL ?>/' + art.featured_image}" class="img-fluid rounded mb-3" style="max-height:300px; object-fit:cover; width:100%;">` : ''}
                        <div style="white-space: pre-wrap;">${escapeHtml(art.content)}</div>
                    `;
                }
            } catch (err) {
                document.getElementById('viewArticleContent').innerHTML = '<p class="text-danger">Erreur de chargement</p>';
            }
        });
    });

    // Edit article
    document.querySelectorAll('.edit-article-btn')?.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const trigger = document.createElement('button');
            trigger.style.display = 'none';
            trigger.setAttribute('data-bs-toggle', 'modal');
            trigger.setAttribute('data-bs-target', '#articleModal');
            trigger.setAttribute('data-mode', 'edit');
            trigger.setAttribute('data-id', this.getAttribute('data-id'));
            document.body.appendChild(trigger);
            trigger.click();
            document.body.removeChild(trigger);
        });
    });

    // Delete article
    document.querySelectorAll('.delete-article-btn')?.forEach(btn => {
        btn.addEventListener('click', async function(e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            
            const result = await Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: 'Cette action est irréversible',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler'
            });
            
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('action', 'delete_article');
                formData.append('id', id);
                
                try {
                    const response = await fetch('<?= APP_URL ?>/index.php?p=ajax/article', { 
                        method: 'POST', 
                        body: formData 
                    });
                    const data = await response.json();
                    if (data.success) {
                        showToast('Succès', data.message, 'success');
                        setTimeout(() => location.reload(), 1000);
                    }
                } catch (err) {
                    showToast('Erreur', 'Une erreur est survenue', 'error');
                }
            }
        });
    });
});
</script>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
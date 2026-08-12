<?php
require_once __DIR__ . '/../includes/init.php';
require_login();

$photoAlbum = new PhotoAlbum();

$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$albums = $photoAlbum->findAllAdmin($page, 12);

$page_title = 'Gestion des Albums Photos';
require_once __DIR__ . '/layouts/header.php';
?>

<style>
    .upload-zone {
        border: 2px dashed #5b2d00;
        border-radius: 10px;
        padding: 40px 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background-color: #f8f5f0;
    }
    .upload-zone:hover, .upload-zone.dragover {
        background-color: #e9e0d5;
        border-color: #8a4e00;
    }
    .upload-zone i {
        font-size: 48px;
        color: #5b2d00;
        margin-bottom: 15px;
    }
    .file-preview-item {
        display: flex;
        align-items: center;
        padding: 10px;
        background-color: #f9f9f9;
        border-radius: 8px;
        margin-bottom: 10px;
    }
    .file-preview-item img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 6px;
        margin-right: 15px;
    }
</style>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <h6 class="m-0 font-weight-bold" style="color: #5b2d00;">
            <i class="bi bi-folder2-images me-2"></i>
            Albums Photos
        </h6>
        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#albumModal">
            <i class="bi bi-plus"></i> Nouvel Album
        </button>
    </div>
    <div class="card-body">
        <?php if ($albums): ?>
            <div class="row g-3" id="albums-grid">
                <?php foreach ($albums as $album): ?>
                    <div class="col-md-6 col-lg-4 album-item" data-id="<?= $album['id'] ?>">
                        <div class="card h-100 shadow-sm">
                            <?php if (!empty($album['cover_photo'])): ?>
                                <img src="<?= APP_URL ?>/<?= $album['cover_photo'] ?>" class="card-img-top" alt="" style="height: 200px; object-fit: cover;">
                            <?php else: ?>
                                <div style="height: 200px; background: #f5f5f5; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-images text-muted" style="font-size: 4rem;"></i>
                                </div>
                            <?php endif; ?>
                            <div class="card-body">
                                <h6 class="fw-bold mb-1 text-truncate"><?= e($album['title']) ?></h6>
                                <p class="text-muted small mb-2">
                                    <?= $album['photo_count'] ?> photo(s)
                                    <?php if ($album['is_published']): ?>
                                        <span class="badge bg-success ms-2">Publié</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary ms-2">Brouillon</span>
                                    <?php endif; ?>
                                </p>
                                <p class="text-muted small mb-2">
                                    <i class="bi bi-calendar"></i>
                                    <?= format_date($album['created_at']) ?>
                                </p>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-primary btn-sm flex-grow-1 view-album" data-id="<?= $album['id'] ?>">
                                        <i class="bi bi-eye"></i> Voir
                                    </button>
                                    <button class="btn btn-warning btn-sm edit-album" data-id="<?= $album['id'] ?>">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm delete-album" data-id="<?= $album['id'] ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="bi bi-folder2-images display-1 text-muted mb-3"></i>
                <h5 class="text-muted">Aucun album pour le moment</h5>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Album -->
<div class="modal fade" id="albumModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #5b2d00, #8a4e00); color: white;">
                <h5 class="modal-title" id="albumModalTitle">Nouvel Album</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="albumForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="action" id="album-action" value="add_album">
                    <input type="hidden" name="album_id" id="album-id" value="">
                    <div class="mb-3">
                        <label class="form-label">Titre de l'album <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="album-title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" id="album-description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3" id="upload-section">
                        <label class="form-label">Photos <span class="text-muted">(glissez-déposez ou cliquez)</span></label>
                        <div class="upload-zone" id="albumUploadZone">
                            <i class="bi bi-cloud-arrow-up"></i>
                            <p><strong>Glissez et déposez vos photos ici</strong></p>
                            <p class="text-muted small mb-2">ou cliquez pour sélectionner des fichiers</p>
                            <input type="file" id="albumPhotoInput" class="form-control d-none" accept="image/*" multiple name="photos[]">
                            <small class="text-muted">Formats supportés : JPG, PNG, GIF, WebP</small>
                        </div>
                        <div class="file-preview mt-3" id="albumFilePreview"></div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_published" id="album-is-published" class="form-check-input" checked>
                            <label class="form-check-label" for="album-is-published">Publier immédiatement</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success" id="album-submit">
                        <i class="bi bi-check"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal View Album -->
<div class="modal fade" id="viewAlbumModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #5b2d00, #8a4e00); color: white;">
                <h5 class="modal-title" id="viewAlbumTitle"></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 d-flex gap-2">
                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addPhotoModal" id="addPhotoBtn">
                        <i class="bi bi-plus"></i> Ajouter une photo
                    </button>
                </div>
                <div class="row g-2" id="albumPhotosGrid"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add Photo to Album -->
<div class="modal fade" id="addPhotoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #5b2d00, #8a4e00); color: white;">
                <h5 class="modal-title">Ajouter une photo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="addPhotoForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add_photo_to_album">
                    <input type="hidden" name="album_id" id="addPhotoAlbumId">
                    <div class="mb-3">
                        <label class="form-label">Photo <span class="text-danger">*</span></label>
                        <input type="file" name="photo" class="form-control" accept="image/*" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Titre (optionnel)</label>
                        <input type="text" name="title" class="form-control">
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_published" id="addPhotoIsPublished" class="form-check-input" checked>
                            <label class="form-check-label" for="addPhotoIsPublished">Publier immédiatement</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check"></i> Ajouter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const albumModalEl = document.getElementById('albumModal');
const albumForm = document.getElementById('albumForm');
const albumModalTitle = document.getElementById('albumModalTitle');
const uploadZone = document.getElementById('albumUploadZone');
const fileInput = document.getElementById('albumPhotoInput');
const filePreview = document.getElementById('albumFilePreview');
let selectedFiles = [];
let currentAlbumId = null;

// Initialize data
<?php if ($albums): ?>
    const albumsData = <?= json_encode($albums) ?>;
<?php else: ?>
    const albumsData = [];
<?php endif; ?>

// Open modal for new album
albumModalEl?.addEventListener('show.bs.modal', function(e) {
    const mode = e.relatedTarget?.getAttribute('data-mode');
    const id = e.relatedTarget?.getAttribute('data-id');
    
    if (mode === 'edit' && id) {
        const album = albumsData.find(a => a.id == id);
        if (album) {
            albumModalTitle.textContent = 'Modifier l\'album';
            document.getElementById('album-action').value = 'update_album';
            document.getElementById('album-id').value = album.id;
            document.getElementById('album-title').value = album.title;
            document.getElementById('album-description').value = album.description || '';
            document.getElementById('album-is-published').checked = album.is_published == 1;
            document.getElementById('upload-section').style.display = 'none';
        }
    } else {
        albumModalTitle.textContent = 'Nouvel Album';
        document.getElementById('album-action').value = 'add_album';
        document.getElementById('album-id').value = '';
        albumForm.reset();
        document.getElementById('upload-section').style.display = 'block';
        filePreview.innerHTML = '';
        selectedFiles = [];
    }
});

// Drag & drop events
uploadZone?.addEventListener('click', () => fileInput?.click());

uploadZone?.addEventListener('dragover', (e) => {
    e.preventDefault();
    uploadZone.classList.add('dragover');
});

uploadZone?.addEventListener('dragleave', () => {
    uploadZone.classList.remove('dragover');
});

uploadZone?.addEventListener('drop', (e) => {
    e.preventDefault();
    uploadZone.classList.remove('dragover');
    if (e.dataTransfer.files.length > 0) {
        handleFiles(e.dataTransfer.files);
    }
});

fileInput?.addEventListener('change', () => {
    if (fileInput.files.length > 0) {
        handleFiles(fileInput.files);
    }
});

// Handle selected files
function handleFiles(files) {
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        if (file.type.startsWith('image/')) {
            selectedFiles.push(file);
            addFilePreview(file);
        }
    }
}

// Add file preview
function addFilePreview(file) {
    const reader = new FileReader();
    reader.onload = (e) => {
        const div = document.createElement('div');
        div.className = 'file-preview-item';
        div.innerHTML = `
            <img src="${e.target.result}" alt="${file.name}">
            <div class="file-info">
                <h6 class="fw-semibold text-truncate">${file.name}</h6>
                <small>${(file.size / 1024 / 1024).toFixed(2)} MB</small>
            </div>
            <div class="file-remove" style="cursor: pointer; color: #dc3545;">
                <i class="bi bi-x-circle fs-4"></i>
            </div>
        `;
        div.querySelector('.file-remove').addEventListener('click', function() {
            const index = selectedFiles.indexOf(file);
            if (index > -1) {
                selectedFiles.splice(index, 1);
            }
            div.remove();
        });
        filePreview.appendChild(div);
    };
    reader.readAsDataURL(file);
}

// Form submission for album
albumForm?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const action = document.getElementById('album-action').value;
    
    // Add all selected files for new album
    if (action === 'add_album') {
        for (let i = 0; i < selectedFiles.length; i++) {
            formData.append('photos[]', selectedFiles[i]);
        }
    }
    
    try {
        const response = await fetch('<?= url('index.php?p=ajax/album') ?>', {
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

// View album
document.querySelectorAll('.view-album').forEach(btn => {
    btn.addEventListener('click', async function() {
        currentAlbumId = this.getAttribute('data-id');
        const album = albumsData.find(a => a.id == currentAlbumId);
        
        document.getElementById('viewAlbumTitle').textContent = album.title;
        document.getElementById('addPhotoAlbumId').value = currentAlbumId;
        
        // Load album photos
        const formData = new FormData();
        formData.append('action', 'get_album');
        formData.append('id', currentAlbumId);
        
        try {
            const response = await fetch('<?= url('index.php?p=ajax/album') ?>', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                const grid = document.getElementById('albumPhotosGrid');
                grid.innerHTML = '';
                
                data.photos.forEach(photo => {
                    const col = document.createElement('div');
                    col.className = 'col-md-4 col-lg-3';
                    col.innerHTML = `
                        <div class="card h-100">
                            <img src="<?= APP_URL ?>/${photo.file_path}" class="card-img-top" style="height: 150px; object-fit: cover;">
                            <div class="card-body p-2">
                                <small class="text-truncate d-block">${photo.title}</small>
                                <button class="btn btn-sm btn-danger w-100 mt-1 delete-photo" data-photo-id="${photo.id}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    `;
                    grid.appendChild(col);
                });
                
                // Delete photo handlers
                document.querySelectorAll('.delete-photo').forEach(btn => {
                    btn.addEventListener('click', deletePhotoFromAlbum);
                });
                
                const modal = new bootstrap.Modal(document.getElementById('viewAlbumModal'));
                modal.show();
            }
        } catch (error) {
            console.error(error);
        }
    });
});

// Edit album
document.querySelectorAll('.edit-album').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        const trigger = document.createElement('button');
        trigger.setAttribute('data-bs-toggle', 'modal');
        trigger.setAttribute('data-bs-target', '#albumModal');
        trigger.setAttribute('data-mode', 'edit');
        trigger.setAttribute('data-id', id);
        trigger.style.display = 'none';
        document.body.appendChild(trigger);
        trigger.click();
        document.body.removeChild(trigger);
    });
});

// Delete album
document.querySelectorAll('.delete-album').forEach(btn => {
    btn.addEventListener('click', async function() {
        const id = this.getAttribute('data-id');
        
        const result = await Swal.fire({
            title: 'Êtes-vous sûr ?',
            text: 'Toutes les photos de cet album seront supprimées !',
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
                formData.append('action', 'delete_album');
                formData.append('id', id);
                
                const response = await fetch('<?= url('index.php?p=ajax/album') ?>', {
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

// Delete photo from album
async function deletePhotoFromAlbum(e) {
    const photoId = this.getAttribute('data-photo-id');
    
    const result = await Swal.fire({
        title: 'Êtes-vous sûr ?',
        text: 'Cette photo sera supprimée définitivement !',
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
            formData.append('action', 'delete_photo_from_album');
            formData.append('photo_id', photoId);
            
            const response = await fetch('<?= url('index.php?p=ajax/album') ?>', {
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
                    // Re-fetch the album
                    const viewBtn = document.querySelector(`.view-album[data-id="${currentAlbumId}"]`);
                    viewBtn?.click();
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
}

// Add photo to album form
document.getElementById('addPhotoForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    try {
        const response = await fetch('<?= url('index.php?p=ajax/album') ?>', {
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
                const addModal = bootstrap.Modal.getInstance(document.getElementById('addPhotoModal'));
                addModal.hide();
                // Re-fetch the album
                const viewBtn = document.querySelector(`.view-album[data-id="${currentAlbumId}"]`);
                viewBtn?.click();
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
</script>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>

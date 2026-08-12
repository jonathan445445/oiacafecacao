<?php
require_once __DIR__ . '/../includes/init.php';
require_login();

$videoModel = new Video();

$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$videos = $videoModel->findAllAdmin($page, 12);

$page_title = 'Gestion des Vidéos';
require_once __DIR__ . '/layouts/header.php';
?>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <h6 class="m-0 font-weight-bold" style="color: #5b2d00;">
            <i class="bi bi-film me-2"></i>
            Vidéos
        </h6>
        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#videoModal">
            <i class="bi bi-plus"></i> Nouvelle Vidéo
        </button>
    </div>
    <div class="card-body">
        <?php if ($videos): ?>
            <div class="row g-3" id="videos-grid">
                <?php foreach ($videos as $video): ?>
                    <div class="col-md-6 col-lg-4 video-item" data-id="<?= $video['id'] ?>">
                        <div class="card h-100 shadow-sm">
                            <img src="<?= e(get_video_thumbnail_url($video)) ?>" class="card-img-top" alt="" style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h6 class="fw-bold mb-1 text-truncate"><?= e($video['title']) ?></h6>
                                <p class="text-muted small mb-2">
                                    <span class="badge bg-<?= $video['video_type'] === 'youtube' ? 'primary' : 'info' ?> me-2">
                                        <?= $video['video_type'] === 'youtube' ? 'YouTube' : 'Upload' ?>
                                    </span>
                                    <?php if ($video['is_published']): ?>
                                        <span class="badge bg-success">Publié</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Brouillon</span>
                                    <?php endif; ?>
                                </p>
                                <p class="text-muted small mb-2">
                                    <i class="bi bi-calendar"></i>
                                    <?= format_date($video['created_at']) ?>
                                </p>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-warning btn-sm flex-grow-1 edit-video" data-id="<?= $video['id'] ?>">
                                        <i class="bi bi-pencil"></i> Modifier
                                    </button>
                                    <button class="btn btn-danger btn-sm delete-video" data-id="<?= $video['id'] ?>">
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
                <i class="bi bi-film display-1 text-muted mb-3"></i>
                <h5 class="text-muted">Aucune vidéo pour le moment</h5>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Video -->
<div class="modal fade" id="videoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #5b2d00, #8a4e00); color: white;">
                <h5 class="modal-title" id="videoModalTitle">Nouvelle Vidéo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="videoForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="subaction" id="video-subaction" value="create">
                    <input type="hidden" name="id" id="video-id" value="">
                    
                    <div class="mb-3">
                        <label class="form-label">Type de vidéo <span class="text-danger">*</span></label>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="video_type" id="video-type-upload" value="upload" checked>
                                    <label class="form-check-label" for="video-type-upload">Uploader une vidéo</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="video_type" id="video-type-youtube" value="youtube">
                                    <label class="form-check-label" for="video-type-youtube">Lien YouTube</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Titre <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="video-title" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" id="video-description" class="form-control" rows="3"></textarea>
                    </div>
                    
                    <div class="mb-3" id="upload-section">
                        <label class="form-label">Fichier vidéo <span class="text-danger">*</span></label>
                        <input type="file" name="video_file" id="video-file" class="form-control" accept="video/mp4,video/webm,video/ogg">
                        <small class="text-muted">Formats supportés : MP4, WebM, OGG</small>
                    </div>
                    
                    <div class="mb-3 d-none" id="youtube-section">
                        <label class="form-label">Lien YouTube <span class="text-danger">*</span></label>
                        <input type="url" name="youtube_url" id="video-youtube-url" class="form-control" placeholder="https://www.youtube.com/watch?v=...">
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_published" id="video-is-published" class="form-check-input" value="1" checked>
                            <label class="form-check-label" for="video-is-published">Publier immédiatement</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success" id="video-submit">
                        <i class="bi bi-check"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const videoModalEl = document.getElementById('videoModal');
const videoForm = document.getElementById('videoForm');
const videoModalTitle = document.getElementById('videoModalTitle');
const videoTypeUpload = document.getElementById('video-type-upload');
const videoTypeYoutube = document.getElementById('video-type-youtube');
const uploadSection = document.getElementById('upload-section');
const youtubeSection = document.getElementById('youtube-section');

// Initialize data
<?php if ($videos): ?>
    const videosData = <?= json_encode($videos) ?>;
<?php else: ?>
    const videosData = [];
<?php endif; ?>

// Toggle video type
function toggleVideoType() {
    if (videoTypeYoutube.checked) {
        uploadSection.classList.add('d-none');
        youtubeSection.classList.remove('d-none');
    } else {
        uploadSection.classList.remove('d-none');
        youtubeSection.classList.add('d-none');
    }
}

videoTypeUpload?.addEventListener('change', toggleVideoType);
videoTypeYoutube?.addEventListener('change', toggleVideoType);

// Open modal for new video
videoModalEl?.addEventListener('show.bs.modal', function(e) {
    const mode = e.relatedTarget?.getAttribute('data-mode');
    const id = e.relatedTarget?.getAttribute('data-id');
    
    if (mode === 'edit' && id) {
        const video = videosData.find(v => v.id == id);
        if (video) {
            videoModalTitle.textContent = 'Modifier la vidéo';
            document.getElementById('video-subaction').value = 'update';
            document.getElementById('video-id').value = video.id;
            document.getElementById('video-title').value = video.title;
            document.getElementById('video-description').value = video.description || '';
            document.getElementById('video-is-published').checked = video.is_published == 1;
            
            if (video.video_type === 'youtube') {
                videoTypeYoutube.checked = true;
                document.getElementById('video-youtube-url').value = `https://www.youtube.com/watch?v=${video.youtube_id}`;
            } else {
                videoTypeUpload.checked = true;
            }
            
            toggleVideoType();
        }
    } else {
        videoModalTitle.textContent = 'Nouvelle Vidéo';
        document.getElementById('video-subaction').value = 'create';
        document.getElementById('video-id').value = '';
        videoForm.reset();
        videoTypeUpload.checked = true;
        toggleVideoType();
    }
});

// Form submission for video
videoForm?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    try {
        const response = await fetch('<?= url('index.php?p=ajax/video') ?>', {
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

// Edit video
document.querySelectorAll('.edit-video').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        const trigger = document.createElement('button');
        trigger.setAttribute('data-bs-toggle', 'modal');
        trigger.setAttribute('data-bs-target', '#videoModal');
        trigger.setAttribute('data-mode', 'edit');
        trigger.setAttribute('data-id', id);
        trigger.style.display = 'none';
        document.body.appendChild(trigger);
        trigger.click();
        document.body.removeChild(trigger);
    });
});

// Delete video
document.querySelectorAll('.delete-video').forEach(btn => {
    btn.addEventListener('click', async function() {
        const id = this.getAttribute('data-id');
        
        const result = await Swal.fire({
            title: 'Êtes-vous sûr ?',
            text: 'Cette vidéo sera supprimée définitivement !',
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
                
                const response = await fetch('<?= url('index.php?p=ajax/video') ?>', {
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

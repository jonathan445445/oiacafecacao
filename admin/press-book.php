<?php
require_once __DIR__ . '/../includes/init.php';
require_login();

$pressBookModel = new PressBook();

$flash_success = $_SESSION['flash_success'] ?? null;
$flash_error = $_SESSION['flash_error'] ?? null;
unset($_SESSION['flash_success'], $_SESSION['flash_error']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $eventId = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $title = clean_input($_POST['title'] ?? '');
    $description = clean_input($_POST['description'] ?? '');
    $date_event = clean_input($_POST['date_event'] ?? '');
    $time_event = clean_input($_POST['time_event'] ?? '');
    $status = clean_input($_POST['status'] ?? 'draft');

    switch ($action) {
        case 'create_event':
        case 'update_event':
            if (empty($title)) {
                $_SESSION['flash_error'] = 'Le titre de l\'événement est requis.';
                header('Location: ' . url('admin/press-book'));
                exit;
            }

            $data = [
                'title' => $title,
                'description' => $description,
                'date_event' => $date_event ?: null,
                'time_event' => $time_event ?: null,
                'status' => in_array($status, ['draft', 'published', 'archived']) ? $status : 'draft',
                'updated_at' => date('Y-m-d H:i:s')
            ];

            if ($action === 'create_event') {
                $result = $pressBookModel->create($data);
                if ($result) {
                    $eventId = $result;
                    $_SESSION['flash_success'] = 'Événement Press Book ajouté avec succès.';
                } else {
                    $_SESSION['flash_error'] = 'Impossible de créer l\'événement.';
                    header('Location: ' . url('admin/press-book'));
                    exit;
                }
            } else {
                $existing = $pressBookModel->findById($eventId, false);
                if ($existing) {
                    $result = $pressBookModel->update($eventId, $data);
                    if ($result !== false) {
                        $_SESSION['flash_success'] = 'Événement mis à jour avec succès.';
                    } else {
                        $_SESSION['flash_error'] = 'Impossible de mettre à jour l\'événement.';
                        header('Location: ' . url('admin/press-book'));
                        exit;
                    }
                } else {
                    $_SESSION['flash_error'] = 'Événement introuvable.';
                    header('Location: ' . url('admin/press-book'));
                    exit;
                }
            }

            // Traiter les photos
            if ($eventId > 0 && isset($_FILES['photos']) && !empty($_FILES['photos']['name'][0])) {
                $photoFiles = $_FILES['photos'];
                $uploaded = 0;
                for ($i = 0; $i < count($photoFiles['name']); $i++) {
                    if ($photoFiles['error'][$i] === UPLOAD_ERR_OK) {
                        $uploadDir = UPLOAD_PATH . '/press-book/photos/';
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0777, true);
                        }
                        $originalName = pathinfo($photoFiles['name'][$i], PATHINFO_FILENAME);
                        $ext = strtolower(pathinfo($photoFiles['name'][$i], PATHINFO_EXTENSION));
                        $safeName = slugify($originalName) ?: 'press-book-photo';
                        $fileName = $safeName . '-' . time() . '-' . $i . '.' . $ext;
                        $destination = $uploadDir . $fileName;
                        if (move_uploaded_file($photoFiles['tmp_name'][$i], $destination)) {
                            $pressBookModel->addPhoto($eventId, 'uploads/press-book/photos/' . $fileName, '', 0, 0);
                            $uploaded++;
                        }
                    }
                }
                if ($uploaded > 0) {
                    $_SESSION['flash_success'] .= " $uploaded photo(s) ajoutée(s).";
                }
            }

            // Traiter les vidéos
            if ($eventId > 0 && isset($_POST['youtube_urls']) && !empty(trim($_POST['youtube_urls']))) {
                $youtubeUrls = clean_input($_POST['youtube_urls']);
                $lines = preg_split('/\r?\n/', trim($youtubeUrls));
                $added = 0;
                foreach ($lines as $line) {
                    $line = trim($line);
                    if ($line !== '' && $pressBookModel->addVideo($eventId, $line)) {
                        $added++;
                    }
                }
                if ($added > 0) {
                    $_SESSION['flash_success'] .= " $added vidéo(s) ajoutée(s).";
                }
            }

            header('Location: ' . url('admin/press-book', ['edit_id' => $eventId]));
            exit;

        case 'delete_event':
            if ($eventId > 0) {
                if ($pressBookModel->delete($eventId)) {
                    $_SESSION['flash_success'] = 'Événement supprimé avec succès.';
                } else {
                    $_SESSION['flash_error'] = 'Impossible de supprimer l\'événement.';
                }
            }
            header('Location: ' . url('admin/press-book'));
            exit;

        case 'upload_photos':
            if ($eventId > 0 && isset($_FILES['photos'])) {
                $photoFiles = $_FILES['photos'];
                $uploaded = 0;
                for ($i = 0; $i < count($photoFiles['name']); $i++) {
                    if ($photoFiles['error'][$i] === UPLOAD_ERR_OK) {
                        $uploadDir = UPLOAD_PATH . '/press-book/photos/';
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0777, true);
                        }
                        $originalName = pathinfo($photoFiles['name'][$i], PATHINFO_FILENAME);
                        $ext = strtolower(pathinfo($photoFiles['name'][$i], PATHINFO_EXTENSION));
                        $safeName = slugify($originalName) ?: 'press-book-photo';
                        $fileName = $safeName . '-' . time() . '-' . $i . '.' . $ext;
                        $destination = $uploadDir . $fileName;
                        if (move_uploaded_file($photoFiles['tmp_name'][$i], $destination)) {
                            $pressBookModel->addPhoto($eventId, 'uploads/press-book/photos/' . $fileName, '', 0, 0);
                            $uploaded++;
                        }
                    }
                }
                $_SESSION['flash_success'] = $uploaded > 0 ? "$uploaded photo(s) ajoutée(s) avec succès." : 'Aucune photo ajoutée.';
            }
            header('Location: ' . url('admin/press-book', ['edit_id' => $eventId]));
            exit;

        case 'delete_photo':
            $photoId = isset($_POST['photo_id']) ? intval($_POST['photo_id']) : 0;
            $photo = $pressBookModel->findPhotoById($photoId);
            if ($photo && $pressBookModel->deletePhoto($photoId)) {
                $filePath = BASE_PATH . '/' . ltrim($photo['photo_path'], '/');
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
                $_SESSION['flash_success'] = 'Photo supprimée.';
            } else {
                $_SESSION['flash_error'] = 'Impossible de supprimer la photo.';
            }
            header('Location: ' . url('admin/press-book', ['edit_id' => $eventId]));
            exit;

        case 'set_cover_photo':
            $photoId = isset($_POST['photo_id']) ? intval($_POST['photo_id']) : 0;
            if ($pressBookModel->markCoverPhoto($photoId, $eventId)) {
                $_SESSION['flash_success'] = 'Photo principale définie.';
            } else {
                $_SESSION['flash_error'] = 'Impossible de définir la photo principale.';
            }
            header('Location: ' . url('admin/press-book', ['edit_id' => $eventId]));
            exit;

        case 'save_photo_order':
            $orderData = $_POST['photo_order'] ?? [];
            if (is_array($orderData)) {
                foreach ($orderData as $photoId => $order) {
                    $pressBookModel->updatePhotoOrder(intval($photoId), intval($order));
                }
                $_SESSION['flash_success'] = 'Ordre des photos mis à jour.';
            }
            header('Location: ' . url('admin/press-book', ['edit_id' => $eventId]));
            exit;

        case 'add_videos':
            $youtubeUrls = clean_input($_POST['youtube_urls'] ?? '');
            $lines = preg_split('/\r?\n/', trim($youtubeUrls));
            $added = 0;
            foreach ($lines as $line) {
                $line = trim($line);
                if ($line !== '' && $pressBookModel->addVideo($eventId, $line)) {
                    $added++;
                }
            }
            $_SESSION['flash_success'] = $added > 0 ? "$added vidéo(s) ajoutée(s)." : 'Aucune vidéo valide ajoutée.';
            header('Location: ' . url('admin/press-book', ['edit_id' => $eventId]));
            exit;

        case 'delete_video':
            $videoId = isset($_POST['video_id']) ? intval($_POST['video_id']) : 0;
            if ($pressBookModel->deleteVideo($videoId)) {
                $_SESSION['flash_success'] = 'Vidéo supprimée.';
            } else {
                $_SESSION['flash_error'] = 'Impossible de supprimer la vidéo.';
            }
            header('Location: ' . url('admin/press-book', ['edit_id' => $eventId]));
            exit;
    }
}

$eventId = isset($_GET['edit_id']) ? intval($_GET['edit_id']) : 0;
$event = $eventId ? $pressBookModel->findById($eventId, false) : null;
$events = $pressBookModel->findAllAdmin(1, 100);
$photos = $event ? $pressBookModel->getPhotos($event['id']) : [];
$videos = $event ? $pressBookModel->getVideos($event['id']) : [];

$page_title = 'Gestion du Press Book';
require_once __DIR__ . '/layouts/header.php';
?>

<?php if ($flash_success): ?>
    <div class="alert alert-success"><?= e($flash_success) ?></div>
<?php endif; ?>
<?php if ($flash_error): ?>
    <div class="alert alert-danger"><?= e($flash_error) ?></div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-xl-5">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><?= $event ? 'Modifier un événement' : 'Ajouter un événement' ?></h6>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="<?= $event ? 'update_event' : 'create_event' ?>">
                    <input type="hidden" name="id" value="<?= $event ? intval($event['id']) : 0 ?>">

                    <div class="mb-3">
                        <label class="form-label">Titre</label>
                        <input type="text" name="title" class="form-control" value="<?= e($event['title'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4"><?= e($event['description'] ?? '') ?></textarea>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Date</label>
                            <input type="date" name="date_event" class="form-control" value="<?= e($event['date_event'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Heure</label>
                            <input type="time" name="time_event" class="form-control" value="<?= e($event['time_event'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Statut</label>
                        <select name="status" class="form-select">
                            <option value="draft" <?= isset($event['status']) && $event['status'] === 'draft' ? 'selected' : '' ?>>Brouillon</option>
                            <option value="published" <?= isset($event['status']) && $event['status'] === 'published' ? 'selected' : '' ?>>Publié</option>
                            <option value="archived" <?= isset($event['status']) && $event['status'] === 'archived' ? 'selected' : '' ?>>Archivé</option>
                        </select>
                    </div>
                    <h5 class="fw-bold mt-4 mb-3">Images</h5>
                    <div class="mb-3">
                        <label class="form-label">Téléverser plusieurs images</label>
                        <input type="file" name="photos[]" class="form-control" accept="image/*" multiple>
                        <small class="text-muted">JPG, PNG, GIF, WebP.</small>
                    </div>
                    <h5 class="fw-bold mt-4 mb-3">Vidéos YouTube</h5>
                    <div class="mb-3">
                        <label class="form-label">URL(s) YouTube</label>
                        <textarea name="youtube_urls" class="form-control" rows="3" placeholder="https://youtu.be/AbCdEfGh123&#10;https://www.youtube.com/watch?v=AbCdEfGh123"></textarea>
                        <small class="text-muted">Un lien par ligne.</small>
                    </div>
                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-success"><?= $event ? 'Mettre à jour' : 'Créer l\'événement' ?></button>
                        <?php if ($event): ?>
                            <a href="<?= url('admin/press-book') ?>" class="btn btn-secondary">Nouvel événement</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <?php if ($event): ?>
            <div class="card shadow mt-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Galerie photos</h6>
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="upload_photos">
                        <input type="hidden" name="id" value="<?= intval($event['id']) ?>">
                        <div class="mb-3">
                            <label class="form-label">Téléverser plusieurs images</label>
                            <input type="file" name="photos[]" class="form-control" accept="image/*" multiple>
                            <small class="text-muted">JPG, PNG, GIF, WebP.</small>
                        </div>
                        <button type="submit" class="btn btn-primary">Téléverser</button>
                    </form>

                    <?php if (!empty($photos)): ?>
                        <form method="POST" class="mt-4">
                            <input type="hidden" name="action" value="save_photo_order">
                            <input type="hidden" name="id" value="<?= intval($event['id']) ?>">
                            <div class="row g-3">
                                <?php foreach ($photos as $photo): ?>
                                    <div class="col-md-6">
                                        <div class="card border <?= $photo['is_cover'] ? 'border-success' : 'border-light' ?>">
                                            <img src="<?= APP_URL . '/' . e($photo['photo_path']) ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                                            <div class="card-body pb-3">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span class="badge bg-<?= $photo['is_cover'] ? 'success' : 'secondary' ?>"><?= $photo['is_cover'] ? 'Principale' : 'Photo' ?></span>
                                                    <div class="btn-group" role="group">
                                                        <?php if (!$photo['is_cover']): ?>
                                                            <button type="submit" formaction="<?= url('admin/press-book') ?>" formmethod="post" name="action" value="set_cover_photo" class="btn btn-sm btn-outline-success" title="Définir comme couverture">
                                                                <i class="bi bi-star"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                        <button type="submit" formaction="<?= url('admin/press-book') ?>" formmethod="post" name="action" value="delete_photo" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer cette photo ?');">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="mb-2">
                                                    <label class="form-label mb-1">Ordre</label>
                                                    <input type="number" name="photo_order[<?= intval($photo['id']) ?>]" class="form-control" value="<?= intval($photo['sort_order']) ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="submit" class="btn btn-outline-primary mt-3">Enregistrer l'ordre</button>
                        </form>
                    <?php else: ?>
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-image fs-1"></i>
                            <p class="mb-0 mt-3">Aucune photo ajoutée pour cet événement.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card shadow mt-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Vidéos YouTube</h6>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="action" value="add_videos">
                        <input type="hidden" name="id" value="<?= intval($event['id']) ?>">
                        <div class="mb-3">
                            <label class="form-label">URL(s) YouTube</label>
                            <textarea name="youtube_urls" class="form-control" rows="3" placeholder="https://youtu.be/AbCdEfGh123\nhttps://www.youtube.com/watch?v=AbCdEfGh123"></textarea>
                            <small class="text-muted">Un lien par ligne.</small>
                        </div>
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                    </form>

                    <?php if (!empty($videos)): ?>
                        <div class="row row-cols-1 row-cols-md-2 g-3 mt-4">
                            <?php foreach ($videos as $video): ?>
                                <div class="col">
                                    <div class="card h-100 shadow-sm">
                                        <div class="ratio ratio-16x9">
                                            <iframe src="https://www.youtube.com/embed/<?= e($video['youtube_id']) ?>" allowfullscreen></iframe>
                                        </div>
                                        <div class="card-body">
                                            <h6 class="card-title text-truncate"><?= e($video['title'] ?: 'Vidéo YouTube') ?></h6>
                                            <p class="text-muted small mb-3">ID : <?= e($video['youtube_id']) ?></p>
                                            <form method="POST" class="d-inline">
                                                <input type="hidden" name="action" value="delete_video">
                                                <input type="hidden" name="id" value="<?= intval($event['id']) ?>">
                                                <input type="hidden" name="video_id" value="<?= intval($video['id']) ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer cette vidéo ?');">
                                                    <i class="bi bi-trash"></i> Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-camera-video fs-1"></i>
                            <p class="mb-0 mt-3">Aucune vidéo ajoutée pour cet événement.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="col-xl-7">
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Événements Press Book</h6>
                <span class="badge bg-success"><?= count($events) ?></span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Titre</th>
                                <th>Catégorie</th>
                                <th>Date</th>
                                <th>Statut</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($events)): ?>
                                <?php foreach ($events as $item): ?>
                                    <tr>
                                        <td><?= intval($item['id']) ?></td>
                                        <td><?= e($item['title']) ?></td>
                                        <td><?= e($item['category'] ?: '-') ?></td>
                                        <td><?= e($item['date_event'] ?: '-') ?></td>
                                        <td>
                                            <span class="badge bg-<?= $item['status'] === 'published' ? 'success' : ($item['status'] === 'archived' ? 'secondary' : 'warning') ?>">
                                                <?= ucfirst($item['status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="<?= url('admin/press-book', ['edit_id' => $item['id']]) ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                            <form method="POST" class="d-inline">
                                                <input type="hidden" name="action" value="delete_event">
                                                <input type="hidden" name="id" value="<?= intval($item['id']) ?>">
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cet événement ?');"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="6" class="text-center">Aucun événement trouvé.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/layouts/footer.php';

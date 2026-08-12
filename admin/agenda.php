<?php
require_once __DIR__ . '/../includes/init.php';
require_login();

$agendaModel = new Agenda();

$flash_success = $_SESSION['flash_success'] ?? null;
$flash_error = $_SESSION['flash_error'] ?? null;
unset($_SESSION['flash_success'], $_SESSION['flash_error']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $eventId = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $title = clean_input($_POST['title'] ?? '');
    $description = clean_input($_POST['description'] ?? '');
    $start_date = clean_input($_POST['start_date'] ?? '');
    $end_date = clean_input($_POST['end_date'] ?? '') ?: null;
    $start_time = clean_input($_POST['start_time'] ?? '') ?: null;
    $end_time = clean_input($_POST['end_time'] ?? '') ?: null;
    $location = clean_input($_POST['location'] ?? '');
    $address = clean_input($_POST['address'] ?? '');
    $is_published = isset($_POST['is_published']) && $_POST['is_published'] == '1' ? 1 : 0;

    switch ($action) {
        case 'create_event':
        case 'update_event':
            if (empty($title)) {
                $_SESSION['flash_error'] = 'Le titre de l\'événement est requis.';
                header('Location: ' . url('admin/agenda'));
                exit;
            }
            if (empty($start_date)) {
                $_SESSION['flash_error'] = 'La date de début est requise.';
                header('Location: ' . url('admin/agenda'));
                exit;
            }

            $data = [
                'title' => $title,
                'description' => $description,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'start_time' => $start_time,
                'end_time' => $end_time,
                'location' => $location,
                'address' => $address,
                'is_published' => $is_published,
                'created_by' => $_SESSION['user_id'] ?? null
            ];

            // Handle image upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = BASE_PATH . '/uploads/agenda/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $fileExt = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                
                if (in_array($fileExt, $allowedExts)) {
                    $fileName = uniqid('agenda_') . '_' . time() . '.' . $fileExt;
                    $destination = $uploadDir . $fileName;
                    
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                        $data['image'] = 'uploads/agenda/' . $fileName;
                        
                        // Delete old image if updating
                        if ($action === 'update_event' && $eventId > 0) {
                            $oldEvent = $agendaModel->findById($eventId, false);
                            if ($oldEvent && !empty($oldEvent['image'])) {
                                $oldFilePath = BASE_PATH . '/' . ltrim($oldEvent['image'], '/');
                                if (file_exists($oldFilePath)) {
                                    @unlink($oldFilePath);
                                }
                            }
                        }
                    }
                }
            }

            if ($action === 'create_event') {
                $result = $agendaModel->create($data);
                if ($result) {
                    $_SESSION['flash_success'] = 'Événement ajouté avec succès.';
                } else {
                    $_SESSION['flash_error'] = 'Impossible de créer l\'événement.';
                }
            } else {
                $existing = $agendaModel->findById($eventId, false);
                if ($existing) {
                    $result = $agendaModel->update($eventId, $data);
                    if ($result !== false) {
                        $_SESSION['flash_success'] = 'Événement mis à jour avec succès.';
                    } else {
                        $_SESSION['flash_error'] = 'Impossible de mettre à jour l\'événement.';
                    }
                } else {
                    $_SESSION['flash_error'] = 'Événement introuvable.';
                }
            }

            header('Location: ' . url('admin/agenda'));
            exit;

        case 'delete_event':
            if ($eventId > 0) {
                $event = $agendaModel->findById($eventId, false);
                if ($event) {
                    // Delete image if exists
                    if (!empty($event['image'])) {
                        $filePath = BASE_PATH . '/' . ltrim($event['image'], '/');
                        if (file_exists($filePath)) {
                            @unlink($filePath);
                        }
                    }
                    if ($agendaModel->delete($eventId)) {
                        $_SESSION['flash_success'] = 'Événement supprimé avec succès.';
                    } else {
                        $_SESSION['flash_error'] = 'Impossible de supprimer l\'événement.';
                    }
                }
            }
            header('Location: ' . url('admin/agenda'));
            exit;
    }
}

$eventId = isset($_GET['edit_id']) ? intval($_GET['edit_id']) : 0;
$event = $eventId ? $agendaModel->findById($eventId, false) : null;
$events = $agendaModel->findAllAdmin(1, 100);

$page_title = 'Gestion de l\'Agenda';
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
                        <label class="form-label">Titre *</label>
                        <input type="text" name="title" class="form-control" value="<?= e($event['title'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4"><?= e($event['description'] ?? '') ?></textarea>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Date de début *</label>
                            <input type="date" name="start_date" class="form-control" value="<?= e($event['start_date'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date de fin</label>
                            <input type="date" name="end_date" class="form-control" value="<?= e($event['end_date'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <label class="form-label">Heure de début</label>
                            <input type="time" name="start_time" class="form-control" value="<?= e($event['start_time'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Heure de fin</label>
                            <input type="time" name="end_time" class="form-control" value="<?= e($event['end_time'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="mb-3 mt-2">
                        <label class="form-label">Lieu</label>
                        <input type="text" name="location" class="form-control" value="<?= e($event['location'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Adresse complète</label>
                        <textarea name="address" class="form-control" rows="2"><?= e($event['address'] ?? '') ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Image</label>
                        <?php if ($event && !empty($event['image'])): ?>
                            <div class="mb-2">
                                <img src="<?= APP_URL . '/' . e($event['image']) ?>" alt="" style="max-height: 150px; object-fit: cover;">
                            </div>
                        <?php endif; ?>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_published" class="form-check-input" value="1" <?= isset($event['is_published']) && $event['is_published'] == 1 ? 'checked' : '' ?>>
                            <label class="form-check-label">Publier immédiatement</label>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success"><?= $event ? 'Mettre à jour' : 'Créer l\'événement' ?></button>
                        <?php if ($event): ?>
                            <a href="<?= url('admin/agenda') ?>" class="btn btn-secondary">Nouvel événement</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-xl-7">
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Événements de l'agenda</h6>
                <span class="badge bg-success"><?= count($events) ?></span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Titre</th>
                                <th>Date</th>
                                <th>Lieu</th>
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
                                        <td>
                                            <?php 
                                                $dateStr = e($item['start_date']);
                                                if (!empty($item['end_date']) && $item['end_date'] != $item['start_date']) {
                                                    $dateStr .= ' - ' . e($item['end_date']);
                                                }
                                                echo $dateStr;
                                            ?>
                                        </td>
                                        <td><?= e($item['location'] ?: '-') ?></td>
                                        <td>
                                            <span class="badge bg-<?= $item['is_published'] == 1 ? 'success' : 'secondary' ?>">
                                                <?= $item['is_published'] == 1 ? 'Publié' : 'Brouillon' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="<?= url('admin/agenda', ['edit_id' => $item['id']]) ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
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

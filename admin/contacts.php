<?php
require_once __DIR__ . '/../includes/init.php';
require_login();

$contact = new Contact();
$page_title = 'Gestion des contacts';

$action = $_GET['action'] ?? 'list';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10;
$statusFilter = $_GET['status'] ?? null;
$searchTerm = $_GET['search'] ?? '';

// Récupérer les statistiques
$stats = $contact->getDashboardStats();

// Pour la page messages
if ($action === 'messages') {
    $messages = $contact->getAllMessages($page, $perPage, $statusFilter, $searchTerm);
    $totalMessages = $contact->getTotalMessages($statusFilter, $searchTerm);
    $totalPages = ceil($totalMessages / $perPage);
}

// Récupérer les coordonnées et adresses pour les onglets
$coordonnees = $contact->getAllCoordonnees(false);
$adresses = $contact->getAllAdresses(false);

require_once __DIR__ . '/layouts/header.php';
?>

<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-chat-left-text me-2" style="color: #5b2d00;"></i>
                Gestion des Contacts
            </h1>
        </div>
    </div>

    <!-- Tabs de navigation -->
    <ul class="nav nav-tabs mb-4 border-0" id="contactTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link rounded-top px-4 <?= !$action || $action === 'list' ? 'active bg-white text-dark' : 'text-muted' ?>" 
               href="<?= url('admin/contacts') ?>">
                <i class="bi bi-grid me-1"></i> Tableau de bord
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link rounded-top px-4 <?= $action === 'messages' ? 'active bg-white text-dark' : 'text-muted' ?>" 
               href="<?= url('admin/contacts', ['action' => 'messages']) ?>">
                <i class="bi bi-inbox me-1"></i> Messages reçus
                <span class="badge bg-danger ms-1 small" id="unread-count"><?= $stats['unread'] ?></span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link rounded-top px-4 <?= $action === 'coordonnees' ? 'active bg-white text-dark' : 'text-muted' ?>" 
               href="<?= url('admin/contacts', ['action' => 'coordonnees']) ?>">
                <i class="bi bi-person-lines-fill me-1"></i> Coordonnées
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link rounded-top px-4 <?= $action === 'adresses' ? 'active bg-white text-dark' : 'text-muted' ?>" 
               href="<?= url('admin/contacts', ['action' => 'adresses']) ?>">
                <i class="bi bi-geo-alt me-1"></i> Adresses
            </a>
        </li>
    </ul>

    <div class="tab-content">
        <!-- TAB 1 : TABLEAU DE BORD -->
        <?php if (!$action || $action === 'list'): ?>
        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="card border-0 shadow h-100 border-left-primary">
                    <div class="card-body text-center">
                        <h3 class="display-6 fw-bold text-dark" id="stat-total"><?= $stats['total'] ?></h3>
                        <p class="text-muted mb-0">Total messages</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow h-100 border-left-danger">
                    <div class="card-body text-center">
                        <h3 class="display-6 fw-bold text-danger" id="stat-unread"><?= $stats['unread'] ?></h3>
                        <p class="text-muted mb-0">Non lus</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow h-100 border-left-warning">
                    <div class="card-body text-center">
                        <h3 class="display-6 fw-bold text-warning" id="stat-en-cours"><?= $stats['en_cours'] ?></h3>
                        <p class="text-muted mb-0">En cours</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow h-100 border-left-success">
                    <div class="card-body text-center">
                        <h3 class="display-6 fw-bold text-success" id="stat-traite"><?= $stats['traite'] ?></h3>
                        <p class="text-muted mb-0">Traités</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card border-0 shadow h-100">
                    <div class="card-body">
                        <h5 class="fw-bold"><i class="bi bi-calendar-day me-2 text-success"></i> Messages aujourd'hui</h5>
                        <p class="display-4 text-center fw-bold mt-4" id="stat-today"><?= $stats['today'] ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow h-100">
                    <div class="card-body">
                        <h5 class="fw-bold"><i class="bi bi-calendar-month me-2 text-success"></i> Messages ce mois</h5>
                        <p class="display-4 text-center fw-bold mt-4" id="stat-month"><?= $stats['month'] ?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- TAB 2 : MESSAGES -->
        <?php if ($action === 'messages'): ?>
        <div class="card border-0 shadow">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom-0 flex-wrap gap-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-inbox me-2 text-success"></i> Tous les messages</h5>
                
                <div class="d-flex gap-2 flex-wrap">
                    <form method="GET" class="d-flex gap-2" id="search-form">
                        <input type="hidden" name="p" value="admin/contacts">
                        <input type="hidden" name="action" value="messages">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="<?= e($searchTerm) ?>">
                            <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
                        </div>
                    </form>
                    
                    <div class="btn-group" role="group">
                        <a href="<?= url('admin/contacts', ['action' => 'messages']) ?>" 
                           class="btn btn-outline-secondary btn-sm <?= !$statusFilter ? 'active' : '' ?>">Tous</a>
                        <a href="<?= url('admin/contacts', ['action' => 'messages', 'status' => 'nouveau']) ?>" 
                           class="btn btn-outline-danger btn-sm <?= $statusFilter === 'nouveau' ? 'active' : '' ?>">Nouveaux</a>
                        <a href="<?= url('admin/contacts', ['action' => 'messages', 'status' => 'en_cours']) ?>" 
                           class="btn btn-outline-warning btn-sm <?= $statusFilter === 'en_cours' ? 'active' : '' ?>">En cours</a>
                        <a href="<?= url('admin/contacts', ['action' => 'messages', 'status' => 'traite']) ?>" 
                           class="btn btn-outline-success btn-sm <?= $statusFilter === 'traite' ? 'active' : '' ?>">Traités</a>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>ID</th>
                                <th style="width: 200px;">Nom</th>
                                <th>Email</th>
                                <th>Objet</th>
                                <th style="width: 120px;">Lu</th>
                                <th style="width: 120px;">Statut</th>
                                <th style="width: 150px;">Date</th>
                                <th style="width: 220px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="messages-tbody">
                            <?php if (!empty($messages)): ?>
                                <?php foreach ($messages as $m): ?>
                                <tr class="message-row" data-id="<?= $m['id'] ?>">
                                    <td><?= $m['id'] ?></td>
                                    <td class="fw-semibold">
                                        <?= e($m['nom']) ?>
                                        <?php if ($m['telephone']): ?>
                                            <br><small class="text-muted"><i class="bi bi-telephone me-1"></i><?= e($m['telephone']) ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= e($m['email']) ?></td>
                                    <td>
                                        <?= e(substr($m['objet'] ?? $m['message'], 0, 60)) ?><?= strlen($m['objet'] ?? $m['message']) > 60 ? '...' : '' ?>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill <?= $m['is_read'] ? 'bg-secondary' : 'bg-danger' ?>" id="badge-read-<?= $m['id'] ?>">
                                            <?= $m['is_read'] ? 'Lu' : 'Non lu' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill status-badge" id="badge-status-<?= $m['id'] ?>" data-status="<?= $m['status'] ?>">
                                            <?php 
                                            $statusLabels = ['nouveau' => 'Nouveau', 'en_cours' => 'En cours', 'traite' => 'Traité', 'archive' => 'Archivé'];
                                            echo $statusLabels[$m['status']] ?? $m['status'];
                                            ?>
                                        </span>
                                    </td>
                                    <td><?= format_date($m['date_add'], 'd/m/Y H:i') ?></td>
                                    <td>
                                        <button class="btn btn-outline-info btn-sm me-1 view-btn" data-id="<?= $m['id'] ?>" title="Voir">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-gear"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item mark-read-btn" href="#" data-id="<?= $m['id'] ?>"><i class="bi bi-check-circle me-2"></i>Marquer lu</a></li>
                                                <li><a class="dropdown-item mark-unread-btn" href="#" data-id="<?= $m['id'] ?>"><i class="bi bi-x-circle me-2"></i>Marquer non lu</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item update-status-btn" href="#" data-id="<?= $m['id'] ?>" data-status="nouveau"><i class="bi bi-circle me-2"></i>Nouveau</a></li>
                                                <li><a class="dropdown-item update-status-btn" href="#" data-id="<?= $m['id'] ?>" data-status="en_cours"><i class="bi bi-arrow-clockwise me-2"></i>En cours</a></li>
                                                <li><a class="dropdown-item update-status-btn" href="#" data-id="<?= $m['id'] ?>" data-status="traite"><i class="bi bi-check-all me-2"></i>Traité</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item update-status-btn" href="#" data-id="<?= $m['id'] ?>" data-status="archive"><i class="bi bi-archive me-2"></i>Archiver</a></li>
                                                <li><a class="dropdown-item text-danger delete-btn" href="#" data-id="<?= $m['id'] ?>"><i class="bi bi-trash me-2"></i>Supprimer</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-2 mb-2 d-block"></i>
                                    Aucun message pour le moment
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if ($totalPages > 1): ?>
                <div class="card-footer d-flex justify-content-center">
                    <nav aria-label="Page navigation">
                        <ul class="pagination mb-0">
                            <?php if ($page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?= url('admin/contacts', array_merge(['action' => 'messages', 'page' => $page - 1], $statusFilter ? ['status' => $statusFilter] : [], $searchTerm ? ['search' => $searchTerm] : [])) ?>" aria-label="Précédent">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                <a class="page-link" href="<?= url('admin/contacts', array_merge(['action' => 'messages', 'page' => $i], $statusFilter ? ['status' => $statusFilter] : [], $searchTerm ? ['search' => $searchTerm] : [])) ?>"><?= $i ?></a>
                            </li>
                            <?php endfor; ?>
                            
                            <?php if ($page < $totalPages): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?= url('admin/contacts', array_merge(['action' => 'messages', 'page' => $page + 1], $statusFilter ? ['status' => $statusFilter] : [], $searchTerm ? ['search' => $searchTerm] : [])) ?>" aria-label="Suivant">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- TAB 3 : COORDONNEES -->
        <?php if ($action === 'coordonnees'): ?>
        <div class="card border-0 shadow">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom-0">
                <h5 class="mb-0 fw-bold"><i class="bi bi-person-lines-fill me-2 text-success"></i> Toutes les coordonnées</h5>
                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#coordonneeModal" data-mode="add">
                    <i class="bi bi-plus-lg me-1"></i> Ajouter une coordonnée
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Type</th>
                                <th>Titre</th>
                                <th>Valeur</th>
                                <th>Ordre</th>
                                <th>Statut</th>
                                <th style="width: 150px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="coordonnees-tbody">
                            <?php if (!empty($coordonnees)): ?>
                                <?php foreach ($coordonnees as $c): ?>
                                <tr class="coordonnee-row" data-id="<?= $c['id'] ?>">
                                    <td>
                                        <span class="badge rounded-pill bg-secondary">
                                            <?= ucfirst($c['type']) ?>
                                        </span>
                                    </td>
                                    <td class="fw-semibold"><?= e($c['titre']) ?></td>
                                    <td><?= e($c['valeur']) ?></td>
                                    <td><?= $c['ordre_affichage'] ?></td>
                                    <td>
                                        <span class="badge rounded-pill bg-<?= $c['statut'] ? 'success' : 'danger' ?>">
                                            <?= $c['statut'] ? 'Actif' : 'Inactif' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-outline-primary btn-sm me-1 edit-coordonnee-btn" data-id="<?= $c['id'] ?>" title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-outline-danger btn-sm delete-coordonnee-btn" data-id="<?= $c['id'] ?>" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-2 mb-2 d-block"></i>
                                    Aucune coordonnée pour le moment
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- TAB 4 : ADRESSES -->
        <?php if ($action === 'adresses'): ?>
        <div class="card border-0 shadow">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom-0">
                <h5 class="mb-0 fw-bold"><i class="bi bi-geo-alt me-2 text-success"></i> Toutes les adresses</h5>
                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#adresseModal" data-mode="add">
                    <i class="bi bi-plus-lg me-1"></i> Ajouter une adresse
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Titre</th>
                                <th>Adresse</th>
                                <th>Coordonnées GPS</th>
                                <th>Ordre</th>
                                <th>Statut</th>
                                <th style="width: 150px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="adresses-tbody">
                            <?php if (!empty($adresses)): ?>
                                <?php foreach ($adresses as $a): ?>
                                <tr class="adresse-row" data-id="<?= $a['id'] ?>">
                                    <td class="fw-semibold"><?= e($a['titre']) ?></td>
                                    <td><?= e(substr($a['adresse'], 0, 80)) ?><?= strlen($a['adresse']) > 80 ? '...' : '' ?></td>
                                    <td>
                                        <?php if (!empty($a['latitude']) && !empty($a['longitude'])): ?>
                                            <span class="text-success"><i class="bi bi-check-circle me-1"></i> Oui</span>
                                        <?php else: ?>
                                            <span class="text-muted"><i class="bi bi-x-circle me-1"></i> Non</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $a['ordre_affichage'] ?></td>
                                    <td>
                                        <span class="badge rounded-pill bg-<?= $a['statut'] ? 'success' : 'danger' ?>">
                                            <?= $a['statut'] ? 'Actif' : 'Inactif' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-outline-primary btn-sm me-1 edit-adresse-btn" data-id="<?= $a['id'] ?>" title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-outline-danger btn-sm delete-adresse-btn" data-id="<?= $a['id'] ?>" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-2 mb-2 d-block"></i>
                                    Aucune adresse pour le moment
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal de détail du message -->
<div class="modal fade" id="messageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-envelope me-2"></i>Détails du message <span id="modal-message-id"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="messageModalBody">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer" id="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour ajouter/éditer une coordonnée -->
<div class="modal fade" id="coordonneeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="coordonneeForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="coordonneeModalTitle">Ajouter une coordonnée</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="coordonnee-id">
                    <div class="mb-3">
                        <label for="coordonnee-type" class="form-label">Type</label>
                        <select class="form-select" id="coordonnee-type" name="type">
                            <option value="telephone">Téléphone</option>
                            <option value="whatsapp">WhatsApp</option>
                            <option value="email">Email</option>
                            <option value="reseau_social">Réseau social</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="coordonnee-titre" class="form-label">Titre</label>
                        <input type="text" class="form-control" id="coordonnee-titre" name="titre">
                    </div>
                    <div class="mb-3">
                        <label for="coordonnee-valeur" class="form-label">Valeur</label>
                        <input type="text" class="form-control" id="coordonnee-valeur" name="valeur" required>
                    </div>
                    <div class="mb-3">
                        <label for="coordonnee-icone" class="form-label">Icône (classe Bootstrap Icons)</label>
                        <input type="text" class="form-control" id="coordonnee-icone" name="icone" placeholder="ex: bi-telephone">
                    </div>
                    <div class="mb-3">
                        <label for="coordonnee-lien" class="form-label">Lien</label>
                        <input type="text" class="form-control" id="coordonnee-lien" name="lien" placeholder="ex: https://wa.me/123456">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="coordonnee-ordre" class="form-label">Ordre d'affichage</label>
                            <input type="number" class="form-control" id="coordonnee-ordre" name="ordre_affichage" value="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="coordonnee-statut" class="form-label">Actif</label>
                            <div class="form-check mt-2">
                                <input type="checkbox" class="form-check-input" id="coordonnee-statut" name="statut" checked>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary" id="coordonnee-submit-btn">
                        <i class="bi bi-check-lg me-1"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal pour ajouter/éditer une adresse -->
<div class="modal fade" id="adresseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="adresseForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="adresseModalTitle">Ajouter une adresse</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="adresse-id">
                    <div class="mb-3">
                        <label for="adresse-titre" class="form-label">Titre</label>
                        <input type="text" class="form-control" id="adresse-titre" name="titre" required>
                    </div>
                    <div class="mb-3">
                        <label for="adresse-adresse" class="form-label">Adresse complète</label>
                        <textarea class="form-control" id="adresse-adresse" name="adresse" rows="3" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="adresse-latitude" class="form-label">Latitude</label>
                            <input type="text" class="form-control" id="adresse-latitude" name="latitude">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="adresse-longitude" class="form-label">Longitude</label>
                            <input type="text" class="form-control" id="adresse-longitude" name="longitude">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="adresse-google-maps" class="form-label">Lien Google Maps</label>
                        <input type="text" class="form-control" id="adresse-google-maps" name="google_maps_url">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="adresse-zoom" class="form-label">Niveau de zoom</label>
                            <input type="number" class="form-control" id="adresse-zoom" name="zoom_level" value="15">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="adresse-statut" class="form-label">Actif</label>
                            <div class="form-check mt-2">
                                <input type="checkbox" class="form-check-input" id="adresse-statut" name="statut" checked>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="adresse-ordre" class="form-label">Ordre d'affichage</label>
                        <input type="number" class="form-control" id="adresse-ordre" name="ordre_affichage" value="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary" id="adresse-submit-btn">
                        <i class="bi bi-check-lg me-1"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
let currentMessageId = null;
let messageModal = null;
let coordonneeModal = null;
let adresseModal = null;

const statusLabels = {
    'nouveau': 'Nouveau',
    'en_cours': 'En cours',
    'traite': 'Traité',
    'archive': 'Archivé'
};

const statusClasses = {
    'nouveau': 'bg-secondary',
    'en_cours': 'bg-warning',
    'traite': 'bg-success',
    'archive': 'bg-dark'
};

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function formatDate(dateStr) {
    const date = new Date(dateStr);
    return date.toLocaleString('fr-FR', { 
        day: '2-digit', 
        month: '2-digit', 
        year: 'numeric', 
        hour: '2-digit', 
        minute: '2-digit' 
    });
}

function showToast(title, text, icon) {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });
    Toast.fire({
        icon: icon,
        title: title,
        text: text
    });
}

function updateStats() {
    const formData = new FormData();
    formData.append('action', 'get_stats');
    
    fetch('<?= APP_URL ?>/index.php?p=ajax/message-action', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.stats) {
            const elTotal = document.getElementById('stat-total');
            const elUnread = document.getElementById('stat-unread');
            const elEnCours = document.getElementById('stat-en-cours');
            const elTraite = document.getElementById('stat-traite');
            const elToday = document.getElementById('stat-today');
            const elMonth = document.getElementById('stat-month');
            const elUnreadCount = document.getElementById('unread-count');
            
            if (elTotal) elTotal.textContent = data.stats.total;
            if (elUnread) elUnread.textContent = data.stats.unread;
            if (elEnCours) elEnCours.textContent = data.stats.en_cours;
            if (elTraite) elTraite.textContent = data.stats.traite;
            if (elToday) elToday.textContent = data.stats.today;
            if (elMonth) elMonth.textContent = data.stats.month;
            if (elUnreadCount) elUnreadCount.textContent = data.stats.unread;
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // Initialize modals
    messageModal = new bootstrap.Modal(document.getElementById('messageModal'));
    coordonneeModal = new bootstrap.Modal(document.getElementById('coordonneeModal'));
    adresseModal = new bootstrap.Modal(document.getElementById('adresseModal'));
    
    // Charger les styles des badges au démarrage
    document.querySelectorAll('.status-badge').forEach(badge => {
        const status = badge.dataset.status;
        badge.classList.add(statusClasses[status]);
    });

    // Voir message
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            viewMessage(id);
        });
    });

    // Marquer lu / non lu
    document.querySelectorAll('.mark-read-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            updateReadStatus(id, true);
        });
    });
    document.querySelectorAll('.mark-unread-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            updateReadStatus(id, false);
        });
    });

    // Mettre à jour le statut
    document.querySelectorAll('.update-status-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            const status = this.getAttribute('data-status');
            updateStatus(id, status);
        });
    });

    // Supprimer message
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            deleteMessage(id);
        });
    });

    // Coordonnées modals
    const coordonneeData = <?php echo json_encode($coordonnees); ?>;
    const coordonneeModalEl = document.getElementById('coordonneeModal');
    coordonneeModalEl.addEventListener('show.bs.modal', function(e) {
        const mode = e.relatedTarget.getAttribute('data-mode');
        if (mode === 'edit') {
            const id = e.relatedTarget.getAttribute('data-id');
            const c = coordonneeData.find(x => x.id == id);
            if (c) {
                document.getElementById('coordonneeModalTitle').textContent = 'Modifier la coordonnée';
                document.getElementById('coordonnee-id').value = c.id;
                document.getElementById('coordonnee-type').value = c.type;
                document.getElementById('coordonnee-titre').value = c.titre;
                document.getElementById('coordonnee-valeur').value = c.valeur;
                document.getElementById('coordonnee-icone').value = c.icone;
                document.getElementById('coordonnee-lien').value = c.lien;
                document.getElementById('coordonnee-ordre').value = c.ordre_affichage;
                document.getElementById('coordonnee-statut').checked = c.statut;
            }
        } else {
            document.getElementById('coordonneeModalTitle').textContent = 'Ajouter une coordonnée';
            document.getElementById('coordonneeForm').reset();
        }
    });

    document.getElementById('coordonneeForm').addEventListener('submit', function(e) {
        e.preventDefault();
        saveCoordonnee();
    });

    document.querySelectorAll('.edit-coordonnee-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const modalTrigger = document.createElement('button');
            modalTrigger.style.display = 'none';
            modalTrigger.setAttribute('data-bs-toggle', 'modal');
            modalTrigger.setAttribute('data-bs-target', '#coordonneeModal');
            modalTrigger.setAttribute('data-mode', 'edit');
            modalTrigger.setAttribute('data-id', this.getAttribute('data-id'));
            document.body.appendChild(modalTrigger);
            modalTrigger.click();
            document.body.removeChild(modalTrigger);
        });
    });

    document.querySelectorAll('.delete-coordonnee-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            deleteCoordonnee(id);
        });
    });

    // Adresses modals
    const adresseData = <?php echo json_encode($adresses); ?>;
    const adresseModalEl = document.getElementById('adresseModal');
    adresseModalEl.addEventListener('show.bs.modal', function(e) {
        const mode = e.relatedTarget.getAttribute('data-mode');
        if (mode === 'edit') {
            const id = e.relatedTarget.getAttribute('data-id');
            const a = adresseData.find(x => x.id == id);
            if (a) {
                document.getElementById('adresseModalTitle').textContent = 'Modifier l\'adresse';
                document.getElementById('adresse-id').value = a.id;
                document.getElementById('adresse-titre').value = a.titre;
                document.getElementById('adresse-adresse').value = a.adresse;
                document.getElementById('adresse-latitude').value = a.latitude;
                document.getElementById('adresse-longitude').value = a.longitude;
                document.getElementById('adresse-google-maps').value = a.google_maps_url;
                document.getElementById('adresse-zoom').value = a.zoom_level;
                document.getElementById('adresse-ordre').value = a.ordre_affichage;
                document.getElementById('adresse-statut').checked = a.statut;
            }
        } else {
            document.getElementById('adresseModalTitle').textContent = 'Ajouter une adresse';
            document.getElementById('adresseForm').reset();
        }
    });

    document.getElementById('adresseForm').addEventListener('submit', function(e) {
        e.preventDefault();
        saveAdresse();
    });

    document.querySelectorAll('.edit-adresse-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const modalTrigger = document.createElement('button');
            modalTrigger.style.display = 'none';
            modalTrigger.setAttribute('data-bs-toggle', 'modal');
            modalTrigger.setAttribute('data-bs-target', '#adresseModal');
            modalTrigger.setAttribute('data-mode', 'edit');
            modalTrigger.setAttribute('data-id', this.getAttribute('data-id'));
            document.body.appendChild(modalTrigger);
            modalTrigger.click();
            document.body.removeChild(modalTrigger);
        });
    });

    document.querySelectorAll('.delete-adresse-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            deleteAdresse(id);
        });
    });

    // Actualiser les stats toutes les 30 secondes
    setInterval(updateStats, 30000);
});

function viewMessage(id) {
    currentMessageId = id;
    
    // Show loading
    document.getElementById('messageModalBody').innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
            <p class="mt-2 text-muted">Chargement du message...</p>
        </div>
    `;
    
    document.getElementById('modal-footer').innerHTML = `
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
    `;
    
    messageModal.show();
    
    const formData = new FormData();
    formData.append('action', 'view');
    formData.append('id', id);
    
    fetch('<?= APP_URL ?>/index.php?p=ajax/message-action', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(text => {
        let data;
        try {
            data = JSON.parse(text);
        } catch (e) {
            showToast('Erreur', 'Réponse du serveur invalide', 'error');
            document.getElementById('messageModalBody').innerHTML = `
                <div class="alert alert-danger">
                    <h5>Erreur de chargement</h5>
                    <p>Réponse du serveur invalide.</p>
                    <pre class="small">${escapeHtml(text)}</pre>
                </div>
            `;
            return;
        }
        
        if (data.success && data.message) {
            const m = data.message;
            const replies = data.replies || [];
            
            let repliesHtml = '';
            if (replies.length > 0) {
                repliesHtml = `
                    <hr class="my-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-reply-all me-2"></i>Réponses (${replies.length})</h6>
                    <div class="list-group">
                `;
                replies.forEach(r => {
                    repliesHtml += `
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">
                                    <i class="bi bi-person-circle me-1"></i>${escapeHtml(r.user_name || 'Admin')}
                                </h6>
                                <small class="text-muted">${formatDate(r.date_add)}</small>
                            </div>
                            <p class="mb-1" style="white-space: pre-wrap;">${escapeHtml(r.content)}</p>
                        </div>
                    `;
                });
                repliesHtml += '</div>';
            }
            
            document.getElementById('modal-message-id').textContent = '(#' + m.id + ')';
            document.getElementById('messageModalBody').innerHTML = `
                <div class="row mb-4">
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Nom complet :</strong> ${escapeHtml(m.nom)}</p>
                        <p class="mb-1"><strong>Email :</strong> <a href="mailto:${escapeHtml(m.email)}">${escapeHtml(m.email)}</a></p>
                        ${m.telephone ? `<p class="mb-1"><strong>Téléphone :</strong> <a href="tel:${escapeHtml(m.telephone)}">${escapeHtml(m.telephone)}</a></p>` : ''}
                    </div>
                    <div class="col-md-6 text-end">
                        <span class="badge rounded-pill ${m.is_read ? 'bg-secondary' : 'bg-danger'}" id="modal-read-badge">${m.is_read ? 'Lu' : 'Non lu'}</span>
                        <span class="badge rounded-pill ${statusClasses[m.status]}" id="modal-status-badge">${statusLabels[m.status]}</span>
                        <p class="text-muted small mt-2"><i class="bi bi-calendar me-1"></i>${formatDate(m.date_add)}</p>
                        ${m.ip_visiteur ? `<p class="text-muted small mb-0"><i class="bi bi-geo-alt me-1"></i>IP : ${escapeHtml(m.ip_visiteur)}</p>` : ''}
                    </div>
                </div>
                ${m.objet ? `<p><strong>Objet :</strong> ${escapeHtml(m.objet)}</p>` : ''}
                <div class="card bg-light mb-4">
                    <div class="card-body">
                        <strong>Message :</strong>
                        <p class="mb-0 mt-2" style="white-space: pre-wrap;">${escapeHtml(m.message)}</p>
                    </div>
                </div>
                
                ${repliesHtml}
                
                <hr class="my-4">
                
                <h6 class="fw-bold mb-3"><i class="bi bi-pencil-square me-2"></i>Répondre</h6>
                <form id="reply-form">
                    <div class="mb-3">
                        <textarea class="form-control" id="reply-content" name="content" rows="4" placeholder="Tapez votre réponse ici..."></textarea>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" id="clear-reply">Effacer</button>
                        <button type="submit" class="btn btn-success" id="send-reply">
                            <i class="bi bi-send me-1"></i>Envoyer la réponse
                        </button>
                    </div>
                </form>
            `;
            
            // Ajouter les événements au formulaire
            setTimeout(() => {
                const replyForm = document.getElementById('reply-form');
                if (replyForm) {
                    replyForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        sendReply();
                    });
                }
                
                const clearBtn = document.getElementById('clear-reply');
                if (clearBtn) {
                    clearBtn.addEventListener('click', function() {
                        document.getElementById('reply-content').value = '';
                    });
                }
            }, 100);
            
            // Update modal footer with status buttons
            document.getElementById('modal-footer').innerHTML = `
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-info modal-read-btn">${m.is_read ? 'Marquer non lu' : 'Marquer lu'}</button>
                    <div class="btn-group dropup">
                        <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Statut
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item modal-status-btn" href="#" data-status="nouveau">Nouveau</a></li>
                            <li><a class="dropdown-item modal-status-btn" href="#" data-status="en_cours">En cours</a></li>
                            <li><a class="dropdown-item modal-status-btn" href="#" data-status="traite">Traité</a></li>
                            <li><a class="dropdown-item modal-status-btn" href="#" data-status="archive">Archiver</a></li>
                        </ul>
                    </div>
                </div>
            `;
            
            // Re-bind modal read button
            document.querySelector('.modal-read-btn').addEventListener('click', function() {
                const newRead = !(document.getElementById('modal-read-badge').textContent === 'Lu');
                updateReadStatus(id, newRead);
                this.textContent = newRead ? 'Marquer non lu' : 'Marquer lu';
                document.getElementById('modal-read-badge').textContent = newRead ? 'Lu' : 'Non lu';
                document.getElementById('modal-read-badge').className = 'badge rounded-pill ' + (newRead ? 'bg-secondary' : 'bg-danger');
            });
            
            // Re-bind modal status buttons
            document.querySelectorAll('.modal-status-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const status = this.getAttribute('data-status');
                    updateStatus(id, status);
                });
            });
            
            updateReadStatusBadge(id, true);
            updateStatusBadge(id, m.status);
        } else {
            showToast('Erreur', data.message || 'Impossible de charger le message', 'error');
            document.getElementById('messageModalBody').innerHTML = `
                <div class="alert alert-danger">
                    <h5>Erreur</h5>
                    <p>${escapeHtml(data.message || 'Impossible de charger le message')}</p>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        showToast('Erreur', 'Erreur de connexion au serveur', 'error');
        document.getElementById('messageModalBody').innerHTML = `
            <div class="alert alert-danger">
                <h5>Erreur de connexion</h5>
                <p>${escapeHtml(error.toString())}</p>
            </div>
        `;
    });
}

// Envoyer une réponse
function sendReply() {
    const content = document.getElementById('reply-content').value.trim();
    if (!content) {
        showToast('Erreur', 'Veuillez écrire une réponse', 'error');
        return;
    }
    
    const formData = new FormData();
    formData.append('action', 'add_reply');
    formData.append('id', currentMessageId);
    formData.append('content', content);
    
    const btn = document.getElementById('send-reply');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Envoi en cours...';
    
    fetch('<?= APP_URL ?>/index.php?p=ajax/message-action', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Succès', data.message, 'success');
            // Recharger la vue du message
            viewMessage(currentMessageId);
            updateStats();
        } else {
            showToast('Erreur', data.message, 'error');
        }
    })
    .catch(() => {
        showToast('Erreur', 'Une erreur est survenue', 'error');
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-send me-1"></i>Envoyer la réponse';
    });
}

function updateReadStatus(id, isRead) {
    const formData = new FormData();
    formData.append('action', isRead ? 'mark_read' : 'mark_unread');
    formData.append('id', id);
    
    fetch('<?= APP_URL ?>/index.php?p=ajax/message-action', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateReadStatusBadge(id, isRead);
            showToast('Succès', data.message, 'success');
            updateStats();
        } else {
            showToast('Erreur', data.message, 'error');
        }
    });
}

function updateReadStatusBadge(id, isRead) {
    const badge = document.getElementById('badge-read-' + id);
    if (badge) {
        badge.textContent = isRead ? 'Lu' : 'Non lu';
        badge.classList.remove('bg-danger', 'bg-secondary');
        badge.classList.add(isRead ? 'bg-secondary' : 'bg-danger');
    }
}

function updateStatus(id, status) {
    const formData = new FormData();
    formData.append('action', 'update_status');
    formData.append('id', id);
    formData.append('status', status);
    
    fetch('<?= APP_URL ?>/index.php?p=ajax/message-action', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateStatusBadge(id, status);
            const modalBadge = document.getElementById('modal-status-badge');
            if (modalBadge) {
                modalBadge.textContent = statusLabels[status];
                modalBadge.className = 'badge rounded-pill ' + statusClasses[status];
            }
            showToast('Succès', data.message, 'success');
            updateStats();
        } else {
            showToast('Erreur', data.message, 'error');
        }
    });
}

function updateStatusBadge(id, status) {
    const badge = document.getElementById('badge-status-' + id);
    if (badge) {
        badge.textContent = statusLabels[status];
        badge.dataset.status = status;
        badge.className = 'badge rounded-pill status-badge ' + statusClasses[status];
    }
}

function deleteMessage(id) {
    Swal.fire({
        title: 'Êtes-vous sûr ?',
        text: 'Cette action est irréversible !',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, supprimer !',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('action', 'delete');
            formData.append('id', id);
            
            fetch('<?= APP_URL ?>/index.php?p=ajax/message-action', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const row = document.querySelector('.message-row[data-id="' + id + '"]');
                    if (row) {
                        row.style.transition = 'opacity 0.3s ease';
                        row.style.opacity = '0';
                        setTimeout(() => row.remove(), 300);
                    }
                    updateStats();
                    showToast('Succès', data.message, 'success');
                }
            });
        }
    });
}

// Coordonnées actions
function saveCoordonnee() {
    const id = document.getElementById('coordonnee-id').value;
    const form = document.getElementById('coordonneeForm');
    const formData = new FormData(form);
    formData.append('action', id ? 'update_coordonnee' : 'add_coordonnee');
    
    const btn = document.getElementById('coordonnee-submit-btn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Enregistrement...';
    
    fetch('<?= APP_URL ?>/index.php?p=ajax/message-action', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Succès', data.message, 'success');
            coordonneeModal.hide();
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast('Erreur', data.message, 'error');
        }
    })
    .catch(() => {
        showToast('Erreur', 'Une erreur est survenue', 'error');
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Enregistrer';
    });
}

function deleteCoordonnee(id) {
    Swal.fire({
        title: 'Êtes-vous sûr ?',
        text: 'Cette action est irréversible !',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, supprimer !',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('action', 'delete_coordonnee');
            formData.append('id', id);
            
            fetch('<?= APP_URL ?>/index.php?p=ajax/message-action', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const row = document.querySelector('.coordonnee-row[data-id="' + id + '"]');
                    if (row) {
                        row.style.transition = 'opacity 0.3s ease';
                        row.style.opacity = '0';
                        setTimeout(() => row.remove(), 300);
                    }
                    showToast('Succès', data.message, 'success');
                }
            });
        }
    });
}

// Adresses actions
function saveAdresse() {
    const id = document.getElementById('adresse-id').value;
    const form = document.getElementById('adresseForm');
    const formData = new FormData(form);
    formData.append('action', id ? 'update_adresse' : 'add_adresse');
    
    const btn = document.getElementById('adresse-submit-btn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Enregistrement...';
    
    fetch('<?= APP_URL ?>/index.php?p=ajax/message-action', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Succès', data.message, 'success');
            adresseModal.hide();
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast('Erreur', data.message, 'error');
        }
    })
    .catch(() => {
        showToast('Erreur', 'Une erreur est survenue', 'error');
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Enregistrer';
    });
}

function deleteAdresse(id) {
    Swal.fire({
        title: 'Êtes-vous sûr ?',
        text: 'Cette action est irréversible !',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, supprimer !',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('action', 'delete_adresse');
            formData.append('id', id);
            
            fetch('<?= APP_URL ?>/index.php?p=ajax/message-action', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const row = document.querySelector('.adresse-row[data-id="' + id + '"]');
                    if (row) {
                        row.style.transition = 'opacity 0.3s ease';
                        row.style.opacity = '0';
                        setTimeout(() => row.remove(), 300);
                    }
                    showToast('Succès', data.message, 'success');
                }
            });
        }
    });
}
</script>

<?php
require_once __DIR__ . '/layouts/footer.php';
?>

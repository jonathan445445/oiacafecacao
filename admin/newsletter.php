<?php
require_once __DIR__ . '/../includes/init.php';
require_login();

$newsletter = new Newsletter();
$page_title = 'Gestion de la Newsletter';

$action = $_GET['action'] ?? 'dashboard';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 20;
$search = $_GET['search'] ?? '';
$statusFilter = isset($_GET['status']) && $_GET['status'] !== '' ? $_GET['status'] : null;

$stats = $newsletter->getDashboardStats();

require_once __DIR__ . '/layouts/header.php';
?>

<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-envelope-paper me-2" style="color: #5b2d00;"></i>
                Gestion de la Newsletter
            </h1>
        </div>
    </div>

    <!-- Tabs de navigation -->
    <ul class="nav nav-tabs mb-4 border-0" id="newsletterTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link rounded-top px-4 <?= !$action || $action === 'dashboard' ? 'active bg-white text-dark' : 'text-muted' ?>" 
               href="<?= url('admin/newsletter') ?>">
                <i class="bi bi-speedometer2 me-1"></i> Tableau de bord
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link rounded-top px-4 <?= $action === 'subscribers' ? 'active bg-white text-dark' : 'text-muted' ?>" 
               href="<?= url('admin/newsletter', ['action' => 'subscribers']) ?>">
                <i class="bi bi-people me-1"></i> Abonnés
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link rounded-top px-4 <?= $action === 'campaigns' ? 'active bg-white text-dark' : 'text-muted' ?>" 
               href="<?= url('admin/newsletter', ['action' => 'campaigns']) ?>">
                <i class="bi bi-send me-1"></i> Campagnes
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link rounded-top px-4 <?= $action === 'templates' ? 'active bg-white text-dark' : 'text-muted' ?>" 
               href="<?= url('admin/newsletter', ['action' => 'templates']) ?>">
                <i class="bi bi-file-earmark-text me-1"></i> Modèles
            </a>
        </li>
    </ul>

    <div class="tab-content">
        <!-- TAB 1: DASHBOARD -->
        <?php if (!$action || $action === 'dashboard'): ?>
            <div class="row g-4 mb-5">
                <div class="col-md-3">
                    <div class="card border-0 shadow h-100 border-left-primary">
                        <div class="card-body text-center">
                            <h3 class="display-6 fw-bold text-dark" id="stat-total"><?= $stats['total_subscribers'] ?></h3>
                            <p class="text-muted mb-0">Total abonnés</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow h-100 border-left-success">
                        <div class="card-body text-center">
                            <h3 class="display-6 fw-bold text-success" id="stat-active"><?= $stats['active_subscribers'] ?></h3>
                            <p class="text-muted mb-0">Abonnés actifs</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow h-100 border-left-info">
                        <div class="card-body text-center">
                            <h3 class="display-6 fw-bold text-info" id="stat-campaigns"><?= $stats['total_campaigns'] ?></h3>
                            <p class="text-muted mb-0">Campagnes totales</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow h-100 border-left-warning">
                        <div class="card-body text-center">
                            <h3 class="display-6 fw-bold text-warning" id="stat-sent"><?= $stats['sent_campaigns'] ?></h3>
                            <p class="text-muted mb-0">Campagnes envoyées</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow h-100">
                        <div class="card-body">
                            <h5 class="fw-bold"><i class="bi bi-calendar-day me-2 text-success"></i> Nouveaux abonnés aujourd'hui</h5>
                            <p class="display-4 text-center fw-bold mt-4" id="stat-today"><?= $stats['today_subscribers'] ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow h-100">
                        <div class="card-body">
                            <h5 class="fw-bold"><i class="bi bi-calendar-month me-2 text-success"></i> Nouveaux abonnés ce mois</h5>
                            <p class="display-4 text-center fw-bold mt-4" id="stat-month"><?= $stats['month_subscribers'] ?></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card border-0 shadow">
                        <div class="card-body">
                            <h5 class="fw-bold mb-3"><i class="bi bi-lightbulb me-2 text-warning"></i> Actions rapides</h5>
                            <div class="d-flex gap-3 flex-wrap">
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#subscriberModal" data-mode="add">
                                    <i class="bi bi-person-plus me-1"></i> Ajouter un abonné
                                </button>
                                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#campaignModal" data-mode="add">
                                    <i class="bi bi-plus-circle me-1"></i> Créer une campagne
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- TAB 2: SUBSCRIBERS -->
        <?php if ($action === 'subscribers'): 
            $subscribers = $newsletter->getAllSubscribers($page, $perPage, $search, $statusFilter);
            $totalSubscribers = $newsletter->getTotalSubscribers($search, $statusFilter);
            $totalPages = ceil($totalSubscribers / $perPage);
        ?>
            <div class="card border-0 shadow">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom-0 flex-wrap gap-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-people me-2 text-success"></i> Liste des abonnés</h5>
                    <div class="d-flex gap-2 flex-wrap">
                        <form method="GET" class="d-flex gap-2">
                            <input type="hidden" name="p" value="admin/newsletter">
                            <input type="hidden" name="action" value="subscribers">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="<?= e($search) ?>">
                                <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
                            </div>
                        </form>
                        <div class="btn-group" role="group">
                            <a href="<?= url('admin/newsletter', ['action' => 'subscribers']) ?>" 
                               class="btn btn-outline-secondary btn-sm <?= $statusFilter === null ? 'active' : '' ?>">Tous</a>
                            <a href="<?= url('admin/newsletter', ['action' => 'subscribers', 'status' => '1']) ?>" 
                               class="btn btn-outline-success btn-sm <?= $statusFilter === '1' ? 'active' : '' ?>">Actifs</a>
                            <a href="<?= url('admin/newsletter', ['action' => 'subscribers', 'status' => '0']) ?>" 
                               class="btn btn-outline-danger btn-sm <?= $statusFilter === '0' ? 'active' : '' ?>">Inactifs</a>
                        </div>
                        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#subscriberModal" data-mode="add">
                            <i class="bi bi-plus-lg me-1"></i> Ajouter
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Email</th>
                                    <th>Prénom</th>
                                    <th>Nom</th>
                                    <th>Statut</th>
                                    <th>Confirmé</th>
                                    <th>Inscrit le</th>
                                    <th style="width: 180px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="subscribers-tbody">
                                <?php if (!empty($subscribers)): ?>
                                    <?php foreach ($subscribers as $sub): ?>
                                        <tr class="subscriber-row" data-id="<?= $sub['id'] ?>">
                                            <td><?= $sub['id'] ?></td>
                                            <td class="fw-semibold"><?= e($sub['email']) ?></td>
                                            <td><?= e($sub['first_name'] ?? '-') ?></td>
                                            <td><?= e($sub['last_name'] ?? '-') ?></td>
                                            <td>
                                                <span class="badge rounded-pill bg-<?= $sub['is_active'] ? 'success' : 'danger' ?>">
                                                    <?= $sub['is_active'] ? 'Actif' : 'Inactif' ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge rounded-pill bg-<?= $sub['is_confirmed'] ? 'success' : 'warning' ?>">
                                                    <?= $sub['is_confirmed'] ? 'Oui' : 'Non' ?>
                                                </span>
                                            </td>
                                            <td><?= format_date($sub['subscribed_at'], 'd/m/Y H:i') ?></td>
                                            <td>
                        <button class="btn btn-outline-primary btn-sm me-1 edit-subscriber-btn" data-id="<?= $sub['id'] ?>" title="Modifier">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <?php if (!$sub['is_confirmed']): ?>
                            <button class="btn btn-outline-success btn-sm me-1 confirm-subscriber-btn" data-id="<?= $sub['id'] ?>" title="Confirmer">
                                <i class="bi bi-check-circle"></i>
                            </button>
                        <?php endif; ?>
                        <button class="btn btn-outline-secondary btn-sm me-1 toggle-status-btn" data-id="<?= $sub['id'] ?>" title="Activer/Désactiver">
                            <i class="bi bi-<?= $sub['is_active'] ? 'slash-eye' : 'eye' ?>"></i>
                        </button>
                        <button class="btn btn-outline-danger btn-sm delete-subscriber-btn" data-id="<?= $sub['id'] ?>" title="Supprimer">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">
                                            <i class="bi bi-inbox fs-2 mb-2 d-block"></i>
                                            Aucun abonné pour le moment
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
                                            <a class="page-link" href="<?= url('admin/newsletter', array_merge(['action' => 'subscribers', 'page' => $page - 1], $search ? ['search' => $search] : [], $statusFilter !== null ? ['status' => $statusFilter] : [])) ?>" aria-label="Précédent">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                        <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                            <a class="page-link" href="<?= url('admin/newsletter', array_merge(['action' => 'subscribers', 'page' => $i], $search ? ['search' => $search] : [], $statusFilter !== null ? ['status' => $statusFilter] : [])) ?>"><?= $i ?></a>
                                        </li>
                                    <?php endfor; ?>
                                    
                                    <?php if ($page < $totalPages): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="<?= url('admin/newsletter', array_merge(['action' => 'subscribers', 'page' => $page + 1], $search ? ['search' => $search] : [], $statusFilter !== null ? ['status' => $statusFilter] : [])) ?>" aria-label="Suivant">
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

        <!-- TAB 3: CAMPAIGNS -->
        <?php if ($action === 'campaigns'): 
            $campaignStatus = $_GET['campaign_status'] ?? null;
            $campaigns = $newsletter->getAllCampaigns($page, 10, $campaignStatus);
            $totalCampaigns = $newsletter->getTotalCampaigns($campaignStatus);
            $totalPages = ceil($totalCampaigns / 10);
            $templates = $newsletter->getAllTemplates();
        ?>
            <div class="card border-0 shadow">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom-0 flex-wrap gap-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-send me-2 text-success"></i> Campagnes</h5>
                    <div class="d-flex gap-2 flex-wrap">
                        <div class="btn-group" role="group">
                            <a href="<?= url('admin/newsletter', ['action' => 'campaigns']) ?>" 
                               class="btn btn-outline-secondary btn-sm <?= !$campaignStatus ? 'active' : '' ?>">Toutes</a>
                            <a href="<?= url('admin/newsletter', ['action' => 'campaigns', 'campaign_status' => 'draft']) ?>" 
                               class="btn btn-outline-warning btn-sm <?= $campaignStatus === 'draft' ? 'active' : '' ?>">Brouillons</a>
                            <a href="<?= url('admin/newsletter', ['action' => 'campaigns', 'campaign_status' => 'sent']) ?>" 
                               class="btn btn-outline-success btn-sm <?= $campaignStatus === 'sent' ? 'active' : '' ?>">Envoyées</a>
                        </div>
                        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#campaignModal" data-mode="add">
                            <i class="bi bi-plus-lg me-1"></i> Nouvelle campagne
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Titre</th>
                                    <th>Sujet</th>
                                    <th>Statut</th>
                                    <th>Créée le</th>
                                    <th style="width: 200px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="campaigns-tbody">
                                <?php if (!empty($campaigns)): ?>
                                    <?php foreach ($campaigns as $camp): ?>
                                        <tr class="campaign-row" data-id="<?= $camp['id'] ?>">
                                            <td><?= $camp['id'] ?></td>
                                            <td class="fw-semibold"><?= e($camp['title']) ?></td>
                                            <td><?= e($camp['subject']) ?></td>
                                            <td>
                                                <?php 
                                                    $statusClasses = ['draft' => 'bg-warning', 'sending' => 'bg-info', 'sent' => 'bg-success', 'cancelled' => 'bg-danger'];
                                                    $statusLabels = ['draft' => 'Brouillon', 'sending' => 'Envoi en cours', 'sent' => 'Envoyée', 'cancelled' => 'Annulée'];
                                                ?>
                                                <span class="badge rounded-pill <?= $statusClasses[$camp['status']] ?? 'bg-secondary' ?>">
                                                    <?= $statusLabels[$camp['status']] ?? $camp['status'] ?>
                                                </span>
                                            </td>
                                            <td><?= format_date($camp['created_at'], 'd/m/Y H:i') ?></td>
                                            <td>
                                                <button class="btn btn-outline-info btn-sm me-1 view-campaign-btn" data-id="<?= $camp['id'] ?>" title="Voir">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <?php if ($camp['status'] === 'draft'): ?>
                                                    <button class="btn btn-outline-primary btn-sm me-1 edit-campaign-btn" data-id="<?= $camp['id'] ?>" title="Modifier">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <button class="btn btn-outline-success btn-sm me-1 send-campaign-btn" data-id="<?= $camp['id'] ?>" title="Envoyer">
                                                        <i class="bi bi-send"></i>
                                                    </button>
                                                <?php endif; ?>
                                                <button class="btn btn-outline-danger btn-sm delete-campaign-btn" data-id="<?= $camp['id'] ?>" title="Supprimer">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">
                                            <i class="bi bi-inbox fs-2 mb-2 d-block"></i>
                                            Aucune campagne pour le moment
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
                                            <a class="page-link" href="<?= url('admin/newsletter', array_merge(['action' => 'campaigns', 'page' => $page - 1], $campaignStatus ? ['campaign_status' => $campaignStatus] : [])) ?>" aria-label="Précédent">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                        <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                            <a class="page-link" href="<?= url('admin/newsletter', array_merge(['action' => 'campaigns', 'page' => $i], $campaignStatus ? ['campaign_status' => $campaignStatus] : [])) ?>"><?= $i ?></a>
                                        </li>
                                    <?php endfor; ?>
                                    
                                    <?php if ($page < $totalPages): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="<?= url('admin/newsletter', array_merge(['action' => 'campaigns', 'page' => $page + 1], $campaignStatus ? ['campaign_status' => $campaignStatus] : [])) ?>" aria-label="Suivant">
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

        <!-- TAB 4: TEMPLATES -->
        <?php if ($action === 'templates'): 
            $templates = $newsletter->getAllTemplates(false);
        ?>
            <div class="card border-0 shadow">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom-0">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-file-earmark-text me-2 text-success"></i> Modèles d'email</h5>
                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#templateModal" data-mode="add">
                        <i class="bi bi-plus-lg me-1"></i> Nouveau modèle
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Nom</th>
                                    <th>Statut</th>
                                    <th>Créé le</th>
                                    <th style="width: 180px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="templates-tbody">
                                <?php if (!empty($templates)): ?>
                                    <?php foreach ($templates as $tpl): ?>
                                        <tr class="template-row" data-id="<?= $tpl['id'] ?>">
                                            <td><?= $tpl['id'] ?></td>
                                            <td class="fw-semibold"><?= e($tpl['name']) ?></td>
                                            <td>
                                                <span class="badge rounded-pill bg-<?= $tpl['is_active'] ? 'success' : 'danger' ?>">
                                                    <?= $tpl['is_active'] ? 'Actif' : 'Inactif' ?>
                                                </span>
                                            </td>
                                            <td><?= format_date($tpl['created_at'], 'd/m/Y H:i') ?></td>
                                            <td>
                                                <button class="btn btn-outline-info btn-sm me-1 view-template-btn" data-id="<?= $tpl['id'] ?>" title="Voir">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button class="btn btn-outline-primary btn-sm me-1 edit-template-btn" data-id="<?= $tpl['id'] ?>" title="Modifier">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button class="btn btn-outline-danger btn-sm delete-template-btn" data-id="<?= $tpl['id'] ?>" title="Supprimer">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            <i class="bi bi-inbox fs-2 mb-2 d-block"></i>
                                            Aucun modèle pour le moment
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

<!-- Modal Subscriber -->
<div class="modal fade" id="subscriberModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="subscriberForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="subscriberModalTitle">Ajouter un abonné</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="subscriber-id">
                    <div class="mb-3">
                        <label for="subscriber-email" class="form-label">Email *</label>
                        <input type="email" class="form-control" id="subscriber-email" name="email" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="subscriber-first_name" class="form-label">Prénom</label>
                            <input type="text" class="form-control" id="subscriber-first_name" name="first_name">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="subscriber-last_name" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="subscriber-last_name" name="last_name">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-check mt-4">
                                <input type="checkbox" class="form-check-input" id="subscriber-is_active" name="is_active" checked>
                                <label class="form-check-label" for="subscriber-is_active">Actif</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-check mt-4">
                                <input type="checkbox" class="form-check-input" id="subscriber-is_confirmed" name="is_confirmed" checked>
                                <label class="form-check-label" for="subscriber-is_confirmed">Confirmé</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary" id="subscriber-submit-btn">
                        <i class="bi bi-check-lg me-1"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Campaign -->
<div class="modal fade" id="campaignModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="campaignForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="campaignModalTitle">Nouvelle campagne</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="campaign-id">
                    <div class="mb-3">
                        <label for="campaign-title" class="form-label">Titre *</label>
                        <input type="text" class="form-control" id="campaign-title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="campaign-subject" class="form-label">Sujet de l'email *</label>
                        <input type="text" class="form-control" id="campaign-subject" name="subject" required>
                    </div>
                    <?php if (!empty($templates)): ?>
                        <div class="mb-3">
                            <label for="campaign-template" class="form-label">Utiliser un modèle</label>
                            <select class="form-select" id="campaign-template">
                                <option value="">-- Sélectionnez un modèle --</option>
                                <?php foreach ($templates as $tpl): ?>
                                    <option value="<?= $tpl['id'] ?>"><?= e($tpl['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <label for="campaign-content" class="form-label">Contenu *</label>
                        <textarea class="form-control" id="campaign-content" name="content" rows="10" required></textarea>
                        <small class="text-muted">Variables disponibles: {FIRST_NAME}, {LAST_NAME}, {UNSUBSCRIBE_LINK}</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary" id="campaign-submit-btn">
                        <i class="bi bi-check-lg me-1"></i> Enregistrer en brouillon
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Template -->
<div class="modal fade" id="templateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="templateForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="templateModalTitle">Nouveau modèle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="template-id">
                    <div class="mb-3">
                        <label for="template-name" class="form-label">Nom *</label>
                        <input type="text" class="form-control" id="template-name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="template-content" class="form-label">Contenu *</label>
                        <textarea class="form-control" id="template-content" name="content" rows="10" required></textarea>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="template-is_active" name="is_active" checked>
                            <label class="form-check-label" for="template-is_active">Actif</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary" id="template-submit-btn">
                        <i class="bi bi-check-lg me-1"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal View Campaign -->
<div class="modal fade" id="viewCampaignModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Aperçu de la campagne</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="viewCampaignContent">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
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
const subscribersData = <?= json_encode($subscribers ?? []) ?>;
const templatesData = <?= json_encode($templates ?? []) ?>;

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
    // ==================== SUBSCRIBERS ====================
    const subscriberModalEl = document.getElementById('subscriberModal');
    const subscriberModal = new bootstrap.Modal(subscriberModalEl);
    
    subscriberModalEl?.addEventListener('show.bs.modal', function(e) {
        const mode = e.relatedTarget.getAttribute('data-mode');
        if (mode === 'edit') {
            const id = e.relatedTarget.getAttribute('data-id');
            const sub = subscribersData.find(s => s.id == id);
            if (sub) {
                document.getElementById('subscriberModalTitle').textContent = 'Modifier l\'abonné';
                document.getElementById('subscriber-id').value = sub.id;
                document.getElementById('subscriber-email').value = sub.email;
                document.getElementById('subscriber-first_name').value = sub.first_name || '';
                document.getElementById('subscriber-last_name').value = sub.last_name || '';
                document.getElementById('subscriber-is_active').checked = sub.is_active;
                document.getElementById('subscriber-is_confirmed').checked = sub.is_confirmed;
            }
        } else {
            document.getElementById('subscriberModalTitle').textContent = 'Ajouter un abonné';
            document.getElementById('subscriberForm').reset();
        }
    });
    
    document.getElementById('subscriberForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        const id = document.getElementById('subscriber-id').value;
        const formData = new FormData(this);
        formData.append('action', id ? 'update_subscriber' : 'add_subscriber');
        if (id) formData.append('id', id);
        
        try {
            const response = await fetch('<?= APP_URL ?>/index.php?p=ajax/newsletter', { method: 'POST', body: formData });
            const data = await response.json();
            if (data.success) {
                showToast('Succès', data.message, 'success');
                subscriberModal.hide();
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast('Erreur', data.message, 'error');
            }
        } catch (err) {
            showToast('Erreur', 'Une erreur est survenue', 'error');
        }
    });
    
    document.querySelectorAll('.edit-subscriber-btn')?.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const trigger = document.createElement('button');
            trigger.style.display = 'none';
            trigger.setAttribute('data-bs-toggle', 'modal');
            trigger.setAttribute('data-bs-target', '#subscriberModal');
            trigger.setAttribute('data-mode', 'edit');
            trigger.setAttribute('data-id', this.getAttribute('data-id'));
            document.body.appendChild(trigger);
            trigger.click();
            document.body.removeChild(trigger);
        });
    });
    
    document.querySelectorAll('.toggle-status-btn')?.forEach(btn => {
        btn.addEventListener('click', async function(e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            const formData = new FormData();
            formData.append('action', 'toggle_subscriber');
            formData.append('id', id);
            
            try {
                const response = await fetch('<?= APP_URL ?>/index.php?p=ajax/newsletter', { method: 'POST', body: formData });
                const data = await response.json();
                if (data.success) {
                    showToast('Succès', data.message, 'success');
                    setTimeout(() => location.reload(), 1000);
                }
            } catch (err) {
                showToast('Erreur', 'Une erreur est survenue', 'error');
            }
        });
    });
    
    document.querySelectorAll('.delete-subscriber-btn')?.forEach(btn => {
        btn.addEventListener('click', async function(e) {
            e.preventDefault();
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
                const id = this.getAttribute('data-id');
                const formData = new FormData();
                formData.append('action', 'delete_subscriber');
                formData.append('id', id);
                
                try {
                    const response = await fetch('<?= APP_URL ?>/index.php?p=ajax/newsletter', { method: 'POST', body: formData });
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
    
    document.querySelectorAll('.confirm-subscriber-btn')?.forEach(btn => {
        btn.addEventListener('click', async function(e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            const formData = new FormData();
            formData.append('action', 'confirm_subscriber');
            formData.append('id', id);
            
            try {
                const response = await fetch('<?= APP_URL ?>/index.php?p=ajax/newsletter', { method: 'POST', body: formData });
                const data = await response.json();
                if (data.success) {
                    showToast('Succès', data.message, 'success');
                    setTimeout(() => location.reload(), 1000);
                }
            } catch (err) {
                showToast('Erreur', 'Une erreur est survenue', 'error');
            }
        });
    });
    
    // ==================== CAMPAIGNS ====================
    const campaignModalEl = document.getElementById('campaignModal');
    const campaignModal = new bootstrap.Modal(campaignModalEl);
    
    document.getElementById('campaign-template')?.addEventListener('change', async function() {
        const tplId = this.value;
        if (tplId) {
            const tpl = templatesData.find(t => t.id == tplId);
            if (tpl) {
                document.getElementById('campaign-content').value = tpl.content;
            }
        }
    });
    
    campaignModalEl?.addEventListener('show.bs.modal', function(e) {
        const mode = e.relatedTarget.getAttribute('data-mode');
        if (mode === 'add') {
            document.getElementById('campaignModalTitle').textContent = 'Nouvelle campagne';
            document.getElementById('campaignForm').reset();
        }
    });
    
    document.getElementById('campaignForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('action', 'add_campaign');
        
        try {
            const response = await fetch('<?= APP_URL ?>/index.php?p=ajax/newsletter', { method: 'POST', body: formData });
            const data = await response.json();
            if (data.success) {
                showToast('Succès', data.message, 'success');
                campaignModal.hide();
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast('Erreur', data.message, 'error');
            }
        } catch (err) {
            showToast('Erreur', 'Une erreur est survenue', 'error');
        }
    });
    
    document.querySelectorAll('.view-campaign-btn')?.forEach(btn => {
        btn.addEventListener('click', async function(e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            const viewModal = new bootstrap.Modal(document.getElementById('viewCampaignModal'));
            viewModal.show();
            
            const formData = new FormData();
            formData.append('action', 'get_campaign');
            formData.append('id', id);
            
            try {
                const response = await fetch('<?= APP_URL ?>/index.php?p=ajax/newsletter', { method: 'POST', body: formData });
                const data = await response.json();
                if (data.success && data.campaign) {
                    document.getElementById('viewCampaignContent').innerHTML = `
                        <h5>${escapeHtml(data.campaign.title)}</h5>
                        <p><strong>Sujet:</strong> ${escapeHtml(data.campaign.subject)}</p>
                        <hr>
                        <div class="border p-3">${data.campaign.content}</div>
                    `;
                }
            } catch (err) {
                document.getElementById('viewCampaignContent').innerHTML = '<p class="text-danger">Erreur de chargement</p>';
            }
        });
    });
    
    document.querySelectorAll('.send-campaign-btn')?.forEach(btn => {
        btn.addEventListener('click', async function(e) {
            e.preventDefault();
            const result = await Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: 'Cette action enverra la campagne à tous les abonnés actifs',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Oui, envoyer',
                cancelButtonText: 'Annuler'
            });
            
            if (result.isConfirmed) {
                const id = this.getAttribute('data-id');
                const formData = new FormData();
                formData.append('action', 'send_campaign');
                formData.append('id', id);
                
                try {
                    const response = await fetch('<?= APP_URL ?>/index.php?p=ajax/newsletter', { method: 'POST', body: formData });
                    const data = await response.json();
                    if (data.success) {
                        showToast('Succès', data.message, 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showToast('Erreur', data.message, 'error');
                    }
                } catch (err) {
                    showToast('Erreur', 'Une erreur est survenue', 'error');
                }
            }
        });
    });
    
    document.querySelectorAll('.delete-campaign-btn')?.forEach(btn => {
        btn.addEventListener('click', async function(e) {
            e.preventDefault();
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
                const id = this.getAttribute('data-id');
                const formData = new FormData();
                formData.append('action', 'delete_campaign');
                formData.append('id', id);
                
                try {
                    const response = await fetch('<?= APP_URL ?>/index.php?p=ajax/newsletter', { method: 'POST', body: formData });
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
    
    // ==================== TEMPLATES ====================
    const templateModalEl = document.getElementById('templateModal');
    const templateModal = new bootstrap.Modal(templateModalEl);
    
    templateModalEl?.addEventListener('show.bs.modal', function(e) {
        const mode = e.relatedTarget.getAttribute('data-mode');
        if (mode === 'edit') {
            const id = e.relatedTarget.getAttribute('data-id');
            const tpl = templatesData.find(t => t.id == id);
            if (tpl) {
                document.getElementById('templateModalTitle').textContent = 'Modifier le modèle';
                document.getElementById('template-id').value = tpl.id;
                document.getElementById('template-name').value = tpl.name;
                document.getElementById('template-content').value = tpl.content;
                document.getElementById('template-is_active').checked = tpl.is_active;
            }
        } else {
            document.getElementById('templateModalTitle').textContent = 'Nouveau modèle';
            document.getElementById('templateForm').reset();
        }
    });
    
    document.getElementById('templateForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        const id = document.getElementById('template-id').value;
        const formData = new FormData(this);
        formData.append('action', id ? 'update_template' : 'add_template');
        if (id) formData.append('id', id);
        
        try {
            const response = await fetch('<?= APP_URL ?>/index.php?p=ajax/newsletter', { method: 'POST', body: formData });
            const data = await response.json();
            if (data.success) {
                showToast('Succès', data.message, 'success');
                templateModal.hide();
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast('Erreur', data.message, 'error');
            }
        } catch (err) {
            showToast('Erreur', 'Une erreur est survenue', 'error');
        }
    });
    
    document.querySelectorAll('.edit-template-btn')?.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const trigger = document.createElement('button');
            trigger.style.display = 'none';
            trigger.setAttribute('data-bs-toggle', 'modal');
            trigger.setAttribute('data-bs-target', '#templateModal');
            trigger.setAttribute('data-mode', 'edit');
            trigger.setAttribute('data-id', this.getAttribute('data-id'));
            document.body.appendChild(trigger);
            trigger.click();
            document.body.removeChild(trigger);
        });
    });
    
    document.querySelectorAll('.delete-template-btn')?.forEach(btn => {
        btn.addEventListener('click', async function(e) {
            e.preventDefault();
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
                const id = this.getAttribute('data-id');
                const formData = new FormData();
                formData.append('action', 'delete_template');
                formData.append('id', id);
                
                try {
                    const response = await fetch('<?= APP_URL ?>/index.php?p=ajax/newsletter', { method: 'POST', body: formData });
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

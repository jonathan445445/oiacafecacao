<?php
require_once __DIR__ . '/../includes/init.php';
require_login();

$page_title = 'Prix et Tendance';
$priceTrendModel = new PriceTrend();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Jeton CSRF invalide.';
            redirect(url('admin/price-trends'));
        }

        $action = $_POST['action'] ?? 'save_product';
        $id = (int)($_POST['id'] ?? 0);

        if ($action === 'delete') {
            if ($id) {
                $priceTrendModel->delete($id);
                $_SESSION['success'] = 'Produit supprime.';
            }
            redirect(url('admin/price-trends'));
        }

        if ($action === 'add_price') {
            $productId = (int)($_POST['price_trend_id'] ?? 0);
            $product = $priceTrendModel->findById($productId);

            if (!$product) {
                $_SESSION['error'] = 'Produit introuvable.';
                redirect(url('admin/price-trends'));
            }

            $nationalPrice = clean_input($_POST['national_price'] ?? '');
            $internationalPrice = clean_input($_POST['international_price'] ?? '');
            $applicationDate = clean_input($_POST['application_date'] ?? date('Y-m-d'));

            if ($nationalPrice === '' || $internationalPrice === '' || $applicationDate === '') {
                $_SESSION['error'] = 'Le produit, les deux prix et la date d\'application sont obligatoires.';
                redirect(url('admin/price-trends'));
            }

            $priceTrendModel->addPriceHistory([
                'price_trend_id' => $productId,
                'national_price' => $nationalPrice,
                'international_price' => $internationalPrice,
                'application_date' => $applicationDate,
                'comment' => clean_input($_POST['comment'] ?? ''),
            ]);

            $_SESSION['success'] = 'Nouveau prix historique enregistre.';
            redirect(url('admin/price-trends'));
        }

        $name = clean_input($_POST['name'] ?? '');
        if ($name === '') {
            $_SESSION['error'] = 'Le nom est obligatoire.';
            redirect(url('admin/price-trends'));
        }

        $data = [
            'name' => $name,
            'description' => clean_input($_POST['description'] ?? ''),
            'status' => ($_POST['status'] ?? 'active') === 'inactive' ? 'inactive' : 'active',
        ];

        if ($id) {
            $priceTrendModel->update($id, $data);
            $_SESSION['success'] = 'Produit mis a jour.';
        } else {
            $priceTrendModel->create($data);
            $_SESSION['success'] = 'Produit ajoute.';
        }

        redirect(url('admin/price-trends'));
    } catch (Exception $e) {
        $_SESSION['error'] = 'Erreur: ' . $e->getMessage();
        redirect(url('admin/price-trends'));
    }
}

$search = clean_input($_GET['q'] ?? '');
$historySearch = clean_input($_GET['history_q'] ?? '');
$productFilter = (int)($_GET['product_id'] ?? 0);
$dateFrom = clean_input($_GET['date_from'] ?? '');
$dateTo = clean_input($_GET['date_to'] ?? '');
$sort = ($_GET['sort'] ?? 'desc') === 'asc' ? 'asc' : 'desc';

$items = $priceTrendModel->findAll($search);
$productOptions = $priceTrendModel->findAll('');
$currentItems = $priceTrendModel->getCurrentPrices(false, $search);
$history = $priceTrendModel->getHistory([
    'search' => $historySearch,
    'product_id' => $productFilter,
    'date_from' => $dateFrom,
    'date_to' => $dateTo,
    'sort' => $sort,
]);
$stats = $priceTrendModel->getDashboardStats();

require_once __DIR__ . '/layouts/header.php';
?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= e($_SESSION['success']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= e($_SESSION['error']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<div class="row g-3 mb-4">
    <div class="col-md-4 col-xl-2">
        <div class="card border-left-primary h-100">
            <div class="card-body">
                <div class="text-muted small">Produits suivis</div>
                <div class="h4 mb-0"><?= (int)$stats['tracked_products'] ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-xl-2">
        <div class="card border-left-info h-100">
            <div class="card-body">
                <div class="text-muted small">Derniere mise a jour</div>
                <div class="h6 mb-0"><?= $stats['last_update'] ? format_date($stats['last_update'], 'd/m/Y') : 'Aucune' ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-xl-2">
        <div class="card border-left-success h-100">
            <div class="card-body">
                <div class="text-muted small">Plus forte hausse</div>
                <div class="h6 mb-0"><?= e($stats['max_increase']['name'] ?? 'Aucune') ?></div>
                <small class="text-success"><?= $priceTrendModel->formatVariation($stats['max_increase']['percent'] ?? 0, '%') ?></small>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-xl-2">
        <div class="card border-left-warning h-100">
            <div class="card-body">
                <div class="text-muted small">Plus forte baisse</div>
                <div class="h6 mb-0"><?= e($stats['max_decrease']['name'] ?? 'Aucune') ?></div>
                <small class="text-danger"><?= $priceTrendModel->formatVariation($stats['max_decrease']['percent'] ?? 0, '%') ?></small>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-xl-2">
        <div class="card h-100">
            <div class="card-body">
                <div class="text-muted small">Moy. national</div>
                <div class="h6 mb-0"><?= $priceTrendModel->formatVariation($stats['avg_national'], '%') ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-xl-2">
        <div class="card h-100">
            <div class="card-body">
                <div class="text-muted small">Moy. international</div>
                <div class="h6 mb-0"><?= $priceTrendModel->formatVariation($stats['avg_international'], '%') ?></div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <h6 class="m-0 font-weight-bold" style="color: #5b2d00;">
            <i class="bi bi-graph-up-arrow me-2"></i>
            Produits et prix du jour
        </h6>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#priceHistoryModal" onclick="resetPriceHistoryForm()">
                <i class="bi bi-cash-coin"></i> Saisir un prix
            </button>
            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#priceTrendModal" onclick="resetPriceTrendForm()">
                <i class="bi bi-plus"></i> Nouveau produit
            </button>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" action="<?= url('admin/price-trends') ?>" class="row g-2 mb-4">
            <div class="col-md-10">
                <input type="search" name="q" class="form-control" value="<?= e($search) ?>" placeholder="Rechercher par nom ou description">
            </div>
            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Rechercher
                </button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>Produit</th>
                        <th>Prix national actuel</th>
                        <th>Prix international actuel</th>
                        <th>Mise a jour</th>
                        <th>Tendance nationale</th>
                        <th>Statut</th>
                        <th style="width: 180px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($currentItems): ?>
                        <?php foreach ($currentItems as $item): ?>
                            <?php
                                $current = $item['current_price'];
                                $national = $item['analysis']['national'];
                                $meta = $priceTrendModel->trendMeta($national['trend']);
                            ?>
                            <tr>
                                <td>
                                    <strong><?= e($item['name']) ?></strong>
                                    <br><small class="text-muted"><?= e($item['slug']) ?></small>
                                </td>
                                <td><?= $current ? $priceTrendModel->formatPrice($current['national_price']) . ' FCFA' : 'Non renseigne' ?></td>
                                <td><?= $current ? $priceTrendModel->formatPrice($current['international_price']) : 'Non renseigne' ?></td>
                                <td><?= $current ? format_date($current['application_date'], 'd/m/Y') : '-' ?></td>
                                <td>
                                    <span class="badge bg-<?= e($meta['class']) ?>">
                                        <i class="bi <?= e($meta['icon']) ?> me-1"></i><?= e($meta['label']) ?>
                                    </span>
                                    <small class="d-block text-muted"><?= $priceTrendModel->formatVariation($national['percent'], '%') ?></small>
                                </td>
                                <td>
                                    <span class="badge rounded-pill bg-<?= $item['status'] === 'active' ? 'success' : 'secondary' ?>">
                                        <?= $item['status'] === 'active' ? 'Actif' : 'Inactif' ?>
                                    </span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#priceHistoryModal" onclick="preparePriceHistoryForm(<?= (int)$item['id'] ?>)">
                                        <i class="bi bi-cash-coin"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#priceTrendModal" onclick="editPriceTrend(<?= (int)$item['id'] ?>)">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ? L historique lie sera aussi supprime.')">
                                        <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= (int)$item['id'] ?>">
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
                                <i class="bi bi-graph-up display-1 mb-3 d-block"></i>
                                Aucun produit trouve
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold" style="color: #5b2d00;">
            <i class="bi bi-clock-history me-2"></i>
            Historique complet des prix
        </h6>
    </div>
    <div class="card-body">
        <form method="GET" action="<?= url('admin/price-trends') ?>" class="row g-2 mb-4">
            <div class="col-md-3">
                <input type="search" name="history_q" class="form-control" value="<?= e($historySearch) ?>" placeholder="Recherche">
            </div>
            <div class="col-md-3">
                <select name="product_id" class="form-select">
                    <option value="0">Tous les produits</option>
                    <?php foreach ($productOptions as $item): ?>
                        <option value="<?= (int)$item['id'] ?>" <?= $productFilter === (int)$item['id'] ? 'selected' : '' ?>>
                            <?= e($item['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="date_from" class="form-control" value="<?= e($dateFrom) ?>">
            </div>
            <div class="col-md-2">
                <input type="date" name="date_to" class="form-control" value="<?= e($dateTo) ?>">
            </div>
            <div class="col-md-1">
                <select name="sort" class="form-select">
                    <option value="desc" <?= $sort === 'desc' ? 'selected' : '' ?>>Desc</option>
                    <option value="asc" <?= $sort === 'asc' ? 'selected' : '' ?>>Asc</option>
                </select>
            </div>
            <div class="col-md-1 d-grid">
                <button type="submit" class="btn btn-primary"><i class="bi bi-funnel"></i></button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>Date</th>
                        <th>Produit</th>
                        <th>Prix national</th>
                        <th>Prix international</th>
                        <th>Evolution (%)</th>
                        <th>Evolution (montant)</th>
                        <th>Tendance</th>
                        <th>Commentaire</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($history): ?>
                        <?php foreach ($history as $row): ?>
                            <?php
                                $nationalAnalysis = $row['analysis']['national'];
                                $internationalAnalysis = $row['analysis']['international'];
                                $nationalMeta = $priceTrendModel->trendMeta($nationalAnalysis['trend']);
                                $internationalMeta = $priceTrendModel->trendMeta($internationalAnalysis['trend']);
                            ?>
                            <tr>
                                <td><?= format_date($row['application_date'], 'd/m/Y') ?></td>
                                <td><?= e($row['product_name']) ?></td>
                                <td><?= $priceTrendModel->formatPrice($row['national_price']) ?> FCFA</td>
                                <td><?= $priceTrendModel->formatPrice($row['international_price']) ?></td>
                                <td>
                                    <div>Nat. <?= $priceTrendModel->formatVariation($nationalAnalysis['percent'], '%') ?></div>
                                    <div class="text-muted small">Intl. <?= $priceTrendModel->formatVariation($internationalAnalysis['percent'], '%') ?></div>
                                </td>
                                <td>
                                    <div>Nat. <?= $priceTrendModel->formatVariation($nationalAnalysis['amount'], ' FCFA') ?></div>
                                    <div class="text-muted small">Intl. <?= $priceTrendModel->formatVariation($internationalAnalysis['amount']) ?></div>
                                </td>
                                <td>
                                    <span class="badge bg-<?= e($nationalMeta['class']) ?> mb-1">
                                        Nat. <i class="bi <?= e($nationalMeta['icon']) ?> mx-1"></i><?= e($nationalMeta['label']) ?>
                                    </span>
                                    <span class="badge bg-<?= e($internationalMeta['class']) ?>">
                                        Intl. <i class="bi <?= e($internationalMeta['icon']) ?> mx-1"></i><?= e($internationalMeta['label']) ?>
                                    </span>
                                </td>
                                <td><?= e($row['comment'] ?: '-') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">Aucun historique disponible.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="priceTrendModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="priceTrendModalTitle">Nouveau produit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
                    <input type="hidden" name="action" value="save_product">
                    <input type="hidden" name="id" id="price_trend_id">

                    <div class="mb-3">
                        <label class="form-label">Produit <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="price_trend_name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" id="price_trend_description" class="form-control" rows="4"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Statut</label>
                        <select name="status" id="price_trend_status" class="form-select">
                            <option value="active">Actif</option>
                            <option value="inactive">Inactif</option>
                        </select>
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

<div class="modal fade" id="priceHistoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Saisir un nouveau prix</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
                    <input type="hidden" name="action" value="add_price">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Produit <span class="text-danger">*</span></label>
                            <select name="price_trend_id" id="history_price_trend_id" class="form-select" required>
                                <option value="">Choisir un produit</option>
                                <?php foreach ($productOptions as $item): ?>
                                    <option value="<?= (int)$item['id'] ?>"><?= e($item['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date d'application <span class="text-danger">*</span></label>
                            <input type="date" name="application_date" id="history_application_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Prix national <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" name="national_price" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Prix international <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" name="international_price" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Commentaire</label>
                            <textarea name="comment" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">Enregistrer le prix</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const priceTrendItems = <?= json_encode($productOptions, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;

function resetPriceTrendForm() {
    document.getElementById('priceTrendModalTitle').textContent = 'Nouveau produit';
    document.getElementById('price_trend_id').value = '';
    document.getElementById('price_trend_name').value = '';
    document.getElementById('price_trend_description').value = '';
    document.getElementById('price_trend_status').value = 'active';
}

function editPriceTrend(id) {
    const item = priceTrendItems.find(entry => Number(entry.id) === Number(id));
    if (!item) return;

    document.getElementById('priceTrendModalTitle').textContent = 'Modifier ' + item.name;
    document.getElementById('price_trend_id').value = item.id;
    document.getElementById('price_trend_name').value = item.name || '';
    document.getElementById('price_trend_description').value = item.description || '';
    document.getElementById('price_trend_status').value = item.status || 'active';
}

function resetPriceHistoryForm() {
    document.getElementById('history_price_trend_id').value = '';
    document.getElementById('history_application_date').value = '<?= date('Y-m-d') ?>';
}

function preparePriceHistoryForm(id) {
    resetPriceHistoryForm();
    document.getElementById('history_price_trend_id').value = id;
}
</script>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>

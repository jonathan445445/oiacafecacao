<?php
require_once __DIR__ . '/../includes/init.php';
require_login();

$db_available = true;
$stats = [
    'users' => 0,
    'articles' => 0,
    'published' => 0,
    'categories' => 0
];
$recent_articles = [];

try {
    $user = new User();
    $article = new Article();
    $category = new Category();
    $filiere = new Filiere();
    
    $stats = [
        'users' => $user->count(),
        'articles' => $article->countAll(),
        'published' => $article->count('published'),
        'categories' => $category->count(),
        'filieres' => $filiere->count()
    ];
    $recent_articles = $article->findAllAdmin(1, 5);
} catch (Exception $e) {
    $db_available = false;
}

$page_title = 'Tableau de bord';
require_once __DIR__ . '/layouts/header.php';
?>

<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0 text-gray-800">Tableau de bord</h1>
        </div>
    </div>
</div>

<?php if (!$db_available): ?>
    <div class="alert alert-warning">
        <h4 class="alert-heading">⚠️ Base de données non installée</h4>
        <p>Veuillez d'abord installer la base de données.</p>
        <a href="../install.php" class="btn btn-success">Installer maintenant</a>
    </div>
<?php endif; ?>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Utilisateurs</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['users'] ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-people fs-2 text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Articles publiés</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['published'] ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-newspaper fs-2 text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total articles</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['articles'] ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-file-alt fs-2 text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Catégories</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['categories'] ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-folder fs-2 text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-left-secondary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Filières</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['filieres'] ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-diagram-3 fs-2 text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Articles récents</h6>
        <a href="articles.php" class="btn btn-primary btn-sm">Voir tous</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Catégorie</th>
                        <th>Auteur</th>
                        <th>Statut</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($db_available && !empty($recent_articles)): ?>
                        <?php foreach ($recent_articles as $art): ?>
                            <tr>
                                <td><?= e($art['title']) ?></td>
                                <td><?= e($art['category_name'] ?? '-') ?></td>
                                <td><?= e($art['author_name'] ?? '-') ?></td>
                                <td>
                                    <span class="badge bg-<?= ($art['status'] ?? '') === 'published' ? 'success' : 'warning' ?>">
                                        <?= ucfirst($art['status'] ?? '-') ?>
                                    </span>
                                </td>
                                <td><?= format_date($art['created_at'] ?? date('Y-m-d H:i:s')) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center text-muted py-4">Aucun article pour l'instant</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>

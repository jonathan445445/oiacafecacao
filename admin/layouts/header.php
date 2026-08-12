<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? e($page_title) . ' | ' : '' ?>Administration - OIA Café-Cacao</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-color: #5b2d00;
            --sidebar-width: 250px;
        }
        
        body {
            background: #f8f9fc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, var(--primary-color) 0%, #3a1d00 100%);
            color: white;
            z-index: 100;
            overflow-y: auto;
        }
        
        .sidebar-brand {
            padding: 1.5rem;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-nav {
            padding: 1rem 0;
        }
        
        .sidebar-nav .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 0.8rem 1.5rem;
            display: flex;
            align-items: center;
            transition: all 0.3s;
        }
        
        .sidebar-nav .nav-link:hover,
        .sidebar-nav .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.1);
        }
        
        .sidebar-nav .nav-link i {
            width: 25px;
            margin-right: 10px;
        }
        
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }
        
        .topbar {
            background: white;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58,59,69,0.15);
            padding: 0.75rem 1.5rem;
        }
        
        .card {
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58,59,69,0.1);
        }
        
        .card-header {
            background: white;
            border-bottom: 1px solid #e3e6f0;
        }
        
        .border-left-primary {
            border-left: 0.25rem solid #4e73df !important;
        }
        
        .border-left-success {
            border-left: 0.25rem solid #1cc88a !important;
        }
        
        .border-left-info {
            border-left: 0.25rem solid #36b9cc !important;
        }
        
        .border-left-warning {
            border-left: 0.25rem solid #f6c23e !important;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <h5 class="fw-bold mb-0">OIA Café-Cacao</h5>
            <small class="text-light">Administration</small>
        </div>
        
        <div class="sidebar-nav">
            <a class="nav-link active" href="<?= url('admin') ?>">
                <i class="bi bi-speedometer2"></i>
                Tableau de bord
            </a>
            <a class="nav-link" href="<?= url('admin/contacts') ?>">
                <i class="bi bi-chat-left-text me-1"></i>
                Gestion des contacts
            </a>
            <a class="nav-link" href="<?= url('admin/newsletter') ?>">
                <i class="bi bi-envelope-paper me-1"></i>
                Newsletter
            </a>
            <a class="nav-link" href="<?= url('admin/articles') ?>">
                <i class="bi bi-newspaper"></i>
                Articles
            </a>
            <a class="nav-link" href="<?= url('admin/photos') ?>">
                <i class="bi bi-images"></i>
                Photos
            </a>
            <a class="nav-link" href="<?= url('admin/videos') ?>">
                <i class="bi bi-film"></i>
                Vidéos
            </a>
            <a class="nav-link" href="<?= url('admin/press-book') ?>">
                <i class="bi bi-book"></i>
                Press Book
            </a>
            <a class="nav-link" href="<?= url('admin/banners') ?>">
                <i class="bi bi-card-image"></i>
                Bannières
            </a>
            <a class="nav-link" href="<?= url('admin/agenda') ?>">
                <i class="bi bi-calendar-event"></i>
                Agenda
            </a>
            <a class="nav-link" href="<?= url('admin/actes') ?>">
                <i class="bi bi-file-earmark-pdf"></i>
                Actes de l'OIA
            </a>
            <a class="nav-link" href="<?= url('admin/documents') ?>">
                <i class="bi bi-file-earmark-text"></i>
                Documents
            </a>
            <a class="nav-link" href="<?= url('admin/filieres') ?>">
                <i class="bi bi-diagram-3"></i>
                Filières
            </a>
            <a class="nav-link" href="<?= url('admin/operators') ?>">
                <i class="bi bi-people"></i>
                Acheteurs / Opérateurs
            </a>
            <a class="nav-link" href="<?= url('admin/colleges') ?>">
                <i class="bi bi-building"></i>
                Collèges
            </a>
            <a class="nav-link" href="<?= url('admin/categories') ?>">
                <i class="bi bi-folder"></i>
                Catégories
            </a>
            <a class="nav-link" href="<?= url('admin/organisations') ?>">
                <i class="bi bi-building"></i>
                Organisations
            </a>
            <a class="nav-link" href="<?= url('admin/partners') ?>">
                <i class="bi bi-handshake"></i>
                Partenaires
            </a>
            <a class="nav-link" href="<?= url('admin/projects-list') ?>">
                <i class="bi bi-kanban"></i>
                Projets
            </a>
            <a class="nav-link" href="<?= url('admin/price-trends') ?>">
                <i class="bi bi-graph-up-arrow"></i>
                Prix et Tendance
            </a>
            <a class="nav-link" href="<?= url('admin/users') ?>">
                <i class="bi bi-people"></i>
                Utilisateurs
            </a>
            <a class="nav-link" href="<?= url('admin/settings') ?>">
                <i class="bi bi-gear"></i>
                Paramètres
            </a>
            <a class="nav-link" href="<?= url('admin/settings-presentation') ?>">
                <i class="bi bi-journal-text"></i>
                Présentations
            </a>
            <hr class="mx-3 my-2 border-light opacity-25">
            <a class="nav-link" href="<?= url() ?>" target="_blank">
                <i class="bi bi-eye"></i>
                Voir le site
            </a>
        </div>
    </nav>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar d-flex justify-content-between align-items-center">
            <button class="btn d-md-none" id="sidebarToggle">
                <i class="bi bi-list fs-4"></i>
            </button>
            
            <h5 class="mb-0 d-none d-md-block"><?= isset($page_title) ? e($page_title) : 'Administration' ?></h5>
            
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-person"></i> <?= isset($_SESSION['user_name']) ? e($_SESSION['user_name']) : 'Admin' ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="<?= APP_URL ?>/admin/logout.php"><i class="bi bi-box-arrow-right"></i> Déconnexion</a></li>
                </ul>
            </div>
        </div>
        
        <div class="container-fluid p-4">

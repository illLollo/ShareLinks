<head>
    <link rel="stylesheet" href="<?= base_url('/Style/headerStyle.css') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<header class="header fixed-top bg-success text-white shadow-sm" style="z-index: 1;">
    <div class="container d-flex justify-content-between align-items-center py-2">
        <div class="logo d-flex align-items-center">
            <a href="<?= base_url('/') ?>" class="text-decoration-none text-white">
                <img src="<?= base_url('/Resources/imgs/ShareLinks_LOGO.png') ?>" alt="Logo" class="me-2" style="height: 40px;">
                <span class="fs-4 fw-bold">SL</span>
            </a>
        </div>
<?php

if (session()->has('userId')): ?>
        <div class="user-menu dropdown">
            <a href="" class="d-flex align-items-center text-decoration-none dropdown-toggle text-white" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle fs-4 me-2"></i> <!-- Icona utente -->
                <span>Benvenuto, <?= $user->name ?></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end text-small shadow" aria-labelledby="userDropdown">
                <li><a class="dropdown-item" href="<?= base_url('/profile') ?>">Il tuo Profilo</a></li>
                <?php if ($driver): ?>
                    <li><a class="dropdown-item" href="<?= base_url('/driver') ?>">Il tuo profilo da Guidatore</a></li>
                    <li><a class="dropdown-item" href="<?= base_url('/cars') ?>">Le tue auto</a></li>
                <?php else: ?>
                    <li><a class="dropdown-item" href="<?= base_url('/driver/registerDriverLicense') ?>">Crea il tuo profilo da guidatore</a></li>
                <?php endif; ?>
                <li><a class="dropdown-item" href="<?= base_url('/settings') ?>">Impostazioni</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="<?= base_url('/Login/logout') ?>" method="post" class="m-0">
                        <button type="submit" class="dropdown-item text-danger">Esci</button>
                    </form>
                </li>
            </ul>
        </div>
        <?php endif; ?>
    </div>
</header>
<style>
    body {
        font-family: 'Roboto', sans-serif;
    }
    .header {
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    .user-menu {
        position: relative;
    }
    .dropdown-item.text-danger {
        color: #dc3545 !important;
    }
    .dropdown-item.text-danger:hover {
        background-color: #f8d7da !important;
        color: #721c24 !important;
    }
    .user-menu:hover .dropdown-menu {
        display: block;
    }
    .user-menu .dropdown-menu {
        display: none;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: opacity 0.3s ease, transform 0.3s ease;
    }

    .user-menu:hover .dropdown-menu {
        display: block;
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    @keyframes dropdownFadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes dropdownFadeOut {
        from {
            opacity: 1;
            transform: translateY(0);
        }
        to {
            opacity: 0;
            transform: translateY(-10px);
        }
    }

    .user-menu {
        position: relative;
    }

    .user-menu .dropdown-menu {
        display: none;
        opacity: 0;
        visibility: hidden;
        animation: dropdownFadeOut 0.3s ease-in-out;
    }

    .user-menu:hover .dropdown-menu {
        display: block;
        opacity: 1;
        visibility: visible;
        animation: dropdownFadeIn 0.3s ease-in-out;
    }
</style>
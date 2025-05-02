<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<style>
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
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top" style="z-index: 5; height: 8vh;">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="<?= base_url('/') ?>">
            <img src="<?= base_url("/Resources/imgs/ShareLinks_LOGO.png") ?>" alt="ShareLinks Logo" class="me-2"
                style="height: 40px;">
            <span class="fw-bold">ShareLinks</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        <?php if (session()->has('userId')): ?>
            <div class="user-menu dropdown">
            <div class="d-flex align-items-center dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="me-3 d-none d-sm-inline">Ciao, <strong><?= $user->name ?></strong></span>
                <img src="https://randomuser.me/api/portraits/men/32.jpg" class="rounded-circle" width="40" height="40" alt="User">
            </div>

                <ul class="dropdown-menu dropdown-menu-end text-small shadow" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="<?= base_url('/homepage') ?>">Homepage</a></li>
                    <li><a class="dropdown-item" href="<?= base_url('/profile') ?>">Il tuo Profilo</a></li>
                    <?php if ($driver): ?>
                        <li><a class="dropdown-item" href="<?= base_url('/driver') ?>">Il tuo profilo da Guidatore</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('/cars') ?>">Le tue auto</a></li>
                    <?php else: ?>
                        <li><a class="dropdown-item" href="<?= base_url('/driver/registerDriverLicense') ?>">Crea il tuo profilo da
                                guidatore</a></li>
                    <?php endif; ?>
                    <li><a class="dropdown-item" href="<?= base_url('/settings') ?>">Impostazioni</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <form action="<?= base_url('/Login/logout') ?>" method="post" class="m-0">
                            <button type="submit" class="dropdown-item text-danger">Esci</button>
                        </form>
                    </li>
                </ul>
            </div>
        <?php endif; ?>
    </div>

</nav>
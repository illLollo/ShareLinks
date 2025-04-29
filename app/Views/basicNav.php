<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top" style="z-index: 1;">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="<?= base_url('/') ?>">
                <img src="<?= base_url("/Resources/imgs/ShareLinks_LOGO.png") ?>" alt="ShareLinks Logo" class="me-2" style="height: 40px;">
                <span class="fw-bold">ShareLinks</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#comeFunziona">Come Funziona</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            Servizi
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Guidatore</a></li>
                            <li><a class="dropdown-item" href="#">Passeggero</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#">Risparmio CO₂</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tellAboutUs">Cosa dicono di noi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contatti">Contatti</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <a href=" <?= base_url("/login") ?>" class="btn btn-outline-success me-2">Accedi</a>
                    <a href=" <?= base_url("/register") ?>" class="btn btn-success">Registrati</a>
                </div>
            </div>
        </div>
    </nav>
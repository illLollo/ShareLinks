<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SL - ShareLinks</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src=" <?= base_url("/Script/observer.js"); ?>"></script>
    <style>
       :root {
            --primary-color: #2E8B57;
            --secondary-color: #3AA76D;
            --dark-color: #1A3A2A;
            --light-color: #F8F9FA;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar-brand img { height: 40px; }
        .btn-primary {
            background-color: var(--primary-color);
            border: none;
        }
        .btn-primary:hover {
            background-color: var(--secondary-color);
        }
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: white;
        }
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('hero-background.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
        }
        .feature-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        .how-it-works {
            background-color: var(--light-color);
        }
        .stats-section {
            background-color: var(--dark-color);
            color: white;
        }
        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }
        input[type="radio"]:checked {
            background-color: green;
            border-color: green;
        }

        input[type="text"]:focus {
           border-color: green;
        }
    </style>
</head>
<body>
<link rel="stylesheet" href="<?= base_url("/Style/headerStyle.css") ?>">

<div class="fade-in">
    <!-- Hero Section -->
    <section class="hero-section text-center fade-in" style="background-image: url(<?= base_url('/Resources/imgs/darkGmaps.png'); ?>); background-size: cover; background-position: center; background-color: rgba(0, 0, 0, 0.3); background-blend-mode: darken;">
        <div class="container py-5">
            <h1 class="display-4 fw-bold mb-4">Condividi il viaggio, riduci l'impatto</h1>
            <p class="lead mb-5">Unisciti alla rivoluzione della mobilità sostenibile e risparmia condividendo i tuoi spostamenti.</p>
            <div class="row g-3 justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="userType" id="driverType" checked>
                                        <label class="form-check-label" for="driverType">
                                            Sono un guidatore
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="userType" id="passengerType">
                                        <label class="form-check-label" for="passengerType">
                                            Sono un passeggero
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row g-2">
                                <div class="col-md-5">
                                    <input type="text" class="form-control" placeholder="Partenza">
                                </div>
                                <div class="col-md-5">
                                    <input type="text" class="form-control" placeholder="Destinazione">
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-success w-100">Cerca</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5 fade-in">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Perché scegliere ShareLink</h2>
                <p class="lead text-muted">La soluzione intelligente per muoverti in modo sostenibile</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="text-center p-4">
                        <div class="feature-icon">
                            <i class="fas fa-leaf"></i>
                        </div>
                        <h3>Riduci la CO₂</h3>
                        <p class="text-muted">Condividendo i viaggi contribuisci attivamente a ridurre le emissioni e il traffico.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center p-4">
                        <div class="feature-icon">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <h3>Risparmia</h3>
                        <p class="text-muted">Dividi i costi del viaggio con altri passeggeri e risparmia sui tuoi spostamenti.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center p-4">
                        <div class="feature-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h3>Guadagna tempo</h3>
                        <p class="text-muted">Meno traffico significa tempi di percorrenza più brevi per tutti.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="how-it-works py-5 fade-in" id="comeFunziona">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Come funziona</h2>
                <p class="lead text-muted">Inizia a usare ShareLink in pochi semplici passaggi</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px; font-size: 1.5rem; margin-bottom: 1rem;">
                                1
                            </div>
                            <h4>Registrati</h4>
                            <p class="text-muted">Crea il tuo account come guidatore o passeggero in pochi minuti.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px; font-size: 1.5rem; margin-bottom: 1rem;">
                                2
                            </div>
                            <h4>Pianifica</h4>
                            <p class="text-muted">Inserisci il tuo itinerario o cerca viaggi disponibili nelle tue vicinanze.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px; font-size: 1.5rem; margin-bottom: 1rem;">
                                3
                            </div>
                            <h4>Viaggia</h4>
                            <p class="text-muted">Conferma la prenotazione e goditi il viaggio in compagnia.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section py-5 fade-in">
        <div class="container">
            <div class="row text-center g-4">
                <div class="col-md-4">
                    <h3 class="display-4 fw-bold">500+</h3>
                    <p class="lead">Viaggi condivisi</p>
                </div>
                <div class="col-md-4">
                    <h3 class="display-4 fw-bold">1.2T</h3>
                    <p class="lead">CO₂ risparmiata</p>
                </div>
                <div class="col-md-4">
                    <h3 class="display-4 fw-bold">200+</h3>
                    <p class="lead">Utenti soddisfatti</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-5 fade-in" id="tellAboutUs">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Cosa dicono di noi</h2>
                <p class="lead text-muted">Le esperienze della nostra community</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <img src="https://randomuser.me/api/portraits/women/32.jpg" class="rounded-circle me-3" width="50" height="50" alt="User">
                                <div>
                                    <h5 class="mb-0">Maria Rossi</h5>
                                    <small class="text-muted">Passeggera frequente</small>
                                </div>
                            </div>
                            <p class="mb-0">"Grazie a ShareLink riesco a risparmiare sui miei spostamenti quotidiani e ho conosciuto persone fantastiche!"</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <img src="https://randomuser.me/api/portraits/men/45.jpg" class="rounded-circle me-3" width="50" height="50" alt="User">
                                <div>
                                    <h5 class="mb-0">Luca Bianchi</h5>
                                    <small class="text-muted">Guidatore</small>
                                </div>
                            </div>
                            <p class="mb-0">"Condividere il mio viaggio quotidiano mi permette di ammortizzare i costi e contribuire all'ambiente."</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <img src="https://randomuser.me/api/portraits/women/68.jpg" class="rounded-circle me-3" width="50" height="50" alt="User">
                                <div>
                                    <h5 class="mb-0">Anna Verdi</h5>
                                    <small class="text-muted">Passeggera</small>
                                </div>
                            </div>
                            <p class="mb-0">"Sistema semplice e intuitivo. Mi sento sempre al sicuro durante i viaggi."</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5 bg-light fade-in">
        <div class="container text-center">
            <h2 class="fw-bold mb-4">Pronto a iniziare?</h2>
            <p class="lead mb-4">Unisciti alla community di ShareLink e contribuisci a un futuro più sostenibile.</p>
            <a href=" <?= base_url("/register") ?>" class="btn btn-danger btn-lg px-4">Registrati ora</a>
        </div>
    </section>
</div>

<div id="contatti">
    <?= view("footer") ?>

</div>

<script src="<?= base_url('/Script/observer.js'); ?>"></script>

</body>
</html>

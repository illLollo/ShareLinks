<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profilo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('/Style/global.css') ?>">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Roboto', sans-serif;
        }

        .card {
            border: none;
            border-radius: 12px;
        }

        .card h3 {
            color: #343a40;
        }

        .btn-primary {
            background-color: #dc3545 !important; /* Rosso acceso */
            border-color: #dc3545 !important;
        }

        .btn-primary:hover {
            background-color: #a71d2a !important;
            border-color: #a71d2a !important;
        }

        .btn-danger {
            background-color: #dc3545;
            border: none;
        }

        .btn-danger:hover {
            background-color: #a71d2a;
        }

        .form-control:focus {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
        }
    </style>
</head>

<body>
    <?php if (session()->has('toastMessage')): ?>
        <?= view('toast') ?>
    <?php endif; ?>

    <div class="fade-in">
        <div class="container mt-5">
            <div class="row justify-content-center align-items-start gap-4">
                <!-- Box 1 - Informazioni Profilo -->
                <div class="col-md-5">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h3 class="text-center mb-4">Informazioni Profilo</h3>
                            <form action="<?= base_url('profile/updateProfile') ?>" method="post">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Nome Utente</label>
                                    <input type="text" class="form-control" id="username" name="username" required value="<?= $user->username ?? '' ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nome</label>
                                    <input type="text" class="form-control" id="name" name="name" required value="<?= $user->name ?? '' ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="surname" class="form-label">Cognome</label>
                                    <input type="text" class="form-control" id="surname" name="surname" required value="<?= $user->surname ?? '' ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="birthDate" class="form-label">Data di Nascita</label>
                                    <input type="date" class="form-control" id="birthDate" name="birthDate" required value="<?= $user->birthDate ?? '' ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="fiscalCode" class="form-label">Codice Fiscale</label>
                                    <input type="text" class="form-control" id="fiscalCode" name="fiscalCode" maxlength="16" required value="<?= $user->fiscalCode ?? '' ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required value="<?= $user->email ?? '' ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Conferma Password</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Modifica</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('/Script/observer.js'); ?>"></script>
    <?= view("footer") ?>
</body>

</html>
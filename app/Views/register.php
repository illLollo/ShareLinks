<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('/Style/global.css') ?>">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
        .btn-primary {
            background-color: #dc3545 !important; /* Rosso acceso */
            border-color: #dc3545 !important;
        }

        .btn-primary:hover {
            background-color: #a71d2a !important;
            border-color: #a71d2a !important;
        }

        .form-control:focus {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
        }
    </style>
</head>
<body class="bg-light">
    <?php include(APPPATH . 'Views/header.php'); ?>

    <div class="container mt-4">
        <?php if (session()->has('toastMessage')): ?>
            <?= view('toast') ?>
        <?php endif; ?>

        <div class="container mt-5 vh-100">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow-lg">
                        <div class="card-body">
                            <h3 class="text-center mb-4">Registrazione</h3>
                            <form action="<?= base_url('register/register')?>" method="post">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Nome Utente</label>
                                    <input type="text" class="form-control" id="username" name="username" required>
                                </div>
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nome</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="surname" class="form-label">Cognome</label>
                                    <input type="text" class="form-control" id="surname" name="surname" required>
                                </div>
                                <div class="mb-3">
                                    <label for="birthDate" class="form-label">Data di Nascita</label>
                                    <input type="date" class="form-control" id="birthDate" name="birthDate" required>
                                </div>
                                <div class="mb-3">
                                    <label for="fiscalCode" class="form-label">Codice Fiscale</label>
                                    <input type="text" class="form-control" id="fiscalCode" name="fiscalCode" maxlength="16" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Conferma Password</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                </div>
                                <button type="submit" class="btn btn-success w-100">Registrati</button>
                                <?php if (!empty($error_message)) : ?>
                                    <p style="color: red;"><?= esc($error_message) ?></p>
                                <?php endif; ?>
                                <?php if (!empty($message)) : ?>
                                    <p style="color: green;"><?= esc($message) ?></p>

                                <?php
                                    endif;
                                ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
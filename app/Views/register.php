<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrati | ShareLinks </title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('/Style/global.css') ?>">
    <script src=" <?= base_url("/Script/observer.js"); ?>"></script>
    <style>
        :root {
            --body-bg: #f8fafc;
            --input-border: #e2e8f0;
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
        }

        body {
            background: var(--body-bg);
            min-height: 100vh;
            display: grid;
            align-items: center;
            font-family: 'Roboto', sans-serif;
        }

        .auth-card {
            max-width: 550px;
            border: 1px solid rgba(0, 0, 0, 0.04);
            border-radius: 16px;
            box-shadow: 0 12px 24px -6px rgba(0, 0, 0, 0.02);
            overflow: hidden;
        }

        .form-control {
            padding: 1rem;
            border: 2px solid var(--input-border);
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            z-index: 5;
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
<body class="pt-5">
<style>
        /* Stili CSS integrati per semplicità */
        :root {
            --primary: #2ecc71;  /* Verde (coerente con la mobilità sostenibile) */
            --secondary: #3498db; /* Blu */
            --dark: #2c3e50;
            --light: #ecf0f1;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .main-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        body {
            background-color: var(--light);
        }
        .login-container {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            background: white;
            padding: 2.5rem;
            border-radius: 10px;
            width: 100%;
            max-width: 550px;
            text-align: center;
        }
        .logo {
            margin-bottom: 1.5rem;
        }
        .logo img {
            height: 150px;
        }
        h1 {
            color: var(--dark);
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }
        .subtitle {
            color: #7f8c8d;
            margin-bottom: 2rem;
            font-size: 0.9rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
            text-align: left;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--dark);
            font-weight: 500;
        }
        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }
        input:focus {
            border-color: var(--primary);
            outline: none;
        }
        .forgot-password {
            text-align: right;
            margin-bottom: 1.5rem;
        }
        .forgot-password a {
            color: var(--secondary);
            text-decoration: none;
            font-size: 0.8rem;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }
        button:hover {
            background-color: #27ae60;
        }
        .divider {
            margin: 1.5rem 0;
            position: relative;
            text-align: center;
            color: #bdc3c7;
        }
        .divider::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #ddd;
            z-index: -1;
        }
        .divider span {
            background: white;
            padding: 0 10px;
        }
        .social-login {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .social-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
        }
        .google {
            background: #db4437;
        }
        .facebook {
            background: #4267b2;
        }
        .apple {
            background: #000;
        }
        .register-link {
            margin-top: 1rem;
            font-size: 0.9rem;
            color: #7f8c8d;
        }
        .register-link a {
            color: var(--secondary);
            text-decoration: none;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <?= view("toast"); ?>
    <div class="main-container" style="margin-top: 15vh;">
        <div class="login-container fade-in">
            <div class="logo">
                <img src="<?= base_url("/Resources/imgs/ShareLinks_LOGO.png"); ?>" alt="ShareLinks Logo">
            </div>
            <h1>Registrati e unisciti a ShareLinks</h1>
            <p class="subtitle">Condividi viaggi, riduci le emissioni di CO₂</p>

            <form id="registerForm" class="fade-in needs-validation" novalidate action="<?= base_url('login/auth') ?>" method="post">
                <div class="form-group">
                    <label for="username">Nome Utente</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="mariorossi1" required>
                    <div class="valid-feedback">Campo valido.</div>
                    <div class="invalid-feedback">Inserisci un nome utente valido.</div>
                </div>
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input type="text" class="form-control" id="nome" name="nome" placeholder="Mario" required>
                    <div class="valid-feedback">Campo valido.</div>
                    <div class="invalid-feedback">Inserisci il nome.</div>
                </div>
                <div class="form-group">
                    <label for="rossi">Cognome</label>
                    <input type="text" class="form-control" id="rossi" name="rossi" placeholder="Rossi" required>
                    <div class="valid-feedback">Campo valido.</div>
                    <div class="invalid-feedback">Inserisci il cognome.</div>
                </div>
                <div class="form-group">
                    <label for="birthDate">Data Di Nascita</label>
                    <input type="date" class="form-control" id="birthDate" name="birthDate" required>
                    <div class="valid-feedback">Campo valido.</div>
                    <div class="invalid-feedback">Inserisci una data di nascita valida.</div>
                </div>
                <div class="form-group">
                    <label for="fiscalCode">Codice Fiscale</label>
                    <input type="text" class="form-control" id="fiscalCode" name="fiscalCode" placeholder="GGGGGG11G1G111G" required pattern="[A-Z0-9]{16}">
                    <div class="valid-feedback">Campo valido.</div>
                    <div class="invalid-feedback">Inserisci un codice fiscale valido (16 caratteri).</div>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="esempio@email.com" required>
                    <div class="valid-feedback">Campo valido.</div>
                    <div class="invalid-feedback">Inserisci una email valida.</div>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required minlength="6">
                    <div class="valid-feedback">Campo valido.</div>
                    <div class="invalid-feedback">La password deve essere di almeno 6 caratteri.</div>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Conferma Password</label>
                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="••••••••" required minlength="6">
                    <div class="valid-feedback">Campo valido.</div>
                    <div class="invalid-feedback">Le password devono coincidere.</div>
                </div>
                <button type="submit">Registrati</button>
            </form>

            <div class="register-link">
                Hai già un account? <a href=" <?= base_url("/login"); ?>">Accedi</a>
            </div>
        </div>
    </div>

    <?= view("footer") ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('/Script/observer.js'); ?>"></script>
    <script>
    // Bootstrap 5 validation live feedback
    (function () {
      'use strict';
      var form = document.getElementById('registerForm');
      var inputs = form.querySelectorAll('.form-control');

      // Live validation on input
      inputs.forEach(function(input) {
        input.addEventListener('input', function() {
          // Password match check
          if (input.id === 'confirmPassword' || input.id === 'password') {
            var password = document.getElementById('password');
            var confirmPassword = document.getElementById('confirmPassword');
            if (password.value !== confirmPassword.value) {
              confirmPassword.setCustomValidity('Le password non coincidono');
            } else {
              confirmPassword.setCustomValidity('');
            }
          }
          if (input.checkValidity()) {
            input.classList.add('is-valid');
            input.classList.remove('is-invalid');
          } else {
            input.classList.add('is-invalid');
            input.classList.remove('is-valid');
          }
        });
      });

      // On submit
      form.addEventListener('submit', function (event) {
        var password = document.getElementById('password');
        var confirmPassword = document.getElementById('confirmPassword');
        if (password.value !== confirmPassword.value) {
          confirmPassword.setCustomValidity('Le password non coincidono');
        } else {
          confirmPassword.setCustomValidity('');
        }
        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    })();
    </script>
</body>
</html>
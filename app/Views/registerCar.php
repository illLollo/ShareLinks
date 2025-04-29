<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione Auto</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url('/Style/global.css') ?>">
    <script src="<?= base_url('/Script/observer.js'); ?>"></script>
</head>
<body class="bg-light">
<?php if (session()->has('toastMessage')): ?>
        <?= view('toast') ?>
    <?php endif; ?>
<div class="container mt-5 fade-in" style="height: 87vh;">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h3 class="text-center mb-4">Registrazione Auto</h3>
                    <form action="<?= base_url('cars/create')?>" method="post">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="plateNumber" class="form-label">Numero Di Targa</label>
                            <input type="text" class="form-control" id="plateNumber" name="plateNumber" required>
                        </div>
                        <div class="mb-3">
                            <label for="productionDate" class="form-label">Data di Produzione</label>
                            <input type="date" class="form-control" id="productionDate" name="productionDate" required>
                        </div>
                        <div class="mb-3">
                            <label for="model" class="form-label">Modello</label>
                            <input type="text" class="form-control" id="model" name="model" required>
                        </div>
                        <div class="mb-3">
                            <label for="euroPerKilometer" class="form-label">Euro al Kilometro</label>
                            <input type="text" class="form-control" id="euroPerKilometer" name="euroPerKilometer" maxlength="16" required>
                        </div>
                        <div class="mb-3">
                            <label for="co2PerKilometer" class="form-label">Cubi di CO2 Al Kilometro</label>
                            <input type="text" class="form-control" id="co2PerKilometer" name="co2PerKilometer" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Registra Veicolo</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= view("footer") ?>
</body>
</html>
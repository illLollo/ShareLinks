<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione Veicolo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url('/Style/global.css') ?>">
</head>
<body class="bg-light">
<?php if (session()->has('toastMessage')): ?>
        <?= view('toast') ?>
    <?php endif; ?>
<div class="container mt-4 fade-in">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h3 class="text-center mb-4">Visualizza dettagli auto</h3>
                    <form id="updateForm" action="<?= base_url('cars/update') ?>" method="post">
                        <input type="hidden" name="carId" value="<?= $car->carId ?>">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="name" name="name" required value="<?= $car->name ?>">
                        </div>
                        <div class="mb-3">
                            <label for="plateNumber" class="form-label">Numero Di Targa</label>
                            <input type="text" class="form-control" id="plateNumber" name="plateNumber" required value="<?= $car->plateNumber ?>">
                        </div>
                        <div class="mb-3">
                            <label for="productionDate" class="form-label">Data di Produzione</label>
                            <input type="date" class="form-control" id="productionDate" name="productionDate" required value="<?= $car->productionDate ?>">
                        </div>
                        <div class="mb-3">
                            <label for="model" class="form-label">Modello</label>
                            <input type="text" class="form-control" id="model" name="model" required value="<?= $car->model ?>">
                        </div>
                        <div class="mb-3">
                            <label for="euroPerKilometer" class="form-label">Euro al Kilometro</label>
                            <input type="text" class="form-control" id="euroPerKilometer" name="euroPerKilometer" maxlength="16" required value="<?= $car->euroPerKilometer ?>">
                        </div>
                        <div class="mb-3">
                            <label for="co2PerKilometer" class="form-label">Cubi di CO2 Al Kilometro</label>
                            <input type="text" class="form-control" id="co2PerKilometer" name="co2PerKilometer" required value="<?= $car->co2PerKilometer ?>">
                        </div>
                    </form>

                    <form id="deleteForm" action="<?= base_url('cars/delete') ?>" method="post">
                        <input type="hidden" name="carId" value="<?= $car->carId ?>">
                    </form>

                    <div class="d-flex gap-3">
                        <button type="button" class="btn btn-warning w-100" onclick="document.getElementById('updateForm').submit();">Modifica Veicolo</button>
                        <button type="button" class="btn btn-danger w-100" onclick="document.getElementById('deleteForm').submit();">Elimina Veicolo</button>
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
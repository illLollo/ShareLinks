<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabella Veicoli</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= base_url('/Style/global.css') ?>">
</head>
<body class="bg-light">
<?php if (session()->has('toastMessage')): ?>
        <?= view('toast') ?>
    <?php endif; ?>
<?php
    $nCars = session()->get('nCars');
    $carChunk = session()->get('carChunk');
    $cars = session()->get('cars');
    $query = session()->get('query');
    $page = session()->get('page');
    $pages = ceil($nCars / $carChunk);
?>
<div class="container mt-4">
    <h2 class="text-center mb-4">La tua ricerca ha prodotto <?= esc($nCars) ?> risultati</h2>

    <form action="<?= base_url('cars/search') ?>" method="get" class="mb-4">
        <div class="input-group">
            <input type="text" class="form-control" name="query" placeholder="Cerca veicolo..." value="<?= esc($query) ?>">
            <button type="submit" class="btn btn-primary">Cerca</button>
        </div>
    </form>
    <?php if ($nCars > 0): ?>
    <div class="table-responsive">
        <table class="table table-hover table-bordered shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>Nome</th>
                    <th>Targa</th>
                    <th>Data Produzione</th>
                    <th>Modello</th>
                    <th>€ per Km</th>
                    <th>CO₂ per Km</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach ($cars as $car) {
                        echo "<tr>
                                <td>" . esc($car->name) . "</td>
                                <td>" . esc($car->plateNumber) . "</td>
                                <td>" . esc($car->productionDate) . "</td>
                                <td>" . esc($car->model) . "</td>
                                <td>€ " . esc($car->euroPerKilometer) . "</td>
                                <td>" . esc($car->co2PerKilometer) . " kg</td>
                                <td>" . '
                                    <a class="btn btn-primary w-100" href="' . base_url("cars/details/" . esc($car->carId)) . '">
                                        Gestisci
                                    </a>
                                ' . "
                                </td>
                              </tr>";
                    }

                ?>
            </tbody>
        </table>
    </div>
    <?php else : ?>
        <div class="container">
            <h2 class="text-center mb-4"> Niente da visualizzare qui </h2>
       </div>
    <?php endif; ?>

    <?php if ($nCars > 0) : ?>

        <!-- Paginazione -->
        <div class="text-center mt-3">
            <span class="fw-bold">Pagine: </span>
            <?php if ($page - 1 > 0): ?>
                <a href="<?= base_url("/cars/search?query=" . esc($query) . "&page=" . ($page - 1)) ?>" class="mx-1 text-decoration-none fw-bold">Indietro</a>
                <?php endif; ?>

                <?php
        for ($i = $page; $i <= $pages && $i <= $page + 3; $i++) {
            echo "<a href='" . base_url("/cars/search?query=" . esc($query) . "&page=" . $i) . "' class='mx-1 text-decoration-none fw-bold'>" . esc($i) . "</a>";
        }
        ?>

<?php if ($page + 1 <= $pages): ?>
    <a href="<?= base_url("/cars/search?query=" . esc($query) . "&page=" . ($page + 1)) ?>" class="mx-1 text-decoration-none fw-bold">Avanti</a>
    <?php endif; ?>
</div>
</div>
<?php endif; ?>

</body>
</html>

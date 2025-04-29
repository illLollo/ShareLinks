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
    $root = $query ? "/cars/search/$query/" : "/cars/index/";
    $pages = ceil($nCars / $carChunk); // Calcolo basato sui dati passati
?>
<div class="fade-in" style="margin-bottom: 20em;">
    <div class="container mt-4" style="height: 80vh;">
        <h2 class="text-center mb-4">Lista Veicoli</h2>

        <form action="<?= base_url('cars/searchRedirect') ?>" method="post" class="mb-4" style="z-index: 0">
            <div class="input-group">
                <input type="text" class="form-control" name="query" placeholder="Cerca veicolo..." value="<?= $query ?? '' ?>">
                <button type="submit" class="btn btn-primary" style="z-index: 0;">Cerca</button>
            </div>
        </form>

        <?php if ($nCars === 0): ?>
            <div class='alert alert-warning text-center'>Nessun risultato trovato<?php if (!$query) : ?>!<?php else: ?> per la query: <strong> <?= htmlspecialchars($query) ?> </strong><?php endif; ?></div>
            <?php else: ?>
                <div class='alert alert-info text-center'>Risultati trovati: <strong> <?= $nCars ?> </strong> <?php if ($query): ?> per la query: <strong> <?= htmlspecialchars($query) ?> </strong> <?php endif; ?></div>

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
                            foreach ($cars as $carArr) {
                                $carObj = (object) $carArr;
                                echo "<tr>
                                <td>{$carObj->name}</td>
                                <td>{$carObj->plateNumber}</td>
                                <td>{$carObj->productionDate}</td>
                                <td>{$carObj->model}</td>
                                <td>€ {$carObj->euroPerKilometer}</td>
                                <td>{$carObj->co2PerKilometer} kg</td>
                                <td>" . '
                                <a class="btn btn-primary w-100" href="' . base_url("cars/details/$carObj->carId") . '">
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
                <?php endif; ?>

                <?php if ($nCars > 0): ?>
                <!-- Paginazione -->
                <div class="text-center mt-3">
                    <span class="fw-bold">Pagine: </span>
                    <?php if ($page - 1 > 0): ?>
                        <a href="<?= base_url($root . ($page - 1)) ?>" class="mx-1 text-decoration-none fw-bold">Indietro</a>
                        <?php endif; ?>

                        <?php
            for ($i = $page; $i <= $pages && $i <= $page + 3; $i++) {
                echo "<a href='" . base_url($root . $i) . "' class='mx-1 text-decoration-none fw-bold'>$i</a>";
            }
            ?>

        <?php if ($page + 1 <= $pages): ?>
                <a href="<?= base_url($root . ($page + 1)) ?>" class="mx-1 text-decoration-none fw-bold">Avanti</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Pulsante "Aggiungi un'auto" sotto la paginazione -->
        <div class="text-center mt-4">
            <a href="<?= base_url('cars/add') ?>" class="btn btn-success btn-lg shadow-sm">
                ➕ Aggiungi un'auto
            </a>
        </div>
    </div>
</div>

<script src="<?= base_url('/Script/observer.js'); ?>"></script>
<?= view("footer") ?>

</body>
</html>

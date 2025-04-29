<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sharelinks | Profilo Guidatore</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<div class="fade-in">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow-lg p-5 text-center" style="max-width: 500px; border-radius: 15px; background-color: #f8f9fa;">
            <h2 class="text-muted mb-4">Mhhh... sembra che tu non abbia un profilo guidatore collegato.</h2>
            <a href="<?= base_url('homepage/registerDriverLicense') ?>" class="btn btn-primary btn-lg">Creiamolo Insieme!</a>
        </div>
    </div>
</div>
<style>
    .btn-primary {
        background-color: #dc3545 !important; /* Rosso acceso */
        border-color: #dc3545 !important;
    }

    .btn-primary:hover {
        background-color: #a71d2a !important;
        border-color: #a71d2a !important;
    }

    body {
        background-color: #e9ecef; /* Sfondo chiaro per contrasto */
    }
</style>
<script src="<?= base_url('/Script/observer.js'); ?>"></script>
<?= view("footer") ?>
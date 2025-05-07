<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Richiesta in attesa</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">

  <form class="card shadow-lg p-4 text-center" style="max-width: 400px;" action="<?= base_url('homepage/cancelRequest') ?>" method="post">
    <div class="card-body">
      <h5 class="card-title mb-3">In attesa che il guidatore confermi la richiesta...</h5>
      <button class="btn btn-outline-danger" type="submit">Rimuovi Richiesta</button>
    </div>
</form>

</body>
</html>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('/Script/global.js') ?>"></script>
<script>
    async function checkRequestStatus() {
      try {
        const response = await fetch('<?= base_url('homepage/checkRequestStatus') ?>', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            token: '<?= $user->token ?>',
            requestId: '<?= $requestId ?>',
          }),
        });

        if (!response.ok) {
          console.error('Errore durante il controllo dello stato della richiesta.');
          return;
        }

        const request = await response.json();

        if (request.status === "ACCEPTED") {
          window.location.href = `/homepage/onBoard/${request.tripId}`
        }
      } catch (error) {
        console.error('Errore durante il controllo dello stato della richiesta:', error);
      }
    }

    // Controlla lo stato della richiesta ogni 2 secondi
    setInterval(checkRequestStatus, 2000);
  </script>
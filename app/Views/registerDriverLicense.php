<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url('/Style/global.css') ?>">
</head>

<body class="bg-light">
    <?php if (session()->has('toastMessage')): ?>
        <?= view('toast') ?>
    <?php endif; ?>

    <h1 class="text-center mb-4 mt-4">Creazione profilo Utente | Registrazione patente di guida</h1>
    <div class="container mt-5" style="height: 80vh;">
        <div class="row justify-content-center align-items-center">
            <div class="d-flex justify-content-center align-items-start" style="height: 100vh; padding-top: 20px;">
                <!-- Div con la patente a sinistra -->
                <div style="position: relative; display: inline-block; max-width: 45%; margin-right: 2%;">
                    <img src="<?= base_url('/Resources/imgs/driver_license_template.png') ?>" alt="Patente di guida" class="img-fluid" style="width: 100%; height: auto;">
                    <div style="position: absolute; top: 12%; left: 28%; color: black; font-size: 1rem;">
                        <p><strong>Cognome:</strong> <?= $user->surname ?></p>
                    </div>
                    <div style="position: absolute; top: 21%; left: 28%; color: black; font-size: 1rem;">
                        <p><strong>Nome:</strong> <?= $user->name ?></p>
                    </div>
                    <div style="position: absolute; top: 30%; left: 28%; color: black; font-size: 1rem;">
                        <p><strong>Data di Nascita:</strong> <?= (new DateTime($user->birthDate))->format('d/m/Y') ?></p>
                    </div>
                    <div style="position: absolute; top: 40%; left: 28%; color: black; font-size: 1rem;">
                        <p><strong>Data Emissione:</strong> <span class="img_fields emissionDate"></span></p>
                    </div>
                    <div style="position: absolute; top: 50%; left: 28%; color: black; font-size: 1rem;">
                        <p><strong>Data Scadenza:</strong> <span class="img_fields expiryDate"></span></p>
                    </div>
                    <div style="position: absolute; top: 63%; left: 32%; color: black; font-size: 1rem;">
                        <p><strong>Codice:</strong> <span class="img_fields code"></span></p>
                    </div>
                    <div style="position: absolute; top: 73%; left: 5%; color: black; font-size: 1rem;">
                        <p><strong>Tipo:</strong> <span class="img_fields type"></span></p>
                    </div>
                </div>

                <!-- Form coi dati a destra -->
                <div style="max-width: 45%; margin-left: 2%;">
                    <form action="<?= base_url('driver/driverLicense') ?>" method="post" class="p-4 border rounded bg-white shadow">
                        <h2 class="text-center">Inserisci i tuoi dati</h2>
                        <div class="mb-3">
                            <label for="emissionDate" class="form-label">Data Emissione</label>
                            <input type="date" class="form-control form-control-lg" id="emissionDate" class="emissionDate" name="emissionDate" required
                                value="">
                        </div>
                        <div class="mb-3">
                            <label for="expiryDate" class="form-label">Data Scadenza</label>
                            <input type="date" class="form-control form-control-lg" id="expiryDate" class="expiryDate" name="expiryDate" required
                                value="">
                        </div>
                        <div class="mb-3">
                            <label for="code" class="form-label">Codice</label>
                            <input type="text" class="form-control form-control-lg" id="code" class="code" name="code" required
                                value="">
                        </div>
                        <div class="mb-3">
                            <label for="type" class="form-label">Tipo</label>
                            <input type="text" class="form-control form-control-lg" id="type" class="type" name="type" required
                                value="">
                        </div>

                        <button type="submit" class="btn btn-success btn-lg w-100">Registra Patente</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>


        // Mappa che associa gli input della form ai rispettivi campi di testo nell'immagine
        const fieldMap = Array.from(document.querySelectorAll('.img_fields')).reduce((map, input) => {
            const key = input.classList[1]; // Usa la seconda classe come chiave
            if (key) {
                map[key] = input; // Associa la chiave all'elemento HTML
            }
            return map;
        }, {});

        const inputs = document.querySelectorAll('form input');

        inputs.forEach(input => {
            input.addEventListener('input', () => {
                const key = input.id; // Usa la seconda classe come chiave
                if (!isNaN(new Date(input.value).getTime())) { // Controlla se il valore è una data valida
                    fieldMap[key].textContent = (new Date(input.value)).toLocaleDateString('it-IT'); // Aggiorna il campo di testo nell'immagine
                } else {
                    fieldMap[key].textContent = input.value; // Aggiorna il campo di testo nell'immagine
                }
            });
        });

    </script>
</body>

</html>
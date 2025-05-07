<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOMEPAGE</title>
    <link rel="stylesheet" href="<?= base_url('/Style/global.css') ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body style="overflow-y: hidden; margin: 0 !important;">
    <style>
        :root {
            --primary-color: #2E8B57;
            --secondary-color: #3AA76D;
            --light-gray: #F8F9FA;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
        }

        .search-card {
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border: none;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(46, 139, 87, 0.25);
        }

        .route-icon {
            color: var(--primary-color);
            font-size: 1.2rem;
        }

        .available-rides {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .driver-avatar {
            width: 50px;
            height: 50px;
            object-fit: cover;
        }

        .price-badge {
            background-color: var(--primary-color);
        }

        .sidebar {
            position: absolute;
            top: 0;
            left: 0;
            width: 25%;
            /* Adjusted width for better visibility */
            height: 100%;
            background-color: #f8f9fa;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            transition: transform 0.3s ease-in-out;
        }

        .sidebar.hidden {
            transform: translateX(-100%);
        }

        .map-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        #map {
            height: 100%;
            width: 100%;
        }

        .toggle-sidebar-btn {
            position: absolute;
            top: 50%;
            right: -20px;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            background-color: grey;
            color: white;
            border: none;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            z-index: 3;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .toggle-sidebar-btn:hover {
            background-color: #0056b3;
        }

        @media (max-width: 768px) {
            .navbar {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                z-index: 10;
                background-color: #f8f9fa;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            }

            .map-container {
                position: absolute;
                top: 56px;
                /* Adjusted for navbar height */
                left: 0;
                width: 100%;
                height: calc(100vh - 56px);
                z-index: 1;
            }

            .sidebar {
                position: fixed;
                bottom: 0;
                left: 0;
                width: 100%;
                height: 300px;
                /* Default height when visible */
                background-color: #f8f9fa;
                box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
                overflow-y: auto;
                z-index: 2;
                transition: transform 0.3s ease-in-out;
                transform: translateY(100%);
                /* Hidden by default */
            }

            .sidebar.visible {
                transform: translateY(0);
            }

            .drag-handle {
                position: absolute;
                top: -20px;
                left: 50%;
                transform: translateX(-50%);
                width: 50px;
                height: 5px;
                background-color: #ccc;
                border-radius: 5px;
                cursor: pointer;
            }

            ;

        }
    </style>
    <?= view('toast') ?>
    <div class="d-flex" style="height: 92vh; margin-top: 8vh;">
        <?= view("sidebar", ["direction" => "left"]); ?>
        <div class="sidebar-header">
            <h1 class="mb-3 fade-in">Informazioni del Viaggio</h1>
        </div>
        <div class="sidebar-content fade-in">
            <div class="trip-info">
                <p><strong>Partenza:</strong> <?= $trip["steps"][0]['name'] ?></p>
                <p><strong>Arrivo:</strong> <?= $trip["steps"][count($trip["steps"]) - 1]['name'] ?></p>
                <p><strong>Distanza Totale:</strong> <?= $trip["distance"] ?> km</p>
                <p><strong>Durata Stimata:</strong> <?= $trip["time"] ?> minuti</p>
            </div>
            <div class="<?= $tripUserInfo["userStatus"] == "ON BOARD" ? 'd-flex gap-5' : '' ?>">
                <h2 id="statusMessage" class="mt-3">
                    <?= $tripUserInfo["userStatus"] == "ON BOARD" ? 'Sei in viaggio!' : 'Il tuo autista sta arrivando da te!' ?>
                </h2>
                <?php if ($tripUserInfo["userStatus"] == "WAITING"): ?>
                    <button id="onBoardButton" class="btn btn-success">Sono a Bordo</button>
                <?php endif; ?>
                <button id="leaveTripButton" class="btn btn-danger">
                    <?= $tripUserInfo["userStatus"] == "ON BOARD" ? 'Scendi e termina la tua tratta' : 'Abbandona il viaggio' ?>
                </button>

            </div>
        </div>

        <script>
            document.getElementById('onBoardButton')?.addEventListener('click', async function () {
                    try {
                    const response = await fetch('/api/setOnBoard', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            tripId: <?= $trip["tripId"] ?>,
                            userId: <?= $user->userId ?>,
                            token: '<?= $user->token ?>',
                        }),
                    });

                    if (response.ok) {
                        location.reload()
                    } else {
                    }
                } catch (error) {
                    console.error('Errore durante la chiamata al servizio:', error);
                }
            });

            document.getElementById('leaveTripButton').addEventListener('click', async () => {
                try {
                    // Ottieni la posizione corrente dell'utente
                    const userPosition = await new Promise((resolve, reject) => {
                        navigator.geolocation.getCurrentPosition(
                            (position) => {
                                resolve({
                                    lat: position.coords.latitude,
                                    lng: position.coords.longitude,
                                });
                            },
                            (error) => {
                                console.error("Errore nel recupero della posizione:", error);
                                alert("Impossibile ottenere la posizione attuale. Per favore, abilita la geolocalizzazione.");
                                reject(error);
                            },
                            {
                                enableHighAccuracy: true, // Richiedi una posizione più precisa
                            }
                        );
                    });

                    // Costruisci la polyline tra la posizione di partenza e quella corrente
                    const directionsService = new google.maps.DirectionsService();
                    const directionsRequest = {
                        origin: {
                            lat: parseFloat(<?= $tripUserInfo["enterLatitude"] ?>),
                            lng: parseFloat(<?= $tripUserInfo["enterLongitude"] ?>),
                        },
                        destination: userPosition,
                        travelMode: google.maps.TravelMode.DRIVING,
                    };

                    const directionsResult = await directionsService.route(directionsRequest);
                    const polyline = directionsResult.routes[0].overview_polyline;

                    // Invia i dati al server
                    const response = await fetch('/api/getOffTrip', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            tripId: <?= $trip["tripId"] ?>,
                            userId: <?= $user->userId ?>,
                            token: '<?= $user->token ?>',
                            latitude: userPosition.lat,
                            longitude: userPosition.lng,
                            polyline: polyline,
                        }),
                    });

                    if (response.ok) {
                        location.reload();
                    } else {
                        console.error('Errore durante la chiamata al servizio:', await response.text());
                    }
                } catch (error) {
                    console.error('Errore durante la chiamata al servizio:', error);
                }
            });
        </script>
        <?= view("closeSidebar") ?>
    </div>
    </div>
    </div>
    </div>

    <div class="map-container fade-in" style="z-index: 0;">
        <div id="map"></div>
    </div>
    </div>

    <div id="resultsContainer" class="mt-4">
        <!-- Risultati dei viaggi disponibili verranno mostrati qui -->
    </div>

</body>

<script>
    (async () => {
        (g => { var h, a, k, p = "The Google Maps JavaScript API", c = "google", l = "importLibrary", q = "__ib__", m = document, b = window; b = b[c] || (b[c] = {}); var d = b.maps || (b.maps = {}), r = new Set, e = new URLSearchParams, u = () => h || (h = new Promise(async (f, n) => { await (a = m.createElement("script")); e.set("libraries", [...r] + ",places"); for (k in g) e.set(k.replace(/[A-Z]/g, t => "_" + t[0].toLowerCase()), g[k]); e.set("callback", c + ".maps." + q); a.src = `https://maps.${c}apis.com/maps/api/js?` + e; d[q] = f; a.onerror = () => h = n(Error(p + " could not load.")); a.nonce = m.querySelector("script[nonce]")?.nonce || ""; m.head.append(a) })); d[l] ? console.warn(p + " only loads once. Ignoring:", g) : d[l] = (f, ...n) => r.add(f) && u().then(() => d[l](f, ...n)) })({
            key: "AIzaSyAZb0l2AiZau6rPCfhGmH7-9CaZFycIK4w",
            v: "weekly",
            // Use the 'v' parameter to indicate the version to use (weekly, beta, alpha, etc.).
            // Add other bootstrap parameters as needed, using camel case.
        });
        const { Map } = await google.maps.importLibrary("maps");
        const { Polyline } = await google.maps.importLibrary("geometry");

        const userPosition = await (new Promise((resolve, reject) => {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    resolve({
                        lat: position.coords.latitude,
                        lng: position.coords.longitude,
                    });
                },
                (error) => {
                    console.error("Errore nel recupero della posizione:", error);
                    alert("Impossibile ottenere la posizione attuale. Per favore, abilita la geolocalizzazione.");
                    reject(error);
                },
                {
                    enableHighAccuracy: true, // Request more precise location
                }
            );
        }));

        const map = new Map(document.getElementById("map"), {
            zoom: 12,
            center: userPosition, // Default center, will be updated to user's location
        });

        new google.maps.Marker({
            position: userPosition,
            map: map,
            title: "La tua posizione",
        });

        map.setCenter(userPosition);
        map.setZoom(12);



        const trip = <?= json_encode($trip) ?>;

        // Draw the route on the map
        const routePath = new google.maps.Polyline({
            path: google.maps.geometry.encoding.decodePath(trip.polyline),
            geodesic: true,
            strokeColor: "#FF0000",
            strokeOpacity: 1.0,
            strokeWeight: 3,
        });

        routePath.setMap(map);

        // Add markers for each step in the trip
        trip.steps.forEach((step, index) => {
            new google.maps.Marker({
                position: { lat: +step.latitude, lng: +step.longitude },
                map: map,
                title: `Step ${index + 1}`,
            });
        });

        // Add a marker for the final destination
        new google.maps.Marker({
            position: {
                lat: +trip.steps[trip.steps.length - 1].latitude,
                lng: +trip.steps[trip.steps.length - 1].longitude,
            },
            map: map,
            title: "Final Destination",
        });
    })()
</script>
<script src="<?= base_url('/Script/observer.js'); ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</html>
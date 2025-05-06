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
            <h1 class="mb-3 fade-in">Viaggi disponibili</h1>
        </div>
        <div class="sidebar-content fade-in">
        </div>
        <?= view("closeSidebar") ?>
    </div>
    </div>
    </div>
    <button>Nascondi</button>
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
        async function onBoard(trip, user, button) {
            // Create a hidden form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/homepage/registerOnBoard';

            // Add trip ID input
            const tripInput = document.createElement('input');
            tripInput.type = 'hidden';
            tripInput.name = 'tripId';
            tripInput.value = trip.tripId;
            form.appendChild(tripInput);

            // Add user ID input
            const userInput = document.createElement('input');
            userInput.type = 'hidden';
            userInput.name = 'userId';
            userInput.value = user.userId;
            form.appendChild(userInput);

            // Add user position inputs
            const latInput = document.createElement('input');
            latInput.type = 'hidden';
            latInput.name = 'enterLatitude';
            latInput.value = userPosition.lat;
            form.appendChild(latInput);

            const lngInput = document.createElement('input');
            lngInput.type = 'hidden';
            lngInput.name = 'enterLongitude';
            lngInput.value = userPosition.lng;
            form.appendChild(lngInput);

            const statusInput = document.createElement('input');
            statusInput.type = 'hidden';
            statusInput.name = 'status';
            statusInput.value = "PENDING";
            form.appendChild(statusInput);

            // Append the form to the body and submit it
            document.body.appendChild(form);
            form.submit();
        };
        let trips = [];


        const updateTripsIfChanged = async (newTrips) => {
            const existingTrips = JSON.stringify(trips);
            const incomingTrips = JSON.stringify(newTrips);

            const sidebarContent = document.querySelector(".sidebar-content");

            if (existingTrips !== incomingTrips) {
                trips = newTrips;
                sidebarContent.innerHTML = ""; // Clear existing content

                if (trips.length === 0) {
                    sidebarContent.parentElement.querySelector(".sidebar-header").innerHTML = `<h1 class="mb-3 fade-in">Nessun viaggio trovato</h1>`;
                    sidebarContent.innerHTML = `<?= view('noResultFound') ?>`;
                    return;
                }

                trips.forEach((trip) => {
                    const tripPath = new google.maps.Polyline({
                        path: google.maps.geometry.encoding.decodePath(trip.polyline),
                        geodesic: true,
                        strokeColor: "#FF0000",
                        strokeOpacity: 1.0,
                        strokeWeight: 2,
                    });
                    tripPath.setMap(map);

                    // Add markers for trip steps
                    trip.steps.forEach((step, index) => {
                        new google.maps.Marker({
                            position: { lat: +step.latitude, lng: +step.longitude },
                            map: map,
                            title: `Step ${index + 1}`,
                        });
                    });

                    // Add trip details to the sidebar
                    const tripCard = document.createElement("div");
                    tripCard.classList.add("card", "p-3", "mb-3", "fade-in");
                    tripCard.innerHTML = `
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center">
                                <img src="https://randomuser.me/api/portraits/men/${Math.floor(Math.random() * (50 - 20 + 1)) + 20}.jpg" class="driver-avatar rounded-circle me-3">
                                <div>
                                    <h6 class="mb-0">Autista: ${trip.driver.surname} ${trip.driver.name}</h6>
                                    <small class="text-muted">Distanza: ${trip.distance} km | Tempo: ${trip.time}</small>
                                </div>
                            </div>
                        </div>
                        <div class="route-details mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <small class="text-muted me-3">Partenza</small>
                                <div class="flex-grow-1">
                                    <span>${trip.steps[0].name}</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <small class="text-muted me-3">Arrivo</small>
                                <div class="flex-grow-1">
                                    <span>${trip.steps[trip.steps.length - 1].name}</span>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="badge bg-light text-dark me-2 d-flex" style="gap: 2em">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-user-friends me-1"></i> Posti: ${trip.remainingSlots}
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-car me-1"></i> Auto: ${trip.car.model}
                                    </div>
                                    <div class="d-flex align-items-center">
                                       Prezzo:  €${trip.pricePerPassenger}
                                    </div>

                                </span>
                            </div>
                            <button class="btn btn-success onBoard btn-outline-success" style="color: white;">Sali a bordo</button>
                        </div>
                    `;
                    tripCard.querySelector("button.onBoard").addEventListener("click", async function () {
                        onBoard(trip, <?= json_encode($user) ?>, this);
                    });
                    sidebarContent.appendChild(tripCard);
                });
            }
        };

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

        const currentUser = <?= json_encode($user) ?>;

        const response = await fetch("/api/getTripsNearUser", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                lat: userPosition.lat,
                lng: userPosition.lng,
                token: currentUser.token,
            }),
        });

        if (!response.ok) {
            console.error("Errore nella richiesta al server.");
            return;
        }

        const newTrips = await response.json();
        updateTripsIfChanged(newTrips);

        setInterval(async () => {
            try {
                const response = await fetch("/api/getTripsNearUser", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        lat: userPosition.lat,
                        lng: userPosition.lng,
                        token: currentUser.token,
                    }),
                });

                if (!response.ok) {
                    console.error("Errore nella richiesta al server.");
                    return;
                }

                const newTrips = await response.json();
                updateTripsIfChanged(newTrips);
            } catch (error) {
                console.error("Errore durante l'interrogazione al database:", error);
            }
        }, 2000);
    })()
</script>
<script src="<?= base_url('/Script/observer.js'); ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOMEPAGE</title>
    <link rel="stylesheet" href="<?= base_url('/Style/global.css') ?>">
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
        }
    </style>
    <?= view('toast') ?>
    <div class="d-flex" style="height: 92vh; margin-top: 8vh;">
        <?= view("sidebar", ["direction" => "left"]); ?>
        <div class="sidebar-header d-flex justify-content-between align-items-center">
            <h1>Informazioni del Viaggio</h1>
        </div>
        <div class="sidebar-content">
            <div class="trip-info">
                <p><strong>Partenza:</strong> <?= $trip->steps[0]['name'] ?></p>
                <p><strong>Arrivo:</strong> <?= $trip->steps[count($trip->steps) - 1]['name'] ?></p>
                <p><strong>Distanza Totale:</strong> <?= $trip->distance ?> km</p>
                <p><strong>Durata Stimata:</strong> <?= $trip->time ?> minuti</p>
            </div>

            <div class="ride-requests">
                <h3>Richieste di Passaggio</h3>
                <div class="ride-requests-content">
                    <?= view("noResultFound") ?>

                </div>
            </div>
        </div>
        <?= view("closeSidebar") ?>
    </div>
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
    <div class="sidebar-container">
        <div class="sidebar" style="right: 20px !important; height: 55vh; width: 15vh; z-index: 1;">
            <button class="close-btn"
                onclick="this.closest('.sidebar').classList.add('hidden'); this.parentElement.parentElement.querySelector('.sidebarToggleBtn').style.display = 'flex';">&times;</button>
            <div class="sidebar-header">
                <h1>Statistiche viaggio</h1>
            </div>
            <form class="sidebar-content" action="/drive/endTrip" method="post">
                <div class="card analytics-card p-3 mb-3">
                    <p><strong>Distanza:</strong> <?= round($trip->distance, 2) ?> km</p>
                    <p><strong>Costo Totale:</strong> €<?= round($analytics["totalCost"], 2) ?></p>
                    <p><strong>Il tuo profitto:</strong> €<?= round($analytics["driverProfit"], 2) ?></p>
                    <p><strong>CO2 Risparmiata:</strong> <?= round($analytics["co2Saved"], 2) ?> g</p>
                    <p><strong>Numero Passeggeri totali:</strong> <?= round($analytics["totalPassengers"], 2) ?></p>
                    <p><strong>Numero Passeggeri a bordo:</strong> <?= round($analytics["passengersOnBoard"], 2) ?></p>
                </div>

                <input type="hidden" name="tripId" value="<?= $trip->tripId ?>">

                <button class="btn btn-danger btn-outline-danger" style="color: white">Termina Viaggio</button>
            </form>
        </div>
        <button
            style="position: fixed; top: 40px; right: 20px; width: 48px; height: 48px; border-radius: 50%; background-color: white; color: black; border: none; box-shadow: 0 2px 6px rgba(0,0,0,0.3); font-size: 24px; display: none; justify-content: center; align-items: center; cursor: pointer; z-index: 1001; transition: opacity 0.3s ease;"
            class="sidebarToggleBtn toggle-btn"
            onclick="this.parentElement.querySelector('.sidebar').classList.remove('hidden'); this.style.display = 'none';">☰
        </button>
    </div>
</body>

<script>
    let requests = [];
    (async () => {
        (g => { var h, a, k, p = "The Google Maps JavaScript API", c = "google", l = "importLibrary", q = "__ib__", m = document, b = window; b = b[c] || (b[c] = {}); var d = b.maps || (b.maps = {}), r = new Set, e = new URLSearchParams, u = () => h || (h = new Promise(async (f, n) => { await (a = m.createElement("script")); e.set("libraries", [...r] + ",places"); for (k in g) e.set(k.replace(/[A-Z]/g, t => "_" + t[0].toLowerCase()), g[k]); e.set("callback", c + ".maps." + q); a.src = `https://maps.${c}apis.com/maps/api/js?` + e; d[q] = f; a.onerror = () => h = n(Error(p + " could not load.")); a.nonce = m.querySelector("script[nonce]")?.nonce || ""; m.head.append(a) })); d[l] ? console.warn(p + " only loads once. Ignoring:", g) : d[l] = (f, ...n) => r.add(f) && u().then(() => d[l](f, ...n)) })({
            key: "AIzaSyAZb0l2AiZau6rPCfhGmH7-9CaZFycIK4w",
            v: "weekly",
            // Use the 'v' parameter to indicate the version to use (weekly, beta, alpha, etc.).
            // Add other bootstrap parameters as needed, using camel case.
        });
        // Initialize and add the map
        const { Map } = await google.maps.importLibrary("maps");
        const { Polyline } = await google.maps.importLibrary("geometry");

        const trip = <?= json_encode($trip) ?>;
        const tripAnalytics = <?= json_encode($analytics) ?>;
        const currentUser = <?= json_encode($user) ?>;

        const { steps } = trip;

        const map = new Map(document.getElementById("map"), {
            zoom: 12,
            center: { lat: +trip.steps[0].latitude, lng: +trip.steps[0].longitude },
        });

        const routePath = new google.maps.Polyline({
            path: google.maps.geometry.encoding.decodePath(trip.polyline),
            geodesic: true,
            strokeColor: "#FF0000",
            strokeOpacity: 1.0,
            strokeWeight: 3,
        });

        routePath.setMap(map);

        steps.forEach((step, i) => {
            new google.maps.Marker({
                position: { lat: +step.latitude, lng: +step.longitude },
                map: map,
                title: `Waypoint ${i + 1}`,
            });
        });

        // Add a marker for the final destination
        new google.maps.Marker({
            position: {
                lat: +steps[steps.length - 1].latitude,
                lng: +steps[steps.length - 1].longitude,
            },
            map: map,
            title: "Final Destination",
        });


        const updateRequestsIfChanged = async (newRequests) => {
            const existingRequests = JSON.stringify(requests);
            const incomingRequests = JSON.stringify(newRequests);

            if (existingRequests !== incomingRequests) {
                requests = newRequests;
                const sidebarContent = document.querySelector(".ride-requests-content");
                const noResultElement = document.querySelector(".ride-requests-content .no-result-found");

                sidebarContent.innerHTML = ""; // Clear existing content

                // Clear existing markers for requests
                if (requestMarkers) {
                    requestMarkers.forEach(marker => marker.setMap(null));
                }
                requestMarkers = [];

                if (requests.length === 0) {
                    // Show noResultFound if there are no requests
                    if (!noResultElement) {
                        const noResultHtml = `<?= view('noResultFound') ?>`;
                        sidebarContent.innerHTML = noResultHtml;
                    }
                } else {
                    // Hide noResultFound if there are requests
                    if (noResultElement) {
                        noResultElement.remove();
                    }

                    requests.forEach((request) => {
                        requestMarkers.push(new google.maps.Marker({
                            position: { lat: +request.enterLatitude, lng: +request.enterLongitude },
                            map: map,
                            title: `Richiedente ${request.name}`,
                            icon: {
                                path: google.maps.SymbolPath.CIRCLE,
                                scale: 8,
                                fillColor: 'blue',
                                fillOpacity: 1,
                                strokeWeight: 1,
                                strokeColor: 'blue'
                            }
                        }));

                        const requestCard = document.createElement("div");
                        requestCard.classList.add("card", "p-3", "mb-3", "fade-in");
                        requestCard.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="d-flex align-items-center">
                            <img src="https://randomuser.me/api/portraits/men/${Math.floor(Math.random() * (50 - 20 + 1)) + 20}.jpg" class="driver-avatar rounded-circle me-3">
                            <div>
                                <h6 class="mb-0">${request.name} ${request.surname}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="route-details mb-3">
                        <p><strong>Indirizzo Mail:</strong> ${request.email}</p>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <button class="btn btn-success btn-sm accept-btn">Accetta</button>
                        <button class="btn btn-danger btn-sm reject-btn">Rifiuta</button>
                    </div>
                `;

                        // Add event listener for accept button
                        requestCard.querySelector(".accept-btn").addEventListener("click", async function () {
                            try {
                                console.log(currentUser)
                                const response = await fetch("/api/acceptPassenger", {
                                    method: "POST",
                                    headers: {
                                        "Content-Type": "application/json",
                                    },
                                    body: JSON.stringify({
                                        token: currentUser.token,
                                        requestId: request.requestId,
                                        tripId: trip.tripId,
                                    }),
                                });

                                if (response.ok) {
                                    requestCard.remove();
                                } else {
                                    console.error("Errore nell'accettare la richiesta.");
                                }
                            } catch (error) {
                                console.error("Errore durante la chiamata al servizio:", error);
                            }
                        });

                        // Add event listener for reject button
                        requestCard.querySelector(".reject-btn").addEventListener("click", async function () {
                            try {
                                const response = await fetch("/api/rejectPassenger", {
                                    method: "POST",
                                    headers: {
                                        "Content-Type": "application/json",
                                    },
                                    body: JSON.stringify({
                                        token: currentUser.token,
                                        requestId: request.requestId,
                                        tripId: trip.tripId,
                                    }),
                                });

                                if (response.ok) {
                                    requestCard.remove();
                                } else {
                                    console.error("Errore nel rifiutare la richiesta.");
                                }
                            } catch (error) {
                                console.error("Errore durante la chiamata al servizio:", error);
                            }
                        });

                        sidebarContent.appendChild(requestCard);
                    });
                }
            }
        };

        let requestMarkers = [];

        setInterval(async () => {
            try {
                const response = await fetch("/api/getRequestsForTrip", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        token: currentUser.token,
                        tripId: trip.tripId,
                    }),
                });

                if (!response.ok) {
                    console.error("Errore nella richiesta al server.");
                    return;
                }

                const newRequests = await response.json();
                console.log("Nuove richieste:", newRequests);
                updateRequestsIfChanged(newRequests);

                const passengersResponse = await fetch("/api/getPassengersOnBoardForTrip", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        token: currentUser.token,
                        tripId: trip.tripId,
                    }),
                });
                if (!passengersResponse.ok) {
                    console.error("Errore nella richiesta al server.");
                    return;
                }

                const passengers = await passengersResponse.json();

                // Fetch updated trip analytics
                const analyticsResponse = await fetch("/api/getAnalyticsForTrip", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        token: currentUser.token,
                        tripId: trip.tripId,
                    }),
                });

                if (!analyticsResponse.ok) {
                    console.error("Errore nella richiesta delle statistiche del viaggio.");
                    return;
                }

                const updatedAnalytics = await analyticsResponse.json();

                // Update the statistics in the UI
                const analyticsCard = document.querySelector(".analytics-card");
                if (analyticsCard) {
                    analyticsCard.innerHTML = `
                        <p><strong>Distanza:</strong> ${(+updatedAnalytics.distance).toFixed(2)} km</p>
                        <p><strong>Costo Totale:</strong> €${(+updatedAnalytics.totalCost).toFixed(2)}</p>
                        <p><strong>Il tuo profitto:</strong> €${(+updatedAnalytics.driverProfit).toFixed(2)}</p>
                        <p><strong>CO2 Risparmiata:</strong> ${(+updatedAnalytics.co2Saved).toFixed(2)} g</p>
                        <p><strong>Numero Passeggeri Totali:</strong> ${(+updatedAnalytics.totalPassengers)}</p>
                        <p><strong>Numero Passeggeri a bordo:</strong> ${(+updatedAnalytics.passengersOnBoard)}</p>
                    `;
                }
            } catch (error) {
                console.error("Errore durante l'interrogazione al database:", error);
            }
        }, 2000);

        let waitingListMarkers = [];

        const updatePassengerLists = async () => {
            try {
                // Fetch passengers on board
                const onBoardResponse = await fetch("/api/getPassengersOnBoardForTrip", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        token: currentUser.token,
                        tripId: trip.tripId,
                    }),
                });

                if (!onBoardResponse.ok) {
                    console.error("Errore nella richiesta dei passeggeri a bordo.");
                    return;
                }

                const passengersOnBoard = await onBoardResponse.json();

                // Fetch passengers in the waiting list
                const waitingListResponse = await fetch("/api/getPassengersForTrip", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        token: currentUser.token,
                        tripId: trip.tripId,
                    }),
                });

                if (!waitingListResponse.ok) {
                    console.error("Errore nella richiesta dei passeggeri in lista d'attesa.");
                    return;
                }

                const passengersWaiting = await waitingListResponse.json();

                // Update the UI
                const sidebarContent = document.querySelector(".ride-requests-content");

                // Create a container for the passenger lists
                const passengerListsContainer = document.createElement("div");
                passengerListsContainer.classList.add("passenger-lists");

                // Add passengers on board section
                let onBoardHtml = "<h4 style='margin-bottom: 10px;'>Passeggeri a bordo</h4>";
                if (passengersOnBoard.length > 0) {
                    onBoardHtml += "<div class='card p-3 mb-3'><ul>";
                    passengersOnBoard.forEach(p => {
                        onBoardHtml += `<li>${p.name} ${p.surname}</li>`;
                    });
                    onBoardHtml += "</ul></div>";
                } else {
                    onBoardHtml += `<div class='card p-3 mb-3'><?= view('noResultFound') ?></div>`;
                }

                // Add passengers waiting section
                let waitingListHtml = "<h4 style='margin-bottom: 10px;'>Lista d'attesa</h4>";
                if (passengersWaiting.length > 0) {
                    waitingListHtml += "<div class='card p-3 mb-3'><ul>";
                    passengersWaiting.forEach(p => {
                        waitingListHtml += `<li>${p.name} ${p.surname}</li>`;
                    });
                    waitingListHtml += "</ul></div>";
                } else {
                    waitingListHtml += `<div class='card p-3 mb-3'><?= view('noResultFound') ?></div>`;
                }

                // Combine sections
                passengerListsContainer.innerHTML = onBoardHtml + waitingListHtml;

                // Remove old passenger lists if they exist
                const oldPassengerLists = sidebarContent.querySelector(".passenger-lists");
                if (oldPassengerLists) {
                    oldPassengerLists.remove();
                }


                sidebarContent.appendChild(passengerListsContainer);

                // Update markers for passengers in the waiting list
                // Clear existing markers
                waitingListMarkers.forEach(marker => marker.setMap(null));
                waitingListMarkers = [];

                passengersWaiting.forEach(passenger => {
                    console.log(passenger)
                    const marker = new google.maps.Marker({
                        position: { lat: +passenger.enterLatitude, lng: +passenger.enterLongitude },
                        map: map,
                        title: `${passenger.name} ${passenger.surname}`,
                        icon: {
                            path: google.maps.SymbolPath.CIRCLE,
                            scale: 8,
                            fillColor: 'orange',
                            fillOpacity: 1,
                            strokeWeight: 1,
                            strokeColor: 'orange'
                        }
                    });
                    waitingListMarkers.push(marker);
                });

                // Remove markers for passengers who are now on board
                passengersOnBoard.forEach(onBoardPassenger => {
                    waitingListMarkers = waitingListMarkers.filter(marker => {
                        if (marker.getTitle() === `${onBoardPassenger.name} ${onBoardPassenger.surname}`) {
                            marker.setMap(null);
                            return false;
                        }
                        return true;
                    });
                });

            } catch (error) {
                console.error("Errore durante l'aggiornamento delle liste dei passeggeri:", error);
            }
        };

        // Call the function periodically to keep the lists and markers updated
        setInterval(updatePassengerLists, 2000);

        const endTripButton = document.querySelector(".btn-danger");

        // Aggiungi stile per il pulsante disabilitato
        endTripButton.style.opacity = "0.5";
        endTripButton.style.cursor = "not-allowed";

        const updateEndTripButtonState = async () => {
            try {
                const response = await fetch("/api/getPassengersOnBoardForTrip", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        token: currentUser.token,
                        tripId: trip.tripId,
                    }),
                });

                if (!response.ok) {
                    console.error("Errore nella richiesta dei passeggeri a bordo.");
                    return;
                }

                const passengersOnBoard = await response.json();

                // Abilita o disabilita il pulsante in base al numero di passeggeri a bordo
                endTripButton.disabled = passengersOnBoard.length !== 0;
                endTripButton.style.opacity = passengersOnBoard.length === 0 ? "1" : "0.5";
                endTripButton.style.cursor = passengersOnBoard.length === 0 ? "pointer" : "not-allowed";
            } catch (error) {
                console.error("Errore durante l'aggiornamento dello stato del pulsante Termina Viaggio:", error);
            }
        };

        // Aggiorna lo stato del pulsante ogni 2 secondi
        setInterval(updateEndTripButtonState, 2000);

        // Esegui un controllo iniziale
        updateEndTripButtonState();
    })()
</script>
<script src="<?= base_url('/Script/observer.js'); ?>"></script>

</html>
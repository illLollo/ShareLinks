<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOMEPAGE</title>
    <link rel="stylesheet" href="<?= base_url('/Style/global.css') ?>">
</head>

<body style="position: relative; margin: 0 !important;">

    <?php if (session()->has('toastMessage')): ?>
        <?= view('toast') ?>
    <?php endif; ?>
    <div class="d-flex" style="height: 92vh; margin-top: 8vh;">
        <div class="sidebar fade-in" style="z-index: 1;">
            <!-- Main Content -->
            <div class="container py-5">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <!-- Search Card -->
                        <div class="card search-card mb-4">
                            <div class="card-body p-4">
                                <h4 class="card-title mb-4">Dove vuoi andare?</h4>

                                <form id="rideRequestForm" method="post">
                                    <!-- Route Selection -->
                                    <div class="mb-4">

                                        <div id="intermediateDestinationContainer" class="d-flex align-items-center"
                                            style="display: none !important; flex-direction: column;">
                                            <div class="d-flex align-items-center">
                                                <div class="route-icon me-3">
                                                    <i class="fas fa-flag-checkered"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <label class="form-label">Destinazione Intermedia</label>
                                                    <input type="text" class="form-control destination"
                                                        name="middleDestination"
                                                        placeholder="Inserisci indirizzo di destinazione">
                                                </div>
                                            </div>
                                            <button type="button" id="removeIntermediateDestination"
                                                class="btn btn-danger mt-2">Rimuovi Destinazione</button>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div class="route-icon me-3">
                                                <i class="fas fa-flag-checkered"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <label class="form-label">Destinazione</label>
                                                <input type="text" class="form-control destination" name="destination"
                                                    placeholder="Inserisci indirizzo di destinazione" required>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" id="addIntermediateDestination" class="btn btn-link">
                                        <img src="path/to/plus-icon.png" alt="Aggiungi"
                                            style="width: 20px; height: 20px;"> Aggiungi destinazione
                                    </button>
                                    <!-- Passengers -->
                                    <div class="mb-4">
                                        <label for="passengers" class="form-label">Quanti passeggeri puoi
                                            portare?</label>
                                        <select class="form-select" id="passengers">
                                            <option value="1">1 passeggero</option>
                                            <option value="2">2 passeggeri</option>
                                            <option value="3">3 passeggeri</option>
                                            <option value="4">4 passeggeri</option>
                                        </select>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100 py-2">
                                        <i class="fas fa-search me-2"></i>Trova percorsi!
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="routeContainer">

                        </div>
                        <!-- Available Rides (Example) -->
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

        const addIntermediateDestinationButton = document.getElementById('addIntermediateDestination');
        const intermediateDestinationContainer = document.getElementById('intermediateDestinationContainer');
        const removeIntermediateDestinationButton = document.getElementById('removeIntermediateDestination');

        addIntermediateDestinationButton.addEventListener('click', function () {
            intermediateDestinationContainer.style.display = 'block';
            this.style.display = 'none'; // Hide the button after adding the destination input
        });
        removeIntermediateDestinationButton.addEventListener('click', function () {
            intermediateDestinationContainer.style.setProperty('display', 'none', 'important');
            addIntermediateDestinationButton.style.display = 'block';
        });

        (g => { var h, a, k, p = "The Google Maps JavaScript API", c = "google", l = "importLibrary", q = "__ib__", m = document, b = window; b = b[c] || (b[c] = {}); var d = b.maps || (b.maps = {}), r = new Set, e = new URLSearchParams, u = () => h || (h = new Promise(async (f, n) => { await (a = m.createElement("script")); e.set("libraries", [...r] + ",places"); for (k in g) e.set(k.replace(/[A-Z]/g, t => "_" + t[0].toLowerCase()), g[k]); e.set("callback", c + ".maps." + q); a.src = `https://maps.${c}apis.com/maps/api/js?` + e; d[q] = f; a.onerror = () => h = n(Error(p + " could not load.")); a.nonce = m.querySelector("script[nonce]")?.nonce || ""; m.head.append(a) })); d[l] ? console.warn(p + " only loads once. Ignoring:", g) : d[l] = (f, ...n) => r.add(f) && u().then(() => d[l](f, ...n)) })({
            key: "AIzaSyAZb0l2AiZau6rPCfhGmH7-9CaZFycIK4w",
            v: "weekly",
            // Use the 'v' parameter to indicate the version to use (weekly, beta, alpha, etc.).
            // Add other bootstrap parameters as needed, using camel case.
        });

        const destinations = {}
        const { Map } = await google.maps.importLibrary("maps");
        const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

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


        // The map, centered at Uluru
        const map = new Map(document.getElementById("map"), {
            zoom: 18,
            center: userPosition,
            mapId: "SL_MAP",
        });

        const marker = new AdvancedMarkerElement({
            map: map,
            position: userPosition,
            title: "La tua posizione",
        });

        const destinationInputs = Array.from(document.querySelectorAll('.destination'));

        const pathRenderers = {}

        destinationInputs.forEach((input) => {
            const autocomplete = new google.maps.places.Autocomplete(input);

            autocomplete.addListener("place_changed", async () => {
                const place = autocomplete.getPlace();
                if (!place.geometry || !place.geometry.location) {
                    console.error("Place has no geometry");
                    return;
                }

                // Store the selected coordinates in a data attribute
                input.dataset.coords = JSON.stringify({
                    lat: place.geometry.location.lat(),
                    lng: place.geometry.location.lng(),
                });

                destinations[input.name] = {
                    lat: place.geometry.location.lat(),
                    lng: place.geometry.location.lng(),
                }

                // Add a marker for the selected place
                const marker = new google.maps.Marker({
                    position: place.geometry.location,
                    map: map,
                    title: input.value,
                });

                // Optionally, center the map on the selected location
                map.setCenter(place.geometry.location);
            });
        });

        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('hidden');
        }

        document.getElementById('rideRequestForm').addEventListener('submit', async function (event) {
            event.stopImmediatePropagation();
            event.preventDefault(); // Prevent form submission

            try {
                const directionsService = new google.maps.DirectionsService();

                let result;
                if (Object.keys(destinations).length === 1) {
                    // Single destination
                    const destinationCoords = Object.values(destinations)[0];
                    result = await directionsService.route({
                        origin: userPosition,
                        destination: destinationCoords,
                        travelMode: google.maps.TravelMode.DRIVING,
                        provideRouteAlternatives: true,
                    });
                } else {
                    // Multiple destinations (intermediate and final)
                    const destinationKeys = Object.keys(destinations);
                    const waypoints = destinationKeys.slice(0, -1).map(key => ({
                        location: destinations[key],
                        stopover: true,
                    }));

                    result = await directionsService.route({
                        origin: userPosition,
                        destination: destinations[destinationKeys[destinationKeys.length - 1]],
                        waypoints: waypoints,
                        travelMode: google.maps.TravelMode.DRIVING,
                        provideRouteAlternatives: true,
                    });
                }

                // Clear previous results
                const routeContainer = document.querySelector('.routeContainer');
                routeContainer.innerHTML = '';

                // Display up to 3 routes on the map and in the sidebar
                const routes = result.routes.slice(0, 3);

                const totalDistance = routes.map(route => route.legs.reduce((total, leg) => total + leg.distance.value, 0));
                const totalTime = routes.map(route => route.legs.reduce((total, leg) => total + leg.duration.value, 0));

                routeContainer.innerHTML = '<h2 class"text-center">Percorsi disponibili</h2>';


                routes.forEach((route, index) => {
                    // Render the route on the map
                    pathRenderers[index] = new google.maps.DirectionsRenderer({
                        map: map,
                        directions: result,
                        routeIndex: index,
                        suppressMarkers: true, // Suppress default markers
                        polylineOptions: {
                            strokeColor: 'green',
                            strokeOpacity: 1.0,
                            strokeWeight: 3,
                        },
                    });

                    console.log(route);
                    let content = ''
                    content +=
                        `               <div class="route p-3 mb-3 card">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center">
                                <div>
                                    <h6 class="mb-0">Tramite ${route.summary}</h6>
                                    <small class="text-muted" class="minutes">Distanza: ${(totalDistance[index] / 1000).toFixed(1)} km | ${Math.ceil(totalTime[index] / 60)} mins</small>
                                </div>
                            </div>
                        </div>

                        <div class="route-details mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <div class="flex-grow-1">
                                `
                    route.legs.forEach((leg, i) => {
                        if (i === 0) {
                            content += `
                                            <div class="d-flex align-items-center">
                                                <div class="route-icon me-2">
                                                    <i class="fas fa-circle" style="font-size: 0.6rem;"></i>
                                                </div>
                                                <span>Partenza: ${leg.start_address}</span>
                                            </div>
                                        `
                        }
                        else {
                            content += `
                                            <div class="d-flex align-items-center">
                                                <div class="route-icon me-2">
                                                    <i class="fas fa-circle" style="font-size: 0.6rem;"></i>
                                                </div>
                                                <span>Per: ${leg.start_address}</span>
                                            </div>
                                        `
                        }
                        if (i === route.legs.length - 1) {
                            content += `
                                            <div class="d-flex align-items-center">
                                                <div class="route-icon me-2">
                                                    <i class="fas fa-circle" style="font-size: 0.6rem;"></i>
                                                </div>
                                                <span>Arrivo: ${leg.end_address}</span>
                                            </div>
                                        `
                        }
                    });
                    content += `
                                </div>
                            </div>
                        </div>

                        <!-- div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-light text-dark me-2">
                                    <i class="fas fa-car me-1"></i> BMW X1
                                </span>
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-user-friends me-1"></i> 2 posti disponibili
                                </span>
                            </div> -->
                            <button class="btn btn-sm btn-outline-primary select-route">Seleziona questa tratta</button>
                        </div>`

                    routeContainer.innerHTML += content;
                });

                const routeElements = routeContainer.querySelectorAll('.route');

                routeElements.forEach((route, index) => {
                    route.addEventListener("click", async () => {

                        routeElements.forEach(el => {
                            el.classList.remove('border-success', 'shadow');
                        });

                        route.classList.add('border-success', 'shadow');

                        Object.values(pathRenderers).forEach((renderer, i) => {
                            if (i === index) {
                                return;
                            }

                            renderer.setMap(null);
                            renderer.setOptions({
                                polylineOptions: {
                                    strokeColor: 'green',
                                    strokeOpacity: 1.0,
                                    strokeWeight: 3,
                                },
                            });
                            renderer.setMap(map);
                        });

                        pathRenderers[index].setMap(null);
                        pathRenderers[index].setOptions({
                            polylineOptions: {
                                strokeColor: 'red',
                                strokeOpacity: 1.0,
                                strokeWeight: 5,
                            },
                        });
                        pathRenderers[index].setMap(map);
                    })
                })

                routeElements[0]?.click();

                document.querySelectorAll('.select-route').forEach(button => {
                    button.addEventListener('click', (e) => {
                        // Open car selection modal with animation
                        const modal = document.createElement('div');
                        modal.classList.add('car-selection-modal');
                        modal.innerHTML = `
                            <div class="modal-content" style="background: white; padding: 20px; border-radius: 8px; width: 400px; text-align: center;">
                                <h5>Seleziona un'auto</h5>
                                <ul class="car-list" style="list-style: none; padding: 0;">
                                    <li class="car-item" style="margin: 10px 0; cursor: pointer;">Auto 1</li>
                                    <li class="car-item" style="margin: 10px 0; cursor: pointer;">Auto 2</li>
                                    <li class="car-item" style="margin: 10px 0; cursor: pointer;">Auto 3</li>
                                </ul>
                                <button class="close-modal" style="margin-top: 20px; padding: 10px 20px; background: var(--primary-color); color: white; border: none; border-radius: 4px; cursor: pointer;">Chiudi</button>
                            </div>
                        `;

                        document.body.appendChild(modal);

                        // Trigger the animation by setting opacity to 1
                        requestAnimationFrame(() => {
                            modal.style.opacity = '1';
                        });

                        // Close modal functionality
                        modal.querySelector('.close-modal').addEventListener('click', () => {
                            modal.style.opacity = '0'; // Fade out animation
                            setTimeout(() => {
                                document.body.removeChild(modal);
                            }, 300); // Wait for the animation to complete before removing
                        });
                    })
                });

            } catch (error) {
                console.error("Errore durante la ricerca dei percorsi:", error);
                alert("Si è verificato un errore durante la ricerca dei percorsi. Riprova più tardi.");
            }
        });
    })();

</script>
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

        .car-selection-modal {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 1000;
                opacity: 0;
                transition: opacity 0.3s ease-in-out;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3); /* Add a nice box shadow */
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
<script src="<?= base_url('/Script/observer.js'); ?>"></script>

</html>
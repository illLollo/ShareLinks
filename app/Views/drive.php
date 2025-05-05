<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOMEPAGE</title>
    <link rel="stylesheet" href="<?= base_url('/Style/global.css') ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body style="overflow-y: hidden; margin: 0 !important;">

    <?php if (session()->has('toastMessage')): ?>
        <?= view('toast') ?>
    <?php endif; ?>
    <div class="d-flex" style="height: 92vh; margin-top: 8vh;">
        <?= view('sidebar', ["direction" => "left"]); ?>
        <div class="sidebar-header d-flex justify-content-center align-items-center">
            <h1 class="text-center">Dove vuoi Andare</h1>
        </div>
        <div class="sidebar-content fade-in">
            <!-- Search Card -->
            <div class="card search-card mb-4">
                <div class="card-body p-4">

                    <form id="rideRequestForm" method="post">
                        <!-- Route Selection -->
                        <div class="mb-4">

                            <div id="intermediateDestinationContainer" style="flex-direction: column;">

                            </div>
                            <div class="d-flex align-items-center">
                                <div class="route-icon me-3">
                                    <i class="fas fa-flag-checkered"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <label class="form-label">Destinazione</label>
                                    <div class="d-flex align-items-center justify-content-between gap-2">
                                        <input type="text" class="form-control destination" name="destination"
                                            placeholder="Inserisci indirizzo di destinazione" required>
                                        <i id="addIntermediateDestination" class="bi bi-plus-circle"
                                            style="font-size: 1.5rem; color: green;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
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
        <?= view('closeSidebar'); ?>

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

        const addIntermediateDestinationButton = document.getElementById('addIntermediateDestination');
        const intermediateDestinationContainer = document.getElementById('intermediateDestinationContainer');


        const pathRenderers = {}
        const markers = []
        const destinations = {
            intermediate: [],
            final: {},
        }

        addIntermediateDestinationButton.addEventListener('click', function () {
            const div = document.createElement('div');
            div.classList.add('intermediateContainer', 'flex-grow-1');
            div.innerHTML = `
                <div class="d-flex align-items-center">
                    <div class="route-icon me-3">
                        <i class="fas fa-flag-checkered"></i>
                    </div>
                </div>
                <label class="form-label">Destinazione Intermedia</label>
                <div class="d-flex align-items-center justify-content-between gap-2">
                    <input type="text" class="form-control destination" name="intermediateDestination"
                        placeholder="Inserisci indirizzo di destinazione">
                    <i class="bi bi-x-circle removeIntermediateDestination" style="font-size: 1.5rem; cursor: pointer;"></i>
                </div>
            `;

            const input = div.querySelector('input');
            const removeButton = div.querySelector('.removeIntermediateDestination');
            const autocomplete = new google.maps.places.Autocomplete(input);

            let marker; // Marker for the intermediate destination

            autocomplete.addListener("place_changed", () => {
                const place = autocomplete.getPlace();
                if (!place.geometry || !place.geometry.location) {
                    console.error("Place has no geometry");
                    return;
                }

                // Save the coordinates in the destinations.intermediate array
                const intermediateCoords = {
                    lat: place.geometry.location.lat(),
                    lng: place.geometry.location.lng(),
                };
                destinations.intermediate.push(intermediateCoords);

                // Add a marker for the intermediate destination
                if (marker) {
                    marker.setMap(null); // Remove the previous marker if it exists
                }
                marker = new google.maps.Marker({
                    position: place.geometry.location,
                    map: map,
                    title: input.value,
                });

                // Optionally, center the map on the selected location
                map.setCenter(place.geometry.location);
            });

            removeButton.addEventListener('click', function () {
                div.remove(); // Remove the input field

                // Remove the marker from the map
                if (marker) {
                    marker.setMap(null);
                    marker = null;
                }

                // Remove the coordinates from the destinations.intermediate array
                const index = destinations.intermediate.findIndex(coord => coord.lat === marker.getPosition().lat() && coord.lng === marker.getPosition().lng());
                if (index !== -1) {
                    destinations.intermediate.splice(index, 1);
                }
            });

            intermediateDestinationContainer.appendChild(div);
        });

        const autocomplete = new google.maps.places.Autocomplete(document.querySelector('.destination'));

        autocomplete.addListener("place_changed", async () => {

            Object.values(pathRenderers).forEach(renderer => {
                renderer.setMap(null);
                delete renderer
            });

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

            destinations["final"] = {
                lat: place.geometry.location.lat(),
                lng: place.geometry.location.lng(),
            };

            // Add a marker for the selected place
            markers.push(new google.maps.Marker({
                position: place.geometry.location,
                map: map,
                title: input.value,
            }));

            // Optionally, center the map on the selected location
            map.setCenter(place.geometry.location);
        });

        const finalDestinationInput = document.querySelector('.destination');
        const finalDestinationAutocomplete = new google.maps.places.Autocomplete(finalDestinationInput);

        let finalMarker; // Marker for the final destination

        finalDestinationAutocomplete.addListener("place_changed", () => {
            const place = finalDestinationAutocomplete.getPlace();
            if (!place.geometry || !place.geometry.location) {
                console.error("Place has no geometry");
                return;
            }

            // Remove the previous marker if it exists
            if (finalMarker) {
                finalMarker.setMap(null);
            }

            // Add a marker for the final destination
            finalMarker = new google.maps.Marker({
                position: place.geometry.location,
                map: map,
                title: finalDestinationInput.value,
            });

            // Optionally, center the map on the selected location
            map.setCenter(place.geometry.location);

            // Save the coordinates in the destinations object under 'final'
            destinations['final'] = {
                lat: place.geometry.location.lat(),
                lng: place.geometry.location.lng(),
            };
        });

        document.getElementById('rideRequestForm').addEventListener('submit', async function (event) {
            console.log(destinations);
            event.stopImmediatePropagation();
            event.preventDefault(); // Prevent form submission

            try {
                // Clear previous routes and markers
                Object.values(pathRenderers).forEach(renderer => {
                    renderer.setMap(null);
                    delete pathRenderers
                });


                const directionsService = new google.maps.DirectionsService();

                let result;
                if (destinations.intermediate.length === 0) {
                    // Single destination
                    const destinationCoords = destinations.final;
                    result = await directionsService.route({
                        origin: userPosition,
                        destination: destinationCoords,
                        travelMode: google.maps.TravelMode.DRIVING,
                        provideRouteAlternatives: true,
                    });
                } else {
                    // Multiple destinations (intermediate and final)
                    const waypoints = destinations.intermediate.map(coord => ({
                        location: coord,
                        stopover: true,
                    }));

                    result = await directionsService.route({
                        origin: userPosition,
                        destination: destinations.final,
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

                console.log(routes)

                const totalDistance = routes.map(route => route.legs.reduce((total, leg) => total + leg.distance.value, 0));
                const totalTime = routes.map(route => route.legs.reduce((total, leg) => total + leg.duration.value, 0));

                routeContainer.innerHTML = '<h2 class="text-center">Percorsi disponibili</h2>';

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

                    let content = '';
                    content += `
                        <div class="route fade-in p-3 mb-3 card">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h6 class="mb-0">Tramite ${route.summary}</h6>
                                        <small class="text-muted">Distanza: ${(totalDistance[index] / 1000).toFixed(1)} km | ${Math.ceil(totalTime[index] / 60)} mins</small>
                                    </div>
                                </div>
                            </div>
                            <div class="route-details mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="flex-grow-1">
                    `;
                    route.legs.forEach((leg, i) => {
                        if (i === 0) {
                            content += `
                                <div class="d-flex align-items-center">
                                    <div class="route-icon me-2">
                                        <i class="fas fa-circle" style="font-size: 0.6rem;"></i>
                                    </div>
                                    <span>Partenza: ${leg.start_address}</span>
                                </div>
                            `;
                        } else {
                            content += `
                                <div class="d-flex align-items-center">
                                    <div class="route-icon me-2">
                                        <i class="fas fa-circle" style="font-size: 0.6rem;"></i>
                                    </div>
                                    <span>Per: ${leg.start_address}</span>
                                </div>
                            `;
                        }
                        if (i === route.legs.length - 1) {
                            content += `
                                <div class="d-flex align-items-center">
                                    <div class="route-icon me-2">
                                        <i class="fas fa-circle" style="font-size: 0.6rem;"></i>
                                    </div>
                                    <span>Arrivo: ${leg.end_address}</span>
                                </div>
                            `;
                        }
                    });
                    content += `
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-sm btn-outline-primary select-route">Seleziona questa tratta</button>
                        </div>
                    `;

                    routeContainer.innerHTML += content;

                    routeContainer.querySelectorAll('.select-route').forEach(button => {
                        button.addEventListener('click', async (e) => {
                            e.stopImmediatePropagation();
                            e.preventDefault(); // Prevent form submission


                            document.querySelectorAll(".select-car").forEach(select => select.remove());
                            const currentUser = <?= json_encode($user); ?>;

                            const response = await fetch("/api/getCars", {
                                method: 'POST',
                                body: JSON.stringify({
                                    token: currentUser.token,
                                }),
                            });

                            if (!response.ok) {
                                throw new Error('Errore nella richiesta al server.');
                            }

                            const cars = await response.json();

                            // <select class="form-select car-list" style="margin: 10px 0;">
                            // <option value="auto1">Auto 1</option>

                            const container = document.createElement('div');
                            container.classList.add('sidebar-container');

                            let carsBody = `
                        <div class="sidebar-container select-car">
                        <div class="sidebar d-flex align-items-center" style="right: 20px; flex-direction: column">
                        <button class="close-btn" onclick="this.parentElement.parentElement.classList.add('hidden'); this.parentElement.parentElement.remove();">&times;</button>`;


                            carsBody += `<div class="sidebar-header">`;
                            carsBody += `<h1>Seleziona un\'auto</h1></div>
                        <div class="sidebar-content d-flex flex-column align-items-center">
                        <select class="form-select car-list" style="margin: 10px 0;">
                        `

                            cars.forEach((car, i) => {
                                carsBody += `
                        <option class="car-item" style="margin: 10px 0; cursor: pointer;" value="${car.carId}">${car.name} | ${car.plateNumber}</option>
                            `;

                            });

                            carsBody += `</select>
                        <button class="startDriving" style="margin-top: 20px; padding: 10px 20px; background: var(--primary-color); color: white; border: none; border-radius: 4px; cursor: pointer;">Iniziamo!</button>
                        </div>`;

                            carsBody += `</div>
                    </div>`;


                            container.innerHTML += carsBody;
                            document.body.appendChild(container);

                            document.querySelector('.startDriving').addEventListener('click', async (event) => {
                                const selectedCarId = document.querySelector('.car-list').value;
                                const selectedRouteIndex = Array.from(document.querySelectorAll('.route')).findIndex(route => route.classList.contains('border-success'));

                                const selectedRoute = routes[selectedRouteIndex];

                                console.log(selectedRoute)

                                const waypoints = [];
                                selectedRoute.legs.forEach((leg, legIndex) => {
                                        // Add the starting point
                                        waypoints.push({
                                            latitude: leg.start_location.lat(),
                                            longitude: leg.start_location.lng(),
                                            ordinal: legIndex
                                        });

                                    if (legIndex === selectedRoute.legs.length - 1) {
                                        waypoints.push({
                                            latitude: leg.end_location.lat(),
                                            longitude: leg.end_location.lng(),
                                            ordinal: legIndex + 1
                                        });
                                    }
                                });

                                const totalDistance = selectedRoute.legs.reduce((total, leg) => total + leg.distance.value, 0) / 1000; // in km
                                const totalTime = selectedRoute.legs.reduce((total, leg) => total + leg.duration.value, 0) / 60; // in minutes

                                const passengers = document.getElementById('passengers').value;

                                const form = document.createElement('form');
                                form.method = 'POST';
                                form.action = '/drive/driving';

                                const appendHiddenInput = (name, value) => {
                                    const input = document.createElement('input');
                                    input.type = 'hidden';
                                    input.name = name;
                                    input.value = value;
                                    form.appendChild(input);
                                };

                                appendHiddenInput('driverId', <?= json_encode($driver->driverId); ?>);
                                appendHiddenInput('carId', selectedCarId);
                                appendHiddenInput('time', totalTime);
                                appendHiddenInput('distance', totalDistance);
                                appendHiddenInput('polyline', selectedRoute.overview_polyline);
                                appendHiddenInput('slots', passengers);
                                appendHiddenInput('steps', JSON.stringify(waypoints));

                                document.body.appendChild(form);

                                console.log(form);
                                form.submit();
                            });
                        });
                    });

                });

                const routeElements = routeContainer.querySelectorAll('.route');

                routeElements.forEach((route, index) => {
                    route.addEventListener("click", () => {
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
                    });
                });

                // Automatically select the first route
                routeElements[0]?.click();

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

    #addIntermediateDestination:hover {
        cursor: pointer;
    }

    .bi-x-circle:hover {
        cursor: pointer;
        transition: box-shadow 0.3s ease-in-out;
        /* Smooth transition */
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


    }
</style>
<script src="<?= base_url('/Script/observer.js'); ?>"></script>

</html>
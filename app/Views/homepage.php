<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOMEPAGE</title>
    <link rel="stylesheet" href="<?= base_url('/Style/global.css') ?>">
</head>

<body style="position: relative; margin: 0 !important;">
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
    <?php if (session()->has('toastMessage')): ?>
        <?= view('toast') ?>
    <?php endif; ?>
    <div class="d-flex" style="height: 92vh; margin-top: 8vh;">
        <?= view("sidebar", ["direction" => "left"]); ?>
        <div class="sidebar-header">
            <h class="mb-3">Viaggi disponibili</h2>
        </div>
        <div class="sidebar-content fade-in">
            <div class="card p-3 mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="d-flex align-items-center">
                        <img src="https://randomuser.me/api/portraits/women/44.jpg"
                            class="driver-avatar rounded-circle me-3">
                        <div>
                            <h6 class="mb-0">Laura Bianchi</h6>
                            <small class="text-muted">4.9 ★ (12 recensioni)</small>
                        </div>
                    </div>
                </div>

                <div class="route-details mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <small class="text-muted me-3">08:30</small>
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center">
                                <div class="route-icon me-2">
                                    <i class="fas fa-circle" style="font-size: 0.6rem;"></i>
                                </div>
                                <span>Piazza del Duomo, Milano</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="route-icon me-2">
                                    <i class="fas fa-circle" style="font-size: 0.6rem;"></i>
                                </div>
                                <span>Stazione Centrale, Milano</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="badge bg-light text-dark me-2">
                            <i class="fas fa-car me-1"></i> BMW X1
                        </span>
                        <span class="badge bg-light text-dark">
                            <i class="fas fa-user-friends me-1"></i> 2 posti disponibili
                        </span>
                    </div>
                    <button class="btn btn-sm btn-outline-primary">Prenota</button>
                </div>
            </div>
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
    (g => { var h, a, k, p = "The Google Maps JavaScript API", c = "google", l = "importLibrary", q = "__ib__", m = document, b = window; b = b[c] || (b[c] = {}); var d = b.maps || (b.maps = {}), r = new Set, e = new URLSearchParams, u = () => h || (h = new Promise(async (f, n) => { await (a = m.createElement("script")); e.set("libraries", [...r] + ",places"); for (k in g) e.set(k.replace(/[A-Z]/g, t => "_" + t[0].toLowerCase()), g[k]); e.set("callback", c + ".maps." + q); a.src = `https://maps.${c}apis.com/maps/api/js?` + e; d[q] = f; a.onerror = () => h = n(Error(p + " could not load.")); a.nonce = m.querySelector("script[nonce]")?.nonce || ""; m.head.append(a) })); d[l] ? console.warn(p + " only loads once. Ignoring:", g) : d[l] = (f, ...n) => r.add(f) && u().then(() => d[l](f, ...n)) })({
        key: "AIzaSyAZb0l2AiZau6rPCfhGmH7-9CaZFycIK4w",
        v: "weekly",
        // Use the 'v' parameter to indicate the version to use (weekly, beta, alpha, etc.).
        // Add other bootstrap parameters as needed, using camel case.
    });
    // Initialize and add the map
    let map;

    let departureInput = document.getElementById("departure");
    let destinationInput = document.getElementById("destination");
    let departureAutocomplete;
    let destinationAutocomplete;
    let departureMarker;
    let destinationMarker;
    let userMarker;

    function initAutocomplete() {
        // Initialize autocomplete for departure and destination inputs
        departureAutocomplete = new google.maps.places.Autocomplete(departureInput);
        destinationAutocomplete = new google.maps.places.Autocomplete(destinationInput);

        // Add listeners for place selection
        departureAutocomplete.addListener("place_changed", () => {
            const place = departureAutocomplete.getPlace();
            if (!place.geometry || !place.geometry.location) {
                console.error("Place has no geometry");
                return;
            }

            // Remove user marker if it exists
            if (userMarker) {
                userMarker.setMap(null);
            }

            // Remove previous departure marker
            if (departureMarker) {
                departureMarker.setMap(null);
            }

            // Add new marker for departure
            departureMarker = new google.maps.Marker({
                position: place.geometry.location,
                map: map,
                title: "Partenza",
            });

            // Center the map on the departure location
            map.setCenter(place.geometry.location);
        });

        destinationAutocomplete.addListener("place_changed", () => {
            const place = destinationAutocomplete.getPlace();
            if (!place.geometry || !place.geometry.location) {
                console.error("Place has no geometry");
                return;
            }

            // Remove user marker if it exists
            if (userMarker) {
                userMarker.setMap(null);
            }

            // Remove previous destination marker
            if (destinationMarker) {
                destinationMarker.setMap(null);
            }

            // Add new marker for destination
            destinationMarker = new google.maps.Marker({
                position: place.geometry.location,
                map: map,
                title: "Destinazione",
            });

            // Center the map on the destination location
            map.setCenter(place.geometry.location);
        });
    }

    async function initMap() {
        // The location of Uluru
        const position = { lat: -25.344, lng: 131.031 };
        // Request needed libraries.
        //@ts-ignore
        const { Map } = await google.maps.importLibrary("maps");
        const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

        // The map, centered at Uluru
        map = new Map(document.getElementById("map"), {
            zoom: 4,
            center: position,
            mapId: "DEMO_MAP_ID",
        });

        // The marker, positioned at Uluru
        const marker = new AdvancedMarkerElement({
            map: map,
            position: position,
            title: "Uluru",
        });

        navigator.geolocation.getCurrentPosition(
            async (position) => {
                const userPosition = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude,
                };

                // Center the map on the user's location
                map.setCenter(userPosition);
                map.setZoom(18); // Higher zoom for more precision

                // Add a marker for the user's location
                userMarker = new google.maps.Marker({
                    position: userPosition,
                    map: map,
                    title: "La tua posizione precisa",
                });

                const currentUser = <?= json_encode($user) ?>;

                const response = await fetch("/api/getTripsNearUser", {
                    method: 'POST',
                    body: JSON.stringify({
                        lat: userPosition.lat,
                        lng: userPosition.lng,
                        token: currentUser.token,
                    }),
                });

                if (!response.ok) {
                    throw new Error('Errore nella richiesta al server.');
                }

                const trips = await response.json();

                console.log(trips);
            },
            (error) => {
                console.error("Errore nel recupero della posizione:", error);
            },
            {
                enableHighAccuracy: true, // Request more precise location
            }
        );

        initAutocomplete();
    }

    initMap();

    function toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        sidebar.classList.toggle('hidden');
    }

    // async function load() {

    //     if (!departureMarker || !destinationMarker) {
    //         alert("Per favore, seleziona sia la partenza che la destinazione sulla mappa.");
    //         return;
    //     }

    //     const departureCoords = {
    //         lat: departureMarker.getPosition().lat(),
    //         lng: departureMarker.getPosition().lng(),
    //     };

    //     const destinationCoords = {
    //         lat: destinationMarker.getPosition().lat(),
    //         lng: destinationMarker.getPosition().lng(),
    //     };

    //     try {
    //         const directionsService = new google.maps.DirectionsService();

    //         // Request directions from Google Maps API
    //         const result = await directionsService.route({
    //             origin: departureCoords,
    //             destination: destinationCoords,
    //             travelMode: google.maps.TravelMode.DRIVING, // You can change this to WALKING, BICYCLING, or TRANSIT
    //             provideRouteAlternatives: true, // Request multiple route options
    //         });

    //         console.log("Dettagli delle opzioni di percorso:", result.routes);

    //         // Log the minimal data needed to reconstruct the route
    //         result.routes.forEach((route, index) => {
    //             const minimalData = {
    //                 start_location: {
    //                     lat: route.legs[0].start_location.lat(),
    //                     lng: route.legs[0].start_location.lng()
    //                 }, // Starting point
    //                 end_location: {
    //                     lat: route.legs[0].end_location.lat(),
    //                     lng: route.legs[0].end_location.lng()
    //                 }, // Ending point
    //                 polyline: route.overview_polyline, // Compressed polyline
    //             };

    //             console.log(`Percorso ${index + 1}:`);
    //             console.log("Dati minimi per ricostruire il percorso:", minimalData);
    //         });
    //     } catch (error) {
    //         console.error("Errore durante la ricerca dei percorsi:", error);
    //         alert("Si è verificato un errore durante la ricerca dei percorsi. Riprova più tardi.");
    //     }
    // }


    // document.getElementById('rideRequestForm').addEventListener('submit', async function(event) {

    //     try {


    //         // Clear previous results
    //         const resultsContainer = document.getElementById('resultsContainer');
    //         resultsContainer.innerHTML = '';

    //         // Add trips to the map and results container
    //         trips.forEach(trip => {
    //             // Add markers for each step of the trip
    //             trip.steps.forEach(step => {
    //                 const marker = new google.maps.Marker({
    //                     position: { lat: step.latitude, lng: step.longitude },
    //                     map: map,
    //                     title: `Step ${step.ordinal}`,
    //                 });
    //             });

    //             // Add trip details to the results container
    //             const tripElement = document.createElement('div');
    //             tripElement.classList.add('trip-result');
    //             tripElement.innerHTML = `
    //                 <h5>Viaggio ID: ${trip.tripId}</h5>
    //                 <p><strong>Partenza:</strong> ${trip.startTime}</p>
    //                 <p><strong>Arrivo stimato:</strong> ${trip.estimatedEndTime}</p>
    //                 <p><strong>Stato:</strong> ${trip.status}</p>
    //             `;
    //             resultsContainer.appendChild(tripElement);
    //         });
    //     } catch (error) {
    //         console.error('Errore durante la ricerca dei viaggi:', error);
    //         alert('Si è verificato un errore durante la ricerca dei viaggi. Riprova più tardi.');
    //     }
    // });
</script>
<script src="<?= base_url('/Script/observer.js'); ?>"></script>

</html>
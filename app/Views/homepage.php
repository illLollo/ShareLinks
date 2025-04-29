<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOMEPAGE</title>
    <link rel="stylesheet" href="<?= base_url('/Style/global.css') ?>">
</head>
<body>
<style>
    :root {
        --primary-color: #2E8B57;
        --secondary-color: #3AA76D;
        --light-gray: #F8F9FA;
    }
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: var(--light-gray);
    }
    .navbar-brand img { height: 40px; }
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
    body {
        margin: 0;
        padding: 0;
        display: flex;
        height: 100vh;
        overflow-y: hidden;
    }

    .sidebar {
        width: 45%;
        background-color: #f8f9fa;
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        overflow-y: scroll;
        height: 100vh;
    }

    .map-container {
        position: relative;
    }

    #map {
    height: 400px; /* The height is 400 pixels */
    width: 400px; /* The width is the width of the web page */
}
</style>
<div class="fade-in">
<?php if (session()->has('toastMessage')): ?>
        <?= view('toast') ?>
    <?php endif; ?>
<body class="driver-bg">

    <div class="sidebar">
        <!-- Main Content -->
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <!-- Search Card -->
                    <div class="card search-card mb-4">
                        <div class="card-body p-4">
                            <h4 class="card-title mb-4">Richiedi un passaggio</h4>

                            <form id="rideRequestForm">
                                <!-- Route Selection -->
                                <div class="mb-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="route-icon me-3">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <label for="departure" class="form-label">Partenza</label>
                                            <input type="text" class="form-control" id="departure" placeholder="Inserisci indirizzo di partenza" required>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center">
                                        <div class="route-icon me-3">
                                            <i class="fas fa-flag-checkered"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <label for="destination" class="form-label">Destinazione</label>
                                            <input type="text" class="form-control" id="destination" placeholder="Inserisci indirizzo di destinazione" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Date and Time -->
                                <div class="row mb-4">
                                    <div class="col-md-6 mb-3 mb-md-0">
                                        <label for="rideDate" class="form-label">Data</label>
                                        <input type="date" class="form-control" id="rideDate" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="rideTime" class="form-label">Orario</label>
                                        <input type="time" class="form-control" id="rideTime" required>
                                    </div>
                                </div>

                                <!-- Passengers -->
                                <div class="mb-4">
                                    <label for="passengers" class="form-label">Numero di passeggeri</label>
                                    <select class="form-select" id="passengers">
                                        <option value="1">1 passeggero</option>
                                        <option value="2">2 passeggeri</option>
                                        <option value="3">3 passeggeri</option>
                                        <option value="4">4 passeggeri</option>
                                    </select>
                                </div>

                                <!-- Preferences -->
                                <div class="mb-4">
                                    <label class="form-label">Preferenze</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="smokingAllowed">
                                        <label class="form-check-label" for="smokingAllowed">Fumatore</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="petFriendly">
                                        <label class="form-check-label" for="petFriendly">Viaggio con animali</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="luggage">
                                        <label class="form-check-label" for="luggage">Bagaglio voluminoso</label>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 py-2">
                                    <i class="fas fa-search me-2"></i>Cerca viaggi disponibili
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Available Rides (Example) -->
                    <h5 class="mb-3">Viaggi disponibili</h5>

                    <div class="available-rides p-3 mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center">
                                <img src="https://randomuser.me/api/portraits/women/44.jpg" class="driver-avatar rounded-circle me-3">
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

                    <div class="available-rides p-3 mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center">
                                <img src="https://randomuser.me/api/portraits/men/65.jpg" class="driver-avatar rounded-circle me-3">
                                <div>
                                    <h6 class="mb-0">Marco Rossi</h6>
                                    <small class="text-muted">4.7 ★ (8 recensioni)</small>
                                </div>
                            </div>
                        </div>

                        <div class="route-details mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <small class="text-muted me-3">08:45</small>
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center">
                                        <div class="route-icon me-2">
                                            <i class="fas fa-circle" style="font-size: 0.6rem;"></i>
                                        </div>
                                        <span>Via Torino, Milano</span>
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
                                    <i class="fas fa-car me-1"></i> Fiat 500
                                </span>
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-user-friends me-1"></i> 1 posto disponibile
                                </span>
                            </div>
                            <button class="btn btn-sm btn-outline-primary">Prenota</button>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button class="btn btn-link text-primary">
                            <i class="fas fa-map-marked-alt me-2"></i>Visualizza tutti i viaggi sulla mappa
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="map-container">
        <div id="map"></div>
        </div>
<script defer>
        (g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})({
        key: "AIzaSyAZb0l2AiZau6rPCfhGmH7-9CaZFycIK4w",
        v: "weekly",
        // Use the 'v' parameter to indicate the version to use (weekly, beta, alpha, etc.).
        // Add other bootstrap parameters as needed, using camel case.
    });
    // Initialize and add the map
    let map;

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
    }

    initMap();
    </script>
</body>
</html>
    <?= view("footer") ?>
</div>
<script src="<?= base_url('/Script/observer.js'); ?>"></script>
</body>
</html>


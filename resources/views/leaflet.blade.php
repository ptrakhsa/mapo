<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
        integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
        crossorigin="" />
    <link rel="stylesheet" href="https://labs.easyblog.it/maps/leaflet-search/src/leaflet-search.css">
    <link rel="stylesheet" href="/assets/css/bootstrap.css">




    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.3.0/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.3.0/dist/MarkerCluster.Default.css" />




    <style>
        #mapid {
            min-height: 100vh;
        }

        body {
            overflow-x: hidden;
        }

        #event-list {
            height: 100vh;
            overflow-y: scroll;
        }

    </style>
</head>

<body>

    <div class="row">

        <div class="col-md-4 container" id="event-list"></div>
        <div class="col-md-8">
            <div id="mapid"></div>
        </div>

    </div>



    <!-- Leaflet JavaScript -->
    <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
        integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
        crossorigin="">
    </script>
    <!-- marker groups ext -->
    <script src="https://unpkg.com/leaflet.markercluster@1.3.0/dist/leaflet.markercluster.js"></script>
    <script>
        // const center 
        const CENTER_POS = {
            lat: -7.797068,
            lng: 110.370529
        }
        const ZOOM = 20;

        // init map
        var map = L
            .map('mapid')
            .setView([CENTER_POS.lat, CENTER_POS.lng], ZOOM);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);





        // parse json as html content 
        function eventToListView(response) {
            const events = response.features
            let listViewContent = Array.from(events).map(it =>
                `<div class="card mb-3">
                <div class="row g-0">
                    <div class="col-md-4">
                        <img src="/assets/images/samples/banana.jpg" class="img-fluid rounded-start">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">${it.properties.name}</h5>
                            <p class="card-text">${it.properties.description}</p>
                            <p class="card-text"><small class="text-muted">${it.properties.start_date}</small></p>
                        </div>
                    </div>
                </div>
            </div>`)
            document.getElementById('event-list').innerHTML = ''
            document.getElementById('event-list').innerHTML = listViewContent

        }

        var markers = L.markerClusterGroup();
        // binding locations
        var layer

        function getEventsByCurrPos(lat, lng) {
            fetch(`/api/locations?lat=${lat}&lng=${lng}`)
                .then(r => r.json())
                .then(d => {

                    eventToListView(d)

                    layer = L.geoJSON(d, {
                            pointToLayer: (geoJsonPoint, latlng) => L.marker(latlng)
                        })
                        .bindPopup(function(layer) {
                            let htmlContent =
                                `
                            <div class="my-2"><strong>Place Name</strong> :<br>  ${layer.feature.properties.name} </div> 
                            <div class="my-2"><strong>Description</strong>:<br>  ${layer.feature.properties.description}  </div>
                            <div class="my-2"><strong>Address</strong>:<br>  ${layer.feature.properties.location}  </div>`
                            return htmlContent;
                        })
                        .addTo(map);
                })
        }

        getEventsByCurrPos(CENTER_POS.lat, CENTER_POS.lng)


        // event listener

        map.on("moveend", function() {
            setTimeout(() => {
                // reset map 
                // clear previous layer 
                map.removeLayer(layer);
                let {
                    lat,
                    lng
                } = map.getCenter()
                getEventsByCurrPos(lat, lng)
            }, 1500);
        });
    </script>
</body>

</html>

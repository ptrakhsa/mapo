<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no" />
    <title>
        Intro to MapView - Create a 2D map | Sample | ArcGIS API for JavaScript
        4.23
    </title>
    <style>
        html,
        body,
        #viewDiv {
            padding: 0;
            margin: 0;
            height: 100%;
            width: 100%;
        }

    </style>

    <link rel="stylesheet" href="https://js.arcgis.com/4.23/esri/themes/light/main.css" />
    <script src="https://js.arcgis.com/4.23/"></script>

    <script>
        require([
            "esri/config",
            "esri/Map",
            "esri/views/MapView",
            "esri/layers/FeatureLayer",
            "esri/widgets/Search",
            "esri/rest/locator",
            "esri/Graphic"
        ], (config, Map, MapView, FeatureLayer, Search, locator, Graphic) => {
            // load api 
            config.apiKey =
                'AAPK8d80c37361a24b9586f81b0d343992075i5r5wpgsl6DATkOWVvHHOUjOVtJshiguosb7lo2dfrPDAo4YFKEeMa-kFfdk3qy'

            // load data 
            const layer = new FeatureLayer({
                url: "https://services5.arcgis.com/3hvIdjVlp5w5AeYl/arcgis/rest/services/trailheads/FeatureServer/0"
            });



            // instantiate map
            const map = new Map({
                basemap: "arcgis-navigation",
                layers: [layer]
            });

            // bind map to html
            const view = new MapView({
                container: "viewDiv",
                map: map,
                zoom: 4,
                center: [-118.80543, 34.02700],
            });

            const search = new Search({ //Add Search widget
                view: view
            });

            view.ui.add(search, "top-right");


            // places 
            const places = ["Choose a place type...", "Parks and Outdoors", "Coffee shop", "Gas station", "Food",
                "Hotel"
            ];

            const select = document.createElement("select", "");
            select.setAttribute("class", "esri-widget esri-select");
            select.setAttribute("style", "width: 175px; font-family: 'Avenir Next W00'; font-size: 1em");


            places.forEach(function(p) {
                const option = document.createElement("option");
                option.value = p;
                option.innerHTML = p;
                select.appendChild(option);
            });

            view.ui.add(select, "top-right");

            const locatorUrl = "http://geocode-api.arcgis.com/arcgis/rest/services/World/GeocodeServer";
            // Find places and add them to the map
            function findPlaces(category, pt) {
                locator
                    .addressToLocations(locatorUrl, {
                        location: pt,
                        categories: [category],
                        maxLocations: 25,
                        outFields: ["Place_addr", "PlaceName"]
                    })
                    .then(function(results) {
                        view.popup.close();
                        view.graphics.removeAll();

                        results.forEach(function(result) {
                            view.graphics.add(
                                new Graphic({
                                    attributes: result.attributes, // Data attributes returned
                                    geometry: result.location, // Point returned
                                    symbol: {
                                        type: "simple-marker",
                                        color: "#000000",
                                        size: "12px",
                                        outline: {
                                            color: "#ffffff",
                                            width: "2px"
                                        }
                                    },

                                    popupTemplate: {
                                        title: "{PlaceName}", // Data attribute names
                                        content: "{Place_addr}"
                                    }
                                }));
                        });

                    });

            }

            view.watch("stationary", function(val) {
                if (val) {
                    findPlaces(select.value, view.center);
                }
            });

            // Listen for category changes and find places
            select.addEventListener('change', function(event) {
                findPlaces(event.target.value, view.center);
            });


        });
    </script>
</head>

<body>
    <div id="viewDiv"></div>
</body>

</html>

@section('title', 'Place Boundaries')
@extends('layouts.admin-panel')


@section('head')

    {{-- leaflet css --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
        integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
        crossorigin="" />
    <link rel="stylesheet" href="https://labs.easyblog.it/maps/leaflet-search/src/leaflet-search.css">
    <script src="https://unpkg.com/vue@2"></script>


    <style>
        #mapid {
            min-height: 80vh;
        }


        .grey-bg {
            background-color: rgba(110, 150, 186, 0.171);
        }

        .white-bg {
            background-color: white;
        }
    </style>

@endsection





@section('content')

    <div class="page-heading" id="app">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Place boundaries</h3>
                    <p class="text-subtitle text-muted">count events by place boundaries</p>
                </div>
            </div>
        </div>

        <div class="row">


            <div class="col-md-7 card card-content px-2 py-2">
                <div id="mapid"></div>
            </div>

            <div class="col-md-5">
                <div class="card">
                    <div class="card-content px-2 py-2">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Pendidikan</th>
                                        <th>Kebudayaan</th>
                                        <th>Total events</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(place,i) in places" :key="i" :id="`tr-${place.id}`">
                                        <td v-text="++i"></td>
                                        <td v-text="place.region"></td>
                                        <td v-text="place.pendidikan"></td>
                                        <td v-text="place.kebudayaan"></td>
                                        <td v-text="place.total"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>



    <!-- Leaflet JavaScript -->
    <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
        integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
        crossorigin=""></script>
    <!-- marker groups ext -->
    <script src="https://unpkg.com/leaflet.markercluster@1.3.0/dist/leaflet.markercluster.js"></script>
    <script>
        new Vue({
            el: '#app',
            data: () => ({
                places: null,
                map: null,
                layer: null,
                centerPos: {
                    lat: -7.797068,
                    lng: 110.370529
                },
            }),

            mounted() {
                this.initMap()
                this.loadJogjaBounds()
            },

            methods: {
                async loadJogjaBounds() {
                    let d = await fetch('/api/admin/place-boundaries').then(r => r.json());
                    let map = this.map;
                    let places = Array.from(d.features).map(it => it.properties)

                    this.places = places;

                    let totals = Array.from(d.features).map(it => it.properties.total).sort((a, b) => a - b)
                    let length = totals.length
                    let q1 = totals[Math.round(0.25 * length)];
                    let q2 = totals[Math.round(0.5 * length)];
                    let q3 = totals[Math.round(0.75 * length)];

                    function getColor(d) {
                        if (d >= q1 && d < q2) return '#FFEDA0'
                        else if (d >= q2 && d < q3) return '#FC4E2A'
                        else return '#800026'
                    }

                    this.layer = L.geoJSON(d, {
                            pointToLayer: (geoJsonPoint, latlng) => L.marker(latlng),
                            onEachFeature: function onEachFeature(feature, layer) {
                                layer.on({
                                    click: (e) => {
                                        map.fitBounds(e.target.getBounds())
                                    },
                                    mouseover: (e) => {
                                        const trId = `tr-${e.target.feature.properties.id}`
                                        const trEl = document.getElementById(trId)
                                        trEl.classList.add('grey-bg')
                                    },
                                    mouseout: (e) => {
                                        const trId = `tr-${e.target.feature.properties.id}`
                                        const trEl = document.getElementById(trId)
                                        trEl.classList.remove('grey-bg')
                                    }
                                });
                            },
                            style: function polystyle(feature, layer) {
                                return {
                                    fillColor: getColor(feature.properties.total),
                                    weight: 2,
                                    opacity: 1,
                                    color: 'grey',
                                    dashArray: '3',
                                    fillOpacity: 0.3
                                };
                            }

                        })
                        .addTo(this.map);
                },

                initMap() {
                    this.map = L
                        .map('mapid')
                        .setView([this.centerPos.lat, this.centerPos.lng], 11);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                        minZoom: 11,
                    }).addTo(this.map);
                },


            }
        })
    </script>
@endsection

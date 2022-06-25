@section('title', 'Event Mapper')
@extends('layouts.master')


@section('head')

    {{-- leaflet css --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
        integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
        crossorigin="" />
    <link rel="stylesheet" href="https://labs.easyblog.it/maps/leaflet-search/src/leaflet-search.css">
    <script src="https://unpkg.com/vue@2"></script>
    <!-- Leaflet JavaScript -->
    <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
        integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
        crossorigin=""></script>
    <!-- marker groups ext -->
    <script src="https://unpkg.com/leaflet.markercluster@1.3.0/dist/leaflet.markercluster.js"></script>

    {{-- load vue components --}}
    {{-- load shimmer --}}
    <script src="/assets/js/components/shimmer-card/index.js"></script>
    <link rel="stylesheet" href="/assets/js/components/shimmer-card/shimmer.css">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    {{-- load event card --}}
    <script src="/assets/js/components/event-card/index.js"></script>

    {{-- load event detail --}}
    <script src="/assets/js/components/event-detail/index.js"></script>

    <style>
        .overlay {
            height: 0;
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            background-color: rgb(255, 255, 255);
            overflow-x: hidden;
            transition: 0.5s;
        }

        .overlay .closebtn {
            position: absolute;
            top: 24px;
            left: 10px;
            font-size: 20px;
            z-index: 10000;
        }

        .overlay-content {
            position: relative;
            top: 70px;
            width: 100%;
        }




        #mapid {
            min-height: 60vh;
        }

        #card-list-view {
            z-index: 1;
            height: 40vh;
            position: fixed;
            bottom: 0px;
            left: 0px;
            right: 0px;

            background-color: white;
            border-radius: 13px;
            overflow-y: scroll;
            overflow-x: hidden;

        }

        .close-btn {
            cursor: pointer;
            background-color: #f0f0f1;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            color: #25396f;
            margin-top: 10px;
            margin-right: 10px;
            border: none;
        }


        html,
        body {
            max-width: 100%;
            overflow-x: hidden;
        }


        .slide-top {
            -webkit-animation: slide-top 0.5s cubic-bezier(0.250, 0.460, 0.450, 0.940) both;
            animation: slide-top 0.5s cubic-bezier(0.250, 0.460, 0.450, 0.940) both;
        }

        .container-1 {
            vertical-align: middle;
            white-space: nowrap;
            position: absolute;
            z-index: 10000;
            margin-top: 10px;
        }

        .container-1 input#search {
            width: 80%;
            height: 50px;
            background: #ffffff;
            border: none;
            font-size: 12pt;
            color: #63717f;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            border-radius: 30px;
            position: absolute;
            text-align: center;
            margin-left: 10%;

            -webkit-transition: background .5s ease;
            -moz-transition: background .55s ease;
            -ms-transition: background .55s ease;
            -o-transition: background .55s ease;
            transition: background .55s ease;
        }

        .container-1 input#search:hover,
        .container-1 input#search:focus,
        .container-1 input#search:active {
            outline: none;
            background: #F2F2F2;
        }


        @-webkit-keyframes slide-top {
            0% {
                -webkit-transform: translateY(0);
                transform: translateY(0);
            }

            100% {
                -webkit-transform: translateY(-15px);
                transform: translateY(-15px);
            }
        }

        @keyframes slide-top {
            0% {
                -webkit-transform: translateY(0);
                transform: translateY(0);
            }

            100% {
                -webkit-transform: translateY(-15px);
                transform: translateY(-15px);
            }
        }

        .btn:focus,
        .btn:active {
            outline: none !important;
            box-shadow: none;
        }
    </style>

@endsection





@section('content')


    <div id="app" class="row bg-transparent">

        <div id="myNav" class="overlay">
            <a href="javascript:void(0)" @click="closeNav()" class="closebtn ">
                <span class="fa-fw select-all fas"></span>
            </a>
            <div class="overlay-content">
                <small class="text-muted">Popular places</small>
                <div class="d-flex flex-wrap mt-2">
                    <button v-for="(pop,i) in popularPlaces" :key="i" v-text="pop.name"
                        class="btn btn-sm btn-outline-primary mx-1 my-1 rounded-pill"></button>
                </div>
            </div>
        </div>


        <div class="container-1">
            <input autocomplete="off" @click="openNav()" type="search" id="search" placeholder="Search .." />
        </div>

        <div id="mapid"></div>

        <div id="card-list-view">
            <div class="px-2 mt-1 mb-2">
                <div style="margin-bottom: 25px;overflow: auto; white-space: nowrap;" class=" py-2">
                    {{-- category filter --}}
                    <div class="d-inline-block">
                        <button @click="toggleCategory(0)" :class="{ 'btn-primary': filter.cat == 0 }"
                            class="btn btn-sm btn-outline-primary rounded-pill">All</button>
                        <button v-for="(category,i) in categories" :key="i"
                            @click="toggleCategory(category.id)" :class="{ 'btn-primary': filter.cat == category.id }"
                            v-text="category.name" class="mx-1 btn btn-sm btn-outline-primary rounded-pill"></button>
                    </div>


                    <div class="d-inline-block mx-1" style="background-color: rgb(236, 237, 238); width: 1px;height: 30px">
                    </div>

                    {{-- time filter --}}

                    <div class="btn-group d-inline-block">
                        <button @click="toggleDate(1)" :class="{ 'btn-success': filter.date == 1 }"
                            class="btn btn-sm btn-outline-success rounded-pill">this week</button>
                        <button @click="toggleDate(2)" :class="{ 'btn-success': filter.date == 2 }"
                            class="btn btn-sm btn-outline-success mx-1 rounded-pill">this month</button>
                        <button @click="toggleDate(3)" :class="{ 'btn-success': filter.date == 3 }"
                            class="btn btn-sm btn-outline-success rounded-pill">this year</button>
                    </div>
                </div>
            </div>

            <div v-if="isLoading == true">
                <shimmer-card></shimmer-card>
                <shimmer-card></shimmer-card>
                <shimmer-card></shimmer-card>
            </div>

            <div v-for="(event,i) in events" :key="i" v-if="isLoading == false"
                @click="getEventDetail(event)">
                <div class="card px-2 py-0 mb-3" style="cursor: pointer;">
                    <div class="row no-gutters">
                        <div class="col-3">
                            <img :src="event.photo" style="border-radius:10px;max-height: 150px;" class="img-fluid">
                            <!-- class:"rounded-start" -->
                        </div>
                        <div class="col-8">
                            <div class="card-content ">
                                <h6 style="color: black;">
                                    <a :href="`/mobile/event/detail/id=${event.id}`" v-text="event.name"></a>
                                </h6>
                                <span class="badge bg-light-primary mb-2" v-text="event.category_name"></span>
                                <p class="card-text text-truncate" v-text="event.description"></p>
                                <p class="card-text">
                                    <small class="text-muted" v-text="event.date"></small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <script>
        new Vue({
            el: '#app',
            data: () => ({
                popularPlaces: [],
                keyword: '',
                categories: [],
                filter: {
                    cat: 0,
                    date: 1,
                },

                message: 'Hello Vue!',
                centerPos: {
                    lat: -7.797068,
                    lng: 110.370529
                },
                zoom: 20,
                map: null,
                events: [],
                isLoading: false,
                layer: null,
                showDetail: false,
                detail: {
                    id: null,
                    name: null,
                    description: null,
                    content: null,
                    location: null,
                    photo: null,
                    link: null,
                    category_name: null,
                    organizer_name: null,
                    lng: null,
                    lat: null,
                    start_date: null,
                    end_date: null,
                }
            }),

            mounted() {
                this.initMap()
                // this.loadJogjaBounds()
                this.loadPopularPlaces()
                this.getCategories()
                this.getEvents(this.centerPos.lat, this.centerPos.lng);
            },

            methods: {
                openNav() {
                    document.getElementById("myNav").style.height = "100%";
                    document.getElementById("myNav").style.zIndex = "10000";
                },

                closeNav() {
                    document.getElementById("myNav").style.height = "0%";
                    document.getElementById("myNav").style.zIndex = "0";
                },

                getCategories() {
                    fetch('/api/categories')
                        .then(r => r.json())
                        .then(d => this.categories = d)
                        .catch(e => this.showAlert('fail load categories'))
                },

                debounce(func, wait, immediate) {
                    var timeout;

                    return function executedFunction() {
                        var context = this;
                        var args = arguments;

                        var later = function() {
                            timeout = null;
                            if (!immediate) func.apply(context, args);
                        };

                        var callNow = immediate && !timeout;

                        clearTimeout(timeout);

                        timeout = setTimeout(later, wait);

                        if (callNow) func.apply(context, args);
                    }
                },

                async loadJogjaBounds() {
                    let d = await fetch('/api/geojson/yogyakarta-province').then(r => r.json())
                    let map = this.map
                    this.layer = L.geoJSON(d, {
                            pointToLayer: (geoJsonPoint, latlng) => L.marker(latlng),
                            onEachFeature: function onEachFeature(feature, layer) {
                                layer.on({
                                    click: (e) => {
                                        map.fitBounds(e.target.getBounds())
                                    }
                                });
                            },
                            style: function polystyle(feature) {
                                return {
                                    fillColor: 'grey',
                                    weight: 2,
                                    opacity: 1,
                                    color: 'grey',
                                    dashArray: '3',
                                    fillOpacity: 0.1
                                };
                            }

                        })
                        .addTo(this.map);

                },

                toggleCategory(id) {
                    this.filter.cat = id;
                },


                search() {
                    let isNotNull = this.keyword && this.keyword != ''
                },

                toggleDate(id) {
                    this.filter.date = id;
                },

                initMap() {
                    this.map = L
                        .map('mapid', {
                            zoomControl: false
                        })
                        .setView([this.centerPos.lat, this.centerPos.lng], 14);

                    L.control.zoom({
                        position: 'bottomright'
                    }).addTo(this.map);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',

                    }).addTo(this.map);

                    let onMoveEnd = this.debounce((props) => {
                        // only load events if map moved more than 200 and zoom level less than equal 14
                        if (props.distance >= 200 && props.target._zoom <= 14) {
                            this.showDetail = false
                            this.detail = {}
                            let {
                                lat,
                                lng
                            } = this.map.getCenter()
                            this.map.removeLayer(this.layer);
                            this.getEvents(lat, lng)
                        }
                    }, 300)

                    this.map.on("dragend", onMoveEnd);

                },

                async loadPopularPlaces() {
                    let response = await fetch('/api/popular-places/all').then(r => r.json())
                    this.popularPlaces = Array.from(response)
                },

                async getEventDetail(property) {
                    if (property.lat && property.lng && property.id) { // ensure required prop exists
                        this.map.setView([property.lat, property.lng], 18);

                        /** {id,name,description,content,location,photo,link,category_name,organizer_name,lng,lat,start_date,end_date,} */
                        this.detail = await fetch(`/api/event/detail/id=${property.id}`)
                            .then(r => r.json())
                            .catch(() => alert('error when get detail'));

                        this.showDetail = true;

                    } else {
                        alert('something wrong when get detail')
                    }

                },

                getEvents(lat, lng) {
                    this.isLoading = true;
                    fetch(`/api/events?lat=${lat}&lng=${lng}`)

                        .catch(() => alert('fail load events'))
                        .then(r => r.json())
                        .then(d => {
                            // set loading to false 
                            this.isLoading = false

                            // bind to data
                            const events = Array.from(d.features).map(it => it.properties.events).flat();
                            this.events = events;


                            // load geoJSON
                            this.layer = L.geoJSON(d, {
                                    pointToLayer: (geoJsonPoint, latlng) => L.marker(latlng)
                                })
                                .bindPopup(function(layer) {
                                    const props = layer.feature.properties
                                    const events = Array.from(layer.feature.properties.events)

                                    const eventList = events.map(it =>
                                        `<li>${it.name} <br> <small>${it.start_date}</small> </li>`
                                    ) // show events as list element
                                    let popupContent =
                                        `<strong>Events</strong> : <br> <ul> ${eventList} </ul> <strong>Lokasi</strong> <br> ${props.location}`

                                    return popupContent;
                                })
                                .on('click', (e) => {
                                    this.map.setView([e.latlng.lat, e.latlng.lng], 30)
                                })
                                .addTo(this.map);
                        })
                }
            }
        })
    </script>
@endsection

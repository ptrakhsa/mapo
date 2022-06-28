@section('title', 'Event Mapper')
@extends('layouts.master')


@section('head')

    {{-- leaflet css --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
        integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
        crossorigin="" />
    <link rel="stylesheet" href="https://labs.easyblog.it/maps/leaflet-search/src/leaflet-search.css">
    <script src="https://unpkg.com/vue@2"></script>

    {{-- load vue components --}}
    {{-- load shimmer --}}
    <script src="/assets/js/components/shimmer-card/index.js"></script>
    <link rel="stylesheet" href="/assets/js/components/shimmer-card/shimmer.css">
    {{-- load event card --}}
    <script src="/assets/js/components/event-card/index.js"></script>

    {{-- load event detail --}}
    <script src="/assets/js/components/event-detail/index.js"></script>

    <style>
        #mapid {
            min-height: 100vh;
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

        .popular-places {
            background-color: rgba(255, 255, 255, 0.584);
            border-radius: 5px;
            display: inline;
            padding-top: 10px;
            padding-bottom: 10px;
            padding-left: 10px;
            padding-right: 10px;
            /* box-shadow: 0 0 15px rgba(0, 0, 0, 0.2); */
        }

        body {
            overflow-x: hidden;
            overflow-y: hidden;
        }

        #event-list {
            height: 80vh;
            overflow-y: scroll;
        }

        #event-detail {
            height: 100vh;
            overflow-y: scroll;
        }

        .slide-top {
            -webkit-animation: slide-top 0.5s cubic-bezier(0.250, 0.460, 0.450, 0.940) both;
            animation: slide-top 0.5s cubic-bezier(0.250, 0.460, 0.450, 0.940) both;
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


        @keyframes blink-color {
            0% {
                background-color: white;
            }

            50% {
                background-color: rgba(110, 150, 186, 0.171);
            }

            100% {
                background-color: white;
            }
        }
    </style>

@endsection





@section('content')


    <div class="row no-gutter bg-white" id="app">
        <div class="col-md-5 d-none d-sm-block d-md-block">

            <div v-if="showDetail == false" class="py-3 px-4 mt-4">
                <input type="text" @focus="showPopularPlaces = true" class="form-control" placeholder="Cari Event"
                    @keyup.enter="search" v-model="keyword">


                {{-- suggestion --}}
                <div class="mt-4 slide-top" v-show="showPopularPlaces">
                    <small class="text-muted">popular location</small>
                    <br>

                    <button :class="{ 'btn-primary': filter.pop == pop.id, 'btn-light': filter.pop != pop.id }"
                        class="btn mt-1 ml-1 btn-sm rounded-pill" v-for="(pop,i) in popularPlaces"
                        @click="getEventsByPopularPlaces(pop.id)" :key="i" v-text="pop.name"></button>


                </div>
                <div class="d-flex mt-3 flex-wrap">
                    {{-- category filter --}}
                    <button @click="toggleCategory(0)" :class="{ 'btn-primary': filter.cat == 0 }"
                        class="btn btn-sm btn-outline-primary rounded-pill">All</button>

                    <button v-for="(category,i) in categories" :key="i" @click="toggleCategory(category.id)"
                        :class="{ 'btn-primary': filter.cat == category.id }" v-text="category.name"
                        class="mx-1 btn btn-sm btn-outline-primary rounded-pill"></button>


                    <div style="background-color: rgb(212, 217, 221); width: 2px;height: 30px" class="mx-2"></div>

                    {{-- time filter --}}

                    <button @click="toggleDate(1)" :class="{ 'btn-success': filter.date == 1 }"
                        class="btn btn-sm btn-outline-success rounded-pill">This week</button>
                    <button @click="toggleDate(2)" :class="{ 'btn-success': filter.date == 2 }"
                        class="btn btn-sm btn-outline-success mx-1 rounded-pill">This month</button>
                    <button @click="toggleDate(3)" :class="{ 'btn-success': filter.date == 3 }"
                        class="btn btn-sm btn-outline-success rounded-pill">This year</button>

                </div>
            </div>

            <div v-if="isLoading == true">
                <shimmer-card></shimmer-card>
                <shimmer-card></shimmer-card>
                <shimmer-card></shimmer-card>
            </div>


            {{-- detail --}}
            <div id="event-detail" v-if="showDetail == true">
                <event-detail class="slide-top" :name="detail.name" :description="detail.description"
                    :category="detail.category_name" :content="detail.content" :photo="detail.photo"
                    :date="detail.start_date" :location="detail.location" :link="detail.link"
                    :organizer="detail.organizer_name">
                    <button @click="showDetail = false" class="close-btn">
                        x
                    </button>
                </event-detail>
            </div>



            {{-- new list --}}

            <div id="event-list">

                {{-- show this element when events is empty --}}

                <div v-if="isEventsEmpty" class="d-flex flex-column justify-content-center align-items-center h-75">
                    <img height="300" src="/assets/images/samples/error-404.png" />
                    <h6>Event not found</h6>
                    <button class="btn btn-sm btn-primary rounded-pill px-4" @click="reloadEvents()">reload</button>
                </div>
                {{-- whenever events not empty --}}
                <div v-for="(event,i) in events" :key="i" v-if="isLoading == false && showDetail == false"
                    @click="getEventDetail(event)">
                    <event-card :id="`event-card-${event.id}`" :name="event.name" :description="event.description"
                        :category="event.category_name" :date="event.start_date" :photo="event.photo">
                    </event-card>
                    <hr>
                </div>
            </div>

        </div>
        <div class="col-md-7">
            <div style="position: fixed;top:10px;right:15px;z-index: 1000;">
                <button class="btn btn-sm btn-light" @click="reloadEvents()" v-show="showReloadBtn">
                    <span class="fa-fw select-all fas"></span>
                    <span style="font-weight: bold;">reload event in this area</span>
                </button>
            </div>

            <div id="mapid"></div>
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
            computed: {
                isEventsEmpty() {
                    return this.events.length === 0 && this.isLoading == false;
                }
            },
            data: () => ({
                showReloadBtn: false,
                showPopularPlaces: false,
                popularPlaces: [],
                keyword: null,
                categories: [],
                filter: {
                    cat: 0,
                    date: 1,
                    pop: null,
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
                this.loadJogjaBounds()
                this.loadPopularPlaces()
                this.getCategories()
                this.getEvents();
            },

            methods: {
                reloadEvents() {
                    this.filter = {
                        cat: 0,
                        date: 1,
                        pop: null,
                    };
                    this.keyword = null;
                    this.showPopularPlaces = false;
                    this.showReloadBtn = false;
                    this.getEvents();
                },

                getEventsByPopularPlaces(id) {
                    if (id) {
                        // make toggle
                        id == this.filter.pop ?
                            this.filter.pop = null :
                            this.filter.pop = id;


                        this.getEvents();
                    }
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
                    this.getEvents();
                },


                search() {
                    // reset all filter when user search event by keyword 
                    this.filter = {
                        cat: 0,
                        date: 1,
                        pop: null,
                    }
                    this.getEvents();
                },

                toggleDate(id) {
                    this.filter.date = id;
                    this.getEvents();
                },

                initMap() {
                    this.map = L
                        .map('mapid')
                        .setView([this.centerPos.lat, this.centerPos.lng], 14);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                        // maxNativeZoom: 19,
                        minZoom: 11
                    }).addTo(this.map);

                    let onMoveEnd = this.debounce((props) => {
                        console.log(props.target._zoom, 'zoom')
                        // only load events if map moved more than 200 and zoom level less than equal 14
                        if (props.distance >= 200 && props.target._zoom <= 16) {
                            console.log('fetch data')
                            this.showDetail = false
                            this.detail = {}

                            let isUserSetFilter = this.filter.cat != 0 || this.filter.date != 1 || this
                                .pop != null || this
                                .keyword != null;
                            isUserSetFilter
                                ?
                                this.showReloadBtn = true :
                                this.showReloadBtn = false



                            this.map.removeLayer(this.layer);
                            this.getEvents()
                        }
                    }, 300)

                    this.map.on("dragend", onMoveEnd);

                },

                async loadPopularPlaces() {
                    let response = await fetch('/api/popular-places/all').then(r => r.json());
                    let popularPlaces = Array.from(response);
                    this.popularPlaces = popularPlaces;
                },

                async getEventDetail(property) {
                    if (property.lat && property.lng && property.id) { // ensure required prop exists
                        this.map.setView([property.lat, property.lng], 18);

                        // open the popup
                        this.openPopupByEventId(property.id)

                        /** {id,name,description,content,location,photo,link,category_name,organizer_name,lng,lat,start_date,end_date,} */
                        this.detail = await fetch(`/api/event/detail/id=${property.id}`)
                            .then(r => r.json())
                            .catch(() => alert('error when get detail'));

                        this.showDetail = true;

                    } else {
                        alert('something wrong when get detail')
                    }

                },

                openPopupByEventId(id) {
                    this.layer.eachLayer(layer => {
                        // id is event id then check existence the given id in layers
                        let events = layer.feature.properties.events
                        let isIdExists = Array.from(events).some(it => it.id == id);

                        if (isIdExists == true) {
                            layer.openPopup();
                            return;
                        }
                    })
                },

                scrollToEventPosition(e) {
                    this.showDetail = false; // force hide detail and show list view
                    setTimeout(() => {
                        const id = e.layer.feature.properties.events[0].id
                        if (id) { // ensure id exists
                            const eventList = document.getElementById('event-list');
                            const eventCard = document.getElementById(`event-card-${id}`);
                            const eventCardOffset = eventCard.offsetTop - 189;

                            eventCard.style.animation = 'blink-color 3s 1'

                            eventList.scrollTo({
                                top: eventCardOffset,
                                behavior: 'smooth'
                            })
                        }
                    }, 100)


                },

                getEvents() {
                    let {
                        lat,
                        lng
                    } = this.map.getCenter()

                    // setup url
                    let url = `/api/events?lat=${lat}&lng=${lng}`
                    if (this.filter.cat != 0) {
                        url += `&cat=${this.filter.cat}`
                    }


                    // setup filter
                    if (this.filter.date == 0) {
                        url += `&date=week`
                    } else if (this.filter.date == 1) {
                        url += `&date=month`
                    } else {
                        url += `&date=year`
                    }

                    let isKeywordNotNull = this.keyword && this.keyword != ''
                    if (isKeywordNotNull) {
                        url += `&keyword=${encodeURI(this.keyword)}`
                    }

                    if (this.filter.pop != null) {
                        url += `&pop=${this.filter.pop}`
                    }

                    this.isLoading = true;
                    fetch(url)
                        .catch(() => alert('fail load events'))
                        .then(r => r.json())
                        .then(d => {
                            // set loading to false 
                            this.isLoading = false

                            // bind to data
                            const events = Array.from(d.features).map(it => it.properties.events).flat();
                            this.events = events;

                            // remove loaded previous layer
                            this.map.removeLayer(this.layer);


                            // load geoJSON
                            this.layer = L.geoJSON(d, {
                                    pointToLayer: (geoJsonPoint, latlng) => L.marker(latlng),
                                    onEachFeature: (feature, layer) => {
                                        const props = layer.feature.properties
                                        const events = Array.from(layer.feature.properties.events)

                                        const eventList = events.map(it =>
                                            `<li>${it.name} <br> <small>${it.start_date}</small> </li>`
                                        ).join('') // show events as list element
                                        let popupContent =
                                            `<strong>Events</strong> : <br> <ul> ${eventList} </ul> <strong>Lokasi</strong> <br> ${props.location}`

                                        layer.bindPopup(popupContent);
                                    }
                                })
                                .on('click', (e) => {
                                    this.scrollToEventPosition(e);
                                    this.map.setView([e.latlng.lat, e.latlng.lng], 30)
                                })
                                .addTo(this.map);
                        })
                }
            }
        })
    </script>
@endsection

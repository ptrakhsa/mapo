@section('title', 'organizer dashboard')
@extends('layouts.master')


@section('head')
    {{-- leaflet css --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
        integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
        crossorigin="" />
    <link rel="stylesheet" href="https://labs.easyblog.it/maps/leaflet-search/src/leaflet-search.css">

    {{-- vue js --}}
    <script src="https://unpkg.com/vue@2"></script>



    {{-- quil editor css --}}
    <link rel="stylesheet" href="/assets/vendors/quill/quill.bubble.css">
    <link rel="stylesheet" href="/assets/vendors/quill/quill.core.css">
    <link rel="stylesheet" href="/assets/vendors/quill/quill.snow.css">
    <link rel="stylesheet" href="/assets/vendors/quill/quill.imageUploader.min.css">

    {{-- load quil js --}}
    <script src="/assets/vendors/quill/quill.min.js"></script>
    <script src="/assets/vendors/quill/image-resize.min.js"></script>
    <script src="/assets/vendors/quill/image-drop.min.js"></script>
    <script src="/assets/vendors/quill/quill.imageUploader.min.js"></script>


    <style>
        #mapid {
            min-height: 400px;
            margin-bottom: 10px;
        }

        .ql-editor {
            min-height: 200px;
        }

        .circle-steper {
            width: 39px;
            height: 39px;
            cursor: pointer;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-weight: bold;
        }

        .image-picker {
            background-color: aliceblue;
            width: 100%;
            min-height: 200px;
            max-height: 200px;
            cursor: pointer;
            position: relative;
        }

        .image-remover-btn {
            background-color: rgb(178, 62, 62);
            width: 39px;
            height: 39px;
            border-radius: 50%;
            cursor: pointer;
            position: absolute;
            top: 10px;
            right: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-weight: bold;
        }
    </style>
@endsection


@section('content')
    <div class="container d-flex justify-content-center align-items-center flex-column" style="height: 100vh;"
        id="app">

        {{-- validation error --}}
        <div style="width: 800px;">
            <div class="alert alert-danger" v-if="Object.keys(inputInvalid).length != 0">
                <span v-for="fields in inputInvalid">
                    <small v-for="err in fields" v-text="err"></small>
                </span>
            </div>
        </div>

        <div class="card" style="min-height: 400px;width: 800px">
            {{-- stepper head --}}
            <div class="d-flex justify-content-around mt-4">
                <div v-for="(step,i) in stepper" :key="i" class="d-flex align-items-center head-steper"
                    style="cursor: pointer" @click="setStep(step.index)"
                    :style="{
                        color: activeIndex == step.index ? 'black' : 'grey',
                        fontWeight: activeIndex == step.index ?
                            'bold' : 'normal'
                    }">
                    <div :style="{ backgroundColor: activeIndex == step.index ? '#435EBE' : '#B3BAD3' }"
                        class="circle-steper" v-text="step.index"></div>
                    <span class="mx-3" v-text="step.label"></span>
                </div>
            </div>

            {{-- hidden form with data binding --}}
            <form action="/organizer/event/store" id="event-form" method="POST" enctype="multipart/form-data"
                class="d-none">
                @csrf
                {{-- event --}}
                <input type="text" name="name" :value="bodyReq.name">
                <input type="text" name="category_id" :value="bodyReq.categoryId">
                <input type="text" name="description" :value="bodyReq.description">
                {{-- time --}}
                <input type="text" name="start_date" :value="startDate">
                <input type="text" name="end_date" :value="endDate">
                {{-- location --}}
                <input type="text" name="lat" :value="bodyReq.location.lat">
                <input type="text" name="lng" :value="bodyReq.location.lng">
                <input type="text" name="location" :value="bodyReq.location.name">
                <input type="text" name="popular_place_id" :value="bodyReq.location.popular_place_id">

                {{-- content --}}
                <input type="file" name="photo" id="imagePicker" @change="onImageInputted">
                <input type="text" name="content" id="content">
                {{-- <input type="text" name="link" :value="bodyReq.link"> --}}

                <input type="submit" value="submit" id="submitEventForm">
            </form>

            {{-- stepper content --}}
            <div class="px-5 py-5">
                {{-- event --}}
                <div v-show="activeIndex == 1">
                    <div class="form-group">
                        <input autocomplete="off" v-model="bodyReq.name" id="name" type="text" class="form-control"
                            placeholder="Event name">
                        <div class="invalid-feedback" id="name-error-feedback"></div>
                    </div>

                    <div class="form-group">
                        <select aria-placeholder="Category" class="form-select" v-model="bodyReq.categoryId">
                            <option v-for="(category,i) in categories" :value="category.id" v-text="category.name">
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <textarea v-model="bodyReq.description" name="" id="" cols="30" rows="10" class="form-control"
                            placeholder="Event description"></textarea>
                    </div>

                    <div class="mt-4 d-flex justify-content-end">
                        <button @click="setStep(2)" class="btn rounded-pill btn-primary px-3 btn-sm"
                            style="font-weight: bold">Next</button>
                    </div>
                </div>


                {{-- time --}}
                <div v-show="activeIndex == 2">
                    <v-date-picker v-model="bodyReq.date" :masks="masks" is-range mode="dateTime" is-expanded>
                    </v-date-picker>
                    <div class="mt-4 d-flex justify-content-end">
                        <button @click="setStep(3)" class="btn rounded-pill btn-primary px-3 btn-sm"
                            style="font-weight: bold">Next</button>
                    </div>
                </div>

                {{-- location --}}
                <div v-show="activeIndex == 3">
                    <a class="my-1 mb-3" @click="showPopularPlace = !showPopularPlace" href="#">
                        <span v-if="showPopularPlace">hide</span>
                        <span v-else>show</span>
                        popular places
                    </a>
                    <div v-if="popularPlaces.length > 0 && showPopularPlace == true"
                        style="display: inline-block;white-space: nowrap;overflow: auto;width: 100%" class="py-2">
                        <button @click="setEventLocationByPopularPlace(place)"
                            class="btn btn-sm btn-outline-primary rounded-pill mx-1" v-for="(place,i) in popularPlaces"
                            :key="i" v-text="place.name"></button>
                    </div>
                    <div id="mapid"></div>
                    <div class="form-group">
                        <textarea v-model="bodyReq.location.name" class="form-control" placeholder="Location name"></textarea>
                    </div>
                    <div class="mt-4 d-flex justify-content-end">
                        <button @click="setStep(4)" class="btn rounded-pill btn-primary px-3 btn-sm"
                            style="font-weight: bold">Next</button>
                    </div>
                </div>

                {{-- content --}}

                <div v-show="activeIndex == 4">
                    <div @click.stop="openImagePicker"
                        class="mb-3 d-flex justify-content-center align-items-center image-picker">
                        <img v-if="imageAsUrl != ''" :src="imageAsUrl" class="img-fluid"
                            style="object-fit: contain;max-height: 200px;" />
                        <div v-else>
                            Click to add image
                        </div>

                        <!-- remove image btn -->
                        <div v-if="imageAsUrl != ''" @click.stop="removeImage" class="image-remover-btn">x</div>
                    </div>

                    <div id="full">
                    </div>
                    {{-- hide link because link already generated by backend --}}
                    {{-- <div class="form-group">
                        <input v-model="bodyReq.link" type="text" class="form-control mt-4"
                            placeholder="link integrasi google map">
                    </div> --}}
                    <div class="mt-4 d-flex ">
                        <button @click="submitEvent()" class="btn rounded-pill btn-primary btn-block btn-sm"
                            style="font-weight: bold">Submit</button>
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
    <!-- 2. Link VCalendar Javascript (Plugin automatically installed) -->
    <script src='https://unpkg.com/v-calendar'></script>

    <script>
        new Vue({
            el: '#app',
            computed: {
                startDate() {
                    return this.bodyReq.date.start ? this.formatDate(this.bodyReq.date.start) : null;
                },

                endDate() {
                    return this.bodyReq.date.end ? this.formatDate(this.bodyReq.date.end) : null;
                },

                imageAsUrl() {
                    return this.bodyReq.image ? URL.createObjectURL(this.bodyReq.image) : ''
                }
            },

            mounted() {
                this.InstantiatingQuil()
                this.initMap()
                this.loadJogjaBounds()
                this.getCategories()
            },

            methods: {

                transformObjectToFormData(object) {
                    let formData = new FormData();
                    for (const key in object) {
                        if (Object.hasOwnProperty.call(object, key)) {
                            let element = object[key];
                            if (element != null || element != undefined) {
                                formData.append(key, element)
                            }
                        }
                    }

                    return formData;
                },

                InstantiatingQuil() {

                    Quill.register("modules/imageUploader", ImageUploader);
                    this.quilInstance = new Quill("#full", {
                        bounds: "#full-container .editor",
                        modules: {
                            imageUploader: {
                                upload: file => new Promise((resolve, reject) => {
                                    const formData = new FormData();
                                    formData.append("content-img", file);
                                    const config = {
                                        method: "POST",
                                        body: formData
                                    }
                                    const url = "/api/organizer/event/upload-content-image"

                                    fetch(url, config)
                                        .then(response => response.json())
                                        .then(result => resolve(result.data.url))
                                        .catch(error => reject("Upload failed"));
                                })
                            },
                            imageResize: {
                                displaySize: true
                            },
                            imageDrop: true,
                            toolbar: [
                                [{
                                    font: []
                                }, {
                                    size: []
                                }],
                                ["bold", "italic", "underline", "strike"],
                                [{
                                        color: []
                                    },
                                    {
                                        background: []
                                    }
                                ],
                                [{
                                        script: "super"
                                    },
                                    {
                                        script: "sub"
                                    }
                                ],
                                [{
                                        list: "ordered"
                                    },
                                    {
                                        list: "bullet"
                                    },
                                    {
                                        indent: "-1"
                                    },
                                    {
                                        indent: "+1"
                                    }
                                ],
                                ["direction", {
                                    align: []
                                }],
                                ["link", "image", "video"],
                                ["clean"]
                            ]
                        },
                        theme: "snow"
                    })
                },

                setEventLocationByPopularPlace(place) {
                    const {
                        name,
                        lat,
                        lng,
                        id
                    } = place

                    if (name && lat && lng) {
                        // set leaflet map positon 
                        this.updateMarker(lat, lng);
                        this.map.setView([lat, lng], 50)

                        // set body request data
                        this.bodyReq.location = {
                            name,
                            lat,
                            lng,
                            popular_place_id: id
                        }
                    } else {
                        this.showAlert('Data bermasalah')
                    }
                },

                async loadPopularPlaces() {
                    fetch('/api/popular-places/all')
                        .then(r => r.json())
                        .then(d => {
                            this.popularPlaces = d;
                        })
                        .catch(e => alert('fail load data'))
                },

                showAlert(message) {
                    Toastify({
                        text: message ?? 'Something wrong',
                        duration: 2500,
                        gravity: "bottom",
                        position: "center",
                        close: true,
                        backgroundColor: "#D04E4E",
                    }).showToast();
                },

                validateBodyRequest(formData) {
                    this.inputInvalid = {}
                    return fetch('/api/event/validate', {
                            method: 'POST',
                            body: formData
                        })
                        .then(async (r) => {
                            let response = await r.json()
                            if (r.status == 422) {
                                this.showAlert('missing input')
                                this.inputInvalid = response;
                                return false;
                            } else {
                                return true;
                            }
                        })

                        .catch((e) => this.showAlert(e))
                },

                getCategories() {
                    fetch('/api/categories')
                        .then(r => r.json())
                        .then(d => this.categories = d)
                        .catch(e => this.showAlert('fail load categories'))
                },

                formatDate(date) {
                    function padTo2Digits(num) {
                        return num.toString().padStart(2, '0');
                    }

                    return (
                        [
                            date.getFullYear(),
                            padTo2Digits(date.getMonth() + 1),
                            padTo2Digits(date.getDate()),
                        ].join('-') +
                        ' ' + [
                            padTo2Digits(date.getHours()),
                            padTo2Digits(date.getMinutes()),
                            padTo2Digits(date.getSeconds()),
                        ].join(':')
                    );
                },
                async submitEvent() {
                    // validate before data submit to backend
                    // object from data vue flatten based on form format
                    // and then send to validation endpoint
                    let flattenBodyRequest = {
                        name: this.bodyReq.name,
                        description: this.bodyReq.description,

                        // parsed , it's means name changed from origin object name
                        category_id: this.bodyReq.categoryId,
                        photo: this.bodyReq.image,
                        location: this.bodyReq.location.name,
                        lat: this.bodyReq.location.lat,
                        lng: this.bodyReq.location.lng,
                        start_date: this.startDate,
                        end_date: this.endDate,
                        popular_place_id: this.bodyReq.location.popular_place_id,
                    }

                    let formData = this.transformObjectToFormData(flattenBodyRequest)
                    let isValidated = await this.validateBodyRequest(formData)
                    console.log(isValidated)
                    return
                    if (isValidated) {
                        // set wysiwyg 
                        // i decided set wysiwyg after validation, cause content field is optional and data too large if sent to validation endpoint
                        let isQuilInstanceLoaded = this.quilInstance != null
                        if (isQuilInstanceLoaded) { // ensure quill loaded properly
                            const contentEditor = this.quilInstance.root.innerHTML;
                            const content = document.getElementById('content');
                            content.value = contentEditor
                        }

                        // trigger submit click
                        document.getElementById('submitEventForm').click()
                    }
                },

                initMap() {
                    this.map = L
                        .map('mapid')
                        .setView([this.centerPos.lat, this.centerPos.lng], 14);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                        minZoom: 11
                    }).addTo(this.map);

                    this.map.on('click', (e) => {
                        let latitude = e.latlng.lat;
                        let longitude = e.latlng.lng;
                        this.bodyReq.location.lat = latitude;
                        this.bodyReq.location.lng = longitude;

                        // reset saved selected popular places when organizer click new marker
                        this.bodyReq.location.popular_place_id = null;
                        this.bodyReq.location.name = null;

                        this.updateMarker(latitude, longitude);
                    });
                    this.marker = L.marker([this.centerPos.lat, this.centerPos.lng]).addTo(this.map);
                },


                async loadJogjaBounds() {
                    let d = await fetch('/api/geojson/yogyakarta-province').then(r => r.json())
                    let map = this.map
                    this.layer = L.geoJSON(d, {
                            pointToLayer: (geoJsonPoint, latlng) => L.marker(latlng),
                            onEachFeature: function onEachFeature(feature, layer) {
                                layer.on({
                                    click: (e) => {
                                        // map.fitBounds(e.target.getBounds())
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

                updateMarker(lat, lng) {
                    this.marker
                        .setLatLng([lat, lng])
                        .bindPopup("Your location :" + this.marker.getLatLng().toString())
                        .openPopup();
                    return false;
                },


                removeImage() {
                    this.bodyReq.image = null
                },

                openImagePicker() {
                    document.getElementById('imagePicker').click()
                },

                onImageInputted(e) {
                    let file = e.target.files[0]
                    this.bodyReq.image = file
                },

                setStep(index) {
                    this.activeIndex = index;
                }
            },
            watch: {
                showPopularPlace(v) {
                    if (v == true && this.popularPlaces.length <= 0) {
                        console.log('load data . . . ')
                        this.loadPopularPlaces()
                    }
                },

                activeIndex() {
                    if (this.map) {
                        this.$nextTick(() => {
                            this.map.invalidateSize();
                        });
                    }
                }
            },
            data: () => ({
                quilInstance: null,
                inputInvalid: {},
                showPopularPlace: false,
                popularPlaces: [],
                categories: [],
                masks: {
                    // iso:"YYYY-MM-DDTHH:mm:ss",
                    // input: 'YYYY-MM-DD h:mm A',
                    // inputDateTime24hr:'YYYY-MM h:mm:ss'
                },
                zoom: 20,
                centerPos: {
                    lat: -7.797068,
                    lng: 110.370529
                },
                bodyReq: {
                    name: null,
                    description: null,
                    categoryId: null,
                    image: null,
                    // link: null,
                    location: {
                        name: null,
                        lat: null,
                        lng: null,
                        popular_place_id: null,
                    },
                    date: {
                        start: null,
                        end: null
                    }
                },
                map: null,
                marker: null,
                activeIndex: 1,
                stepper: [{
                        index: 1,
                        label: 'Event'
                    },
                    {
                        index: 2,
                        label: 'Time'
                    },
                    {
                        index: 3,
                        label: 'Location'
                    },
                    {
                        index: 4,
                        label: 'Content'
                    },
                ]
            }),

        })
    </script>
    {{-- move to top --}}
    {{-- <script src="/assets/vendors/quill/quill.min.js"></script> --}}
    {{-- <script src="/assets/js/pages/form-editor.js"></script> --}}
@endsection

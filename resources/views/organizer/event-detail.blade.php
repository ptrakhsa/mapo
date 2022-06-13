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
    <link rel="stylesheet" href="/assets/vendors/quill/quill.snow.css">


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
    <div class="container d-flex justify-content-center flex-column align-items-center" style="height: 100vh;" id="app">
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
            <form action="/organizer/event/store" method="POST" enctype="multipart/form-data" class="d-none">
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

                {{-- content --}}
                <input type="file" name="photo" id="imagePicker" @change="onImageInputted">
                <input type="text" name="content" id="content">
                <input type="text" name="link" :value="bodyReq.link">

                <input type="submit" value="submit" id="submitEventForm">
            </form>

            {{-- stepper content --}}
            <div class="px-5 py-5">
                {{-- event --}}
                <div v-show="activeIndex == 1">
                    <div class="form-group">
                        <input v-model="bodyReq.name" id="name" type="text" class="form-control" placeholder="Event name">
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
                            Drop image here
                        </div>

                        <!-- remove image btn -->
                        <div v-if="imageAsUrl != ''" @click.stop="removeImage" class="image-remover-btn">x</div>
                    </div>

                    <div id="full">
                    </div>
                    <div class="form-group">
                        <input v-model="bodyReq.link" type="text" class="form-control mt-4"
                            placeholder="link integrasi google map">
                    </div>
                    <div class="mt-4 d-flex ">
                        <button @click="submitEvent()" class="btn rounded-pill btn-primary btn-block btn-sm"
                            style="font-weight: bold">Submit</button>
                    </div>
                </div>


            </div>



        </div>
        <button class="btn btn-sm" @click="getSubmissionHistory()">See submission history</button>

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
                this.initMap()
                this.getCategories()
            },

            methods: {
                getSubmissionHistory() {
                    let eventId = {{ $event->id }}
                    fetch(`/api/organizer/event/${eventId}/submission-history`)
                        .then(r => r.json())
                        .then(history => {
                            let content = Array.from(history).map(it => `
                                <div style="max-height:400px;overflow-y:scroll;" class="d-flex justify-content-start flex-column align-items-start">
                                    <div class="d-flex">
                                        <div class="bg-info" style="width: 20px;height: 20px;border-radius: 50%;"></div> 
                                        <div class="d-flex justify-content-start align-items-start mx-2 flex-column">
                                            <span>${it.status}</span>
                                            <span class="${it.reason ? '' : 'd-none'}">${it.reason}</span>
                                            <small class="text-muted">${it.created_at}</small>
                                        </div> 
                                    </div>
                                </div>
                            `);

                            Swal.fire({
                                html: content,
                                title: "History"
                            })
                        }).catch(e => this.showAlert(e))

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

                validateBodyRequest() {
                    fetch('/api/event/validate', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(this.bodyReq)
                        })
                        .then(r => r.json())
                        .then(d => {
                            if (d.status_code == 422) {
                                this.showAlert('missing input')
                                let errors = Array.from(d.data)
                                errors.forEach(it => {
                                    document.getElementById(it.field).classList.add('is-invalid')
                                    const feedbackEl = document.getElementById(
                                        `${it.field}-error-feedback`)
                                    feedbackEl.innerHTML = it.message
                                })
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
                submitEvent() {
                    // set wysiwyg 
                    const contentEditor = document.getElementById('full').innerHTML;
                    const content = document.getElementById('content');
                    content.value = contentEditor

                    // trigger submit click
                    document.getElementById('submitEventForm').click()
                },

                initMap() {
                    this.map = L
                        .map('mapid')
                        .setView([this.centerPos.lat, this.centerPos.lng], 50);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                    }).addTo(this.map);

                    this.map.on('click', (e) => {
                        let latitude = e.latlng.lat;
                        let longitude = e.latlng.lng;
                        this.bodyReq.location.lat = latitude;
                        this.bodyReq.location.lng = longitude;
                        this.updateMarker(latitude, longitude);
                    });
                    this.marker = L.marker([this.centerPos.lat, this.centerPos.lng]).addTo(this.map);
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
                activeIndex() {
                    if (this.map) {
                        this.$nextTick(() => {
                            this.map.invalidateSize();
                        });
                    }
                }
            },
            data: () => ({
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
                    link: null,
                    location: {
                        name: null,
                        lat: null,
                        lng: null,
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
    <script src="/assets/vendors/quill/quill.min.js"></script>
    <script src="/assets/js/pages/form-editor.js"></script>
@endsection

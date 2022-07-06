@section('title', 'Event Detail')
@extends('layouts.admin-panel')

@section('content')
    <div class="page-heading">


        {{-- DASHBOARD EVENT LIST --}}
        <section id="content-types">



            <div class="card px-4 py-4">
                <div class="card-content">
                    @if ($errors->any())
                        <div class="alert alert-danger">{{ $errors->first() }}</div>
                    @endif
                    <h3>{{ $event->name }}</h3>
                    <span class="badge bg-light-primary mb-3">kebudayaan</span>
                    <img src="{{ $event->photo }}" class="img-fluid w-100" alt="">
                    <div class="card-body">
                        <p class="text-subtitle">{{ $event->description }}</p>
                        {!! $event->content !!}
                        <div class="card-text">
                            {{-- time --}}
                            {{-- start & end date --}}
                            <small>
                                <span class="fa-fw select-all fas"></span>
                                {{ date_format(date_create($event->start_date), 'H:i A, j F Y') }}
                                - {{ date_format(date_create($event->end_date), 'H:i A, j F Y') }}
                            </small>
                            <br>
                            <small>
                                <span class="fa-fw select-all fas"></span>
                                {{ $event->location }}
                            </small>
                            <br>
                            <small>
                                <span class="fa-fw select-all fas"></span>
                                <a href="{{ $event->link }}" target="_blank">{{ $event->link }}</a>
                            </small>
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-between">
                        <span>{{ $event->organizer->name }}</span>
                        {{-- footer if event in waiting --}}
                        @if (in_array($event->status->status, ['waiting', 'rejected']))
                            <div>
                                <form id="reject-form" method="POST" action="/admin/event/reject/{{ $event->id }}"
                                    class="d-none">
                                    @csrf
                                    <input type="text" name="reason" id="reject-reason">
                                    <input type="submit" id="reject-form-submit">
                                </form>

                                <button onclick="reject()" id="reject-btn" class="btn"
                                    style="color:rgb(158, 19, 19)">Reject</button>
                                <script>
                                    async function reject() {
                                        const {
                                            value: reason
                                        } = await Swal.fire({
                                            title: 'Write your reason',
                                            input: 'textarea',
                                            inputPlaceholder: 'Type your reason here...',
                                            inputAttributes: {
                                                'aria-label': 'Type your reason here'
                                            },
                                            showCancelButton: true,
                                            inputValidator: (value) => {
                                                return new Promise((resolve) => {
                                                    if (value) {
                                                        resolve()
                                                    } else {
                                                        resolve('You need to write reason')
                                                    }
                                                })
                                            }
                                        })

                                        if (reason) {
                                            document.getElementById('reject-reason').value = reason
                                            document.getElementById('reject-form-submit').click()
                                        }

                                    }
                                </script>



                                <form id="accept-form" method="POST" action="/admin/event/accept/{{ $event->id }}"
                                    class="d-none">
                                    @csrf
                                    <input type="submit" id="accept-form-submit">
                                </form>
                                <button onclick="accept()" class="btn btn-primary">Accept</button>
                                <script>
                                    async function accept() {
                                        Swal.fire({
                                            title: 'Do you want to accept this event ?',
                                            showCancelButton: true,
                                            confirmButtonText: 'Yes',
                                            denyButtonText: 'No',
                                            customClass: {
                                                cancelButton: 'order-1 right-gap',
                                                confirmButton: 'order-2',
                                            }
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                document.getElementById('accept-form-submit').click()
                                            }
                                        })
                                    }
                                </script>
                            </div>
                        @else
                            <div>
                                <form id="takedown-form" method="POST" action="/admin/event/takedown/{{ $event->id }}"
                                    class="d-none">
                                    @csrf
                                    <input type="text" name="reason" id="takedown-reason">
                                    <input type="submit" id="takedown-form-submit">
                                </form>

                                <button onclick="takedown()" id="takedown-btn" class="btn btn-danger">takedown</button>
                                <script>
                                    async function takedown() {
                                        const {
                                            value: reason
                                        } = await Swal.fire({
                                            title: 'Write your reason',
                                            input: 'textarea',
                                            inputPlaceholder: 'Type your reason here...',
                                            inputAttributes: {
                                                'aria-label': 'Type your reason here'
                                            },
                                            showCancelButton: true,
                                            inputValidator: (value) => {
                                                return new Promise((resolve) => {
                                                    if (value) {
                                                        resolve()
                                                    } else {
                                                        resolve('You need to write reason')
                                                    }
                                                })
                                            }
                                        })

                                        if (reason) {
                                            document.getElementById('takedown-reason').value = reason
                                            document.getElementById('takedown-form-submit').click()
                                        }

                                    }
                                </script>

                                <form id="done" method="POST" action="/admin/event/done/{{ $event->id }}"
                                    class="d-none">
                                    @csrf
                                    <input type="submit" id="done-form-submit">
                                </form>

                                <button onclick="done()" id="done-btn" class="btn btn-success">mark as done</button>
                                <script>
                                    async function done() {
                                        Swal.fire({
                                            title: 'Do you want to mark this event as done ?',
                                            showCancelButton: true,
                                            confirmButtonText: 'Yes',
                                            denyButtonText: 'No',
                                            customClass: {
                                                cancelButton: 'order-1 right-gap',
                                                confirmButton: 'order-2',
                                            }
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                document.getElementById('done-form-submit').click()
                                            }
                                        })

                                    }
                                </script>

                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
        {{-- END DASHBOARD EVENT LIST --}}


    </div>
@endsection

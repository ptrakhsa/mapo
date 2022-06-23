@section('title', 'organizer dashboard')
@extends('layouts.master')
@section('head')
    <style>
        .fab-add-event {
            position: fixed;
            bottom: 40px;
            right: 40px;
            width: 50px;
            height: 50px;
            cursor: pointer;
            border-radius: 50%;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
        }
    </style>
@endsection

@section('content')
    {{-- header --}}
    <div class="d-flex justify-content-between align-items-center px-3 py-3 bg-white">
        <div class="dropdown-toggle d-flex align-items-center dropdown" id="dropdownMenuButton" data-bs-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false" style="cursor: pointer">
            <div class="avatar bg-info avatar-lg bg-warning me-3">
                <img src="/assets/images/faces/1.jpg" alt="" srcset="">
            </div>
            <h4>{{ auth()->guard('organizer')->user()->name }}</h4>
        </div>
        {{-- hidden --}}
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <a class="dropdown-item" href="/organizer/profile">Profile</a>
            <a class="dropdown-item" href="/organizer/toc">TOC</a>
        </div>


        <div class="d-flex align-content-center">
            <a class="btn" href="/organizer/activity">activity</a>
            <form action="/organizer/logout" method="POST">
                @csrf
                <input type="submit" class="btn" value="Logout">
            </form>
        </div>
    </div>

    {{-- content --}}

    <div class="d-flex justify-content-center row mx-3 mt-4">
        @forelse ($my_events as $event)
            <div class="col-xl-4 col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-content">
                        <img style="max-height: 400px;object-fit: contain;" class="img-fluid w-100"
                            src="{{ $event->photo }}" alt="Card image cap">
                        <div class="card-body">
                            <h4 class="card-title">{{ $event->name }}</h4>
                            <span class="badge bg-light-primary mb-3">{{ $event->category->name }}</span>
                            <p class="card-text">
                                {{ $event->description }}
                            </p>
                            <p class="card-text">
                                <small>
                                    <span class="fa-fw select-all fas"></span>
                                    {{ date_format(date_create($event->start_date), 'Y/m/d H:i:s') }}
                                </small>
                                <br>
                                <small>
                                    <span class="fa-fw select-all fas"></span>
                                    {{ $event->location }}
                                </small>
                            </p>


                        </div>

                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <div>
                            @if ($event->status->status == 'takedown' || $event->status->status == 'rejected')
                                <span class="badge bg-danger">{{ $event->status->status }}</span>
                            @endif
                            @if ($event->status->status == 'waiting')
                                <span class="badge bg-info">{{ $event->status->status }}</span>
                            @endif
                            @if ($event->status->status == 'verified')
                                <span class="badge bg-success">{{ $event->status->status }}</span>
                            @endif
                        </div>

                        <div>
                            @if ($event->status->status == 'verified' || $event->status->status == 'takedown')
                                <a href="/organizer/event/detail/{{ $event->id }}"
                                    class="btn btn-light-primary">Detail</a>
                            @else
                                <form action="/organizer/event/delete/{{ $event->id }}" method="POST" class="d-none">
                                    @csrf
                                    <input type="submit" id="delete-form-submit">
                                </form>

                                <button onclick="showDeleteDialog()" class="btn btn-light-danger">hapus</button>
                                <script>
                                    async function showDeleteDialog() {
                                        Swal.fire({
                                            title: 'Do you want to delete this event ?',
                                            showCancelButton: true,
                                            confirmButtonText: 'Yes',
                                            denyButtonText: 'No',
                                            customClass: {
                                                cancelButton: 'order-1 right-gap',
                                                confirmButton: 'order-2',
                                            }
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                document.getElementById('delete-form-submit').click()
                                            }
                                        })
                                    }
                                </script>
                                <a href="/organizer/event/edit/{{ $event->id }}" class="btn btn-light-primary">Edit</a>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        @empty
            <div style="height: 100vh;" class="d-flex justify-content-center align-items-center flex-column">
                <h3>Upps</h3>
                <p>Sepertinya anda belum memiliki event buat sekarang</p>
                <a href="/organizer/create" class="btn btn-primary">Buat event</a>
            </div>
        @endforelse

        <a href="/organizer/create" class="bg-primary fab-add-event">+</a>
    </div>




@endsection

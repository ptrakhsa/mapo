@section('title', 'Organizer Profile')
@extends('layouts.admin-panel')

@section('content')
    <div class="page-heading">
        {{-- DASHBOARD EVENT LIST --}}
        <section id="content-types" class="card px-5 py-5">
            {{-- profile --}}
            <div class="d-flex justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-xl me-4">
                        <img src="/assets/images/faces/1.jpg" alt="" srcset="">
                    </div>
                    <div class="d-flex flex-column">
                        <h4>{{ $organizer->name }}</h4>
                        <small style="line-height: 1.2;">{{ $organizer->email }}</small>
                        <small>{{ $organizer->address }}</small>
                    </div>
                </div>
            </div>


            {{-- event info --}}
            <div class="mt-2">
                <small>
                    Joined at {{ date_format(date_create($organizer->created_at), 'H:i A j F Y') }}, total
                    {{ count($organizer->events) }} events submitted
                </small>
            </div>

            @if (count($event_statuses) !== 0)
                <div class="mt-4">
                    {{-- event by statuses --}}
                    <a href="/admin/organizer/{{ $organizer->id }}/events?status=all" @class([
                        'btn',
                        'btn-sm',
                        'rounded-pill',
                        'btn-primary' => $recent_status_query == 'all',
                    ])>
                        all events
                    </a>

                    @foreach ($event_statuses as $status => $count)
                        <a href="/admin/organizer/{{ $organizer->id }}/events?status={{ $status }}"
                            @class([
                                'btn',
                                'btn-sm',
                                'rounded-pill',
                                'btn-primary' => $recent_status_query == $status,
                            ])>
                            {{ $status }}
                            <span @class([
                                'badge',
                                'bg-transparent' => $recent_status_query == $status,
                                'bg-secondary' => $recent_status_query != $status,
                            ])>{{ $count }}</span>
                        </a>
                    @endforeach


                    {{-- events style="background-color: #f8f8f8;" --}}
                    <div class="row mt-3 py-3">
                        @forelse ($events_by_status as $event)
                            <div class="col-xl-4 col-md-6 col-sm-12">
                                <div class="card" style="border: 1px solid #cdd0d2;">
                                    <div class="card-content">
                                        <img style="max-height: 400px;object-fit: contain;" class="img-fluid w-100"
                                            src="{{ $event->photo }}" alt="Card image cap">
                                        <div class="card-body">
                                            <h4 class="card-title">{{ $event->name }}</h4>
                                            <span class="badge bg-light-primary mb-3">{{ $event->category->name }}</span>
                                            <p class="card-text truncate-threeline">
                                                {{ $event->description }}
                                            </p>
                                            <p class="card-text">
                                                <small>
                                                    <span class="fa-fw select-all fas"></span>
                                                    {{ date_format(date_create($event->start_date), 'H:i A, j F Y') }}
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
                                            <span @class([
                                                'badge',
                                                'bg-secondary' => $event->status->status == 'waiting',
                                                'bg-warning' => $event->status->status == 'rejected',
                                                'bg-info' => $event->status->status == 'verified',
                                                'bg-success' => $event->status->status == 'done',
                                                'bg-danger' => $event->status->status == 'takedown',
                                            ])>{{ $event->status->status }}</span>
                                        </div>
                                        <div>
                                            <a href="/admin/event/show/detail/{{ $event->id }}"
                                                class="btn btn-light-primary">Detail</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div>No data</div>
                        @endforelse
                    </div>

                </div>
            @endif
        </section>
        {{-- END DASHBOARD EVENT LIST --}}

    </div>
@endsection

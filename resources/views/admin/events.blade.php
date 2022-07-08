@section('title', 'Event Organizers')
@extends('layouts.admin-panel')

@section('content')
    <div class="page-heading">

        {{-- PAGE DESCRIPTION --}}
        {{--  --}}
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Events</h3>
                    <p class="text-subtitle text-muted">List of events group by verified and rejected</p>
                </div>
            </div>
        </div>
        {{--  --}}
        {{-- END PAGE DESCRIPTION --}}



        {{-- DASHBOARD EVENT LIST --}}
        <section id="content-types">

            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title">{{ $current_status }} events</h4>
                    <select name="" id="" class="form-select" style="width:200px"
                        onchange="changeEventStatus(this)">
                        <option {{ $current_status == 'all' ? 'selected' : '' }} value="all">All</option>
                        <option {{ $current_status == 'verified' ? 'selected' : '' }} value="verified">Verified</option>
                        <option {{ $current_status == 'done' ? 'selected' : '' }} value="done">Done</option>
                        <option {{ $current_status == 'rejected' ? 'selected' : '' }} value="rejected">Rejected</option>
                        <option {{ $current_status == 'takedown' ? 'selected' : '' }} value="takedown">Takedown</option>
                    </select>
                    <script>
                        function changeEventStatus(e) {
                            window.location = `/admin/events?status=${e.value}`
                        }
                    </script>
                </div>
                <div class="card-content">
                    <!-- Table with no outer spacing -->
                    <div class="table-responsive">
                        <table class="table mb-0 table-lg">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Start date</th>
                                    <th>Location</th>
                                    <th>Organizer</th>
                                    <th class="{{ $current_status != 'all' ? 'd-none' : '' }}">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($events as $event)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td class="text-bold-500">
                                            <a href="/admin/event/show/detail/{{ $event->id }}"
                                                style="font-weight: bold;">{{ $event->name }}</a>
                                        </td>
                                        <td>{{ $event->start_date }}</td>
                                        <td class="text-bold-500">{{ $event->location }}</td>
                                        <td>{{ $event->organizer->name }}</td>
                                        <td class="{{ $current_status != 'all' ? 'd-none' : '' }}">
                                            <span @class([
                                                'badge',
                                                'bg-secondary' => $event->status->status == 'waiting',
                                                'bg-warning' => $event->status->status == 'rejected',
                                                'bg-info' => $event->status->status == 'verified',
                                                'bg-success' => $event->status->status == 'done',
                                                'bg-danger' => $event->status->status == 'takedown',
                                            ])>
                                                {{ $event->status->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="5">No data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
        {{-- END DASHBOARD EVENT LIST --}}

    </div>
@endsection

@section('title', 'Terms and Conditions')
@extends('layouts.master')

@section('content')
    {{-- content --}}
    <div style="height: 100vh;" class="d-flex justify-content-center align-items-center flex-column">
        <div class="card" style="min-height: 400px;width: 800px">
            <div class="card-body d-flex justify-content-center align-items-center flex-column">
                <h3>Term of conditions</h3>
                <br>
                <ol>
                    <li>
                        After the event is submitted, <strong> Mapo </strong> will review and then decide whether your event is accepted
                        or rejected.
                    </li>
                    <li>An event that has been verified can not be edited or removed.</li>
                    <li>Organizer can't submit an event which the same time and location as another verified event.</li>
                    <li>If your event is marked as abuse, <strong> Mapo </strong> can takedown it.</li>
                    <li>Event cant more than one week.</li>
                    <li>
                        Every submitted event has 5 statuses :
                        <table class="table">
                            <tr>
                                <td><span class="badge bg-secondary">waiting</span></td>
                                <td><small>waiting means an event still waiting for <strong> Mapo </strong> review.</small></td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-warning">rejected</span></td>
                                <td>
                                    <small>
                                        rejected means your event was rejected/denied by <strong> Mapo </strong>, this status comes with
                                        various reasons why your event had rejected. even if your event is rejected you can
                                        fix it and resubmit again.
                                    </small>
                                </td>
                            </tr>

                            <tr>
                                <td><span class="badge bg-info">verified</span></td>
                                <td>
                                    <small>
                                        verified means your event has been accepted by <strong> Mapo </strong> and listed in <strong> Mapo </strong> application.
                                    </small>
                                </td>
                            </tr>

                            <tr>
                                <td><span class="badge bg-danger">takedown</span></td>
                                <td>
                                    <small>
                                        takedown means your event has takedown by <strong> Mapo </strong> because we realize if your event is
                                        abuse.
                                    </small>
                                </td>
                            </tr>

                            <tr>
                                <td><span class="badge bg-success">done</span></td>
                                <td>
                                    <small>
                                        done means your event had been finished.
                                    </small>
                                </td>
                            </tr>
                        </table>
                    </li>
                </ol>
            </div>
        </div>

    </div>



@endsection

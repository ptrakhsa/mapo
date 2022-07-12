@section('title', 'Categories')
@extends('layouts.admin-panel')

@section('content')
    <div class="page-heading">

        {{-- PAGE DESCRIPTION --}}
        {{--  --}}
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Event Categories</h3>
                    <p class="text-subtitle text-muted">Count event by categories</p>
                </div>
            </div>
        </div>
        {{--  --}}
        {{-- END PAGE DESCRIPTION --}}



        {{-- DASHBOARD EVENT LIST --}}
        <section id="content-types">
            <div class="card">
                <div class="card-content">
                    <!-- Table with no outer spacing -->
                    <div class="table-responsive">
                        <table class="table mb-0 table-lg">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Total events</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($categories as $category)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td class="text-bold-500">
                                            {{ $category->name }}
                                        </td>
                                        <td class="text-bold-500">{{ $category->count }}</td>
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

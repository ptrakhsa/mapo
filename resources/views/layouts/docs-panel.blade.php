<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mapo Docs | @yield('title')</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    {{-- assets --}}
    <link rel="stylesheet" href="/assets/css/bootstrap.css">
    <link rel="stylesheet" href="/assets/vendors/iconly/bold.css">
    <link rel="stylesheet" href="/assets/vendors/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="/assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/app.css">
    <link rel="shortcut icon" href="/assets/images/favicon.svg" type="image/x-icon">

    <link rel="stylesheet" href="/assets/vendors/fontawesome/all.min.css">
    <link rel="stylesheet" href="/assets/vendors/bootstrap-icons/bootstrap-icons.css">

    <link rel="stylesheet" href="/assets/css/animation.css">
    <script src="/assets/vendors/sweetalert2/sweetalert2.all.min.js"></script>
    {{-- end assets --}}

    {{-- basic styles --}}
    <style>
        .fontawesome-icons .the-icon svg {
            font-size: 24px;
        }

        pre {
            background-color: #f3f3f3;
            color: rgb(214, 71, 71);
            border-radius: 10px;
            padding-top: 20px;
            padding-bottom: 20px;
        }

        /* width */
        ::-webkit-scrollbar {
            width: 8px;
        }

        /* Handle */
        ::-webkit-scrollbar-thumb {
            background: #90a4ae;
            border-radius: 10px;
        }

        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
            background: #78909c;
        }
    </style>

    {{-- head content --}}
    @yield('head')
    {{--  --}}

</head>

<body>

    <div id="app">
        <div id="sidebar" class="active">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header">
                    <div class="d-flex justify-content-between">
                        <div class="toggler">
                            <a href="#" class="sidebar-hide d-xl-none d-block"><i
                                    class="bi bi-x bi-middle"></i></a>
                        </div>
                    </div>
                </div>

                {{-- SIDEBAR --}}
                {{--  --}}

                <div class="sidebar-menu">
                    <ul class="menu">
                        @foreach ($navs as $nav)
                            <li class="sidebar-item  ">
                                <a href="/docs?page={{ $nav }}" class='sidebar-link'>
                                    <span>{{ $nav }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
            </div>
        </div>

        {{-- MAIN CONTENT --}}
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            <div class="card">
                <div class="card-content px-5 py-5">
                    @yield('content')
                </div>
            </div>

        </div>
    </div>



    <script src="/assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="/assets/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/vendors/fontawesome/all.min.js"></script>
    <script src="/assets/js/mazer.js"></script>
</body>

</html>

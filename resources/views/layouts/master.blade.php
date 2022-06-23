<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Event map @yield('title')</title>
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
    <script src="/assets/vendors/sweetalert2/sweetalert2.all.min.js"></script>
    {{-- end assets --}}

    {{-- toastify --}}
    <script src="/assets/vendors/toastify/toastify.js"></script>
    <link rel="stylesheet" href="/assets/vendors/toastify/toastify.css">
    {{-- end toastify --}}


    {{-- quil css --}}
    <link rel="stylesheet" href="/assets/vendors/quill/quill.bubble.css">
    <link rel="stylesheet" href="/assets/vendors/quill/quill.snow.css">
    <link rel="stylesheet" href="/assets/vendors/quill/quill.core.css">

    {{-- basic styles --}}
    <style>
        .fontawesome-icons .the-icon svg {
            font-size: 24px;
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

    @yield('head')
</head>

<body>


    @yield('content')



    <script src="/assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="/assets/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/vendors/fontawesome/all.min.js"></script>
    <script src="/assets/js/mazer.js"></script>
</body>

</html>

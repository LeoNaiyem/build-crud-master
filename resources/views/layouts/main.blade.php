<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel & MySQL CRUD App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            padding-top: 56px;
            padding-bottom: 60px;
        }

        footer.fixed-bottom {
            z-index: 1030;
        }
    </style>
</head>

<body>

    {{-- Fixed Header --}}
    @include('layouts.header')

    {{-- Main Content --}}
    <main class="container py-4">
        @yield('content')
    </main>

    {{-- Fixed Footer --}}
    @include('layouts.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Court Appointment Calendar</title>
    <!-- Link to your compiled CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- Link to Material Design Icons (example using a CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.x/css/materialdesignicons.min.css" rel="stylesheet">
    <!-- FullCalendar CSS -->
    <link href='{{ asset("fullcalendar/main.css") }}' rel='stylesheet' />
    <!-- You might link a Material Design CSS framework here later -->

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body>

    <div id="app">
        @yield('content')
    </div>

    <!-- Link to your compiled JavaScript -->
    <script src="{{ asset('js/app.js') }}"></script>
    <!-- FullCalendar JS -->
    <script src='{{ asset("fullcalendar/main.js") }}'></script>
</body>
</html>

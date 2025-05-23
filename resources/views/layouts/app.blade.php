<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Legal Appointment Dashboard</title>
    <!-- Link to Material Design Icons (example using a CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.x/css/materialdesignicons.min.css" rel="stylesheet"/>
    <link href="{{ asset('css/fullcalendar/main.css') }}" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/calendar.js'])

</head>
<body>

    <div id="app">
        @yield('content')
    </div>

</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <title>Appointment Reminder</title>
</head>
<body>
    <h2>Appointment Reminder: {{ $appointment->title }}</h2>

    <p>This is a reminder for your upcoming appointment.</p>

    <ul>
        <li><strong>Title:</strong> {{ $appointment->title }}</li>
        <li><strong>Description:</strong> {{ $appointment->description }}</li>
        <li><strong>Date:</strong> {{ \Carbon\Carbon::parse($appointment->date)->format('F j, Y') }}</li>
        <li><strong>Time:</strong> {{ \Carbon\Carbon::parse($appointment->time)->format('h:i A') }}</li>
        <li><strong>Location:</strong> {{ $appointment->location }}</li>
    </ul>

    <p>Please ensure you are prepared for this appointment.</p>

    <p>Thank you.</p>
</body>
</html>
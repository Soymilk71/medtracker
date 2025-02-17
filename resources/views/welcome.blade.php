<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicatie</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js" defer></script>

</head>
<body>
    <main>

<H1>Medicatie check</H1>

@if (session('success'))
        <p>{{ session('success') }}</p>
    @endif
    <form action="/medscheck" method="POST">
    @csrf  <!-- Laravel CSRF bescherming -->

    <label for="meds_taken">Medicatie ingenomen: </label>
    <input type="checkbox" name="meds_taken" id="meds_taken" required>
    <button type="submit">Verzenden</button>
</form>
<br>


    @if ($last_dose && $next_dose)
        <p>Laatste dosis: {{ \Carbon\Carbon::parse($last_dose)->format('d-m H:i') }}</p>
        <p>Volgende dosis: {{ \Carbon\Carbon::parse($next_dose)->format('d-m H:i') }}</p>
    @else
        <p>Er zijn nog geen medicatie-informatie beschikbaar.</p>
    @endif
</body>
</html>
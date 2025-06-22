<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Códigos de Gift Card</title>
</head>
<body>
    <h2>Hola {{ $user->name }},</h2>
    <p>Gracias por tu compra. Aquí están los códigos de tus Gift Cards:</p>

    <ul>
        @foreach ($codes as $code)
            <li><strong>{{ $code['gift_card'] }}</strong>: {{ $code['code'] }}</li>
        @endforeach
    </ul>

    <p>¡Disfrutalas!</p>
    <p>— El equipo</p>
</body>
</html>

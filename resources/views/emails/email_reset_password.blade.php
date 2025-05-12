<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Cardify - Restablecer Contraseña</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #0f172a;
            color: #ffffff;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #1e293b;
            padding: 30px;
            border-radius: 8px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            width: 100px;
            margin-bottom: 10px;
        }
        .header h1 {
            color: #a4cadc;
            font-size: 32px;
        }
        .message {
            font-size: 18px;
            line-height: 1.6;
            color: #ffffff;
            margin-bottom: 20px;
        }
        .cta-button {
            background-color: #163f47;
            color: white;
            padding: 15px 25px;
            text-decoration: none;
            font-size: 16px;
            display: inline-block;
            border-radius: 5px;
            text-align: center;
        }
        .cta-button:hover {
            background-color: #050f1b;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
        }
        .footer a {
            color: #a4cadc;
            text-decoration: none;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <img src="https://img.icons8.com/ios-filled/100/ffffff/bank-card-back-side.png" alt="Cardify Logo">
            <h1>Cardify</h1>
        </div>

        <div class="message">
            <p>Hola, hemos recibido una solicitud para restablecer la contraseña de tu cuenta en Cardify.</p>
            <p>Si no realizaste esta solicitud, ignora este correo.</p>
            <p>Si deseas restablecer tu contraseña, haz clic en el siguiente enlace:</p>
        </div>

        <div class="cta">
            <a href="{{ $resetUrl }}" class="cta-button">Restablecer Contraseña</a>
        </div>

        <div class="footer">
            <p>Si no puedes hacer clic en el botón, copia y pega el siguiente enlace en tu navegador:</p>
            <p><a href="{{ $resetUrl }}">{{ $resetUrl }}</a></p>
            <p>Si tienes alguna pregunta, no dudes en contactarnos.</p>
        </div>
    </div>

</body>
</html>

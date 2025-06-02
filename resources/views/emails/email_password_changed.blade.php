<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Cardify - Confirmación de Cambio de Contraseña</title>
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
            <p>Estimado/a {{ $user->name }},</p>
            <p>Le informamos que su contraseña ha sido modificada correctamente. Si no ha realizado esta acción, le solicitamos que se ponga en contacto con nuestro equipo de soporte lo antes posible.</p>
            <p>Si tiene alguna consulta adicional, no dude en ponerse en contacto con nosotros.</p>
        </div>

        <div class="footer">
            <p>Para cualquier duda o solicitud, por favor no dude en escribirnos a <a href="mailto:support@cardify.com">support@cardify.com</a>.</p>
            <p>Atentamente,</p>
            <p><strong>El equipo de Cardify</strong></p>
        </div>
    </div>

</body>
</html>

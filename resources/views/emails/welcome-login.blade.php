<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .content {
            padding: 30px;
            color: #333;
        }
        .greeting {
            font-size: 18px;
            color: #667eea;
            margin-bottom: 20px;
        }
        .info-box {
            background-color: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 20px 0;
        }
        .info-box strong {
            color: #667eea;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .security-notice {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>🎉 Bienvenue !</h1>
        </div>

        <div class="content">
            <p class="greeting">
                Bonjour <strong>{{ $personnel->prenom_personnel }} {{ $personnel->nom_personnel }}</strong>,
            </p>

            <p>
                Nous sommes ravis de vous revoir ! Vous venez de vous connecter avec succès à votre espace personnel.
            </p>

            <div class="info-box">
                <strong>📅 Date et heure :</strong> {{ $loginTime }}<br>
                <strong>🌐 Adresse IP :</strong> {{ $ipAddress }}<br>
                <strong>👤 Login :</strong> {{ $personnel->login_personnel }}
            </div>

            <div class="security-notice">
                <strong>🔒 Note de sécurité :</strong><br>
                Si vous n'êtes pas à l'origine de cette connexion, veuillez contacter immédiatement l'administrateur système.
            </div>

            <p>
                Nous vous souhaitons une excellente session de travail !
            </p>
        </div>

        <div class="footer">
            <p>© {{ date('Y') }} {{ config('app.name') }}. Tous droits réservés.</p>
            <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
        </div>
    </div>
</body>
</html>

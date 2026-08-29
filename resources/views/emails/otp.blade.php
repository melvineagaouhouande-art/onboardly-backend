<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Votre code de vérification Onboardly</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 20px; border-radius: 8px; border: 1px solid #ddd; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2 style="color: #4f46e5; text-align: center;">Bienvenue sur Onboardly !</h2>
        <p>Bonjour {{ $userName }},</p>
        <p>Pour valider votre action, veuillez saisir le code de vérification à 6 chiffres suivant dans l'application :</p>
        <div style="font-size: 32px; font-weight: bold; color: #4f46e5; text-align: center; margin: 30px 0; padding: 15px; background-color: #f3f4f6; letter-spacing: 5px; border-radius: 5px;">
            {{ $otpCode }}
        </div>
        <p style="font-size: 13px; color: #666; line-height: 1.5;">Ce code est valide pendant 10 minutes. Si vous n'êtes pas à l'origine de cette demande, vous pouvez ignorer cet e-mail.</p>
        <hr style="border: none; border-top: 1px solid #eee; margin: 20px 0;">
        <p style="font-size: 12px; color: #888; text-align: center; margin: 0;">L'équipe RH Onboardly</p>
    </div>
</body>
</html>

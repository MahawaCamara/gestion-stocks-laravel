<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Bienvenue</title>
</head>
<body>
    <h2>Bonjour {{ $name }},</h2>
    <p>Votre compte a été créé avec succès.</p>
    <p>Voici vos identifiants :</p>
    <ul>
        <li><strong>Email :</strong> {{ $email }}</li>
        <li><strong>Mot de passe :</strong> {{ $password }}</li>
    </ul>
    <p>Merci de vous connecter et de changer votre mot de passe après la première connexion.</p>
</body>
</html>

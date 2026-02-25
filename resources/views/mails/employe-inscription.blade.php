@component('mail::message')
# Bonjour {{ $name }},

Votre compte a été créé avec succès.

Voici vos informations de connexion :

- **Email :** {{ $email }}
- **Mot de passe :** {{ $password }}

> Pour votre sécurité, veuillez changer votre mot de passe dès votre première connexion.

Merci,  
L’équipe RH
@endcomponent

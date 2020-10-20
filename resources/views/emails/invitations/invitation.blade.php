@component('mail::message')
# Bonjour {{ $invitation->cosplayer->name }}, 😊

Je vous invite à rejoindre mon site internet en tant que cosplayer. Grâce à la création de ce compte vous aurez accès au téléchargement de l'album et bien plus encore. Pour créer ce compte vous allez voir c'est très simple :

1. Dans un premier temps, si ce n'est pas déjà fait, je vous invite à créer votre compte :

@component('mail::button', ['url' => url(config('app.frontend_url'))])
S'enregistrer
@endcomponent

⚠ Assurez-vous de bien valider votre adresse email, sinon certaines fonctionnalités ne seront pas accessibles (tel que le téléchargement).

2. Une fois connecté à votre compte, rendez-vous sur le lien ci-dessous.

@component('mail::button', ['url' => url(config('app.frontend_url').'/invitations/'.$invitation->uuid)])
Valider mon invitation
@endcomponent

Votre compte est dorénavant relié au cosplayer {{ $invitation->cosplayer->name }}.

3. Vous pouvez mainenant accéder et télécharger vos albums depuis 'Mon profil' -> 'Mes albums'.

Si vous avez la moindre question, n'hésitez pas à me contacter via le formulaire de contact, ou sur les réseaux sociaux.
Je me ferrais un plaisir de vous répondre. 😉

{{ config('app.name') }}.
@endcomponent




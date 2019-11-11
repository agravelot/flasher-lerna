Bonjour {{ $invitation->cosplayer->name }},

Je vous invite à rejoindre mon site internet en tant que cosplayer, en créant un compte vous aurez la possibilité de télécharger vos photos, et bien plus encore.

1. C'est très simple, si ce n'est pas déjà fait, je vous invite à créer votre compte :
{{ url(route('register')) }}

Assurez-vous de bien valider votre adresse email, sinon certaines fonctionnalités ne seront pas accessibles (tel que le téléchargement).

2. Une fois connecté à votre compte, rendez-vous sur le lien ci-dessous.
{{ $temporaryInvitationUrl }}

Votre compte est maintenant relié au cosplayer : {{ $invitation->cosplayer->name }}.

3. Vous pouvez mainenant accéder et télécharger vos albums depuis 'Mon profil' -> 'Mes albums'.


Si vous avez la moindre question, n'hésitez pas à me contacter via le formulaire de contact, ou sur les réseaux sociaux.
Je me ferrais un plaisir de vous répondre.

JKanda.



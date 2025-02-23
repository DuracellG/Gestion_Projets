<p>Bonjour {{ $user->name }},</p>
<p>Vous avez été invité à rejoindre le projet <strong>{{ $projet->titre }}</strong>.</p>
<p>Connectez-vous à votre compte pour accéder au projet en cliquant sur le lien ci-dessous :</p>
<p><a href="{{ url('/dashboard') }}">Accéder au projet</a></p>
<p>Merci!</p>

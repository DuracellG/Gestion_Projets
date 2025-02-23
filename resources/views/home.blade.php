@extends('layouts.app')

@section('content')
<div class="container d-flex flex-column justify-content-center align-items-center" style="height: 100vh; text-align: center;">
    <div id="welcome" class="fw-bold" style="font-size: 2.5rem; white-space: pre-line; overflow: hidden; max-width: 90%; word-wrap: break-word; animation: typing 4s steps(50, end) forwards;"></div>
    <img src="https://www.pngmart.com/files/22/Welcome-PNG-Photo.png" alt="Bienvenue" style="width: 200px; margin-top: 20px;">
</div>

<script>
    const text = "Bienvenue sur votre application de\nGestion de projets collaboratifs ! \n😊";
    let i = 0;
    function typeEffect() {
        if (i < text.length) {
            document.getElementById("welcome").textContent += text.charAt(i);
            i++;
            setTimeout(typeEffect, 50);
        } else {
            // Ajoute une animation pour le curseur à la fin de l'écriture
            document.getElementById("welcome").style.borderRight = "5px solid black"; // Curseur visible
            document.getElementById("welcome").style.animation = "blink 0.8s infinite";
        }
    }
    typeEffect();
</script>

<style>
    @keyframes blink {
        50% { border-color: transparent; }
    }
</style>
<!-- Bouton pour accéder au dashboard -->
<a href="{{ route('dashboard') }}" class="btn btn-primary position-absolute top-1 end-0 m-0">Accéder au Dashboard</a>
@endsection

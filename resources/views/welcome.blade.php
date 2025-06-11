<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-g">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <style>
            body {
                display: flex;
                align-items: center;
                justify-content: center;
                min-height: 100vh;
                background-color: #f8f9fa;
            }
        </style>
    </head>
    <body>
        @if (Route::has('login'))
            <div class="position-absolute top-0 end-0 p-3 text-end">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-outline-secondary">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary">Log in</a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-secondary ms-2">Registrar</a>
                    @endif
                @endauth
            </div>
        @endif

        <div class="container text-center">
            <h1 class="display-4 fw-bold">Bem-vindo ao ProjectHub</h1>
            <p class="lead text-muted mt-3">
                Sua plataforma completa para gerenciamento de projetos e tarefas. Organize suas equipes, acompanhe o progresso e atinja seus objetivos.
            </p>
        </div>
    </body>
</html>
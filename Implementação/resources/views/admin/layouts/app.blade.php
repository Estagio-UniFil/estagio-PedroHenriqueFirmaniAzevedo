@if (auth()->check())

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Organização Pensamento Computacional</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Afacad+Flux:wght@100..1000&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="{{ asset('build/assets/logo-unifil.png') }}">
    <style>
        body {
            background-color: #F7F7F7;
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            overflow-x: hidden;
        }

        .navbar {
            background-color: #F29400;
            color: white;
            padding: 10px 20px;
            position: sticky;
            top: 0;
            z-index: 50;
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        .navbar-toggler {
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
        }

        .navbar-brand img {
            max-height: 40px;
            margin-right: 10px;
        }

        .sidebar {
            background-color: #707173;
            height: 100vh;
            padding: 20px;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            color: white;
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
            z-index: 25;
            overflow-y: auto;
        }

        .sidebar.open {
            transform: translateX(0);
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px;
            margin-bottom: 5px;
            white-space: nowrap;
        }

        .sidebar a:hover {
            background-color: #F29400;
        }

        .content {
            margin-left: 0;
            padding: 20px;
            transition: margin-left 0.3s ease-in-out;
        }

        .content.shifted {
            margin-left: 250px;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
            }

            .content.shifted {
                margin-left: 0;
            }

            .navbar {
                flex-direction: row;
            }

            .navbar-brand img {
                max-height: 30px;
            }

            .navbar-toggler {
                font-size: 20px;
            }
        }

        @media (max-width: 576px) {
            .navbar-brand img {
                max-height: 25px;
            }

            .navbar-brand span {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <button class="navbar-toggler" id="navbar-toggler">
            ☰
        </button>
        <div class="navbar-brand">
            <img src="{{ asset('storage/logo-unifil.png') }}" alt="Logo">
            <span style="font-family: Afacad Flux;">ComPensa</span>
        </div>
    </nav>

    <div class="sidebar" id="sidebar">
        <div class="d-flex align-items-center mb-4 mt-4">

        </div>
        <hr>
        @if (auth()->check() && auth()->user()->role !== 'aluno')
        <a href="{{ route('dashboard.inicial') }}">Dashboard</a>
        <hr>
        <a href="{{ route('turmas.index') }}">Turmas</a>
        <hr>
        <a href="{{ route('alunos.index') }}">Alunos</a>
        <hr>
        <a href="{{ route('monitores.index') }}">Monitores</a>
        <hr>
        <a href="{{ route('escolas.index') }}">Escola</a>
        <hr>
        <a href="{{ route('presencas.index') }}">Presença</a>
        <hr>
        <a href="{{ route('atividades.index') }}">Atividades</a>
        <hr>
        <a href="{{ route('import.export') }}">Importar/Exportar</a>
        <hr>
        <a href="{{ route('graficos.index') }}">Gráficos</a>
        <hr>
        @endif

        @if (auth()->check())
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        <a href="#" class="btn btn-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sair</a>
        @endif
    </div>

    <div class="content" id="content">
        <main>
            @yield('content')
        </main>
    </div>
    @endif
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <script>
        document.getElementById('navbar-toggler').addEventListener('click', function() {
            var sidebar = document.getElementById('sidebar');
            var content = document.getElementById('content');
            sidebar.classList.toggle('open');
            content.classList.toggle('shifted');
        });
    </script>
</body>

</html>

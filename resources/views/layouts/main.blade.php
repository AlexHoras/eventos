<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        
        <title>@yield('title')</title>
        <link rel="stylesheet" href="/css/style.css">
        <script src="/js/js.js"></script>
        <script src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons.js"></script>

        <!-- Fontes do google -->
        <!-- Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
       
    </head>
    <body >
        <header>
            <nav class="navbar navbar-expand-lg navbar-light">
                <div class="collapse navbar-collapse" id="navbar">
                    <a href="/" class="navbar-brand">
                        <img src="/img/logo.png" alt="Brasil Prensas">
                    </a>
                    <ul class="navbar-nav">
                        <li class="navbar-item">
                            <a href="/" class="nav-link">Eventos</a>
                        </li>
                        <li class="navbar-item">
                            <a href="/events/create" class="nav-link">Criar Eventos</a>
                        </li>

                        @auth
                            <li class="navbar-item">
                                <a href="/dashboard" class="nav-link">Meus eventos</a>
                            </li>
                            <li class="navbar-item">
                                <form action="/logout" method="POST">
                                    @csrf
                                    <a href="/logout" 
                                        class="nav-link" 
                                        onclick="event.preventDefault();
                                        this.closest('form').submit();
                                    ">
                                        Sair
                                    </a>
                                </form>
                            </li>
                        @endauth

                        @guest
                            <li class="navbar-item">
                                <a href="/login" class="nav-link">Entrar</a>
                            </li>
                            <li class="navbar-item">
                                <a href="/register" class="nav-link">Cadastra</a>
                            </li>
                        @endguest                        
                    </ul>
                </div>
            </nav>
        </header>
        <main>
            <div class="container-fluid">
                <div class="row">
                    @if (session('msg'))
                        <p class="msg">{{ session('msg') }}</p>
                    @endif
                    @yield('content')
                </div>
            </div>
        </main>
        <footer>
            <p>HDC Events &copy; 2021 </p>
        </footer>        
        <script  type = "module"  src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js" > </script> 
        <script  nomodule  src = "https: // unpkg .com / ionicons @ 5.5.2 / dist / ionicons / ionicons.js " > </script>
    </body>
</html>
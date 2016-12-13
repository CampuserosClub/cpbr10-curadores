<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <a href="http://campuseirosclub.com/">
                        <img src="http://campuseirosclub.com/wp-content/uploads/2015/12/logo-atualizado-horizontal.png" height="120px" />
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <span align="center">
                        <h1>Vire um Curador<br /><small>Resultado</small></h1>
                    </span>
                </div>
            </div>
        </nav>

        @yield('content')

        <div class="navbar-fixed-bottom text-center text-muted">
            <div class="container-fluid badge alert-warning">
                Developed by <a href="https://twitter.com/jaonoctus" target="_blank" class="alert-link">@jaonoctus</a>
                | Made with <i class="fa fa-heart-o"></i>. We are on <a href="https://github.com/CampuserosClub/vire-um-curador" class="alert-link">GitHub</a>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="/js/app.js"></script>
</body>
</html>

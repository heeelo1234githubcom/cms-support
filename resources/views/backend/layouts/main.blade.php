<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name') }} - Manage</title>

        <link href="{{ mix('/assets/backend/css/bootstrap.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ mix('/assets/backend/css/app.css') }}" rel="stylesheet" type="text/css">
        <script src="/assets/backend/js/pace.min.js"></script>

        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <script>
            var Application = {!! getApplicationConfigs() !!};
        </script>
    </head>
    <body>

        @yield('content')

        <script src="{{ mix('/assets/backend/js/app.js') }}"></script>
        <script src="/assets/backend/js/pixeladmin.min.js"></script>
        <script src="{{ mix('/assets/backend/js/init.js') }}"></script>
    </body>
</html>

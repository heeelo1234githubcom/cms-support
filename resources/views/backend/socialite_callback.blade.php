<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Redirecting...</title>

    <script>
        var $token = '{{ $token }}';
        if ($token) {

            localStorage.setItem('jwt-token', 'Bearer ' + $token);
            localStorage.setItem('jwt-time-generate', Math.floor(Date.now() / 1000));
            localStorage.setItem('remember-login', true);

            // redirect to backend page
            window.location.href = '/manage';
        }
    </script>
</head>
<body>
    Redirecting...
</body>
</html>

<?php
$admin = ''.($admin ?? 'Urself');
?>
<!DOCTYPE html>
<html>

<head>
    <title>Goodbye From Tokoman</title>
</head>

<body>
    <img src="https://tokoman.s3.ap-southeast-2.amazonaws.com/asset/logo.png" width="300" height="300" alt="Logo">
    <h1>Hello, {{ $user->name }}</h1>
    <p>Welcome to TOKOMAN App</p>
    <p>May You Got Best Experience with Us</p>
    <p>If you have any trouble, please contact us at <a
            href="mailto:tokomananekabotolplastik@gmail.com">tokomananekabotolplastik@gmail.com</a>
    </p>
</body>

</html>
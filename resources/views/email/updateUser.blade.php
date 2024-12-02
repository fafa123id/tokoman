@php
$admin = ''.($admin ?? 'Urself');
@endphp
<!DOCTYPE html>
<html>

<head>
    <title>User Updated</title>
</head>

<body>
    <img src="https://tokoman.s3.ap-southeast-2.amazonaws.com/asset/logo.png" width="300" height="300" alt="Logo">
    <h1>Hello, {{ $user->name }}</h1>
    <p>Here is A Recent Action in Your Account</p>
    <p>Ur Account has been edited by {{ $admin }}</p>
    <p>This is your update details</p>
    <ul>
        <li>Name: {{$admin=='admin'? ' Ask Admin':$user->name }}</li>
        <li>Email: {{$admin=='admin'? ' Ask Admin':$user->email }}</li>
        <li>password: {{ $change ? ' Changed' : ' Not Changed' }}</li>
    </ul>
    <p>If you have any trouble, please contact us at <a
            href="mailto:tokomananekabotolplastik@gmail.com">tokomananekabotolplastik@gmail.com</a>
    </p>
</body>

</html>
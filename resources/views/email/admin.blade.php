<!DOCTYPE html>
<html>

<head>
    <title>New Verification Request</title>
</head>

<body>
    <img src="https://tokoman.s3.ap-southeast-2.amazonaws.com/asset/logo.png" width="300" height="300" alt="Logo">
    <h1>Hello, Greater Admin</h1>
    <p>There Is Someone Requesting to Join Tokoman App</p>
    <p>Here Is The Account Detail:</p>
    <ul>
        <li>Name: {{$user->name}}</li>
        <li>Email: {{$user->email}}</li>
        <li>Role Requested: {{$user->role_id==0?' Admin':' Employee'}}</li>
        <li>Registered at: {{$user->created_at}}</li>
    </ul>
    <p>If you dont want to accept it, no action required</p>
    <p>If you want accept it</p>
    <a href="{{route('stokBarang').'/admin-verify/'.$token}}">Verify Here</a>
</body>

</html>
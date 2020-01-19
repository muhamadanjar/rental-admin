<!DOCTYPE html>
<html>
<head>
    <title>Verifikasi Email</title>
</head>
 
<body>
<h2>Selamat datang {{$name}}</h2>
<br/>
Your registered email-id is {{$email}} , Please click on the below link to verify your email account
<br/>
<a href="{{url('user/verify', $verification_code)}}">Verify Email</a>
</body>
 
</html>
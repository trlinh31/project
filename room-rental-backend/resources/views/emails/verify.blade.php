<!DOCTYPE html>
<html>
<head>
    <title>Xác thực Email</title>
</head>
<body>
    <h1>Chào {{ $user->name }}</h1>
    <p>Vui lòng nhấp vào liên kết dưới đây để xác thực email của bạn:</p>
    <a href="{{ $verificationUrl }}">Xác thực email</a>
    <p>Nếu bạn không yêu cầu hành động này, vui lòng bỏ qua email này.</p>
</body>
</html>

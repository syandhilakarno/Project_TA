<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>BSPG Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: url('/images/bg.jpg') no-repeat center center fixed;
      background-size: cover;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .login-card {
      background: white;
      border-radius: 15px;
      padding: 30px;
      width: 380px;
      box-shadow: 0px 4px 10px rgba(0,0,0,0.25);
    }
    .login-card img {
      display: block;
      margin: 0 auto 20px;
      width: 280px;
    }
  </style>
</head>
<body>
  <div class="login-card">
    <img src="/img/LOGO.png" alt="BSPG Logo">
    <form method="POST" action="{{ route('login.post') }}">
      @csrf
      <div class="mb-3">
        <input type="email" name="email" class="form-control" placeholder="Email" required autofocus>
      </div>
      <div class="mb-3">
        <input type="password" name="password" class="form-control" placeholder="Password" required>
      </div>
      @if(session('msg'))
        <div class="alert alert-danger text-center p-2">{{ session('msg') }}</div>
      @endif

      <button type="submit" class="btn btn-dark w-100 mt-2">Login</button>
    </form>
  </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login – NGD Admin</title>

  <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/fonts.min.css') }}">

  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      min-height: 100vh;
      background: linear-gradient(135deg, #1e2a3a 0%, #243447 60%, #2c4a6e 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Helvetica Neue', Arial, sans-serif;
    }

    .login-wrapper {
      width: 100%;
      max-width: 440px;
      padding: 20px;
    }

    .login-card {
      background: #ffffff;
      border-radius: 16px;
      box-shadow: 0 20px 60px rgba(0,0,0,.35);
      overflow: hidden;
    }

    /* ── header band ── */
    .login-header {
      background: linear-gradient(135deg, #1e2a3a, #2c4a6e);
      padding: 36px 40px 28px;
      text-align: center;
    }
    .login-header .logo-wrap {
      width: 80px;
      height: 80px;
      margin: 0 auto 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: rgba(255,255,255,.1);
      border-radius: 50%;
      padding: 8px;
    }
    .login-header .logo-wrap img {
      width: 60px;
      height: 60px;
      object-fit: contain;
    }
    .login-header h2 {
      color: #fff;
      font-size: 22px;
      font-weight: 700;
      letter-spacing: .5px;
      margin: 0;
    }
    .login-header .company-tag {
      color: #E8A020;
      font-size: 11px;
      font-weight: 600;
      letter-spacing: 2px;
      text-transform: uppercase;
      margin-top: 4px;
      display: block;
    }
    .login-header p {
      color: rgba(255,255,255,.55);
      font-size: 13px;
      margin-top: 6px;
    }

    /* ── form body ── */
    .login-body { padding: 36px 40px 40px; }

    .form-group { margin-bottom: 20px; }
    .form-group label {
      display: block;
      font-size: 12px;
      font-weight: 700;
      color: #6c757d;
      text-transform: uppercase;
      letter-spacing: .8px;
      margin-bottom: 8px;
    }

    .input-icon { position: relative; }
    .input-icon i {
      position: absolute;
      left: 14px; top: 50%;
      transform: translateY(-50%);
      color: #adb5bd;
      font-size: 14px;
    }
    .input-icon input {
      width: 100%;
      padding: 12px 14px 12px 42px;
      border: 1.5px solid #e9ecef;
      border-radius: 8px;
      font-size: 14px;
      color: #1e2a3a;
      background: #f8f9fa;
      transition: all .2s;
      outline: none;
    }
    .input-icon input:focus {
      border-color: #E8A020;
      background: #fff;
      box-shadow: 0 0 0 3px rgba(232,160,32,.15);
    }
    .input-icon input.is-invalid { border-color: #dc3545; background: #fff8f8; }

    .error-msg {
      background: #fff5f5;
      border: 1px solid #f5c6cb;
      border-radius: 8px;
      padding: 10px 14px;
      margin-bottom: 20px;
      color: #721c24;
      font-size: 13px;
      display: flex; align-items: center; gap: 8px;
    }

    .btn-login {
      width: 100%;
      padding: 13px;
      background: linear-gradient(135deg, #E8A020, #c8850e);
      border: none;
      border-radius: 8px;
      color: #fff;
      font-size: 15px;
      font-weight: 700;
      letter-spacing: .5px;
      cursor: pointer;
      transition: opacity .2s, transform .1s;
      margin-top: 4px;
    }
    .btn-login:hover  { opacity: .92; transform: translateY(-1px); }
    .btn-login:active { transform: translateY(0); }

    .login-footer {
      text-align: center;
      margin-top: 24px;
      font-size: 12px;
      color: #adb5bd;
    }
    .login-footer span { color: #E8A020; font-weight: 600; }
  </style>
</head>
<body>

<div class="login-wrapper">
  <div class="login-card">

    <div class="login-header">
      <div class="logo-wrap">
        <img src="{{ asset('assets/img/ngd-logo.svg') }}" alt="NGD Logo">
      </div>
      <h2>NGD Admin</h2>
      <span class="company-tag">NGD Company</span>
      <p>Sign in to your account</p>
    </div>

    <div class="login-body">

      @if ($errors->any())
        <div class="error-msg">
          <i class="fas fa-exclamation-circle"></i>
          {{ $errors->first() }}
        </div>
      @endif

      <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
          <label for="email">Email Address</label>
          <div class="input-icon">
            <i class="fas fa-envelope"></i>
            <input
              type="email"
              id="email"
              name="email"
              value="{{ old('email') }}"
              placeholder="admin@gmail.com"
              class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
              required
              autofocus
            >
          </div>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <div class="input-icon">
            <i class="fas fa-lock"></i>
            <input
              type="password"
              id="password"
              name="password"
              placeholder="••••••••"
              required
            >
          </div>
        </div>

        <button type="submit" class="btn-login">
          <i class="fas fa-sign-in-alt me-2"></i> Sign In
        </button>
      </form>

      <div class="login-footer">
        &copy; {{ date('Y') }} <span>NGD</span>. All rights reserved.
      </div>

    </div>
  </div>
</div>

<script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
</body>
</html>

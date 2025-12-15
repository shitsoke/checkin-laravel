<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Verify Your Email</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body{background:#fff5f6;font-family:Segoe UI, Tahoma, Geneva, Verdana, sans-serif;color:#333}
    .box{max-width:600px;margin:28px auto;padding:28px;background:#fff;border-radius:10px;border:1px solid #f3d6d6}
    .btn-verify{background:#dc3545;color:#fff;border:none;padding:10px 18px;border-radius:6px;text-decoration:none}
  </style>
</head>
<body>
  <div class="box">
    <h3 style="color:#dc3545;margin-bottom:6px">Verify your email</h3>
    <p>Hi {{ $user->first_name ?? $user->email }},</p>
    <p>Thanks for registering. Click the button below to verify your email address and activate your account.</p>
    <p style="text-align:center;margin:28px 0">
      <a href="{{ $url }}" class="btn-verify">Verify Email</a>
    </p>
    <p style="color:#666;font-size:.95rem">If the button doesn't work, paste this link into your browser:</p>
    <p style="word-break:break-all;color:#666">{{ $url }}</p>
    <p style="color:#999;font-size:.9rem;margin-top:18px">If you didn't register, you can safely ignore this email.</p>
  </div>
</body>
</html>

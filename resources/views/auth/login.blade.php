<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - نظام إدارة العيادات</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Cairo', sans-serif; }
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #1e293b 0%, #2563eb 100%);
            display: flex; align-items: center; justify-content: center;
        }
        .login-card {
            width: 100%; max-width: 420px;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,.3);
        }
        .login-logo {
            width: 64px; height: 64px;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.8rem; color: white;
            margin: 0 auto 1rem;
        }
    </style>
</head>
<body>
<div class="login-card card border-0 p-4">
    <div class="text-center mb-4">
        <div class="login-logo"><i class="fas fa-clinic-medical"></i></div>
        <h4 class="fw-bold mb-1">نظام إدارة العيادات</h4>
        <p class="text-muted small">سجّل دخولك للمتابعة</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle me-1"></i>
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="/login">
        @csrf
        <div class="mb-3">
            <label class="form-label fw-semibold">البريد الإلكتروني</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-envelope text-muted"></i></span>
                <input type="email" name="email" class="form-control"
                    value="{{ old('email') }}" placeholder="admin@admin.com" required autofocus>
            </div>
        </div>
        <div class="mb-4">
            <label class="form-label fw-semibold">كلمة المرور</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-lock text-muted"></i></span>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="remember" class="form-check-input" id="remember">
            <label class="form-check-label" for="remember">تذكرني</label>
        </div>
        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
            <i class="fas fa-sign-in-alt me-2"></i> دخول
        </button>
    </form>

    <div class="text-center mt-3">
        <small class="text-muted">الحساب التجريبي: admin@admin.com / admin</small>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

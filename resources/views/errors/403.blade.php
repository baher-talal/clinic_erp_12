<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>غير مصرح</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
    <style>* { font-family: 'Cairo', sans-serif; } body { background: #f1f5f9; }</style>
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100">
<div class="text-center">
    <div class="display-1 fw-bold text-danger">403</div>
    <h3 class="fw-bold">غير مصرح</h3>
    <p class="text-muted">{{ $message ?? 'ليس لديك صلاحية للوصول لهذه الصفحة' }}</p>
    <a href="{{ url()->previous() }}" class="btn btn-outline-primary me-2">رجوع</a>
    <a href="{{ route('dashboard') }}" class="btn btn-primary">الرئيسية</a>
</div>
</body>
</html>

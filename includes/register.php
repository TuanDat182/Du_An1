<?php session_start(); ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký tài khoản</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">

<div class="card shadow p-4" style="width: 400px;">
    <h3 class="text-center mb-3">Đăng ký tài khoản</h3>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <form method="post" action="register-process.php">
        <div class="mb-3">
            <label>Email:</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Mật khẩu:</label>
            <input type="password" name="mat_khau" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Họ tên:</label>
            <input type="text" name="ho_ten" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Số điện thoại:</label>
            <input type="text" name="so_dien_thoai" class="form-control">
        </div>
        <div class="mb-3">
            <label>Địa chỉ:</label>
            <textarea name="dia_chi" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-success w-100">Đăng ký</button>
        <p class="text-center mt-2">Đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
    </form>
</div>

</body>
</html>

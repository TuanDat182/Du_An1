<?php
session_start();
require_once 'db_crud.php';

$email = $_POST['email'] ?? '';
$mat_khau = $_POST['mat_khau'] ?? '';

if ($email && $mat_khau) {
    // Tìm tài khoản theo email
    $user = select('tai_khoan', 'email = :email', ['email' => $email]);

    if ($user && password_verify($mat_khau, $user[0]['mat_khau'])) {
        // Lưu id tài khoản vào session
        $_SESSION['tai_khoan_id'] = $user[0]['id'];
        $_SESSION['email'] = $user[0]['email'];

        header('Location: index.php'); // hoặc về trang chủ
        exit;
    } else {
        $_SESSION['error'] = "Sai email hoặc mật khẩu!";
        header('Location: login.php');
        exit;
    }
} else {
    $_SESSION['error'] = "Vui lòng nhập đầy đủ thông tin!";
    header('Location: login.php');
    exit;
}
?>

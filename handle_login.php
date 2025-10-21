<?php
// Nạp file kết nối CSDL
require_once 'data/db.php';

// Kiểm tra xem người dùng đã gửi form chưa
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ form
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Kiểm tra dữ liệu đầu vào
    if (empty($email) || empty($password)) {
        // Chuyển hướng lại trang login với thông báo lỗi
        header("Location: login.php?error=empty");
        exit();
    }

    // Chuẩn bị câu lệnh SQL để tránh SQL Injection
    $stmt = $conn->prepare("SELECT user_id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    // Xác thực người dùng (so sánh mật khẩu thường)
    if ($user && $password == $user['password']) {
        // Đăng nhập thành công, lưu user_id vào session
        $_SESSION['user_id'] = $user['user_id'];

        // Chuyển hướng về trang chủ
        header("Location: index.php");
        exit();
    } else {
        // Đăng nhập thất bại, chuyển hướng lại trang login với thông báo lỗi
        header("Location: login.php?error=invalid");
        exit();
    }
} else {
    // Nếu không phải là POST request, chuyển về trang chủ
    header("Location: index.php");
    exit();
}
?>


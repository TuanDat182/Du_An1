<?php
// Bắt đầu session và nạp file kết nối CSDL
require_once 'data/db.php';

// Kiểm tra xem người dùng đã gửi form chưa
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ form
    $fullname = $_POST['fullname'] ?? '';
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Kiểm tra dữ liệu đầu vào cơ bản
    if (empty($fullname) || empty($username) || empty($email) || empty($password)) {
        die("Vui lòng điền đầy đủ thông tin.");
    }

    // Kiểm tra xem email đã tồn tại chưa
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        die("Email này đã được sử dụng. Vui lòng chọn một email khác.");
    }
    $stmt->close();

    // Bắt đầu transaction
    $conn->begin_transaction();

    try {
        // 1. Thêm vào bảng `users`
        // Thay đổi: không băm mật khẩu
        $stmt_users = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt_users->bind_param("sss", $username, $email, $password);
        $stmt_users->execute();
        
        // Lấy user_id vừa tạo
        $user_id = $conn->insert_id;
        $stmt_users->close();

        // 2. Thêm vào bảng `customers`
        $stmt_customers = $conn->prepare("INSERT INTO customers (user_id, full_name) VALUES (?, ?)");
        $stmt_customers->bind_param("is", $user_id, $fullname);
        $stmt_customers->execute();
        $stmt_customers->close();

        // Nếu mọi thứ thành công, commit transaction
        $conn->commit();

        echo "Đăng ký thành công! Bạn sẽ được chuyển đến trang đăng nhập.";
        header("Refresh: 2; url=login.php");
        exit();

    } catch (Exception $e) {
        // Nếu có lỗi, rollback transaction
        $conn->rollback();
        die("Đã có lỗi xảy ra trong quá trình đăng ký: " . $e->getMessage());
    }

} else {
    // Nếu không phải là POST request, chuyển về trang chủ
    header("Location: index.php");
    exit();
}
?>


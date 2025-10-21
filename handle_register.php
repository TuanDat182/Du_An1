<?php
session_start();
require 'data/db.php'; // Nạp file kết nối CSDL

// Hàm để hợp nhất giỏ hàng từ session vào database
function mergeCart($pdo, $userId, $sessionCart) {
    if (empty($sessionCart)) {
        return;
    }

    foreach ($sessionCart as $productId => $item) {
        // Kiểm tra xem sản phẩm đã có trong giỏ hàng của user trong DB chưa
        $stmt = $pdo->prepare("SELECT * FROM cart_items WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$userId, $productId]);
        $existingItem = $stmt->fetch();

        if ($existingItem) {
            // Nếu có rồi, cập nhật số lượng
            $newQuantity = $existingItem['quantity'] + $item['quantity'];
            $updateStmt = $pdo->prepare("UPDATE cart_items SET quantity = ? WHERE cart_item_id = ?");
            $updateStmt->execute([$newQuantity, $existingItem['cart_item_id']]);
        } else {
            // Nếu chưa có, thêm mới
            $insertStmt = $pdo->prepare("INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, ?)");
            $insertStmt->execute([$userId, $productId, $item['quantity']]);
        }
    }

    // Xóa giỏ hàng trong session sau khi đã hợp nhất
    unset($_SESSION['cart']);
}


// Kiểm tra xem có phải là phương thức POST không
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Lấy thông tin người dùng từ CSDL
    $stmt = $pdo->prepare("SELECT user_id, password_hash FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // Xác thực mật khẩu
    if ($user && password_verify($password, $user['password_hash'])) {
        // Đăng nhập thành công, lưu user_id vào session
        $_SESSION['user_id'] = $user['user_id'];

        // Hợp nhất giỏ hàng session vào CSDL
        if (!empty($_SESSION['cart'])) {
            mergeCart($pdo, $user['user_id'], $_SESSION['cart']);
        }

        // Chuyển hướng về trang chủ
        header('Location: index.php');
        exit();
    } else {
        // Đăng nhập thất bại
        $_SESSION['login_error'] = 'Email hoặc mật khẩu không chính xác.';
        header('Location: login.php');
        exit();
    }
} else {
    // Nếu không phải POST, chuyển hướng về trang đăng nhập
    header('Location: login.php');
    exit();
}
?>


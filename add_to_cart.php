<?php
// Bắt đầu session và nạp file kết nối CSDL
// Đây là bước quan trọng nhất để khắc phục lỗi
require_once 'data/db.php';

// Yêu cầu người dùng phải đăng nhập để sử dụng tính năng này
if (!isset($_SESSION['user_id'])) {
    // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
    header("Location: login.php");
    exit();
}

// Chỉ xử lý khi người dùng gửi dữ liệu bằng phương thức POST và có product_id
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    
    $userId = $_SESSION['user_id'];
    $productId = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);

    // Kiểm tra xem product_id có hợp lệ không
    if ($productId === false) {
        // Nếu không hợp lệ, quay về trang chủ
        header("Location: index.php");
        exit();
    }

    // SỬA LỖI: Sử dụng biến $conn đã được nạp từ db.php
    // 1. Kiểm tra xem sản phẩm đã tồn tại trong giỏ hàng của người dùng chưa
    $stmt_check = $conn->prepare("SELECT quantity FROM cart_items WHERE user_id = ? AND product_id = ?");
    $stmt_check->bind_param("ii", $userId, $productId);
    $stmt_check->execute();
    $result = $stmt_check->get_result();
    
    if ($result->num_rows > 0) {
        // 2a. Nếu sản phẩm đã tồn tại, cập nhật số lượng (tăng thêm 1)
        $stmt_update = $conn->prepare("UPDATE cart_items SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?");
        $stmt_update->bind_param("ii", $userId, $productId);
        $stmt_update->execute();
        $stmt_update->close();
    } else {
        // 2b. Nếu sản phẩm chưa tồn tại, thêm mới vào giỏ hàng với số lượng là 1
        $stmt_insert = $conn->prepare("INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, 1)");
        $stmt_insert->bind_param("ii", $userId, $productId);
        $stmt_insert->execute();
        $stmt_insert->close();
    }
    
    $stmt_check->close();
}

// Sau khi xử lý xong, chuyển hướng người dùng về lại trang chủ
header("Location: index.php");
exit();
?>


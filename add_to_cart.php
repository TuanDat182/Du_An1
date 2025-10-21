<?php
// Luôn luôn bắt đầu bằng việc include file kết nối và bắt đầu session
require_once 'data/db.php';

// Kiểm tra xem có phải là request POST không
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['product_id'])) {
    // Nếu không, chuyển hướng về trang chủ
    header('Location: index.php');
    exit();
}

$productId = (int)$_POST['product_id'];

// Giả sử bạn lưu user_id trong session khi họ đăng nhập
$isLoggedIn = isset($_SESSION['user_id']);

if ($isLoggedIn) {
    // --- LOGIC KHI NGƯỜI DÙNG ĐÃ ĐĂNG NHẬP ---
    $userId = $_SESSION['user_id'];

    // 1. Tìm cart_id của người dùng
    $stmt = $pdo->prepare("SELECT cart_id FROM carts WHERE user_id = ?");
    $stmt->execute([$userId]);
    $cart = $stmt->fetch();

    $cartId = null;
    if ($cart) {
        $cartId = $cart['cart_id'];
    } else {
        // Nếu người dùng chưa có giỏ hàng, tạo mới
        $stmt = $pdo->prepare("INSERT INTO carts (user_id) VALUES (?)");
        $stmt->execute([$userId]);
        $cartId = $pdo->lastInsertId();
    }

    // 2. Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
    $stmt = $pdo->prepare("SELECT * FROM cart_items WHERE cart_id = ? AND product_id = ?");
    $stmt->execute([$cartId, $productId]);
    $existingItem = $stmt->fetch();

    if ($existingItem) {
        // Nếu đã có, cập nhật số lượng
        $newQuantity = $existingItem['quantity'] + 1;
        $stmt = $pdo->prepare("UPDATE cart_items SET quantity = ? WHERE cart_item_id = ?");
        $stmt->execute([$newQuantity, $existingItem['cart_item_id']]);
    } else {
        // Nếu chưa có, thêm sản phẩm mới vào
        $stmt = $pdo->prepare("INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (?, ?, 1)");
        $stmt->execute([$cartId, $productId]);
    }

} else {
    // --- LOGIC KHI NGƯỜI DÙNG CHƯA ĐĂNG NHẬP (Dùng SESSION) ---
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Lấy thông tin sản phẩm từ DB để lưu vào session
    $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch();

    if ($product) {
        if (isset($_SESSION['cart'][$productId])) {
            // Nếu sản phẩm đã có trong giỏ hàng, tăng số lượng
            $_SESSION['cart'][$productId]['quantity']++;
        } else {
            // Nếu chưa, thêm mới vào giỏ hàng
            $_SESSION['cart'][$productId] = [
                'name' => $product['name'],
                'price' => $product['price'],
                'image_url' => $product['image_url'],
                'quantity' => 1
            ];
        }
    }
}

// Sau khi xử lý xong, quay lại trang chủ
header('Location: index.php');
exit();
?>

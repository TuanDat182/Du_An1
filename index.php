<?php
    session_start();
    include_once 'data/data.php';

	// Nếu chưa có giỏ hàng thì tạo giỏ hàng rỗng
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Nếu có dữ liệu POST gửi lên
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $img = $_POST['img'];

    // Nếu sản phẩm đã tồn tại trong giỏ thì tăng số lượng
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['quantity']++;
    } else {
        $_SESSION['cart'][$id] = [
            'name' => $name,
            'price' => $price,
            'img' => $img,
            'quantity' => 1
        ];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Shop</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <?php include "includes/header.php"; ?> 
  <?php include "pages/home.php"; ?> 
  <?php include 'includes/footer.php'; ?>
</body>
</html>
<?php
// Bước 1: Gọi file db.php đầu tiên.
// File này sẽ kết nối cơ sở dữ liệu và quan trọng nhất là bắt đầu session.
require_once 'data/db.php';

// Bước 2: Nạp phần đầu của trang (header).
// File header chứa <!DOCTYPE>, <head>, menu và logic giỏ hàng.
require_once 'includes/header.php';

// Bước 3: Nạp nội dung chính của trang (danh sách sản phẩm).
require_once 'pages/home.php';

// Bước 4: Nạp phần cuối của trang (footer).
// File footer chứa thông tin chân trang, các file JavaScript, và thẻ đóng HTML.
require_once 'includes/footer.php';
?>


<?php
// Luôn bắt đầu session ở đầu file
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// === THAY ĐỔI THÔNG TIN CỦA BẠN VÀO ĐÂY ===
$servername = "localhost"; // hoặc 127.0.0.1
$username = "root";       // Tên người dùng CSDL của bạn
$password = "";           // Mật khẩu CSDL của bạn (của Laragon thường là rỗng)
$dbname = "laptop_store"; // << TÊN CƠ SỞ DỮ LIỆU CỦA BẠN

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// KIỂM TRA KẾT NỐI -> Đây là bước quan trọng nhất để tìm lỗi
if ($conn->connect_error) {
    // Nếu có lỗi, dừng chương trình và hiển thị chính xác lỗi là gì
    die("Kết nối CSDL thất bại: " . $conn->connect_error);
}

// (Tùy chọn) Thiết lập charset để hỗ trợ tiếng Việt
$conn->set_charset("utf8mb4");

// Hàm select bạn đã dùng (giữ lại nếu cần)
function select($table, $condition = "") {
    global $conn;
    $sql = "SELECT * FROM $table $condition";
    $result = $conn->query($sql);
    if ($result) {
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    return [];
}
?>
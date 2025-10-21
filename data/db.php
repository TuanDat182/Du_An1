<?php
// Bắt đầu session ở đầu file để có thể sử dụng ở mọi nơi
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Thay đổi các thông tin sau cho phù hợp với cấu hình của bạn
$host = 'localhost';
$dbname = 'laptop_store';
$username = 'root';
$password = '';
$charset = 'utf8mb4';

// Tạo chuỗi DSN (Data Source Name)
$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Tạo đối tượng PDO để kết nối
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (\PDOException $e) {
    // Nếu kết nối thất bại, hiển thị lỗi và dừng chương trình
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

/**
 * Hàm đơn giản để lấy tất cả bản ghi từ một bảng.
 * @param string $tableName Tên của bảng cần lấy dữ liệu.
 * @return array Mảng chứa tất cả các sản phẩm.
 */
function select($tableName)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM " . $tableName);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (\PDOException $e) {
        // Xử lý lỗi nếu có
        error_log("Select failed: " . $e->getMessage());
        return [];
    }
}
?>

<?php
// Cấu hình kết nối CSDL
$host = 'localhost';
$dbname = 'cua_hang_truc_tuyen';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Kết nối CSDL thất bại: " . $e->getMessage());
}

/**
 * Thêm bản ghi mới vào bảng
 * @param string $table tên bảng
 * @param array $data ['cot1' => 'giatri1', 'cot2' => 'giatri2']
 */
function insert($table, $data) {
    global $pdo;
    $columns = implode(", ", array_keys($data));
    $placeholders = ":" . implode(", :", array_keys($data));

    $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute($data);
}

/**
 * Lấy dữ liệu
 * @param string $table tên bảng
 * @param string $where điều kiện (tuỳ chọn)
 * @param array $params mảng tham số điều kiện
 */
function select($table, $where = '', $params = []) {
    global $pdo;
    $sql = "SELECT * FROM $table";
    if ($where) {
        $sql .= " WHERE $where";
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Cập nhật bản ghi
 * @param string $table tên bảng
 * @param array $data ['cot' => 'giatri']
 * @param string $where điều kiện
 * @param array $params mảng điều kiện
 */
function update($table, $data, $where, $params = []) {
    global $pdo;
    $setPart = implode(", ", array_map(fn($k) => "$k = :$k", array_keys($data)));

    $sql = "UPDATE $table SET $setPart WHERE $where";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute(array_merge($data, $params));
}

/**
 * Xoá bản ghi
 * @param string $table tên bảng
 * @param string $where điều kiện
 * @param array $params mảng điều kiện
 */
function delete($table, $where, $params = []) {
    global $pdo;
    $sql = "DELETE FROM $table WHERE $where";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute($params);
}
?>

    <?php
session_start();
require_once 'db_crud.php';

$email = trim($_POST['email'] ?? '');
$mat_khau = trim($_POST['mat_khau'] ?? '');
$ho_ten = trim($_POST['ho_ten'] ?? '');
$dia_chi = trim($_POST['dia_chi'] ?? '');
$so_dien_thoai = trim($_POST['so_dien_thoai'] ?? '');

if ($email && $mat_khau && $ho_ten) {
    // Kiểm tra email đã tồn tại chưa
    $existing = select('Tai_Khoan', 'email = :email', ['email' => $email]);
    if ($existing) {
        $_SESSION['error'] = "Email này đã được sử dụng!";
        header('Location: register.php');
        exit;
    }

    // Mã hóa mật khẩu
    $mat_khau_mahoa = password_hash($mat_khau, PASSWORD_BCRYPT);

    // Thêm tài khoản
    $tai_khoan_id = insert('Tai_Khoan', [
        'email' => $email,
        'mat_khau' => $mat_khau_mahoa,
        'ngay_tao' => date('Y-m-d H:i:s')
    ]);

    // Thêm hồ sơ khách hàng
    insert('Ho_So_Khach_Hang', [
        'tai_khoan_id' => $tai_khoan_id,
        'ho_ten' => $ho_ten,
        'dia_chi' => $dia_chi,
        'so_dien_thoai' => $so_dien_thoai,
        'ngay_sinh' => null
    ]);

    $_SESSION['success'] = "Đăng ký thành công! Hãy đăng nhập để tiếp tục.";
    header('Location: login.php');
    exit;
} else {
    $_SESSION['error'] = "Vui lòng nhập đầy đủ thông tin bắt buộc!";
    header('Location: register.php');
    exit;
}
?>

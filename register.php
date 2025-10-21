<?php
// Bắt đầu session để có thể sử dụng biến $_SESSION
session_start();

// Nếu người dùng đã đăng nhập, chuyển hướng họ về trang chủ
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Nạp file header
include 'includes/header.php';
?>

<!-- SECTION -->
<div class="section">
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="billing-details">
                    <div class="section-title">
                        <h3 class="title">Đăng Ký Tài Khoản</h3>
                    </div>
                    <!-- Form đăng ký -->
                    <form action="handle_register.php" method="POST">
                        <div class="form-group">
                            <input class="input" type="text" name="first_name" placeholder="Tên" required>
                        </div>
                        <div class="form-group">
                            <input class="input" type="text" name="last_name" placeholder="Họ" required>
                        </div>
                        <div class="form-group">
                            <input class="input" type="email" name="email" placeholder="Email" required>
                        </div>
                        <div class="form-group">
                            <input class="input" type="password" name="password" placeholder="Mật khẩu" required>
                        </div>
                        <div class="form-group">
                            <input class="input" type="password" name="confirm_password" placeholder="Xác nhận mật khẩu" required>
                        </div>
                        <div class="form-group">
                            <input class="input" type="tel" name="phone" placeholder="Số điện thoại">
                        </div>
                        <div class="form-group">
                            <input class="input" type="text" name="address" placeholder="Địa chỉ">
                        </div>
                        
                        <!-- Hiển thị thông báo lỗi nếu có -->
                        <?php if (isset($_SESSION['register_error'])): ?>
                            <div class="alert alert-danger">
                                <?php 
                                    echo $_SESSION['register_error']; 
                                    unset($_SESSION['register_error']); // Xóa thông báo sau khi hiển thị
                                ?>
                            </div>
                        <?php endif; ?>

                        <button type="submit" class="primary-btn order-submit">Đăng Ký</button>
                    </form>
                    <div class="text-center" style="margin-top: 20px;">
                        <p>Đã có tài khoản? <a href="login.php">Đăng nhập ngay</a></p>
                    </div>
                </div>
            </div>
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div>
<!-- /SECTION -->

<?php
// Nạp file footer
include 'includes/footer.php';
?>


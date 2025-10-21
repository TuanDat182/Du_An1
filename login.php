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
                        <h3 class="title">Đăng Nhập</h3>
                    </div>
                    <!-- Form đăng nhập -->
                    <form action="handle_login.php" method="POST">
                        <div class="form-group">
                            <input class="input" type="email" name="email" placeholder="Email" required>
                        </div>
                        <div class="form-group">
                            <input class="input" type="password" name="password" placeholder="Mật khẩu" required>
                        </div>
                        
                        <!-- Hiển thị thông báo lỗi hoặc thành công -->
                        <?php if (isset($_SESSION['login_error'])): ?>
                            <div class="alert alert-danger">
                                <?php 
                                    echo $_SESSION['login_error']; 
                                    unset($_SESSION['login_error']); // Xóa thông báo sau khi hiển thị
                                ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($_SESSION['register_success'])): ?>
                            <div class="alert alert-success">
                                <?php 
                                    echo $_SESSION['register_success']; 
                                    unset($_SESSION['register_success']); // Xóa thông báo sau khi hiển thị
                                ?>
                            </div>
                        <?php endif; ?>

                        <button type="submit" class="primary-btn order-submit">Đăng Nhập</button>
                    </form>
                     <div class="text-center" style="margin-top: 20px;">
                        <p>Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a></p>
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


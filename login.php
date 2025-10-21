<?php
// Nạp file header
require_once 'includes/header.php';
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

                    <?php
                    // Đoạn code hiển thị thông báo lỗi
                    if (isset($_GET['error'])) {
                        echo '<div class="alert alert-danger">';
                        if ($_GET['error'] == 'invalid') {
                            echo 'Email hoặc mật khẩu không chính xác.';
                        } elseif ($_GET['error'] == 'empty') {
                            echo 'Vui lòng nhập đầy đủ thông tin.';
                        }
                        echo '</div>';
                    }
                    ?>

                    <form action="handle_login.php" method="POST">
                        <div class="form-group">
                            <input class="input" type="email" name="email" placeholder="Email" required>
                        </div>
                        <div class="form-group">
                            <input class="input" type="password" name="password" placeholder="Mật khẩu" required>
                        </div>
                        <button type="submit" class="primary-btn order-submit">Đăng Nhập</button>
                    </form>
                    <div style="margin-top: 15px;">
                        <a href="register.php">Chưa có tài khoản? Đăng ký ngay</a>
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
require_once 'includes/footer.php';
?>


<?php
// index.php đã nạp file db.php, vì vậy biến $conn đã tồn tại ở đây.

// Lấy tất cả sản phẩm từ bảng 'products'
// SỬA LỖI: Sử dụng biến $conn từ file db.php, không phải $pdo.
$sql = "SELECT product_id, name, price, image_url FROM products ORDER BY created_at DESC";
$result = $conn->query($sql);

$products = [];
if ($result && $result->num_rows > 0) {
    // Lấy tất cả dữ liệu vào một mảng
    $products = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!-- SECTION -->
<div class="section">
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">
            <!-- section title -->
            <div class="col-md-12">
                <div class="section-title">
                    <h3 class="title">Sản phẩm mới</h3>
                    <div class="section-nav">
                        <ul class="section-tab-nav tab-nav">
                            <li class="active"><a data-toggle="tab" href="#tab1">Laptops</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /section title -->

            <!-- Products tab & slick -->
            <div class="col-md-12">
                <div class="row">
                    <div class="products-tabs">
                        <!-- tab -->
                        <div id="tab1" class="tab-pane active">
                            <div class="products-slick" data-nav="#slick-nav-1">
                                <?php if (!empty($products)): ?>
                                    <?php foreach ($products as $product): ?>
                                        <!-- product -->
                                        <div class="product">
                                            <div class="product-img">
                                                <img src="<?php echo htmlspecialchars($product['image_url'] ?? './assets/img/product01.png'); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                            </div>
                                            <div class="product-body">
                                                <p class="product-category">Laptop</p>
                                                <h3 class="product-name"><a href="#"><?php echo htmlspecialchars($product['name']); ?></a></h3>
                                                <h4 class="product-price"><?php echo number_format($product['price']); ?> VND</h4>
                                            </div>
                                            <div class="add-to-cart">
                                                <?php if (isset($_SESSION['user_id'])): ?>
                                                    <!-- Nếu đã đăng nhập, hiển thị nút thêm vào giỏ hàng -->
                                                    <form method="post" action="add_to_cart.php">
                                                        <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                                        <button type="submit" class="add-to-cart-btn"><i class="fa fa-shopping-cart"></i> Thêm vào giỏ</button>
                                                    </form>
                                                <?php else: ?>
                                                    <!-- Nếu chưa đăng nhập, hiển thị nút yêu cầu đăng nhập -->
                                                    <a href="login.php" class="add-to-cart-btn" style="text-align: center; display: block;">
                                                        <i class="fa fa-lock"></i> Đăng nhập để mua
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <!-- /product -->
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="col-md-12">
                                        <p style="text-align: center;">Hiện chưa có sản phẩm nào.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div id="slick-nav-1" class="products-slick-nav"></div>
                        </div>
                        <!-- /tab -->
                    </div>
                </div>
            </div>
            <!-- Products tab & slick -->
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div>
<!-- /SECTION -->


<!-- SECTION -->
		<div class="section">
			<!-- container -->
			<div class="container">
				<!-- row -->
				<div class="row">

					<!-- section title -->
					<div class="col-md-12">
						<div class="section-title">
							<h3 class="title">New Products</h3>
							<div class="section-nav">
								<ul class="section-tab-nav tab-nav">
									<li class="active"><a data-toggle="tab" href="#tab1">Laptops</a></li>
									<li><a data-toggle="tab" href="#tab1">Smartphones</a></li>
									<li><a data-toggle="tab" href="#tab1">Cameras</a></li>
									<li><a data-toggle="tab" href="#tab1">Accessories</a></li>
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
										<!-- product -->
                                        <?php foreach($products as $id => $product): ?>
										<div class="product">
											<div class="product-img">
												<img src="<?php echo $product['product-img'];?>" alt="<?php echo $product['product-name']; ?>">
											</div>
											<div class="product-body">
												<p class="product-category">Laptop</p>
												<h3 class="product-name"><a href="#"><?php echo $product['product-name'];?></a></h3>
												<h4 class="product-price"><?php echo $product['product-price'];?>$ <del class="product-old-price"><?php echo $product['product-old-price'];?>$</del></h4>
											</div>
											<div class="add-to-cart">
												<form method="post">
													<input type="hidden" name="id" value="<?php echo $id; ?>">
													<input type="hidden" name="img" value="<?php echo $product['product-img']; ?>">
													<input type="hidden" name="name" value="<?php echo $product['product-name']; ?>">
													<input type="hidden" name="price" value="<?php echo $product['product-price']; ?>">
													<button type="submit" class="add-to-cart-btn"><i class="fa fa-shopping-cart"></i> add to cart</button>
												</form>
												
											</div>
										</div>
                                        <?php endforeach; ?>
										<!-- /product -->

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
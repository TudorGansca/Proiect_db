<?php include('layouts/header.php'); ?>

    <!--Home-->
    <section id="home">
        <div class="container">
            <h5>NEW ARRIVALS</h5>
            <h1><span>Best Prices</span> This Season</h1>
            <p>Eshop offers the best products for the most affordable prices</p>
            <a href="shop.php"><button>Shop now</button></a>
        </div>
    </section>

    <!--Featured products-->
    <section id="featured" class="my-5 pb5">
        <div class="container text-center mt-5 py-5">
            <h3>Our Featured</h3>
            <hr>
            <p>Here you can check out our featured products</p>
        </div>
        <div class="row mx-auto container-fluid">
             <?php include('server/get_featured_products.php'); ?>
             <?php while($row = $featured_products->fetch_assoc()){ ?>

            <div class="product text-center col-lg-3 col-md-4 col-sm-12">
                <img class="img-fluid mb-3" src="assets\imgs\<?php echo $row['product_image']; ?>"/>
                <div class="star">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                    <h5 class="p-name"><?php echo $row['product_name']; ?></h5>
                    <h4 class="p-price">Size <?php echo $row['product_size']; ?></h4>
                    <h4 class="p-price">RON<?php echo $row['product_price']; ?></h4>
                    <a href="single_product.php?product_id=<?php echo $row['product_id']?>"><button class="buy-btn">Buy now</button></a>
            </div>
            
            <?php } ?>
        </div>
    </section>
    
<?php include('layouts/footer.php'); ?>
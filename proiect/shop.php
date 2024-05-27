<?php 

include('server/connection.php');

if(isset($_POST['search'])){//use search section

    if(isset($_GET['page_no']) && $_GET['page_no'] != ""){
        $page_no = $_GET['page_no'];
    }else{//if user just entered, default page is 1
        $page_no = 1;
    }

    $category = $_POST['category'];
    $price = $_POST['price'];

    $stmt1 = $conn->prepare("SELECT COUNT(*) AS total_records FROM products WHERE product_category=? AND product_price<=?");
    $stmt1->bind_param('si',$category,$price);
    $stmt1->execute();
    $stmt1->bind_result($total_records);
    $stmt1->store_result();
    $stmt1->fetch();

    $total_records_per_page = 8;
    $offset = ($page_no-1) * $total_records_per_page;
    $previous_page = $page_no - 1;
    $next_page = $page_no + 1;
    $adjacents = "2";
    $total_no_of_pages = ceil($total_records / $total_records_per_page);

    $stmt2 = $conn->prepare("SELECT * FROM products WHERE product_category=? AND product_price<=? LIMIT $offset,$total_records_per_page");
    $stmt2->bind_param("si",$category,$price);
    $stmt2->execute();
    $products = $stmt2->get_result();

}else{

    if(isset($_GET['page_no']) && $_GET['page_no'] != ""){
        $page_no = $_GET['page_no'];
    }else{//if user just entered, default page is 1
        $page_no = 1;
    }

    $stmt1 = $conn->prepare("SELECT COUNT(*) AS total_records FROM products"); 
    $stmt1->execute();
    $stmt1->bind_result($total_records);
    $stmt1->store_result();
    $stmt1->fetch();

    //products per page
    $total_records_per_page = 8;
    $offset = ($page_no-1) * $total_records_per_page;
    $previous_page = $page_no - 1;
    $next_page = $page_no + 1;
    $adjacents = "2";
    $total_no_of_pages = ceil($total_records / $total_records_per_page);

    //get all products
    $stmt2 = $conn->prepare("SELECT * FROM products LIMIT $offset,$total_records_per_page");
    $stmt2->execute();
    $products = $stmt2->get_result();
}

?>
    
<?php include('layouts/header.php'); ?>
<style>
    .btn-primary, .form-range {
        background-color: white;
        border-color: rgb(255, 35, 133);
        color: white;
    }

    .form-range::-webkit-slider-thumb {
         background-color: rgb(255, 35, 133);
    }



    .product img {
        width: 100%;
        height: auto;
        box-sizing: border-box;
        object-fit: cover;
    }

    .pagination a {
        color: rgb(255,35,133);
    }

    .pagination li:hover a {
        color: white;
        background-color: rgb(255,35,133);
    }

    #search {
        width: 25%; /* Adjust this width as needed */
    }

    #shop {
        width: 70%; /* Adjust this width as needed */
    }

    .container-row {
        display: flex;
        justify-content: space-between;
    }
</style>

<div class="container-row">
    <!-- Search -->
    <section id="search" class="my-5 py-5 ms-2">
        <div class="container mt-5 py5">
            <p>Search products</p>
            <hr>
        </div>
        <form action="shop.php" method="POST">
            <div class="row mx-auto container">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <p>Category</p>
                    <div class="form-check">
                        <input class="form-check-input" value="women" type="radio" name="category" id="category_one" <?php if(isset($category) && $category=='women'){echo 'checked';} ?>>
                        <label class="form-check-label" for="flexRadioDefault1">Women</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" value="men" type="radio" name="category" id="category_two" <?php if(isset($category) && $category=='men'){echo 'checked';} ?>>
                        <label class="form-check-label" for="flexRadioDefault2">Men</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" value="girls" type="radio" name="category" id="category_three" <?php if(isset($category) && $category=='girls'){echo 'checked';} ?>>
                        <label class="form-check-label" for="flexRadioDefault3">Girls</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" value="boys" type="radio" name="category" id="category_four" <?php if(isset($category) && $category=='boys'){echo 'checked';} ?>>
                        <label class="form-check-label" for="flexRadioDefault4">Boys</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" value="newest" type="radio" name="category" id="category_five" <?php if(isset($category) && $category=='newest'){echo 'checked';} ?>>
                        <label class="form-check-label" for="flexRadioDefault5">Newest</label>
                    </div>
                </div>
            </div>

            <div class="row mx-auto container mt-5">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <p>Price</p>
                    <input type="range" class="form-range w-50" name="price" value="<?php if(isset($price)){echo $price;}else{echo 1000;} ?>" min="1" max="1000" id="customrange2"/>
                    <div class="w-50">
                        <span style="float: left;">1</span>
                        <span style="float: right;">1000</span>
                    </div>
                </div>
            </div>

            <div class="form-group my-3 mx-3">
                <input type="submit" name="search" value="Search" class="btn btn-primary" style="background-color: rgb(255, 35, 133); border-color: rgb(255, 35, 133); color: white;"/>
            </div>
        </form>
    </section>
    
    <!-- Products -->
    <section id="shop" class="my-5 py-5">
        <div class="container mt-5 py-5">
            <h3>Our Products</h3>
            <p>Coolest clothing</p>
        </div>
        <div class="row mx-auto container">
            <?php while($row=$products->fetch_assoc()){ ?>
                <div onclick="window.location.href='single_product.html';" class="product text-center col-lg-3 col-md-4 col-sm-12">
                    <img class="img-fluid mb-3" src="assets\imgs\<?php echo $row['product_image'];?>"/>
                    <div class="star">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <h5 class="p-name"><?php echo $row['product_name'];?></h5>
                    <h4 class="p-price">Size <?php echo $row['product_size'];?></h4>
                    <h4 class="p-price"><?php echo $row['product_price'];?> RON</h4>
                    <a class="btn buy-btn" href="single_product.php?product_id=<?php echo $row['product_id'];?>"><button>Buy now</button></a>
                </div>
            <?php } ?>
            
            <nav aria-label="Page navigation example">
                <ul class="pagination mt-5">
                    <li class="page-item <?php if($page_no<=1){echo 'disabled';} ?>">
                        <a class="page-link" href="<?php if($page_no <=1){echo '#';}else{ echo "?page_no=".($page_no-1);} ?>">Previous</a>
                    </li>

                    <li class="page-item"><a class="page-link" href="?page_no=1">1</a></li>
                    <li class="page-item"><a class="page-link" href="?page_no=2">2</a></li>
                    <?php if($page_no >=3){ ?>
                        <li class="page-item"><a class="page-link" href="#">...</a></li>
                        <li class="page-item"><a class="page-link" href="<?php echo "?page_no=".$page_no;?>"><?php echo $page_no; ?></a></li>
                    <?php } ?>

                    <li class="page-item <?php if($page_no >= $total_no_of_pages){echo 'disabled';}?>">
                        <a class="page-link" href="<?php if($page_no >= $total_no_of_pages){echo '#';}else{echo "?page_no=".($page_no+1);} ?>">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </section>
</div>

<?php include('layouts/footer.php'); ?>
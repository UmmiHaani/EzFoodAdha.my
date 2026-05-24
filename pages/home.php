<?php
$site_name = htmlspecialchars($_SESSION['setting_name'] ?? 'Our Restaurant');
$site_logo = $_SESSION['setting_logo_img'] ?? '';
?>
<!-- Homepage hero -->
<header class="masthead masthead-home">
    <div class="container hero-banner">
        <div class="hero-content text-center">
            <h1 class="hero-title text-white">Welcome to <?php echo $site_name; ?></h1>
            <p class="hero-subtitle">Fresh meals delivered to your classroom</p>
            <hr class="divider my-3" />
            <a class="btn btn-primary btn-hero js-scroll-trigger" href="#menu">Order Now</a>
        </div>
    </div>
</header>

<section class="page-section home-menu-section" id="menu">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="section-heading">Our Menu</h2>
            <hr class="divider my-3" />
        </div>
        <div class="menu-filters text-center mb-4">
            <button type="button" class="btn btn-sm btn-outline-primary menu-filter-btn active" data-category="all">All</button>
            <?php
            $categories = $conn->query("SELECT * FROM category_list ORDER BY name ASC");
            while($cat = $categories->fetch_assoc()):
            ?>
            <button type="button" class="btn btn-sm btn-outline-primary menu-filter-btn" data-category="<?php echo $cat['id'] ?>"><?php echo htmlspecialchars($cat['name']) ?></button>
            <?php endwhile; ?>
        </div>
        <div id="menu-field" class="row menu-grid">
            <?php
            $qry = $conn->query("SELECT p.*, c.name AS category_name FROM product_list p LEFT JOIN category_list c ON c.id = p.category_id WHERE p.status = 1 ORDER BY p.name ASC");
            while($row = $qry->fetch_assoc()):
            ?>
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4 menu-card-col" data-category="<?php echo $row['category_id'] ?>">
                <div class="card menu-item h-100">
                    <div class="menu-item-img-wrap">
                        <img src="assets/img/<?php echo htmlspecialchars($row['img_path']) ?>" class="card-img-top menu-item-img" alt="<?php echo htmlspecialchars($row['name']) ?>">
                    </div>
                    <div class="card-body d-flex flex-column">
                        <span class="menu-item-category"><?php echo htmlspecialchars($row['category_name'] ?? 'Menu') ?></span>
                        <h5 class="card-title menu-item-title"><?php echo htmlspecialchars($row['name']) ?></h5>
                        <p class="card-text truncate menu-item-desc"><?php echo htmlspecialchars($row['description']) ?></p>
                        <p class="menu-item-price mb-2"><strong>RM <?php echo number_format($row['price'], 2) ?></strong></p>
                        <div class="text-center mt-auto">
                            <button class="btn btn-sm btn-outline-primary view_prod btn-block" type="button" data-id="<?php echo $row['id'] ?>"><i class="fa fa-eye"></i> View</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>
<script>
    $('.menu-filter-btn').on('click', function(){
        $('.menu-filter-btn').removeClass('active');
        $(this).addClass('active');
        var category = $(this).data('category');
        if(category === 'all'){
            $('.menu-card-col').show();
        } else {
            $('.menu-card-col').hide();
            $('.menu-card-col[data-category="' + category + '"]').show();
        }
    });

    $('.view_prod').click(function(){
        uni_modal_right('Product','pages/view_prod.php?id='+$(this).attr('data-id'))
    })
</script>

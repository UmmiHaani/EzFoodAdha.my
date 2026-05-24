<?php
$site_name = htmlspecialchars($_SESSION['setting_name'] ?? 'Our canteen');
$site_email = htmlspecialchars($_SESSION['setting_email'] ?? '');
$site_contact = htmlspecialchars($_SESSION['setting_contact'] ?? '');
$admin_about = $_SESSION['setting_about_content'] ?? '';
$admin_about_plain = trim(strip_tags(html_entity_decode($admin_about)));
$show_admin_about = $admin_about_plain !== ''
    && stripos($admin_about_plain, 'lorem ipsum') === false
    && strlen($admin_about_plain) > 40;
?>
<header class="masthead masthead-page">
    <div class="container hero-banner">
        <div class="hero-content text-center">
            <h1 class="hero-title text-white">About Us</h1>
            <p class="hero-subtitle">Who we are and how ordering works</p>
            <hr class="divider my-3" />
        </div>
    </div>
</header>

<section class="page-section about-page-section">
    <div class="container">
        <div class="row about-page-layout">
            <div class="col-lg-8 mb-4 mb-lg-0">
                <div class="about-block">
                    <h2 class="about-heading">Welcome to <?php echo $site_name ?></h2>
                    <p class="about-lead">We take food orders online so you can skip the queue and know what is available before you come down.</p>
                    <p>Pick what you want from the menu, adjust quantities in your cart, then check out with your name and delivery details. If you already have an account, log in and your cart will stay with you.</p>
                </div>

                <?php if($show_admin_about): ?>
                <div class="about-block about-block--admin">
                    <h3 class="about-subheading">A note from us</h3>
                    <div class="about-admin-content">
                        <?php echo html_entity_decode($admin_about); ?>
                    </div>
                </div>
                <?php endif; ?>

                <div class="about-block">
                    <h3 class="about-subheading">How to order</h3>
                    <ol class="about-steps-list">
                        <li><strong>Browse the menu</strong> on the home page and open any item for details.</li>
                        <li><strong>Add to cart</strong> and change quantities before you pay.</li>
                        <li><strong>Check out</strong> with your contact info. We will prepare your order from there.</li>
                    </ol>
                    <p class="about-cta mb-0">
                        <a href="index.php?page=home#menu" class="btn btn-primary btn-hero">View menu</a>
                    </p>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card about-contact-card shadow-sm">
                    <div class="card-body">
                        <h3 class="about-subheading mb-3">Get in touch</h3>
                        <?php if($site_email): ?>
                        <p class="about-contact-row mb-2">
                            <i class="fa fa-envelope text-muted"></i>
                            <a href="mailto:<?php echo $site_email ?>"><?php echo $site_email ?></a>
                        </p>
                        <?php endif; ?>
                        <?php if($site_contact): ?>
                        <p class="about-contact-row mb-0">
                            <i class="fa fa-phone text-muted"></i>
                            <a href="tel:<?php echo preg_replace('/\s+/', '', $site_contact) ?>"><?php echo $site_contact ?></a>
                        </p>
                        <?php endif; ?>
                        <?php if(!$site_email && !$site_contact): ?>
                        <p class="text-muted small mb-0">Contact details can be added in Admin &rarr; Site Settings.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card about-tip-card shadow-sm mt-3">
                    <div class="card-body">
                        <p class="small text-muted mb-0"><strong>Tip:</strong> Create an account if you order often. Your cart is easier to manage and checkout is faster next time.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

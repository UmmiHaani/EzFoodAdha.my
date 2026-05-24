<!DOCTYPE html>
<html lang="en">
    <?php
    session_start();
    require_once __DIR__ . '/config/db_connect.php';

	$query = $conn->query("SELECT * FROM system_settings limit 1")->fetch_array();
	foreach ($query as $key => $value) {
		if(!is_numeric($key))
			$_SESSION['setting_'.$key] = $value;
	}
    include('header.php');
    ?>

    <?php
    $allowed_pages = ['home', 'about', 'cart_list', 'checkout', 'view_prod'];
    $page = isset($_GET['page']) ? $_GET['page'] : 'home';
    if (!in_array($page, $allowed_pages, true)) {
        $page = 'home';
    }
    $is_home = ($page === 'home');
    $page_body_classes = array(
        'home' => 'home-page',
        'cart_list' => 'cart-page',
        'about' => 'about-page',
        'checkout' => 'checkout-page',
    );
    $body_class = isset($page_body_classes[$page]) ? $page_body_classes[$page] : '';
    $cover_img = !empty($_SESSION['setting_cover_img']) ? $_SESSION['setting_cover_img'] : 'theater-bg.jpg';
    $site_logo = $_SESSION['setting_logo_img'] ?? '';
    $site_logo_dark = $_SESSION['setting_logo_img_dark'] ?? '';
    $site_name = htmlspecialchars($_SESSION['setting_name'] ?? 'Home');
    $has_logo = !empty($site_logo) || !empty($site_logo_dark);
    $logo_single_fallback = !empty($site_logo) && empty($site_logo_dark);
    $admin_preview = isset($_GET['preview']) && $_GET['preview'] === '1'
        && isset($_SESSION['login_type']) && (int)$_SESSION['login_type'] === 1;
    ?>
    <style>
    	header.masthead {
		  background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.35)), url(assets/img/<?php echo htmlspecialchars($cover_img) ?>);
		  background-position: center;
		  background-repeat: no-repeat;
		  background-size: cover;
		}
    </style>
    <body id="page-top" class="<?php echo htmlspecialchars($body_class) ?>">
        <!-- Navigation-->
        <div class="toast" id="alert_toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-body text-white">
        </div>
      </div>
        <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" id="mainNav">
            <div class="container">
                <?php if($admin_preview): ?>
                <a class="navbar-brand js-scroll-trigger" href="index.php?page=home&preview=1">Home</a>
                <?php else: ?>
                <a class="navbar-brand js-scroll-trigger navbar-brand-logos" href="./">
                    <?php if($has_logo): ?>
                        <?php if(!empty($site_logo)): ?>
                        <img src="assets/img/<?php echo htmlspecialchars($site_logo) ?>" alt="<?php echo $site_name ?>" class="navbar-brand-logo logo-for-dark-bg<?php echo $logo_single_fallback ? ' logo-single-fallback' : '' ?>">
                        <?php endif; ?>
                        <?php if(!empty($site_logo_dark)): ?>
                        <img src="assets/img/<?php echo htmlspecialchars($site_logo_dark) ?>" alt="<?php echo $site_name ?>" class="navbar-brand-logo logo-for-light-bg">
                        <?php endif; ?>
                    <?php else: ?>
                        <?php echo $site_name; ?>
                    <?php endif; ?>
                </a>
                <?php endif; ?>
                <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ml-auto my-2 my-lg-0">
                        <?php $preview_q = $admin_preview ? '&preview=1' : ''; ?>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="index.php?page=home<?php echo $preview_q ?>">Home</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="index.php?page=cart_list<?php echo $preview_q ?>"><span> <span class="badge badge-danger item_count">0</span> <i class="fa fa-shopping-cart"></i>  </span>Cart</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="index.php?page=about<?php echo $preview_q ?>">About</a></li>
                        <?php if($admin_preview): ?>
                        <li class="nav-item"><a class="nav-link" href="admin/index.php?page=home">Back to admin</a></li>
                        <?php elseif(isset($_SESSION['login_user_id'])): ?>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="admin/ajax.php?action=logout2"><?php echo "Welcome ". $_SESSION['login_first_name'].' '.$_SESSION['login_last_name'] ?> <i class="fa fa-power-off"></i></a></li>
                      <?php else: ?>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="javascript:void(0)" id="login_now">Login</a></li>
                      <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
       
        <?php include __DIR__ . '/pages/' . $page . '.php'; ?>
       

<div class="modal fade" id="confirm_modal" role='dialog'>
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title">Confirmation</h5>
      </div>
      <div class="modal-body">
        <div id="delete_content"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id='confirm' onclick="">Continue</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="uni_modal" role='dialog'>
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title"></h5>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id='submit' onclick="$('#uni_modal form').submit()">Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="uni_modal_right" role='dialog'>
    <div class="modal-dialog modal-full-height  modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span class="fa fa-arrow-righ t"></span>
        </button>
      </div>
      <div class="modal-body">
      </div>
      </div>
    </div>
  </div>
        
       <?php include('footer.php') ?>
    </body>

    <?php $conn->close() ?>

</html>

<?php
session_start();
if(isset($_SESSION['login_id'])){
    header('Location: admin/index.php?page=home');
    exit;
}
if(isset($_SESSION['login_user_id'])){
    $go = isset($_GET['redirect']) ? $_GET['redirect'] : 'index.php?page=home';
    header('Location: '.$go);
    exit;
}
require_once __DIR__.'/includes/gallery_helper.php';
require_once __DIR__.'/includes/logo_helper.php';

$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'index.php?page=home';
$is_embed = isset($_GET['embed']) && $_GET['embed'] == '1';
$site_logo = get_light_bg_logo();
$site_name = htmlspecialchars($_SESSION['setting_name'] ?? 'Our Restaurant');
$gallery_images = get_school_gallery_images();
$signup_url = 'signup.php?embed=1&redirect='.urlencode($redirect);
if(!$is_embed){
    $signup_url = 'index.php?page=signup&redirect='.urlencode($redirect);
}
?>
<?php if(!$is_embed): ?>
<header class="masthead masthead-page masthead-auth">
    <div class="container hero-banner">
        <div class="hero-content text-center">
            <h1 class="hero-title text-white">Login</h1>
            <p class="hero-subtitle">Sign in to order and track your cart</p>
            <hr class="divider my-3" />
        </div>
    </div>
</header>
<section class="page-section auth-page-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
<?php endif; ?>

<div class="auth-modal-layout <?php echo $is_embed ? 'auth-modal-layout--embed' : '' ?>">
    <?php if(!empty($gallery_images)): ?>
    <div class="auth-gallery-panel">
        <div class="auth-gallery-header">
            <h3 class="auth-gallery-title"><?php echo $site_name ?></h3>
            <p class="auth-gallery-subtitle">Discover our campus</p>
        </div>
        <div class="auth-gallery-stage">
            <?php foreach($gallery_images as $i => $img): ?>
            <div class="auth-gallery-slide<?php echo $i === 0 ? ' active' : '' ?>" data-index="<?php echo $i ?>">
                <img src="assets/img/<?php echo htmlspecialchars($img) ?>" alt="School photo <?php echo $i + 1 ?>">
            </div>
            <?php endforeach; ?>
        </div>
        <?php if(count($gallery_images) > 1): ?>
        <div class="auth-gallery-dots">
            <?php foreach($gallery_images as $i => $img): ?>
            <button type="button" class="auth-gallery-dot<?php echo $i === 0 ? ' active' : '' ?>" data-index="<?php echo $i ?>" aria-label="Photo <?php echo $i + 1 ?>"></button>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="auth-form-panel">
        <div class="auth-card shadow-sm">
            <div class="auth-card-header text-center">
                <?php if(!empty($site_logo)): ?>
                    <img src="assets/img/<?php echo htmlspecialchars($site_logo) ?>" alt="<?php echo $site_name ?>" class="auth-logo">
                <?php else: ?>
                    <h2 class="h5 mb-0"><?php echo $site_name ?></h2>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <p class="text-muted small text-center mb-3">Sign in to continue ordering</p>
                <form action="" id="login-frm">
                    <div class="form-group">
                        <label for="login_email" class="control-label">Email or username</label>
                        <input type="text" id="login_email" name="email" required class="form-control" placeholder="you@example.com or admin username" autocomplete="username">
                    </div>
                    <div class="form-group mb-3">
                        <label for="login_password" class="control-label">Password</label>
                        <input type="password" id="login_password" name="password" required class="form-control" placeholder="Enter your password" autocomplete="current-password">
                    </div>
                    <button type="submit" class="btn btn-primary btn-block btn-hero">Login</button>
                </form>
                <hr class="my-3">
                <p class="text-center mb-2">
                    <a href="<?php echo htmlspecialchars($signup_url) ?>" class="auth-link" id="new_account">Create new account</a>
                </p>
                <?php if(!$is_embed): ?>
                <p class="text-center mb-0">
                    <a href="index.php?page=home" class="auth-link-muted"><i class="fa fa-arrow-left"></i> Back to home</a>
                </p>
                <?php else: ?>
                <p class="text-center mb-0">
                    <button type="button" class="btn btn-link auth-link-muted p-0" data-dismiss="modal">Continue browsing</button>
                </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php if(!$is_embed): ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php include __DIR__.'/includes/auth_gallery_script.php'; ?>
<script>
(function(){
    $('#new_account').click(function(e){
        <?php if($is_embed): ?>
        e.preventDefault();
        open_auth_modal('Create an Account', $(this).attr('href'));
        <?php endif; ?>
    });

    $('#login-frm').submit(function(e){
        e.preventDefault();
        var $btn = $('#login-frm button[type="submit"]');
        $btn.prop('disabled', true).text('Logging in...');
        $('#login-frm .alert-danger').remove();
        $.ajax({
            url: 'admin/ajax.php?action=login2',
            method: 'POST',
            data: $(this).serialize(),
            error: function(){
                $btn.prop('disabled', false).text('Login');
                alert_toast('Unable to connect. Please try again.', 'danger');
            },
            success: function(resp){
                if(resp == 1){
                    location.href = <?php echo json_encode($redirect) ?>;
                } else if(resp == 4){
                    location.href = 'admin/index.php?page=home';
                } else {
                    $('#login-frm').prepend('<div class="alert alert-danger">Email or password is incorrect.</div>');
                    $btn.prop('disabled', false).text('Login');
                }
            }
        });
    });
})();
</script>

<?php

session_start();

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

$login_url = 'login.php?embed=1&redirect='.urlencode($redirect);

if(!$is_embed){

    $login_url = 'index.php?page=login&redirect='.urlencode($redirect);

}

?>

<?php if(!$is_embed): ?>

<header class="masthead masthead-page masthead-auth">

    <div class="container hero-banner">

        <div class="hero-content text-center">

            <h1 class="hero-title text-white">Create Account</h1>

            <p class="hero-subtitle">Register to start ordering</p>

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

    <?php include __DIR__.'/includes/auth_gallery_panel.php'; ?>



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

                <p class="text-muted small text-center mb-3">Create your account to order</p>

                <form action="" id="signup-frm">

                    <div class="row">

                        <div class="col-md-6 form-group">

                            <label for="first_name" class="control-label">First name</label>

                            <input type="text" id="first_name" name="first_name" required class="form-control" autocomplete="given-name">

                        </div>

                        <div class="col-md-6 form-group">

                            <label for="last_name" class="control-label">Last name</label>

                            <input type="text" id="last_name" name="last_name" required class="form-control" autocomplete="family-name">

                        </div>

                    </div>

                    <div class="form-group">

                        <label for="mobile" class="control-label">Contact number</label>

                        <input type="text" id="mobile" name="mobile" required class="form-control" autocomplete="tel">

                    </div>

                    <div class="form-group">

                        <label for="address" class="control-label">Delivery address</label>

                        <textarea id="address" cols="30" rows="2" name="address" required class="form-control" autocomplete="street-address"></textarea>

                    </div>

                    <div class="form-group">

                        <label for="signup_email" class="control-label">Email</label>

                        <input type="email" id="signup_email" name="email" required class="form-control" placeholder="you@example.com" autocomplete="email">

                    </div>

                    <div class="form-group mb-3">

                        <label for="signup_password" class="control-label">Password</label>

                        <input type="password" id="signup_password" name="password" required class="form-control" autocomplete="new-password">

                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-hero">Create account</button>

                </form>

                <hr class="my-3">

                <p class="text-center mb-2">

                    Already have an account? <a href="<?php echo htmlspecialchars($login_url) ?>" class="auth-link" id="go_login">Login here</a>

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

    $('#go_login').click(function(e){

        <?php if($is_embed): ?>

        e.preventDefault();

        open_auth_modal('Login', $(this).attr('href'));

        <?php endif; ?>

    });



    $('#signup-frm').submit(function(e){

        e.preventDefault();

        var $btn = $('#signup-frm button[type="submit"]');

        $btn.prop('disabled', true).text('Creating account...');

        $('#signup-frm .alert-danger').remove();

        $.ajax({

            url: 'admin/ajax.php?action=signup',

            method: 'POST',

            data: $(this).serialize(),

            error: function(){

                $btn.prop('disabled', false).text('Create account');

                alert_toast('Unable to connect. Please try again.', 'danger');

            },

            success: function(resp){

                if(resp == 1){

                    location.href = <?php echo json_encode($redirect) ?>;

                } else {

                    $('#signup-frm').prepend('<div class="alert alert-danger">That email is already registered. Please use the login link below.</div>');

                    $btn.prop('disabled', false).text('Create account');

                }

            }

        });

    });

})();

</script>


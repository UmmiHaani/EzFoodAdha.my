<?php
require_once __DIR__.'/includes/ip_helper.php';
if(isset($_SESSION['login_user_id'])){
    $data = "where c.user_id = '".(int)$_SESSION['login_user_id']."' ";
}else{
    $ip = $conn->real_escape_string(get_client_ip());
    $data = "where c.client_ip = '".$ip."' ";
}
$total = 0;
$get = $conn->query("SELECT *,c.id as cid FROM cart c inner join product_list p on p.id = c.product_id ".$data);
$cart_count = $get ? $get->num_rows : 0;
?>
<header class="masthead masthead-page">
    <div class="container hero-banner">
        <div class="hero-content text-center">
            <h1 class="hero-title text-white">Shopping Cart</h1>
            <p class="hero-subtitle">Review your items before checkout</p>
            <hr class="divider my-3" />
        </div>
    </div>
</header>

<section class="page-section cart-page-section">
    <div class="container">
        <div class="row cart-page-layout">
            <div class="col-lg-8 mb-4 mb-lg-0">
                <div class="card cart-panel shadow-sm">
                    <div class="card-header cart-panel-header d-flex justify-content-between align-items-center">
                        <h2 class="h5 mb-0">Your Items</h2>
                        <span class="badge badge-primary cart-item-badge"><?php echo $cart_count ?> item<?php echo $cart_count === 1 ? '' : 's' ?></span>
                    </div>
                    <div class="card-body p-0">
                        <?php if($cart_count === 0): ?>
                        <div class="cart-empty text-center py-5 px-3">
                            <i class="fa fa-shopping-cart fa-3x text-muted mb-3"></i>
                            <p class="mb-3 text-muted">Your cart is empty.</p>
                            <a href="index.php?page=home#menu" class="btn btn-primary btn-hero">Browse Menu</a>
                        </div>
                        <?php else: ?>
                        <?php while($row = $get->fetch_assoc()):
                            $line_total = $row['qty'] * $row['price'];
                            $total += $line_total;
                        ?>
                        <div class="cart-item">
                            <div class="row align-items-center no-gutters">
                                <div class="col-md-3 col-4 cart-item-media">
                                    <div class="cart-item-img-wrap">
                                        <img src="assets/img/<?php echo htmlspecialchars($row['img_path']) ?>" alt="<?php echo htmlspecialchars($row['name']) ?>">
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-danger rem_cart mt-2" data-id="<?php echo $row['cid'] ?>" title="Remove item">
                                        <i class="fa fa-trash"></i> Remove
                                    </button>
                                </div>
                                <div class="col-md-5 col-8 cart-item-details">
                                    <h3 class="cart-item-name"><?php echo htmlspecialchars($row['name']) ?></h3>
                                    <p class="cart-item-desc truncate"><?php echo htmlspecialchars($row['description']) ?></p>
                                    <p class="cart-item-unit mb-2">Unit price: <strong>RM <?php echo number_format($row['price'], 2) ?></strong></p>
                                    <label class="cart-qty-label">Quantity</label>
                                    <div class="input-group cart-qty-group">
                                        <div class="input-group-prepend">
                                            <button class="btn btn-outline-secondary qty-minus" type="button" data-id="<?php echo $row['cid'] ?>"><span class="fa fa-minus"></span></button>
                                        </div>
                                        <input type="number" readonly value="<?php echo $row['qty'] ?>" min="1" class="form-control text-center" name="qty">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary qty-plus" type="button" data-id="<?php echo $row['cid'] ?>"><span class="fa fa-plus"></span></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 cart-item-total text-md-right text-left mt-3 mt-md-0">
                                    <span class="cart-line-label">Line total</span>
                                    <p class="cart-line-amount mb-0">RM <?php echo number_format($line_total, 2) ?></p>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card cart-summary shadow-sm sticky-summary">
                    <div class="card-header cart-panel-header">
                        <h2 class="h5 mb-0">Order Summary</h2>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between cart-summary-row">
                            <span>Subtotal</span>
                            <strong>RM <?php echo number_format($total, 2) ?></strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between cart-summary-total mb-4">
                            <span>Total</span>
                            <strong class="cart-total-amount">RM <?php echo number_format($total, 2) ?></strong>
                        </div>
                        <button class="btn btn-primary btn-block btn-hero" type="button" id="checkout" <?php echo $cart_count === 0 ? 'disabled' : '' ?>>Proceed to Checkout</button>
                        <a href="index.php?page=home#menu" class="btn btn-outline-secondary btn-block mt-2">Continue Shopping</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $('.qty-minus').click(function(){
        var qty = $(this).closest('.input-group').find('input[name="qty"]').val();
        update_qty(parseInt(qty) - 1, $(this).attr('data-id'));
        if(qty > 1){
            $(this).closest('.input-group').find('input[name="qty"]').val(parseInt(qty) - 1);
        }
    });

    $('.qty-plus').click(function(){
        var qty = $(this).closest('.input-group').find('input[name="qty"]').val();
        $(this).closest('.input-group').find('input[name="qty"]').val(parseInt(qty) + 1);
        update_qty(parseInt(qty) + 1, $(this).attr('data-id'));
    });

    $('.rem_cart').click(function(){
        rem_cart($(this).attr('data-id'));
    });

    function update_qty(qty, id){
        if(qty < 1) return;
        start_load();
        $.ajax({
            url:'admin/ajax.php?action=update_cart_qty',
            method:'POST',
            data:{id:id, qty:qty},
            success:function(resp){
                if(resp == 1){
                    location.reload();
                }
                end_load();
            }
        });
    }

    function rem_cart(id){
        start_load();
        $.ajax({
            url:'admin/ajax.php?action=delete_cart',
            method:'POST',
            data:{id:id},
            success:function(resp){
                if(resp == 1){
                    location.reload();
                }
                end_load();
            }
        });
    }

    $('#checkout').click(function(){
        if('<?php echo isset($_SESSION['login_user_id']) ? '1' : '0' ?>' === '1'){
            location.replace('index.php?page=checkout');
        }else{
            open_auth_modal('Sign in to checkout', 'login.php?embed=1&redirect=' + encodeURIComponent('index.php?page=checkout'));
        }
    });
</script>

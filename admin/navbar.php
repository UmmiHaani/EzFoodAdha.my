<?php
$current_page = isset($_GET['page']) ? $_GET['page'] : 'home';
$is_admin = isset($_SESSION['login_type']) && $_SESSION['login_type'] == 1;
require_once 'db_connect.php';
$pending_orders = 0;
$pending_q = $conn->query("SELECT COUNT(*) as c FROM orders WHERE status = 0 AND archived = 0");
if($pending_q){
    $pending_orders = (int)$pending_q->fetch_assoc()['c'];
}
?>
<aside id="sidebar" class="admin-sidebar" aria-label="Admin navigation">
    <div class="sidebar-inner">
        <div class="sidebar-section">
            <span class="sidebar-label">Overview</span>
            <a href="index.php?page=home" class="nav-item nav-home<?php echo $current_page === 'home' ? ' active' : '' ?>">
                <span class="nav-icon"><i class="fa fa-home" aria-hidden="true"></i></span>
                <span class="nav-text">Dashboard</span>
            </a>
        </div>

        <div class="sidebar-section">
            <span class="sidebar-label">Orders &amp; menu</span>
            <a href="index.php?page=orders" class="nav-item nav-orders<?php echo $current_page === 'orders' ? ' active' : '' ?>">
                <span class="nav-icon"><i class="fa fa-clipboard-list" aria-hidden="true"></i></span>
                <span class="nav-text">Orders</span>
                <?php if($pending_orders > 0): ?>
                <span class="sidebar-badge" id="pending-orders-badge" data-count="<?php echo $pending_orders ?>"><?php echo $pending_orders ?></span>
                <?php endif; ?>
            </a>
            <a href="index.php?page=menu" class="nav-item nav-menu<?php echo $current_page === 'menu' ? ' active' : '' ?>">
                <span class="nav-icon"><i class="fa fa-utensils" aria-hidden="true"></i></span>
                <span class="nav-text">Menu items</span>
            </a>
            <a href="index.php?page=categories" class="nav-item nav-categories<?php echo $current_page === 'categories' ? ' active' : '' ?>">
                <span class="nav-icon"><i class="fa fa-tags" aria-hidden="true"></i></span>
                <span class="nav-text">Categories</span>
            </a>
        </div>

        <?php if($is_admin): ?>
        <div class="sidebar-section">
            <span class="sidebar-label">Administration</span>
            <a href="index.php?page=users" class="nav-item nav-users<?php echo $current_page === 'users' ? ' active' : '' ?>">
                <span class="nav-icon"><i class="fa fa-users" aria-hidden="true"></i></span>
                <span class="nav-text">Users</span>
            </a>
            <a href="index.php?page=site_settings" class="nav-item nav-site_settings<?php echo $current_page === 'site_settings' ? ' active' : '' ?>">
                <span class="nav-icon"><i class="fa fa-cog" aria-hidden="true"></i></span>
                <span class="nav-text">Site settings</span>
            </a>
        </div>
        <?php endif; ?>

        <div class="sidebar-footer">
            <a href="../index.php?page=home&preview=1" class="nav-item nav-storefront" target="_blank" rel="noopener">
                <span class="nav-icon"><i class="fa fa-external-link-alt" aria-hidden="true"></i></span>
                <span class="nav-text">View website</span>
            </a>
        </div>
    </div>
</aside>
<div id="sidebar-overlay" class="sidebar-overlay" aria-hidden="true"></div>

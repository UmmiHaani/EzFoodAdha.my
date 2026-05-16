<?php
$current_page = isset($_GET['page']) ? $_GET['page'] : 'home';
$page_titles = array(
	'home' => 'Dashboard',
	'orders' => 'Orders',
	'menu' => 'Menu',
	'categories' => 'Categories',
	'site_settings' => 'Site settings',
	'users' => 'Users',
);
$topbar_title = $page_titles[$current_page] ?? 'Admin';
?>
<nav id="admin-topbar" class="navbar navbar-dark bg-dark fixed-top admin-topbar">
  <div class="admin-topbar-inner">
    <button type="button" class="sidebar-toggle btn btn-link text-white d-lg-none" id="sidebar-toggle" aria-label="Open menu">
      <i class="fa fa-bars"></i>
    </button>
    <span class="admin-topbar-title d-none d-sm-inline"><?php echo htmlspecialchars($topbar_title) ?></span>
    <div class="admin-topbar-user">
      <a href="ajax.php?action=logout" class="admin-logout-link" title="Logout" aria-label="Logout">
        <i class="fa fa-power-off"></i>
      </a>
    </div>
  </div>
</nav>

<?php
include 'db_connect.php';

$login_name = htmlspecialchars($_SESSION['login_name'] ?? 'Admin');
$active_where = "archived = 0";
$pending_count = (int)$conn->query("SELECT COUNT(*) as c FROM orders WHERE status = 0 AND ".$active_where)->fetch_assoc()['c'];
$confirmed_count = (int)$conn->query("SELECT COUNT(*) as c FROM orders WHERE status = 1 AND ".$active_where)->fetch_assoc()['c'];
$total_orders = (int)$conn->query("SELECT COUNT(*) as c FROM orders WHERE ".$active_where)->fetch_assoc()['c'];

$revenue_row = $conn->query("SELECT COALESCE(SUM(ol.qty * p.price), 0) as total
    FROM orders o
    INNER JOIN order_list ol ON ol.order_id = o.id
    INNER JOIN product_list p ON p.id = ol.product_id
    WHERE o.status = 1 AND o.archived = 0")->fetch_assoc();
$revenue_total = $revenue_row ? (float)$revenue_row['total'] : 0;

$order_total_sql = "(SELECT COALESCE(SUM(ol.qty * p.price), 0)
        FROM order_list ol
        INNER JOIN product_list p ON p.id = ol.product_id
        WHERE ol.order_id = o.id) AS order_total";

$recent_orders = $conn->query("SELECT o.*, ".$order_total_sql."
    FROM orders o
    WHERE o.archived = 0
    ORDER BY o.id DESC
    LIMIT 10");

$archived_orders = $conn->query("SELECT o.*, ".$order_total_sql."
    FROM orders o
    WHERE o.archived = 1
    ORDER BY o.id DESC");
?>
<div class="admin-dashboard">
    <h4 class="mb-1">Dashboard</h4>
    <p class="text-muted mb-3">Welcome, <?php echo $login_name ?>.</p>

    <?php if($pending_count > 0): ?>
    <div class="alert alert-warning py-2 mb-3" role="alert">
        <strong><?php echo $pending_count ?> order<?php echo $pending_count === 1 ? '' : 's' ?></strong> need confirmation.
        <a href="index.php?page=orders" class="alert-link ml-1">Go to orders</a>
    </div>
    <?php endif; ?>

    <div class="row mb-3">
        <div class="col-6 col-md-3 mb-2">
            <div class="card">
                <div class="card-body py-3">
                    <div class="text-muted small">Pending</div>
                    <div class="h5 mb-0"><?php echo $pending_count ?></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-2">
            <div class="card">
                <div class="card-body py-3">
                    <div class="text-muted small">Confirmed</div>
                    <div class="h5 mb-0"><?php echo $confirmed_count ?></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-2">
            <div class="card">
                <div class="card-body py-3">
                    <div class="text-muted small">Active orders</div>
                    <div class="h5 mb-0"><?php echo $total_orders ?></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-2">
            <div class="card">
                <div class="card-body py-3">
                    <div class="text-muted small">Sales (confirmed)</div>
                    <div class="h5 mb-0">RM <?php echo number_format($revenue_total, 2) ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center py-2">
            <span>Recent orders</span>
            <a href="index.php?page=orders">View all</a>
        </div>
        <div class="card-body p-0">
            <?php if($recent_orders && $recent_orders->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0" id="active-orders-table">
                    <thead class="thead-light">
                        <tr>
                            <th style="width:36px;">
                                <input type="checkbox" id="select-all-orders" title="Select all">
                            </th>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Phone</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($order = $recent_orders->fetch_assoc()): ?>
                        <tr data-order-id="<?php echo (int)$order['id'] ?>">
                            <td class="text-center">
                                <input type="checkbox" class="order-archive-check" value="<?php echo (int)$order['id'] ?>">
                            </td>
                            <td><?php echo (int)$order['id'] ?></td>
                            <td><?php echo htmlspecialchars($order['name']) ?></td>
                            <td><?php echo htmlspecialchars($order['mobile']) ?></td>
                            <td>RM <?php echo number_format((float)$order['order_total'], 2) ?></td>
                            <td>
                                <?php if($order['status'] == 1): ?>
                                Confirmed
                                <?php else: ?>
                                <strong>New</strong>
                                <?php endif; ?>
                            </td>
                            <td class="text-nowrap">
                                <button type="button" class="btn btn-sm btn-primary view_order" data-id="<?php echo (int)$order['id'] ?>">View</button>
                                <button type="button" class="btn btn-sm btn-danger delete_order" data-id="<?php echo (int)$order['id'] ?>">Delete</button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer py-2">
                <button type="button" class="btn btn-sm btn-secondary" id="archive-selected-btn" disabled>Archive selected</button>
            </div>
            <?php else: ?>
            <p class="text-muted mb-0 p-3">No orders yet.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="card">
        <div class="card-header py-2">
            <span>Archive</span>
        </div>
        <div class="card-body p-0">
            <?php if($archived_orders && $archived_orders->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-sm mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Phone</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($order = $archived_orders->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo (int)$order['id'] ?></td>
                            <td><?php echo htmlspecialchars($order['name']) ?></td>
                            <td><?php echo htmlspecialchars($order['mobile']) ?></td>
                            <td>RM <?php echo number_format((float)$order['order_total'], 2) ?></td>
                            <td>
                                <?php if($order['status'] == 1): ?>
                                Confirmed
                                <?php else: ?>
                                New
                                <?php endif; ?>
                            </td>
                            <td class="text-nowrap">
                                <button type="button" class="btn btn-sm btn-outline-secondary view_order" data-id="<?php echo (int)$order['id'] ?>">View</button>
                                <button type="button" class="btn btn-sm btn-outline-primary unarchive_order" data-id="<?php echo (int)$order['id'] ?>">Restore</button>
                                <button type="button" class="btn btn-sm btn-outline-danger delete_order" data-id="<?php echo (int)$order['id'] ?>">Delete</button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <p class="text-muted mb-0 p-3">No archived orders.</p>
            <?php endif; ?>
        </div>
    </div>

    <p class="text-muted small mt-3 mb-0">
        <a href="index.php?page=menu">Menu</a> &middot;
        <a href="index.php?page=categories">Categories</a> &middot;
        <a href="../index.php?page=home&preview=1" target="_blank" rel="noopener">View website</a>
    </p>
</div>

<script>
function updateArchiveBtn(){
    var n = $('.order-archive-check:checked').length;
    $('#archive-selected-btn').prop('disabled', n === 0);
}
$('.view_order').click(function(){
    uni_modal('Order', 'view_order.php?id=' + $(this).data('id'));
});
$('#select-all-orders').on('change', function(){
    $('.order-archive-check').prop('checked', $(this).is(':checked'));
    updateArchiveBtn();
});
$(document).on('change', '.order-archive-check', function(){
    var total = $('.order-archive-check').length;
    var checked = $('.order-archive-check:checked').length;
    $('#select-all-orders').prop('checked', total > 0 && checked === total);
    updateArchiveBtn();
});
$('#archive-selected-btn').click(function(){
    var ids = [];
    $('.order-archive-check:checked').each(function(){
        ids.push($(this).val());
    });
    if(!ids.length) return;
    start_load();
    $.ajax({
        url: 'ajax.php?action=archive_orders',
        method: 'POST',
        data: { ids: ids },
        success: function(resp){
            end_load();
            if(resp == 1){
                alert_toast('Order(s) archived', 'success');
                setTimeout(function(){ location.reload(); }, 800);
            } else {
                alert_toast('Could not archive orders', 'danger');
            }
        }
    });
});
$(document).on('click', '.delete_order', function(){
    var id = $(this).data('id');
    _conf('Delete this order permanently?', 'delete_order', [id]);
});
function delete_order(id){
    start_load();
    $.ajax({
        url: 'ajax.php?action=delete_order',
        method: 'POST',
        data: { id: id },
        success: function(resp){
            end_load();
            if(resp == 1){
                alert_toast('Order deleted', 'success');
                setTimeout(function(){ location.reload(); }, 800);
            } else {
                alert_toast('Could not delete order', 'danger');
            }
        }
    });
}
$(document).on('click', '.unarchive_order', function(){
    var id = $(this).data('id');
    start_load();
    $.ajax({
        url: 'ajax.php?action=unarchive_order',
        method: 'POST',
        data: { id: id },
        success: function(resp){
            end_load();
            if(resp == 1){
                alert_toast('Order restored', 'success');
                setTimeout(function(){ location.reload(); }, 800);
            } else {
                alert_toast('Could not restore order', 'danger');
            }
        }
    });
});
</script>

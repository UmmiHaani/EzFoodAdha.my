<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Online Food Ordering System</title>
 	

<?php
	session_start();
  if(!isset($_SESSION['login_id']))
    header('location:../login.php');
 include('./header.php'); 
 // include('./auth.php'); 
 ?>

</head>
<style>
	body{
        background: #eef1f5;
  }
</style>

<body>
	<?php include 'topbar.php' ?>
	<?php include 'navbar.php' ?>
  <div class="toast" id="alert_toast" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-body text-white">
    </div>
  </div>
  <main id="view-panel" >
      <?php
      $allowed_pages = ['home', 'menu', 'categories', 'orders', 'users', 'site_settings', 'view_order', 'checkout'];
      $page = isset($_GET['page']) ? $_GET['page'] : 'home';
      if (!in_array($page, $allowed_pages, true)) {
          $page = 'home';
      }
      ?>
  	<?php include __DIR__ . '/' . $page . '.php' ?>
  	

  </main>

  <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

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

  <footer class="admin-site-footer">
    <p class="mb-0">&copy; 2021 Haani Shahrul. All rights reserved.</p>
  </footer>
</body>
<script>
	 window.start_load = function(){
    $('body').prepend('<di id="preloader2"></di>')
  }
  window.end_load = function(){
    $('#preloader2').fadeOut('fast', function() {
        $(this).remove();
      })
  }

  window.uni_modal = function($title = '' , $url=''){
    start_load()
    $.ajax({
        url:$url,
        error:err=>{
            console.log()
            alert("An error occured")
        },
        success:function(resp){
            if(resp){
                $('#uni_modal .modal-title').html($title)
                $('#uni_modal .modal-body').html(resp)
                $('#uni_modal').modal('show')
                end_load()
            }
        }
    })
}
window._conf = function($msg='',$func='',$params = []){
     $('#confirm_modal #confirm').attr('onclick',$func+"("+$params.join(',')+")")
     $('#confirm_modal .modal-body').html($msg)
     $('#confirm_modal').modal('show')
  }
   window.alert_toast= function($msg = 'TEST',$bg = 'success'){
      $('#alert_toast').removeClass('bg-success')
      $('#alert_toast').removeClass('bg-danger')
      $('#alert_toast').removeClass('bg-info')
      $('#alert_toast').removeClass('bg-warning')

    if($bg == 'success')
      $('#alert_toast').addClass('bg-success')
    if($bg == 'danger')
      $('#alert_toast').addClass('bg-danger')
    if($bg == 'info')
      $('#alert_toast').addClass('bg-info')
    if($bg == 'warning')
      $('#alert_toast').addClass('bg-warning')
    $('#alert_toast .toast-body').html($msg)
    $('#alert_toast').toast({delay:3000}).toast('show');
  }
  $(document).ready(function(){
    $('#sidebar-toggle').on('click', function(){
      $('body').toggleClass('sidebar-open');
    });
    $('#sidebar-overlay').on('click', function(){
      $('body').removeClass('sidebar-open');
    });
    $('#sidebar .nav-item:not([target="_blank"])').on('click', function(){
      if(window.innerWidth < 992){
        $('body').removeClass('sidebar-open');
      }
    });

    var lastPendingCount = parseInt($('#pending-orders-badge').data('count') || 0, 10);
    function updatePendingBadge(count){
      var $badge = $('#pending-orders-badge');
      var $link = $('.nav-orders');
      if(count > 0){
        if($badge.length === 0){
          $link.append('<span class="sidebar-badge" id="pending-orders-badge"></span>');
          $badge = $('#pending-orders-badge');
        }
        $badge.attr('data-count', count).text(count);
      } else {
        $badge.remove();
      }
    }
    setInterval(function(){
      $.get('ajax.php?action=get_pending_orders', function(resp){
        var count = parseInt(resp, 10);
        if(isNaN(count)) return;
        updatePendingBadge(count);
        if(count > lastPendingCount){
          alert_toast(count + ' new order' + (count === 1 ? '' : 's') + ' waiting for confirmation.', 'warning');
        }
        lastPendingCount = count;
      });
    }, 20000);
  })
</script>	
</html>
<footer class="site-footer">
    <div class="container text-center">
        <p class="site-footer-copy mb-0">&copy; 2021 Haani Shahrul. All rights reserved.</p>
    </div>
</footer>
 <script>
 	$('.datepicker').datepicker({
 		format:"yyyy-mm-dd"
 	})
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
  window.open_auth_modal = function($title, $url){
    start_load()
    var $modal = $('#uni_modal')
    var $dialog = $('#uni_modal .modal-dialog')
    $dialog.addClass('modal-lg auth-modal-lg')
    $modal.addClass('auth-modal')
    $('#uni_modal .modal-footer').hide()
    $.ajax({
        url: $url,
        error: function(){
            alert("An error occured")
            end_load()
        },
        success: function(resp){
            if(resp){
                $('#uni_modal .modal-title').html($title)
                $('#uni_modal .modal-body').html(resp)
                $modal.modal('show')
            }
            end_load()
        }
    })
    $modal.one('hidden.bs.modal', function(){
        $dialog.removeClass('modal-lg auth-modal-lg')
        $modal.removeClass('auth-modal')
        $('#uni_modal .modal-footer').show()
    })
  }
  window.uni_modal_right = function($title = '' , $url=''){
    start_load()
    $.ajax({
        url:$url,
        error:err=>{
            console.log()
            alert("An error occured")
        },
        success:function(resp){
            if(resp){
                $('#uni_modal_right .modal-title').html($title)
                $('#uni_modal_right .modal-body').html(resp)
                $('#uni_modal_right').modal('show')
                end_load()
            }
        }
    })
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
  window.load_cart = function(){
    $.ajax({
      url:'admin/ajax.php?action=get_cart_count',
      success:function(resp){
        var count = parseInt(resp, 10);
        if(isNaN(count) || count < 0){
          count = 0;
        }
        $('.item_count').html(count);
      }
    })
  }
  $(document).ready(function(){
    load_cart()
    $('#login_now').on('click', function(){
      var redirect = 'index.php' + (window.location.search || '?page=home')
      open_auth_modal('Login', 'login.php?embed=1&redirect=' + encodeURIComponent(redirect))
    })
  })
 </script>
 <!-- Bootstrap core JS-->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
        <!-- Third party plugin JS-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
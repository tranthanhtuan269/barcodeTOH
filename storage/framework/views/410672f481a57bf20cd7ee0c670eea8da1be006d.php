<?php if(session('flash_message_err') != ''): ?>
	<script type="text/javascript">
		var errors = '<?php echo session("flash_message_err"); ?>';
        swal({
          html: '<div class="alert-danger">'+errors+'</div>',
        }).then((result) => {
          if (result.value) {
            window.history.back(); 
          }
        });
	</script>
<?php endif; ?>

<?php if(session('flash_message_err_and_reload') != ''): ?>
  <script type="text/javascript">
    var errors = '<?php echo session("flash_message_err_and_reload"); ?>';
        swal({
          html: '<div class="alert-danger">'+errors+'</div>',
        }).then((result) => {
          if (result.value) {
            window.history.back();
          }
        });
  </script>
<?php endif; ?>

<?php if(session('flash_message_err_special') != ''): ?>
  <script type="text/javascript">
    var errors = '<div class="alert-danger"><?php echo session("flash_message_err_special"); ?></div>';
    swal({
      html: errors,
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'YES',
      cancelButtonText: 'NO',
    })
    .then((result) => {
      if (result.value) {
        window.location.href = baseURL + '/payment';
      }
    });
  </script>
<?php endif; ?>

<?php if(session('flash_message_succ') != ''): ?>
  <script type="text/javascript">
    var success = '<?php echo session("flash_message_succ"); ?>';
        swal({
          html: '<div class="alert-success">'+success+'</div>',
        })

  </script>
<?php endif; ?>

<?php if(session('flash_message_succ_special') != ''): ?>
	<script type="text/javascript">
    var success = '<?php echo session("flash_message_succ_special"); ?>';
      swal({
        html: '<div class="alert-success">'+success+'</div>',
      })
    .then((result) => {
      if (result.value) {
        window.location.href = baseURL + '/barcode/list/<?php echo e(Auth::user()->id); ?>';
      }
    });
	</script>
<?php endif; ?>

<?php if(count($errors) > 0): ?>
<div class="alert-danger" role="alert">
    <ul>
    	<script type="text/javascript">
    		var tempArray = <?php echo json_encode($errors->all()); ?>;
        var errors = '';
        $.each( tempArray, function( key, value) {
          errors += '<div class="alert-danger">'+value+'</div>';
        });
        swal({
          html: errors,
        }).then((result) => {
          if (result.value) {
            window.history.back(); 
          }
        });
    	</script>
    </ul>
</div>
<?php endif; ?>


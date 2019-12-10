<?php $__env->startSection('title', 'Barcode Live'); ?>

<?php $__env->startSection('content'); ?>

<div class="special"></div>
<div class="page">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<h3><?php echo e($data->title); ?></h3>
				<div class="content">
					<?php echo $data->content; ?>

				</div>
			</div>	
		</div>
	</div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts_frontend.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
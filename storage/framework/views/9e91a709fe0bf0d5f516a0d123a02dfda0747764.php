<?php $__env->startSection('title', 'Barcode Live'); ?>

<?php $__env->startSection('content'); ?>
<div class="special"></div>
<div class="account">
	<div class="container">
		<div class="row">
			<!-- Thông báo -->
			<h3></h3>
			<?php echo $__env->make('layouts_frontend.notification', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
			<?php echo $__env->make('layouts_frontend.info_account', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
			<div class="item_2 clearfix">
				<div class="filed clearfix">
					<div class="col-sm-3 text-right">
						Gender
					</div>
					<div class="col-sm-9">
						<b>
							<?php echo e(BatvHelper::getGender($info_account->gender)); ?>

						</b>
					</div>
				</div>
				<div class="filed clearfix">
					<div class="col-sm-3 text-right">
						Birthday
					</div>
					<div class="col-sm-9">
						<b><?php echo e(BatvHelper::formatDateStandard('Y-m-d',$info_account->birthday,'d/m/Y')); ?></b>
					</div>
				</div>
				<div class="filed clearfix">
					<div class="col-sm-3 text-right">
						Phone number
					</div>
					<div class="col-sm-9">
						<b><?php echo e($info_account->phone); ?></b>
					</div>
				</div>
				<div class="filed clearfix">
					<div class="col-sm-3 text-right">
						Address
					</div>
					<div class="col-sm-9">
						<b class="text-too-length"><?php echo e($info_account->address); ?></b>
					</div>
				</div>
				<div class="filed clearfix">
					<div class="col-sm-3 text-right">
						Career
					</div>
					<div class="col-sm-9">
						<b class="text-too-length"><?php echo e($info_account->career); ?></b>
					</div>
				</div>
				<div class="filed clearfix">
					<div class="col-sm-3 text-right">
						Organization 
					</div>
					<div class="col-sm-9">
						<b class="text-too-length"><?php echo e($info_account->organization); ?></b>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts_frontend.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
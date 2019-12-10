<?php $__env->startSection('title', 'Barcode Live'); ?>

<?php $__env->startSection('content'); ?>

<div class="special"></div>
<div class="page contact">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<h3><?php echo e($data->title); ?></h3>
				<?php echo $__env->make('layouts_frontend.notification', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
			</div>	
			<div class="col-sm-6">
				<div class="info">
					<span class="email pull-lefft"> <i class="fa fa-envelope" aria-hidden="true"></i> <a href="mailto:<?php echo e($data->email); ?>"><?php echo e($data->email); ?></a></span>
					<span class="phone pull-right"><i class="fa fa-phone" aria-hidden="true"></i> <a href="tel:<?php echo e($data->phone); ?>"><?php echo e($data->phone); ?></a> </span>
					<p class="desc">
						<?php echo $data->content; ?>

					</p>
				</div>
			</div>
			<div class="col-sm-offset-1 col-sm-5">
				<div class="form-questions">
					<h4 class="text-center">Have any questions?</h4>
					<p class="desc">We want to hear from you!  Fill in the form below with any questions or comments and we'll get back to you.</p>
					<form method="post">
	                    <div class="item_detail">
	                        <input type="text" class="form-control" name="name" placeholder="Name..." value="<?php echo e(old('name')); ?>">
	                    </div>
	                    <div class="item_detail">
	                        <input type="text" class="form-control" name="phone" placeholder="+XXX-XXX-XXXXX" value="<?php echo e(old('phone')); ?>">
	                    </div>
	                    <div class="item_detail">
	                        <input type="text" class="form-control" name="email" placeholder="Email..." value="<?php echo e(old('email')); ?>">
	                    </div>
	                    <div class="item_detail">
	                        <textarea name="question" placeholder="Your question or comment..." class="form-control"><?php echo e(old('question')); ?></textarea>
	                    </div>
                        <div class="item_detail">
							<div class="g-recaptcha" data-sitekey="6LfM8lIUAAAAAKsfBLVWDRV9ytZ_q7nIBW1J7Hcp"></div> 
                        </div>
	                    <div class="button text-center">
	                        <button class="register" id="add-edit-barcode">Send Message</button>
	                    </div>
	                    <?php echo e(csrf_field()); ?>

					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts_frontend.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
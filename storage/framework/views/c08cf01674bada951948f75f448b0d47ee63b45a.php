<div class="item_1 clearfix">
	<div class="col-sm-3">
		<div class="text-right">
		    <?php if(!empty($info_account->avatar)): ?>
		    	<img src="<?php echo e(asset('uploads/users/'.$info_account->avatar)); ?>" alt="avatar">
    		<?php else: ?>
    			<img src="<?php echo e(asset('frontend/images/avatar-default_2.png')); ?>">
		    <?php endif; ?>
		</div>
	</div>
	<div class="col-sm-9">
		<h3 class="name"><?php echo e($info_account->name); ?> <a href="<?php echo e(route('getAccountEdit',['id'=>$info_account->id ])); ?>"><img src="<?php echo e(asset('frontend/images/edit_account.png')); ?>"></a></h3>
		<span>Email : <b><?php echo e($info_account->email); ?></b></span>
<!-- 		<span>Phone : <b><?php echo e($info_account->phone); ?></b></span>
		<span>Career : <b><?php echo e($info_account->career); ?></b></span> -->
	</div>
</div>
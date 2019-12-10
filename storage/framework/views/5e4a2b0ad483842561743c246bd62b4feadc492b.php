

<?php $__env->startSection('title'); ?>
<?php if(!empty($data->name)): ?>
<?php echo e($data->name); ?>

<?php endif; ?>
<?php if(!empty($data->barcode)): ?>
- <?php echo e($data->barcode); ?>

<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('description'); ?>
<?php if(!empty($data->name)): ?>
<?php echo e($data->name); ?>

<?php endif; ?>
<?php if(!empty($data->barcode)): ?>
- <?php echo e($data->barcode); ?>

<?php endif; ?>
<?php if(!empty($data->description)): ?>
- <?php echo e($data->description); ?>

<?php else: ?>
Description
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('canonical'); ?>
<?php if(!empty($data->name) && !empty($data->barcode)): ?>
<?php echo e(route('seo-barcode', ['slug' => str_slug($data->name, "-") . '-' . $data->barcode])); ?>

<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<?php echo $__env->make('layouts_frontend.box_search', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<div class="container">
	<div class="row">
		<div class="box_search clearfix">
			<?php if( !empty($data->barcode) ): ?>
			<div class="col-sm-5" style="padding-right: 0px;">
				<div class="image">
                    <?php if(strlen($data->image) > 0): ?>
                        <?php 
                            $list_file = explode(',', $data->image); 
                            $check_link_http = false;

                            if (count($list_file) == 1 && strpos($data->image, "http") !== false) {
                               $check_link_http = true;
                            }
                        ?>
                        <div id="myCarousel" class="carousel slide" data-ride="carousel">
                            <?php if(count($list_file) > 1): ?>
                                <ol class="carousel-indicators">
                                    <?php foreach($list_file as  $key => $image): ?>
                                        <li data-target="#myCarousel" data-slide-to="<?php echo e($key); ?>" class="<?php if($key == 0): ?> active <?php endif; ?>"></li>
                                    <?php endforeach; ?>
                                </ol>
                            <?php endif; ?>
                            <div class="carousel-inner">
                                <?php if($check_link_http): ?>
                                    <div class="item active">
                                        <img src="<?php echo e($data->image); ?>">
                                    </div>
                                <?php else: ?>
                                    <?php foreach($list_file as  $key => $image): ?>
                                        <div class="item <?php if($key == 0): ?> active <?php endif; ?>">
                                            <img src="<?php echo e(asset('uploads/barcode/'.$image)); ?>" alt="<?php echo e($image); ?>">
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
    
                            <?php if(count($list_file) > 1): ?>
                                <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                                    <span class="glyphicon glyphicon-chevron-left"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="right carousel-control" href="#myCarousel" data-slide="next">
                                    <span class="glyphicon glyphicon-chevron-right"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            <?php endif; ?>
                        </div>
					<?php else: ?>
						<img src="<?php echo e(asset('frontend/images/image_barrcode_df.png')); ?>">
					<?php endif; ?>
				</div>
			</div>
			<div class="col-sm-7">
				<h1>EAN <?php echo $data->barcode; ?></h1>
				<div class="title">
					<?php echo e($data->name); ?>

				</div>
				<div class="info">
					<p>Model: <b><?php echo !empty($data->model) ? $data->model : 'n/a'; ?></b></p>
				</div>
				<div class="info">
					<p>Manufacturer: <b><?php echo !empty($data->manufacturer) ? $data->manufacturer : 'n/a'; ?></b></p>
				</div>
				<div class="info">
					<p>Price: <b id="numFormatResult"><?php echo !empty($data->avg_price) ? $data->avg_price : 'n/a'; ?></b> <b> <?php echo !empty($data->currency_unit) ? $data->currency_unit : 'n/a'; ?></b></p>
				</div>
				<p>Share</p>
				<div class="fb-like" data-href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fbarcodelive.org%2F?barcode=<?php echo e($data->barcode); ?>" data-layout="button_count" data-action="like" data-size="small" data-show-faces="false" data-share="false"></div>
				<div class="fb-share-button" data-layout="button" data-size="small" data-mobile-iframe="true"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fbarcodelive.org%2F?barcode=<?php echo e($data->barcode); ?>" class="fb-xfbml-parse-ignore">Chia sẻ</a></div>
			</div>

			<div class="col-sm-12">
				<div class="special_search">
					<div class="detail">
						<div class="inner clearfix">
						  <div class="label"><img src="<?php echo e(asset('frontend/images/product.png')); ?>" alt="Descriptions"></div>
						  <h2 class="title">Descriptions</h2>
						</div>
						<div class="show_content"><?php echo !empty($data->description) ? $data->description : 'n/a'; ?></div>

					</div>
				</div>
			</div>

			<div class="col-sm-6">
				<div class="detail special_hold">
					<div class="inner clearfix">
					  <div class="label"><img src="<?php echo e(asset('frontend/images/product.png')); ?>" alt="Specification"></div>
					  <h2 class="title">Specification</h2>
					</div>
					<div class="show_content"><?php echo !empty($data->spec) ? $data->spec : 'n/a'; ?></div>

				</div>
			</div>

			<div class="col-sm-6">
				<div class="detail feature_hold">
					<div class="inner clearfix">
					  <div class="label"><img src="<?php echo e(asset('frontend/images/product.png')); ?>" alt="Feature"></div>
					  <h2 class="title">Feature</h2>
					</div>
					<div class="show_content"><?php echo !empty($data->feature) ? $data->feature : 'n/a'; ?></div>
				</div>
			</div>
			<?php else: ?>
					<div class="alert-danger" style="text-align: center;">No data available</div>
			<?php endif; ?>

		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts_frontend.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
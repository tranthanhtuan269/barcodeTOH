<?php $__env->startSection('title', 'Barcode Live'); ?>
<?php $__env->startSection('content'); ?>
<div class="special"></div>
<div class="container">
    <div class="row">
        <div class="view-barcode account clearfix">
            <h3>View Product</h3>
            <!-- Thông báo -->
            <?php echo $__env->make('layouts_frontend.notification', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <?php if(!empty($data)): ?>
            <div class="col-xs-offset-2 col-sm-8">
                <div class="item_detail row">
                    <div class="name-field">
                        <div class="item-field-label">
                            Image
                        </div>
                        <div class="item-field-data">
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
                                <b>n/a</b>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
  
              <div class="item_detail row">
                  <div class="name-field">
                      <div class="item-field-label">
                          BarCode
                      </div>
                      <div class="item-field-data">
                          <b><?php echo e($data->barcode); ?></b>
                      </div>
                  </div>
              </div>
              <div class="item_detail row">
                  <div class="name-field">
                      <div class="item-field-label">
                          Name
                      </div>
                      <div class="item-field-data">
                          <b><?php echo e($data->name); ?></b>
                      </div>
                  </div>
              </div>
              <div class="item_detail row">
                  <div class="name-field">
                      <div class="item-field-label">
                          Model
                      </div>
                      <div class="item-field-data">
                          <b><?php echo e($data->model); ?></b>
                      </div>
                  </div>
              </div>
              <div class="item_detail row">
                  <div class="name-field">
                      <div class="item-field-label">
                          Manufacturer
                      </div>
                      <div class="item-field-data">
                          <b><?php echo e($data->manufacturer); ?></b>
                      </div>
                  </div>
              </div>
              <div class="item_detail row">
                  <div class="row">
                    <div class="name-field">
                        <div class="item-field-label">
                            Avg Price
                        </div>
                        <div class="item-field-data">
                            <b><span id="numFormatResult"><?php echo e($data->avg_price); ?></span>

                            <?php $currency_list =  config('batv_config.currency_list') ?>
                            <?php foreach($currency_list as $key=>$value): ?>
                              <?php echo e(strtoupper(old('currency_unit', $data->currency_unit)) == strtoupper($key) ? $key : ''); ?>

                            <?php endforeach; ?>
                            </b>
                        </div>
                    </div>
                  </div>
              </div>
              <div class="item_detail row">
                  <div class="name-field">
                    <div class="item-field-label">
                      Specification
                    </div>
                    <div class="item-field-data">
                        <b class="long-data"><?php if(strlen($data->spec) > 0) echo $data->spec; else echo 'n/a'; ?></b>
                    </div>
                  </div>
              </div>
              <div class="item_detail row">
                  <div class="name-field">
                    <div class="item-field-label">
                      Feature
                    </div>
                    <div class="item-field-data">
                        <b class="long-data"><?php if(strlen($data->feature) > 0) echo $data->feature; else echo 'n/a'; ?></b>
                    </div>
                  </div>
              </div>
              <div class="item_detail row">
                  <div class="name-field">
                    <div class="item-field-label">
                      Description
                    </div>
                    <div class="item-field-data">
                        <b class="long-data"><?php if(strlen($data->description) > 0) echo $data->description; else echo 'n/a'; ?></b>
                    </div>
                  </div>
              </div>
              <div class="button text-center martop">
                  <a href="<?php echo e(url('/')); ?>/barcode/edit/<?php echo e($data->id); ?>" class="register">Edit product</a>
                  <a href="<?php echo e(url('/')); ?>/barcode/list/<?php echo e(\Auth::user()->id); ?>" class="cancel">Back to List</a>
              </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts_frontend.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
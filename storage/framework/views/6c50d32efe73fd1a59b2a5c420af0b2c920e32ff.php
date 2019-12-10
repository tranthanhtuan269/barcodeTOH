
<?php $__env->startSection('title', 'Barcode Live'); ?>
<?php $__env->startSection('content'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('frontend/css/combobox.css')); ?>">
<script src="https://cdn.ckeditor.com/4.6.1/standard/ckeditor.js"></script>
<script src="<?php echo e(asset('frontend/js/jquery.combobox.js')); ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.min.js"></script>
<?php /* <script src="http://jcrop-cdn.tapmodo.com/v0.9.12/js/jquery.Jcrop.min.js"></script>
 */ ?>
 <script src="<?php echo e(asset('frontend/js/dropzone.js')); ?>"></script>
<script>
    Dropzone.autoDiscover = false;
    var uploadedDocumentMap = {}
    check_action_droponejs = false;

    var src = $(this).attr('src');
    var txt_base64 = 'data:image/';
    if(src.indexOf(txt_base64) === -1){
        window.open('<?php echo e(url("uploads/barcode")); ?>' + '/' + $(this).attr('alt'), '_blank');
    }
    
</script>
<div class="special"></div>
<div class="container">
    <div class="row">
        <div class="add-barcode account clearfix">
            <h3>Edit Product</h3>
            <!-- Thông báo -->
            <?php echo $__env->make('layouts_frontend.notification', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <?php if(!empty($data)): ?>
            <form enctype="multipart/form-data" method="post" action="<?php echo e(route('putBarCodeEditbyUser',['id'=>$data->id])); ?>">
                <input type="hidden" name="_method" value="PUT">
                <div class="col-xs-offset-2 col-sm-8">
                    <div class="item_detail">
                        <div class="name-field clearfix">
                            <div class="pull-left">
                                Upload product image
                            </div>
                        </div>
                        <div class="product-image">
                            <div class="dropzone dz-clickable clearfix" id="myDrop">
                                <div class="dz-default dz-message" data-dz-message="">
                                    <i class="fa fa-upload fa-4x" aria-hidden="true"></i>
                                </div>
                            </div>

                            <div id="preview-template" style="display: none;">
                                <div class="dz-preview dz-file-preview">
                                    <div class="dz-image"><img data-dz-thumbnail=""></div>
                                    <div class="dz-details">
                                        <?php /* <div class="dz-size"><span data-dz-size=""></span></div> */ ?>
                                        <div class="dz-filename"><span data-dz-name=""></span></div>
                                    </div>
                                    <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress=""></span></div>
                                    <div class="dz-error-message"><span data-dz-errormessage=""></span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="item_detail">
                        <!--     <label class="control-label"> BarCode <span class="required">*</span> <span class="character-barcode"></span></span>/50</label> -->
                        <div class="name-field clearfix">
                            <div class="pull-left">
                                BarCode <span class="required">*</span>
                            </div>
                            <div class="pull-right">
                                <span class="character-barcode"></span></span>/12-13
                            </div>
                        </div>
                        <input type="text" class="form-control" id="barcodetxt" name="barcode" value="<?php echo e($data->barcode); ?>" pattern="\d*">
                    </div>
                    <div class="item_detail">
                        <div class="name-field clearfix">
                            <div class="pull-left">
                                Name <span class="required">*</span>
                            </div>
                            <div class="pull-right">
                                <span class="character-name"></span></span>/200
                            </div>
                        </div>
                        <input type="text" class="form-control" name="name" id="name-barcode" value="<?php echo e(old('name',isset($data->name) ? $data->name : null)); ?>">
                    </div>
                    <div class="item_detail">
                        <div class="name-field clearfix">
                            <div class="pull-left">
                                Model <span class="required">*</span>
                            </div>
                            <div class="pull-right">
                                <span class="character-model"></span></span>/50
                            </div>
                        </div>
                        <input type="text" class="form-control" name="model" value="<?php echo e(old('model',isset($data->model) ? $data->model : null)); ?>">
                    </div>
                    <div class="item_detail">
                        <div class="name-field clearfix">
                            <div class="pull-left">
                                Manufacturer <span class="required">*</span>
                            </div>
                            <div class="pull-right">
                                <span class="character-manufacturer"></span></span>/200
                            </div>
                        </div>
                        <input type="text" class="form-control" name="manufacturer" value="<?php echo e(old('manufacturer',isset($data->manufacturer) ? $data->manufacturer : null)); ?>">
                    </div>
                    <div class="item_detail">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="name-field clearfix">
                                    <div class="pull-left">
                                        Avg Price <span class="required">*</span>
                                    </div>
                                    <div class="pull-right">
                                        <span class="character-avg_price"></span></span>/100
                                    </div>
                                </div>
                                <input type="text" id="numFormatResult" class="form-control" name="avg_price_tmp" value="<?php echo e(old('avg_price_tmp',isset($data->avg_price) ? $data->avg_price : null)); ?>" maxlength="133" size="133">
                                <input type="hidden" name="avg_price" id="result" value="<?php echo e(isset($data->avg_price) ? $data->avg_price : null); ?>">
                            </div>
                            <div class="col-sm-6">
                                <div class="name-field clearfix">
                                    Currency Unit
                                </div>
                                <?php $currency_list =  config('batv_config.currency_list') ?>
                                <select class="form-control select2 wrap" name="currency_unit">
                                <?php foreach($currency_list as $key=>$value): ?>
                                  <option value="<?php echo e($key); ?>" <?php echo e(strtoupper(old('currency_unit', $data->currency_unit)) == strtoupper($key) ? 'selected' : ''); ?>><?php echo e($key); ?></option>
                                <?php endforeach; ?>
                                </select>
                                <script type="text/javascript">
                                    var $select2 = $('.select2').select2({
                                        containerCssClass: "wrap"
                                    })
                                            
                                </script>
                            </div>
                        </div>
                    </div>
                    <div class="item_detail">
                        <div class="name-field clearfix">
                            Spec
                        </div>
                        <textarea rows="4" onkeydown="expandtext(this);" name="spec" class="form-control" ><?php echo e(old('spec',isset($data->spec) ? $data->spec : null)); ?></textarea>
                    </div>
                    <div class="item_detail">
                        <div class="name-field clearfix">
                            Feature
                        </div>
                        <textarea rows="4" onkeydown="expandtext(this);" name="feature" class="form-control" ><?php echo e(old('feature',isset($data->feature) ? $data->feature : null)); ?></textarea>
                    </div>
                    <div class="item_detail">
                        <div class="name-field clearfix">
                            Description
                        </div>
                        <textarea rows="4" onkeydown="expandtext(this);" name="description_field" class="form-control" ><?php echo e(old('description',isset($data->description) ? $data->description : null)); ?></textarea>
                    </div>
                    <div id="pre_ajax_loading_barcode" style="display: none;text-align: center;margin: 0px 0px 15px 0px;"><img src="<?php echo e(asset('images/general/bx_loader.gif')); ?>"></div>
                    <div class="button text-center">
                        <button class="register" id="add-edit-barcode">Submit</button>
                        <div class="hidden" id="str_image"></div>
                        <a class="cancel" href="javascript:void(0);" onclick="alertMessage('Are you sure you want to cancel?');">Cancel</a>
                    </div>
                </div>
                <?php echo e(csrf_field()); ?>

            </form>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- Modal -->
<div id="change-image" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg modal-image">
        <!-- Modal content-->
        <div class="modal-content">
            <form id="form" >
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Select new image</h4>
                </div>
                <div class="modal-body">
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped active" role="progressbar"
                        aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width:80%">
                            80%
                        </div>
                    </div>
                    <input id="file" type="file" class="hide" accept="image/*">
                    <div id="views"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" id="load-btn">Load image</button>
                    <button type="button" class="btn btn-primary hide" id="submit-btn">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<link rel="stylesheet" href="http://jcrop-cdn.tapmodo.com/v0.9.12/css/jquery.Jcrop.min.css" type="text/css" />
<script type="text/javascript">

    var inputEl = document.getElementById('barcodetxt');
       var goodKey = '0123456789';
    
    var crop_max_width = 400;
    var crop_max_height = 400;
    var jcrop_api;
    var canvas;
    var context;
    var image;

    var prefsize;

    CKEDITOR.replace('description_field');
    CKEDITOR.replace('feature');
    CKEDITOR.replace('spec');
    

    CKEDITOR.instances['spec'].on('contentDom', function() {
        this.document.on('click', function(event){
            var temp = $(window).scrollTop();
            $('.select2').select2("close");
            CKEDITOR.instances['spec'].focus();
            $(window).scrollTop(temp);
         });
    });

    CKEDITOR.instances['feature'].on('contentDom', function() {
        this.document.on('click', function(event){
            var temp = $(window).scrollTop();
            $('.select2').select2("close");
            CKEDITOR.instances['feature'].focus();
            $(window).scrollTop(temp);
         });
    });

    CKEDITOR.instances['description_field'].on('contentDom', function() {
        this.document.on('click', function(event){
            var temp = $(window).scrollTop();
            $('.select2').select2("close");
            CKEDITOR.instances['description_field'].focus();
            $(window).scrollTop(temp);
         });
    });
    
    $(document).ready(function(){
      var $file = null;

      $('#change-image').on('shown.bs.modal', function (e) {
          e.preventDefault();
          var fileExtension = ['jpeg', 'jpg', 'png', 'gif', 'bmp'];
          if ($.inArray($($file).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
              swal({
                  html: '<div class="alert-danger">Only formats are allowed : '+fileExtension.join(', ')+'</div>',
                })
              return;
          }
          loadImage($file);
      });

      wordCounter('barcode',13, true);
      wordCounter('name',200);
      wordCounter('model',50);
      wordCounter('manufacturer',200);
      wordCounter('avg_price',100);
    
           var checkInputTel = function(e) {
             var key = (typeof e.which == "number") ? e.which : e.keyCode;
             var start = this.selectionStart,
               end = this.selectionEnd;
    
             var filtered = this.value.split('').filter(filterInput);
             this.value = filtered.join("");
    
             /* Prevents moving the pointer for a bad character */
             var move = (filterInput(String.fromCharCode(key)) || (key == 0 || key == 8)) ? 0 : 1;
             this.setSelectionRange(start - move, end - move);
           }
    
           var filterInput = function(val) {
             return (goodKey.indexOf(val) > -1);
           }
    
           inputEl.addEventListener('input', checkInputTel);
    
            $('#product-image').click(function(){
              $('#file').val("");
              $('#file').click();
            });
    
           $('#open-modal').click(function(){
              $('#file').val("");
              $('#file').click();
           });
    
           $("#file").change(function() {
            $file = this;
            if($(this).val().length > 0){
              $('.progress').removeClass('hide');
              // $('#change-image').modal('show');
              loadImage(this);
            }
           });
    
           $('#load-btn').click(function(){
            $('#file').val("");
            $('#change-image').modal('hide');
            $('#file').click();
           });
    
           function loadImage(input) {
             if (input.files && input.files[0]) {
               var reader = new FileReader();
               canvas = null;
               reader.onload = function(e) {
                 image = new Image();
                 image.onload = validateImage;
                 image.src = e.target.result;
               }
               reader.readAsDataURL(input.files[0]);
               $('#submit-btn').removeClass('hide');
             }
           }
    
           function validateImage() {
              $('.progress').addClass('hide');
              if (canvas != null) {
                image = new Image();
                image.onload = restartJcrop;
                image.src = canvas.toDataURL('image/png');
    
                $("#form").submit();
              } else restartJcropOpen();
           }

            function restartJcropOpen() {
                if(image.width < 160 || image.height < 160 || image.width > 3000 || image.height > 3000){
                    $("#views").empty();
                    swal({
                        html: '<div class="alert-danger">Image must be between 160 x 160 — 3,000 x 3,000 pixels. Please select a different image.</div>',
                    });
                  }else{
                    $('#change-image').modal('show');
                    restartJcrop();
                  }
            }
    
           function restartJcrop() {
             if (jcrop_api != null) {
               jcrop_api.destroy();
             }
             $("#views").empty();
             $("#views").append("<canvas id=\"canvas\">");
             canvas = $("#canvas")[0];
             context = canvas.getContext("2d");
             canvas.width = image.width;
             canvas.height = image.height;
             var imageSize = (image.width > image.height)? image.height : image.width;
             imageSize = (imageSize > 800)? 800: imageSize;
             context.drawImage(image, 0, 0);
             $("#canvas").Jcrop({
               onSelect: selectcanvas,
               onRelease: clearcanvas,
               boxWidth: crop_max_width,
               boxHeight: crop_max_height,
               setSelect: [0,0,(imageSize*4/3),imageSize],
               aspectRatio: 4/3,
               bgOpacity:   .4,
               bgColor:     'black'
             }, function() {
               jcrop_api = this;
             });
             clearcanvas();
             selectcanvas({x:0,y:0,w:(imageSize*4/3),h:imageSize});
           }
    
           function clearcanvas() {
             prefsize = {
               x: 0,
               y: 0,
               w: canvas.width,
               h: canvas.height,
             };
           }
    
           function selectcanvas(coords) {
             prefsize = {
               x: Math.round(coords.x),
               y: Math.round(coords.y),
               w: Math.round(coords.w),
               h: Math.round(coords.h)
             };
           }
    
           $('#submit-btn').click(function(){
               canvas.width = prefsize.w;
               canvas.height = prefsize.h;
               context.drawImage(image, prefsize.x, prefsize.y, prefsize.w, prefsize.h, 0, 0, canvas.width, canvas.height);
               validateImage();
           });
    
           $("#form").submit(function(e) {
             e.preventDefault();
             $('#change-image').modal('hide');
             $('#product-image').removeClass('hide');
             formData = new FormData($(this)[0]);
             //---Add file blob to the form data
             formData.append("base64", canvas.toDataURL('image/png'));
    
             $.ajaxSetup(
             {
                 headers:
                 {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               }
             });
             $.ajax({
               url: "<?php echo e(url('/')); ?>/images/uploadImageBarcode",
               type: "POST",
               data: formData,
               contentType: false,
               processData: false,
               beforeSend: function() {
                    $('#product-image').css('width', '165px');
                    $('#product-image').css('height', '125px');
                    $('#product-image').show();
                    $("#image-loading").show();
                },
               success: function(data) {
                $("#pre_ajax_loading").hide();
                  if(data.code == 200){
                    $('#product-image').attr('src', "<?php echo e(url('/')); ?>/uploads/barcode/" + data.image_url);
                    $('#image').val(data.image_url);
                    $('#change-image').modal('hide');
                    $("#views").empty();
                  }else{
                    $('#product-image').addClass('hide');
                    swal({
                        html: '<div class="alert-danger">An error occurred during save process, please try again</div>',
                      })
                    return;
                  }
                  $('#product-image').on('load', function () {
                    $("#image-loading").hide();
                  });
               },
               error: function(data) {
                   alert("Error");
               },
               complete: function(data) {}
             });
           });
       });
</script>
<script type="text/javascript">
    file_old_deleted = [];

    $(document).ready(function(){
        myDropzone = new Dropzone("div#myDrop", 
        { 
            paramName: "files", // The name that will be used to transfer the file
            addRemoveLinks: true,
            uploadMultiple: true,
            autoProcessQueue: true,
            parallelUploads: 50,
            maxFiles: 10,
            maxFilesize: 2, // MB
            thumbnailWidth: null,
            thumbnailHeight: null,
            maxThumbnailFilesize: 2,
            dictRemoveFile: '<i class="fa fa-times fa-3" aria-hidden="true"></i>',
            acceptedFiles: ".png, .jpeg, .jpg, .gif",
            previewTemplate: document.querySelector('#preview-template').innerHTML,
            url: "<?php echo e(route('droponejs-file')); ?>",
            headers: {
                'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"
            },

            success: function(file, response){
                $(".ajax_waiting").removeClass("loading");
                var arr_str_image  = [];

                $.each( response.data, function(index, item){
                    // if(jQuery.inArray(file.name, arr_str_image) === -1) {
                        arr_str_image.push(file.name)
                        $('#str_image').append('<div data-title="'+ file.name +'">'+ item +'</div>')
                    // }
                });
      

            },
            accept: function(file, done) {
                check_action_droponejs = true;
                done();
            },
            error: function(file, message, xhr){
                file.previewElement.remove()
                
                swal({
                    type: 'warning',
                    html: message,
                })
            },
            sending: function(file, xhr, formData) {
                
            },
            complete: function complete(file) {
                if (file._removeLink) {
                    file._removeLink.innerHTML = this.options.dictRemoveFile;
                }
                if (file.previewElement) {
                    return file.previewElement.classList.add("dz-complete");
                }

            },
            init: function() {
                var thisDropzone = this;
                str = '<?php echo e($data->image); ?>';
                str = str.trim();
                data = [];

                if (str != '') {
                    data = str.split(",");
                }

                

                $.each(data, function(key,value){
                    if (value) {
                        var mockFile = { name: value };
                        thisDropzone.options.addedfile.call(thisDropzone, mockFile);
                        var image_path = "<?php echo e(url('/uploads/barcode/')); ?>" + "/" +value;
                        thisDropzone.options.thumbnail.call(thisDropzone, mockFile, image_path);
                    }
                });

                this.on('addedfile', function(file) {
                    var max_file = $('#myDrop .dz-image').length;

                    if (max_file > 10) {
                        this.removeFile(file);
                        swal({
                            type: 'warning',
                            html: 'You can not upload any more files.',
                        })

                        return;
                    }

                    if (file.size == 0) {
                        file.previewElement.remove()
                        swal({
                            type: 'warning',
                            html: 'Please enter a valid file!',
                        })

                        return;
                    }

                    // var ext = file.name.split('.').pop();

                    // if (ext != "png" && ext != "jpeg" && ext != "jpg" && ext != "gif" && ext != "webp") {
                    //     $(file.previewElement).find(".dz-image img").attr("src", "<?php echo e(asset('images/general/document.png')); ?>");
                    // }

                    if (this.files.length) {
                        var _i, _len;
                        for (_i = 0, _len = this.files.length; _i < _len - 1; _i++) // -1 to exclude current file
                        {
                            if(this.files[_i].name === file.name && this.files[_i].size === file.size && this.files[_i].lastModifiedDate.toString() === file.lastModifiedDate.toString())
                            {
                                swal({
                                    type: 'warning',
                                    html: 'File already exists!',
                                })   
                                this.removeFile(file);
                            }
                        }
                    }

                });

                this.on('removedfile', function(file) {
                    file.previewElement.remove()
                    $('#str_image div[data-title="'+ file.name +'"]').remove()
                    file_old_deleted.push(file.name);
                });

            },
        });

        $("body").on("click", "#add-edit-barcode", function (e) {
            var str_image = '';

            $('#str_image div').each(function(i, obj) {
                var item  = $(obj).html();
                str_image += item.trim() + ',';
            });
            
            var barcode = $('input[name="barcode"]').val();
            var name = $('input[name="name"]').val();
            var model = $('input[name="model"]').val();
            var manufacturer = $('input[name="manufacturer"]').val();
            var currency_unit = $('input[name="currency_unit"]').val();
            var spec = CKEDITOR.instances.spec.getData();
            var feature = CKEDITOR.instances.feature.getData();
            var description = CKEDITOR.instances.description_field.getData();

            var options = {
                data: {
                    barcode: barcode,
                    name: name,
                    model: model,
                    manufacturer: manufacturer,
                    currency_unit: currency_unit,
                    spec: spec,
                    feature: feature,
                    description: description,
                    str_image :  str_image,
                    file_old_deleted : JSON.stringify(file_old_deleted)
                    // file_list: file_list,
                    // file_old_deleted: file_old_deleted,
                    // form_data: form_data,
                    // file_list_title: file_list_title,
                    },
                beforeSend: function () {
                    $(".ajax_waiting").addClass('loading');
                },
                complete: function (response) {
                    $(".ajax_waiting").removeClass('loading');

                    if ($.isEmptyObject(response.responseJSON.error)) {
                        var urlRedirect = baseURL + "/barcode/list/" + response.responseJSON.user_id;
                        swal({
                            html: '<div class="alert-success">' + response.responseJSON.success + '</div>',
                        })
                        .then((result) => {
                            window.location.href = urlRedirect;
                        });
                    } else {
                        var errors = '';
                        if (typeof response.responseJSON.error == "string") {
                            swal(settingSweetalerForPayment(response.responseJSON.error))
                            .then((result) => {
                                if (result.value) {
                                    window.location.href = baseURL + "/payment";
                                }
                            });
                        } else {
                            $.each(response.responseJSON.error, function (key, value) {
                                errors += '<div class="alert-danger">' + value + '</div>';
                            });
                            swal({
                                html: errors,
                            })
                        }
                    }
                }
            };

            $(this).parents("form").ajaxForm(options);     
        })
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts_frontend.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
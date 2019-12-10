<header>
	<div class="container">
		<div class="row">
			<div class="col-sm-offset-3 col-sm-6">
				<div class="item clearfix">
					<h3 class="text_search">
						Information and images for millions of products globally.
					</h3>
					<div class="search">
						<input type="text" name="barcode" id="barcodetxt" class="form-control"  placeholder="Enter a barcode number" value="{{ isset($barcode) ? $barcode : '' }}" required>
						<button type="button" class="btn btn-primary" onclick="return validateKeywords();">Search</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</header>
<script type="text/javascript">
	function validateKeywords() {
		var keywords = $('#barcodetxt').val();
        var check = /^\d+$/;
   
        if (keywords != '') {
	        if (!check.test(keywords)) {
	            swal({
	              html: '<div class="alert-danger">The barcode must be a number</div>',
	            })
	            return false;
	        }

        } else {
            swal({
              html: '<div class="alert-danger">Please enter a barcode in the search box</div>',
            })
            return false;
        }

        $.ajaxSetup(
        {
            headers:
            {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    
        var data    = {
            barcode : keywords.trim(),
        };
    
        $.ajax({
            type: "POST",
            url: "{{ route('get-slug-barcode') }}",
            data: data,
            dataType: 'json',
            beforeSend: function(r, a){
                $(".ajax_waiting").addClass("loading");
            },
            complete: function() {
                $(".ajax_waiting").removeClass("loading");
            },
            success: function(response) {
                if(response.status == 200) {
                	window.location.replace("{{ route('index') }}" + "/barcode/" + response.slug + "-" + keywords);
                } else {
                	window.location.replace("{{ route('index') }}" + "/barcode/s-" + keywords);
                }
            },
            error: function (data) {

            }
        });
	}
</script>
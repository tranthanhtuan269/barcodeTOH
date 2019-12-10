<div class="panel panel-primary filterable">
    <div class="panel-heading">
        <div class="panel-title">Detail</div>
        <div class="pull-right">
            <button class="btn btn-default btn-xs btn-filter"><span class="glyphicon glyphicon-filter"></span> Filter</button>
        </div>
    </div>
    <div class="table-responsive">
	    <table class="table">
	        <thead>
	            <tr class="filters">
	                <th><input type="text" class="text-center form-control" placeholder="Id" disabled></th>
	                <th><input type="text" class="form-control" placeholder="Order Name" disabled></th>
	                <th><input type="text" class="form-control" placeholder="Amount" disabled></th>
	                <th><input type="text" class="form-control" placeholder="Price" disabled></th>
	                <th><input type="text" class="form-control" placeholder="Created" disabled></th>
	            </tr>
	        </thead>
	        <tbody>
			    @foreach($history as $item)
	                <tr>
	                    <td class="text-center">{{ $item->id }}</td>
	                    <td>{{ $item->order_name }}</td>
	                    <td>{{ $item->amount }}</td>
	                    <td>{{ $item->price }} $</td>
	                    <td>{{ BatvHelper::formatDateStandard('Y-m-d H:i:s',$item->created_at ,'Y-m-d H:i:s') }}</td>
	                </tr>
			    @endforeach
	        </tbody>
	    </table>
    </div>
</div>
<div class="text-center">{!! $history->render() !!}</div>
<script type="text/javascript">
	$(document).ready(function(){
	    $('.filterable .btn-filter').click(function(){
	        var $panel = $(this).parents('.filterable'),
	        $filters = $panel.find('.filters input'),
	        $tbody = $panel.find('.table tbody');
	        if ($filters.prop('disabled') == true) {
	            $filters.prop('disabled', false);
	            $filters.first().focus();
	        } else {
	            $filters.val('').prop('disabled', true);
	            $tbody.find('.no-result').remove();
	            $tbody.find('tr').show();
	        }
	    });

	    $('.filterable .filters input').keyup(function(e){
	        /* Ignore tab key */
	        var code = e.keyCode || e.which;
	        if (code == '9') return;
	        /* Useful DOM data and selectors */
	        var $input = $(this),
	        inputContent = $input.val().toLowerCase(),
	        $panel = $input.parents('.filterable'),
	        column = $panel.find('.filters th').index($input.parents('th')),
	        $table = $panel.find('.table'),
	        $rows = $table.find('tbody tr');
	        /* Dirtiest filter function ever ;) */
	        var $filteredRows = $rows.filter(function(){
	            var value = $(this).find('td').eq(column).text().toLowerCase();
	            return value.indexOf(inputContent) === -1;
	        });
	        /* Clean previous no-result if exist */
	        $table.find('tbody .no-result').remove();
	        /* Show all rows, hide filtered ones (never do that outside of a demo ! xD) */
	        $rows.show();
	        $filteredRows.hide();
	        /* Prepend no-result row if all rows are filtered */
	        if ($filteredRows.length === $rows.length) {
	            $table.find('tbody').prepend($('<tr class="no-result text-center"><td colspan="'+ $table.find('.filters th').length +'">No result found</td></tr>'));
	        }
	    });
	});
</script>
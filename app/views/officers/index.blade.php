@extends('template')
@section('content')
<br />
<h4 class="text-success">{{ $title }}<span class="badge badge-success"><?=$count;?></span></h4>
<table width="100%" id="myTable" class="table">
  <thead>
  <tr>
    <th width="2%">&nbsp;</th>
    <th width="19%"><strong>LGU</strong></th>
    <th width="25%">Name</th>
    <th width="23%">Mobile</th>
    <th width="18%">&nbsp;</th>
    <td width="13%">
      <a href="{{Request::root()}}/admin/photo/add" class="btn btn-primary btn-small">Add</a>
    </td>
  </tr>
  </thead>
  <tbody>
  <?php $i=1;?>
  
  @foreach($rows as $row)
  <tr>
    <td><?=$i++;?></td>
    <td>{{ $row->lgu }}</td>
    <td>{{ $row->name }}</td>
    <td>{{ $row->mobile }}</td>
    <td></td>
    <td>
      <a href="{{Request::root()}}/admin/photo/edit/{{ $row->id }}" class="btn btn-primary btn-small">Edit</a>
      <a href="#" class="btn btn-small" id="delete" val="{{ $row->id }}">Delete</a>
    </td>
  </tr>
  @endforeach
  </tbody>
</table>


<?php echo $rows->links(); ?>


<script>
$(document).ready(function() 
    { 
        $("#myTable").tablesorter(); 
    } 
);

$('a#delete').click(function(){

var val = $(this).attr('val');

//alert(val);

if (confirm("Are you sure?")) {
	$.post('<?php echo Request::root().'/admin/photo/delete/';?>'+val);
	$(this).parent().parent().remove();
}
return false;

});
</script>  

@stop


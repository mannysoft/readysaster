@extends('template')
@section('content')
<br />
<h4 class="text-success">{{ $title }}<span class="badge badge-success"></span></h4>
<table width="100%" id="myTable" class="table">
  <thead>
  <tr>
    <th width="3%">&nbsp;</th>
    <th width="22%"><strong>Title</strong></th>
    <th width="1%">&nbsp;</th>
    <th width="2%">&nbsp;</th>
    <th width="57%"><strong>Photo URL</strong></th>
    <td width="15%">
      <a href="{{Request::root()}}/admin/photo/add" class="btn btn-primary btn-small">Add</a>
    </td>
  </tr>
  </thead>
  <tbody>
 
  <tr>
    <td></td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td></td>
    <td>
      <a href="{{Request::root()}}/admin/photo/edit/" class="btn btn-primary btn-small">Edit</a>
      <a href="#" class="btn btn-small" id="delete" val="">Delete</a>
    </td>
  </tr>
  
  </tbody>
</table>


<?php //echo $rows->links(); ?>


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


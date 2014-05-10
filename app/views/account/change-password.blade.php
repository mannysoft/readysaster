@extends('template')
@section('content')



<h4 class="text-success">Change Password</h4>

@if(count($errors) >= 1)
    <div class="alert alert-error">
        @foreach ($errors as $error)
            <div class="error">{{ $error }}</div>
        @endforeach
    </div>
@endif

<form method="post">
<table width="100%" class="table">
  <tr>
    <td width="33%">Current Password</td>
    <td width="67%"><input name="old_password" type="password" id="old_password" value="" /></td>
  </tr>
  <tr>
    <td>New Password</td>
    <td><input name="password" type="password" id="password" value="" /></td>
  </tr>
  <tr>
    <td>Retype New Password</td>
    <td><input name="password2" type="password" id="password2" value="" /></td>
  </tr>
  <tr>
    <td></td>
    <td><button class="btn btn-small btn-primary" type="submit">Save</button> <a href="{{ Request::root()}}/profile" class="btn btn-small btn-primary">Cancel</a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp; <input name="op" type="hidden" id="op" value="1" /></td>
  </tr>
</table>
</form>

@stop
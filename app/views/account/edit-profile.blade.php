@extends('template')
@section('content')


<h4 class="text-success">Edit Account Profile</h4>


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
    <td width="10%">Username</td>
    <td><input name="username" type="text" id="username" value="<?=$info->username;?>" /></td>
  </tr>
  <tr>
    <td>FirstName</td>
    <td><input name="fname" type="text" id="fname" value="<?=$info->fname;?>" /></td>
  </tr>
  <tr>
    <td>MiddleName</td>
    <td><input name="mname" type="text" id="mname" value="<?=$info->mname;?>" /></td>
  </tr>
  <tr>
    <td>LastName</td>
    <td><input name="lname" type="text" id="lname" value="<?=$info->lname;?>" /></td>
  </tr>
  <tr>
    <td>Email Address </td>
    <td><input name="email" type="text" id="email" value="<?=$info->email;?>" /></td>
  </tr>
  <tr>
    <td>
    </td>
    <td>
    <input type="submit" class="btn btn-small btn-primary" value="Save">
    <a href="{{ Request::root()}}/profile" class="btn btn-small">Cancel</a>
    <input name="op" type="hidden" id="op" value="1" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
</form>

@stop
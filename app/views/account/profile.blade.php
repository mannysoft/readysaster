@extends('template')
@section('content')



<h4 class="text-success">My Account Profile</h4>


<table width="100%" class="table">

  <tr>
    <td width="26%">Username</td>
    <td width="74%"><?=$info->username;?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><a href="{{Request::root()}}/change_password">Change Password</a></td>
  </tr>
  <tr>
    <td>Name </td>
    <td><?=ucwords($info->fname.' '.$info->mname.' '.$info->lname);?></td>
  </tr>
  <tr>
    <td>Email Address </td>
    <td><?=$info->email;?></td>
  </tr>
  <tr>
    <td><a href="profile/edit" class="btn btn-small btn-primary">Edit</a></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>


@stop
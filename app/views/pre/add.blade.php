@extends('template')
@section('content')


@if(count($errors) >= 1)
    <div class="alert alert-error">
        @foreach ($errors as $error)
            <div class="error">{{ $error }}</div>
        @endforeach
    </div>
@endif

<h1>{{ $title }}</h1>

<form method="post" enctype="multipart/form-data">
<table width="100%" class="table">
  <tr>
    <td width="10%">Title</td>
    <td><input name="title" type="text" id="title" value="{{Input::get('title')}}" /></td>
  </tr>
  <tr>
    <td>Photo</td>
    <td><input type="file" name="photo" id="photo" /></td>
  </tr>
  <tr>
    <td>Photo URL</td>
    <td><textarea name="photo_url" id="photo_url">{{Input::get('photo_url')}}</textarea></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>
      <input name="save" type="submit" class="btn btn-small btn-primary" value="Save"/>
      <a href="{{ Request::root()}}/admin/photo" class="btn btn-small">Cancel</a>
      <input name="op" type="hidden" id="op" value="1" /></td>
  </tr>

</table>

</form>
   
@stop

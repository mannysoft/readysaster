@extends('public')

@section('content')

<form method="post" action="{{Request::root()}}/restaurant/search">

<div class="input-append">
 <input name="restaurant" type="text" class="input-large" id="restaurant" value="{{Input::get('restaurant')}}" placeholder="Find: Restaurants, etc">
 <!--<input name="near" type="text" class="input-large" id="near" value="{{Input::get('near')}}" placeholder="Near: Boyle Heights, LA">-->
 <input type="submit"class="btn btn-primary" value="Find Latees">
 <!--<button class="btn" type="button">Find Latees</button>-->
<input name="op" type="hidden" value="1" />
</div>
</form>



<div class="container-fluid">
  <div class="row-fluid">
    <div class="span6">
      <!--Sidebar content-->
      
      
       <h4>Featured Latees Restaurants</h4>
          
          
        
          
          <div class="row-fluid">
            
            @foreach($featured as $f)
                <div class="span6">
                  <a href="{{Request::root()}}/restaurant/map/{{$f->id}}">
                  @if($f->photo != '')
                  	<img src="{{ Request::root()}}/{{$f->photo}}"  height="150" width="150">
                  @else
                  	<img src="http://placehold.it/150x150" class="img-polaroid">
                  @endif
                  </a>
                   <h5> <a href="{{Request::root()}}/restaurant/map/{{$f->id}}">{{ $f->title }}</a></h5>
                   <h6>{{ $f->description }}</h6>
                </div>
            @endforeach
            
          </div>
       
      
      
    </div>
    <div class="span6">
      <!--Body content-->
      
      
       <h4>Featured User Reviews</h4>
          
          <div class="row-fluid">
             @foreach($reviews as $r)
                <div class="span6">
                  <a href="{{Request::root()}}/restaurant/map/{{$r->restaurant->id}}">
                  @if($r->restaurant->photo != '')
                  	<img src="{{ Request::root()}}/{{$r->restaurant->photo}}" class="img-polaroid" height="150" width="150">
                  @else
                  	<img src="http://placehold.it/150x150" class="img-polaroid">
                  @endif
                  </a>
                   <h5><a href="{{Request::root()}}/restaurant/map/{{$r->restaurant->id}}">{{ $r->restaurant->title }}</a></h5>
                   <p>{{ $r->reviews }} </p> <p class="muted"> -- {{$r->user->fname}} {{$r->user->lname}}</p>
                </div>
            @endforeach
          </div>
          
      
    </div>
  </div>
  
  
  <div class="row-fluid">
    <div class="span12" align="right">
    	<a href="{{Request::root()}}/restaurant/search">View All</a>
    </div>
    </div>
  
  
  
  
</div>
@stop
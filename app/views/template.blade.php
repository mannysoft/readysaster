<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>App - {{ $title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="{{ Request::root()}}/assets/css/bootstrap.css" rel="stylesheet">
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
    <!-- CSS -->
	<style type="text/css">
    *{ padding:0; margin:0; }
    #map_div{
        width:100%;
        height:350px;
        border:6px solid #F4F4F4;
        margin:20px auto 0 auto;
    }
    </style>

    <link href="{{ Request::root()}}/assets/css/bootstrap-responsive.css" rel="stylesheet">
    
        <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="{{ Request::root()}}/assets/js/jquery.js"></script>

    <script src="{{ Request::root()}}/assets/js/bootstrap-transition.js"></script>
    <script src="{{ Request::root()}}/assets/js/bootstrap-alert.js"></script>
    <script src="{{ Request::root()}}/assets/js/bootstrap-modal.js"></script>
    <script src="{{ Request::root()}}/assets/js/bootstrap-dropdown.js"></script>
    <script src="{{ Request::root()}}/assets/js/bootstrap-scrollspy.js"></script>
    <script src="{{ Request::root()}}/assets/js/bootstrap-tab.js"></script>
    <script src="{{ Request::root()}}/assets/js/bootstrap-tooltip.js"></script>
    <script src="{{ Request::root()}}/assets/js/bootstrap-popover.js"></script>
    <script src="{{ Request::root()}}/assets/js/bootstrap-button.js"></script>
    <script src="{{ Request::root()}}/assets/js/bootstrap-collapse.js"></script>
    <script src="{{ Request::root()}}/assets/js/bootstrap-carousel.js"></script>
    <script src="{{ Request::root()}}/assets/js/bootstrap-typeahead.js"></script>
     <script src="{{ Request::root()}}/assets/js/sorter/jquery.tablesorter.js"></script>
     
     <script src="//maps.google.com/maps/api/js?sensor=true" type="text/javascript"></script>
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="{{ Request::root()}}/assets/js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{ Request::root()}}/assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{ Request::root()}}/assets/ico/apple-touch-icon-114-precomposed.png">
      <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{ Request::root()}}/assets/ico/apple-touch-icon-72-precomposed.png">
                    <link rel="apple-touch-icon-precomposed" href="{{ Request::root()}}/assets/ico/apple-touch-icon-57-precomposed.png">
                                   <link rel="shortcut icon" href="{{ Request::root()}}/assets/ico/favicon.png">
  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="{{ Request::root()}}/dashboard">Pinxala App</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li><a href="{{ Request::root()}}/officers">Local Disaster Managers</a></li>
              <li><a href="{{ Request::root()}}/pre-disaster">Exposure Data</a></li>
              <li><a href="{{ Request::root()}}/post-disaster">Post-Damage</a></li>
              <li><a href="{{ Request::root()}}/alert">Localized Alert</a></li>
              
              @if (Auth::user()->user_type == 'admin')
              <!--<li><a href="{{ Request::root()}}/online">Who's Online</a></li>-->
              @endif
              
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Account<b class="caret"></b></a>
                <ul class="dropdown-menu">
                  
                  <li><a href="{{ Request::root()}}/logout">Sign Out</a></li>
                </ul>
              </li>
              
             
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">

      <!--<h1>Bootstrap starter template</h1>
      <p>Use this document as a way to quick start any new project.<br> All you get is this message and a barebones HTML document.</p>
      
      Welcome, <b>{{ Auth::user()->username }}</b>!-->
         
      @yield('content')
      
   

    </div> <!-- /container -->



  </body>
</html>
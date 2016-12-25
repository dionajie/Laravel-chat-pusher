<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Twitter Chat</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->

  <link rel="stylesheet" href="{{ URL::asset('admin/css/AdminLTE.min.css') }}">
  <link rel="stylesheet" href="{{ URL::asset('admin/css/skins/skin-blue.min.css') }}">
  
  <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
  <script src="https://cdn.rawgit.com/samsonjs/strftime/master/strftime-min.js"></script>
  <script src="//js.pusher.com/3.0/pusher.min.js"></script>

    <script>
        // Ensure CSRF token is sent with AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Added Pusher logging
        Pusher.log = function(msg) {
            console.log(msg);
        };
    </script>

  <style type="text/css">
      .small-box .icon {
        top: 0px !important;
      }

      .sidebar-menu .treeview-menu>li>a {
        font-size: 15px;
        padding: 10px 15px 10px 40px;
      }

      .navbar-nav>.user-menu>.dropdown-menu>li.user-header {
        height: 70px !important;
      }

      
  </style>

</head>

<body class="hold-transition skin-blue sidebar-mini">
 
<div class="wrapper">

  <header class="main-header">
    <a href="{{ url('/') }}" class="logo">
      <span class="logo-mini"><b>TC</b></span>
      <span class="logo-lg"><b>Twitter Chat</b></span>
    </a>

    <nav class="navbar navbar-static-top">
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
    
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="{{ Auth::user()->avatar }}" class="user-image" alt="User Image">
              <span class="hidden-xs">{{ Auth::user()->name }}</span>
            </a>
            <ul class="dropdown-menu">

              <li class="user-header">
                <p>
                  {{ Auth::user()->name }}
                  <small></small>
                </p>
              </li>
              <li class="user-footer">
                  <div class="pull-left">
                    <a href="{{ url('/dashboard') }}" class="btn btn-default btn-flat"><i class="fa fa-tachometer" aria-hidden="true"></i> Dashboard</a>
                  </div>
                  <div class="pull-right">
                    <a href="{{ url('/logout') }}" class="btn btn-default btn-flat"> <i class="fa fa-sign-out" aria-hidden="true"></i> Log out</a>
                  </div>
              </li>
            </ul>
          </li>
         
        </ul>
      </div>
    </nav>
  </header>

  <aside class="main-sidebar">
    <section class="sidebar" style="font-size: 17px;">
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{ Auth::user()->avatar }}" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>{{ Auth::user()->name }}</p>
          <a href="#"><i class="fa fa-circle text-success"></i>Online</a>
        </div>
      </div>
 
      <ul class="sidebar-menu">
        <li class="header">Menu</li>
        <li>
          <a href="{{ url('/chat') }}"><i class="fa fa-comments"></i> <span>General</span></a>
        </li>
    </ul>
    </section>
 
  </aside>

  @yield('content')

</div>



</body>
</html>


<!-- JavaScripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
<script type="text/javascript" src="{{ URL::asset('admin/plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('admin/plugins/fastclick/fastclick.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('admin/js/app.min.js') }}"></script>
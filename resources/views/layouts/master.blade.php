<!DOCTYPE html>
<html lang="en">
@include('layouts.header') 
<body>
  <div id="wrap">
@include('layouts.nav') 
    <div class="container">
@yield('content') 
    </div>
@include('layouts.footer') 
  </div>
</body>
</html>
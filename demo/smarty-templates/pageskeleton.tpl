<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>{$title}</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <!-- Site Global CSS -->
    <link href="assets/css/main.css" rel="stylesheet"> 
  <!-- Jquery and Bootstrap Javascripts -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
  <style>
	#footer {
	  position: absolute;
	  bottom: 0;
	  width: 100%;
	  height: 2.5rem;            /* Footer height */
	}
</style>
  </head>
<body>
  <header id="header" class="header d-flex align-items-center" style="border-bottom: 2px solid black;">
    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">
      <a href="index" class=" d-flex align-items-center ">
        <h1 class="head-title">{$title}</h1>
      </a>
      <a href="#" class="mx-2 js-search-open">
        <span class=""></span>
      </a>
      <i class="bi bi-list mobile-nav-toggle"></i>
      <div class="search-form-wrap js-search-form-wrap">
        <form action="search-result.html" class="search-form">
          <span class="icon bi-search"></span>
          <input type="text" placeholder="Search" class="form-control">
          <button class="btn js-search-close">
            <span class="bi-x"></span>
          </button>
        </form>
      </div>
    </div>
    </div>
  </header>
  <main id="main">
    <!-- Content Here -->
    {$content}
  </br>
  </main>
  <footer id="footer" class="footer" style=" color:black;">
    <div class="footer-legal"style="border-top: 2px solid black;">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-6 text-center text-md-start mb-md-0">
            <div class="copyright text-center">
             Copyright © {$year} {$footertext}
            </div>
          </div>
        </div>
      </div>
   </div>
  </footer>
</body>
</html>
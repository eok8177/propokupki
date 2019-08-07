<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
  content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" href="favicon.ico" type="image/x-icon"> 
  <link rel="stylesheet" type="text/css" href="/css/app.css">
  <title>Pro Pokupki</title>
</head>

<body>
  <div id="app"></div>

  <script src="{{ asset('js/app.js') }}"></script>

  <!-- <script src="/plugins/jquery-1.8.1.js"></script> -->
  <!-- <script src="/plugins/slick/slick.min.js"></script> -->
  <script src="/js/app.js"></script>

  <script>
    WebFontConfig = {
      google: { families: [ 'Roboto:400' ] }
    };
    (function() {
      var wf = document.createElement('script');
      wf.src = 'https://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
      wf.type = 'text/javascript';
      wf.async = 'true';
      var s = document.getElementsByTagName('script')[0];
      s.parentNode.insertBefore(wf, s);
    })(); 
  </script>

  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-145110644-1"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-145110644-1');
  </script>

</body>
</html>

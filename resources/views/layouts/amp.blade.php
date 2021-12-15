<!DOCTYPE html>
<html amp lang="en">
  <head>
    <meta charset="utf-8">
    <script async src="https://cdn.ampproject.org/v0.js"></script>
    <link rel="canonical" href="http://localhost/es/documentation/guides-and-tutorials/start/create/basic_markup">
    <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
    <title>Go Guy Travel</title>
    <style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>
    <style amp-custom>
      a{text-decoration: none;}
      amp-sidebar{ padding: 10px }
      amp-sidebar a{display: block;width: 100%;padding:5px; text-align: center;}
      header{
        padding: 10px;
        display: flex;
      }
    </style>
    <script async custom-element="amp-sidebar" src="https://cdn.ampproject.org/v0/amp-sidebar-0.1.js"></script>
    <script async custom-element="amp-carousel" src="https://cdn.ampproject.org/v0/amp-carousel-0.1.js"></script>
  </head>
  <body>
        <header>
          <amp-img src="{{SettingsComponent::getLogoUrl()}}" width="140" height="55" layout="fixed" class="my0 mx-auto " alt=""></amp-img>
        </header>
        <amp-sidebar id="sidebar"
          class="sample-sidebar"
          layout="nodisplay"
          side="right">
          <a href="#">HOME</a>
        </amp-sidebar>
        @yield('content')
  </body>
</html>
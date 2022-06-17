<!DOCTYPE html>
<html>
  <head>
    <meta property="og:title" content="Oyechef">
    <meta property="og:description" content="Oyechef Food">
    <meta property="og:image" content="https://oyechef.com/oyechef-food/public/frontend/img/location-map.png">
    <title>Hiding Map Features With Styling</title>
    <style type="text/css">
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
#map {
  height: 100%;
}

/* Optional: Makes the sample page fill the window. */
html,
body {
  height: 100%;
  margin: 0;
  padding: 0;
}

.map-control {
  background-color: #fff;
  border: 1px solid #ccc;
  box-shadow: 0 2px 2px rgba(33, 33, 33, 0.4);
  font-family: "Roboto", "sans-serif";
  margin: 10px;
  padding-right: 5px;
  /* Hide the control initially, to prevent it from appearing
           before the map loads. */
  display: none;
}

/* Display the control once it is inside the map. */
#map .map-control {
  display: block;
}

.selector-control {
  font-size: 14px;
  line-height: 30px;
  vertical-align: baseline;
}
    </style>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <!-- jsFiddle will insert css and js -->
  </head>
  <body>
    <a href="https://www.reddit.com/submit?url=https://www.oyechef.com/oyechef-food/menu/sJJom4cUuFVxdBO8MrA5rg==" class="btn btn-success" target="_blank">Reddit</a><br>
    <a href="https://reddit.com/submit?url=https://www.oyechef.com/oyechef-food/menu/dgDTmYIYSMk6eCqkCOOfsg==&title=title" target="_blank">Reddit New</a>
  </body>
</html>
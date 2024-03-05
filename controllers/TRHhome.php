<!DOCTYPE html>
<html>
<head>
<title>The Racing Hub</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
<style>
body,h1,p {font-family: "Raleway", sans-serif; color: black;}
body, html {height: 100%}
.bgimg {
  background-image: url('newmarket.jpeg');
  min-height: 100%;
  background-position: center;
  /* Adjust the background size to zoom out */
  background-size: 100%;
}
/* Style for the "COMING SOON" heading */
.coming-soon {
  font-weight: bold;
  text-shadow: 
    2px 2px 0 white,   /* top-left */
    -2px -2px 0 white,  /* top-right */
    2px -2px 0 white,   /* bottom-left */
    -2px 2px 0 white;   /* bottom-right */
}
</style>
</head>
<body>

<div class="bgimg w3-display-container w3-animate-opacity w3-text-white">
  <!-- Make the title black -->
  <div class="w3-display-topleft w3-padding-large w3-xlarge" style="color: black;">
    THE RACING HUB
  </div>
  <div class="w3-display-middle">
    <!-- Apply styles to the "COMING SOON" heading -->
    <h1 class="w3-jumbo w3-animate-top coming-soon">COMING SOON</h1>
    <hr class="w3-border-grey" style="margin:auto;width:40%">
    <p class="w3-large w3-center">50 days left</p>
  </div>
  <!-- Make "Powered by DH" text black -->
  <div class="w3-display-bottomleft w3-padding-large" style="color: black;">
    Powered by <a href="" target="_blank">DH</a>
  </div>
</div>

</body>
</html>


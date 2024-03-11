<!DOCTYPE html>
<html>
<head>
<title>The Racing Puzzle</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
<style>
body, h1, p {
    font-family: "Raleway", sans-serif;
    color: black; /* Change font color to black */
}

body, html {
    height: 100%;
    margin: 0; /* Remove default margin */
}

.bgimg {
    min-height: 100%;
    background-image: url('../puzzle.png');
    background-size: 40%;
    background-position: center;
    display: flex; /* Use flexbox for vertical and horizontal centering */
    justify-content: center; /* Center horizontally */
    align-items: center; /* Center vertically */
}

.container {
    text-align: center; /* Center text within container */
    background-color: rgba(255, 255, 255, 0.8); /* Set container background to white with semi-transparency */
    padding: 20px; /* Add padding */
    border-radius: 10px; /* Add some rounding to the corners */
}

.logo {
    width: 200px; /* Set logo size */
    height: auto; /* Maintain aspect ratio */
}

.buttons a {
    margin: 10px; /* Add spacing between buttons */
    background-color: black; /* Change button background color to black */
    color: white; /* Change button text color to white */
    border-radius: 20px; /* Round the buttons */
    padding: 10px 20px; /* Add padding to the buttons */
    transition: background-color 0.3s, color 0.3s; /* Smooth transition effect */
}

.buttons a:hover {
    background-color: red !important; /* Change background color to red on hover */
    color: white !important; /* Change text color to white on hover */
}


/* Make "Powered by DH" text black */
.w3-display-bottomleft {
    color: black; /* Change text color to black */
}
</style>
</head>
<body>

<div class="bgimg">
  <div class="container">
    <img src="../trp_logo.png" alt="Your Image" class="logo"> <!-- Apply logo class -->
    <h1 class="w3-xlarge">THE RACING PUZZLE</h1>
    <div class="buttons">
      <a href="login.php" class="w3-button">Login</a> <!-- Remove w3-black class -->
      <a href="registration.php" class="w3-button">Register</a> <!-- Remove w3-black class -->
    </div>
  </div>
</div>

<div class="w3-display-bottomleft w3-padding-large"> <!-- Adjust as needed -->
    Powered by <a href="" target="_blank">DH</a>
</div>

</body>
</html>




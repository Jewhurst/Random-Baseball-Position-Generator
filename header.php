<?php 
require 'global.php' ;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=yes">
    <meta name="description" content="Baseball position generator. Randomly generate positions based on players and innings. Just add player names and print your lineup or roster">
    <meta name="author" content="James Jewhurst">
    <title>Baseball Position: Generator</title>
	<meta property="og:title" content="Baseball Position Generator">
	<meta property="og:description" content="Randomly generate positions based on players and innings.">
	<meta property="og:image" content="http://baseball.playpositions.com//i/bplogo.png">
	<meta property="og:url" content="http://baseball.playpositions.com/">
	<meta name="twitter:title" content="Baseball Position Generator">
	<meta name="twitter:description" content="Randomly generate positions based on players and innings.">
	<meta name="twitter:image" content="http://baseball.playpositions.com/i/bplogo.png">
	<meta name="twitter:card" content="http://baseball.playpositions.com/">
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.8&appId=1206938052658772";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
	
    <link rel="icon" href="favicon.ico">	
	<link href="https://fonts.googleapis.com/css?family=Rock+Salt" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Questrial|Quicksand|Ubuntu" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Titillium+Web:900" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <link href="style.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
	
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-93513354-1', 'auto');
  ga('send', 'pageview');

</script>	
  </head>

  <body>
    <div class="container">

 <!--nav class="navbar navbar-light bg-faded rounded navbar-toggleable-md">
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#containerNavbar" aria-controls="containerNavbar" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="/baseball/">Roster Builder</a>

        <div class="collapse navbar-collapse" id="containerNavbar">
          <ul class="navbar-nav mr-auto">
		  <li class="nav-item">
			<a class="nav-link" style="text-align:right;" href="reset.php">Reset</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link " href="roster.php?playercount=9&inningcount=9">9 Players/Inning</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link " href="roster.php?playercount=10&inningcount=6">6 Players/Inning</a>
		  </li>
          </ul>
        </div>
      </nav-->
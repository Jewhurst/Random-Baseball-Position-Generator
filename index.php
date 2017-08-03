<?php 
session_start();
//if(isset($_SESSION['thencode'])){unset($_SESSION['thencode']);}
unset($_SESSION['thencode']);
session_unset();
require 'header.php';
//$_SESSION['thencode'] = '';
echo $_SESSION['thencode'];
if($_GET){
	foreach ($_GET as $key => $value) {
		switch ($key) {
			case 'msg' :			
				switch ($value) {
					case 'nodata' :			
						$message = '<div class="alert alert-danger" role="alert"><strong>Hang on a sec!</strong> You need to select some things first.</div>';
						break;
					case 'error' :			
						$message = '<div class="alert alert-danger" role="alert"><strong>Uh oh</strong> Something went wrong. <a style="text-decoration:underline;" href="#" data-toggle="modal" data-target="#playerList">Try again</a>.</div>';
						break;
					default :
						break;
				}
			break;
			default :
				break;
		}
	}	
}
?>

	 <div class="" style="">
        <!--h1 class="display-3">Baseball Position Randomizer</h1--> 
        <div class="row">
			<div class="col-md-6" >
				<center><a href="<?php echo HOME; ?>"><img class="img-responsive" src="i/bplogo.png" alt="" width="80%" ></a></center>
				<h3 class="tcenter" style="">Welcome to PlayPositions.com<br><b>Baseball Position Generator</b></h3>
				<p class="lead1 text-justify" style="padding:0 20px 20px 20px;">Easily build a random baseball position sheet and give each kid time to play each position. If players have to sit out they will sit out as fairly as possible.</p>
				<p class="lead1 text-justify" style="padding:0 20px 20px 20px;">This is great for T-Ball, coach and machine pitch lineups, or if you just need a quick list for a game with some friends! Select between 6 - 18 players and select 6 - 9 innings, and give each person a fair turn to learn.</p>

			</div>
			<div class="col-md-6">
				<div class="card" style="">
				  <div class="card-block">
				  <img class="card-img-top" src="i/bg.jpg" alt="Baseball Position Generator" width="100%" style="padding-bottom:10px;" />
				  <div style="padding:0 20px 20px 20px;">
					<h4 class="clr-brorange rock lh150 tcenter">Quick and easy. Free to use.</h4>
					<h5 class="clr-brorange rock lh150 tcenter"><b>Giving everyone a turn to learn</b></h5>
					<br>
					<h4 class="card-title clr-brorange tcenter" style="line-height:1.5em;">Build a lineup in just 3 steps</h4>
				</div>
					
					<?php if(isset($message)){ echo '<p class="card-text">'.$message.'</p>'; } ?>					
				  </div>
				  <ul class="list-group list-group-flush">
					<li class="list-group-item rock tcenter">1. Select how many players and innings</li>
					<li class="list-group-item rock tcenter">2. Add Player Names</li>
					<li class="list-group-item rock tcenter">3. View Lineup and Print</li>
				  </ul>
				  <div class="card-block">
					<div class="row">
						<div class="col-md-6 col-sm-12">
							<a style="width:100%;" class="btn bgclr-red clr-white animated fadeIn mousehand centersmall" data-toggle="modal" data-target="#playerList"><span class="clr-white boldme">GET STARTED</span></a>
						</div>
						<div class="col-md-6 col-sm-12">
							<a class="btn bgclr-brorange clr-white animated fadeIn mousehand centersmall" style="width:100%;" href="https://docs.google.com/spreadsheets/d/1J2Nz_cjCh0dWdKgy6B9QTjiJHEznsY-P12mGjWP2fNc/pubhtml" target="_blank">
								<span class="clr-white boldme">PRINT YOUR OWN</span>
							</a>
						</div>
					</div>
					<br>
					<center><span style="font-size:1.4em;">Share on&nbsp;&nbsp;</span><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo getPageURL(); ?>" target="_blank"><i class="fa fa-facebook-square clr-facebook fa-2x no-print" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;<a class=" no-print" style="" href="https://twitter.com/intent/tweet?text=View%20lineup%20at%20<?php echo getPageURL(); ?>" data-size="large" target="_blank"><i class="fa fa-twitter-square fa-2x clr-twitter" aria-hidden="true"></i></a></center>
				  </div>
				</div>
				  
			</div>
			
		</div>
		<br class="clear">
		<br class="clear">
		<!--div class="row">
			<div class="col-md-8"><img src="i/steps.png" class="img-responsive" style="width:100%;"/></div>
			<div class="col-md-4"><img src="i/batball.png" class="img-responsive" style="width:50%;float:right;" /></div>
		</div-->

<div class="modal fade" id="playerList" tabindex="-1" role="dialog" aria-labelledby="playerListTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="playerListTitle">Select how many players and innings</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
	  <div class="col-md-12">
			<center>
			<div>
			<form action="roster.php" method="POST" class="form">
			  <div class="row">
			  <div class="col">
			  <label class="mr-sm-2 boldme" for="playercount">Players</label>
			  <select class="custom-select mb-2 mr-sm-2 mb-sm-0" id="playercount" name="playercount">
				<option disabled selected>Choose...</option>
				<?php for ($y = 6; $y <= 18; $y++) {echo '<option value="'.$y.'">'.$y.'</option>';}?>
			  </select>
			  </div>
			  <div class="col">
			  <label class="mr-sm-2 boldme" for="inningcount">Innings</label>
			  <select class="custom-select mb-2 mr-sm-2 mb-sm-0" id="inningcount" name="inningcount" disabled>
				<option disabled selected>Choose...</option>
				<?php for ($x = 6; $x <= 9; $x++) {echo '<option value="'.$x.'">'.$x.'</option>';}?>
			  </select>
			  </div>
			  </div>
				<br class="clear"><br>
			  <button type="submit" id="submit_main" name="submit_main" class="btn bgclr-brorange clr-white mousehand">Build Roster</button>
			</form>
			</div>
			</center>
	</div>
      </div>
    </div>
  </div>
</div>  
<div class="modal fade" id="oddwarning" tabindex="-1" role="dialog" aria-labelledby="oddwarningLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="oddwarningLabel">You're about to use less than 9 innings!</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body ">
	  <h4>Some problems you may face:</h4>
        <ul>
			<li>Some players may not play each position.</li>
			<li>If players are sitting out, some players may sit out more than others unevenly. However, each player will sit out fairly unless there is an odd number of players or inning</li>
		</ul>
		<p></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn bgclr-red clr-white " style="width:100%" data-dismiss="modal" >I UNDERSTAND</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="plywarning" tabindex="-1" role="dialog" aria-labelledby="plywarningLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="plywarningLabel">You're about to use more or less than 9 players!</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body ">
	  <h4>Some problems you may face using under 9 players:</h4>
        <ul>
			<li>Not each position will be utilized.</li>
			<li>However, key positions are used under 9 players:
				<ul>
					<li>6 players use positions C, P, 1<sup>st</sup>, 2<sup>nd</sup>, 3<sup>rd</sup>, SS</li>
					<li>7 players use positions C, P, 1<sup>st</sup>, 2<sup>nd</sup>, 3<sup>rd</sup>, SS, CF</li>
					<li>8 players use positions C, P, 1<sup>st</sup>, 2<sup>nd</sup>, 3<sup>rd</sup>, SS, LF, RF</li>
				</ul>
			</li>
		</ul>
	   <h4>Some problems you may face using over 9 players:</h4>
        <ul>
			<li>Players will sit out as typically only 9 are allowed on field.</li>
			<li>Player will each sit out the fair amount of times in 9 innings.</li>
			<li>Some players may sit out more than others unevenly. However, each player will sit out fairly unless there is an odd number of players or inning</li>
		</ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn bgclr-red clr-white " style="width:100%" data-dismiss="modal" >I UNDERSTAND</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="plyinnwarning" tabindex="-1" role="dialog" aria-labelledby="plyinnwarningLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="plyinnwarningLabel">Important Information</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body ">
	  <h4>Some problems you may face using less than 9 innings:</h4>
        <ul>
			<li>Some players may not play each position.</li>
			<li>If players are sitting out, some players may sit out more than others unevenly. However, each player will sit out fairly unless there is an odd number of players or inning</li>
		</ul>
	  <h4>Some problems you may face using under 9 players:</h4>
        <ul>
			<li>Not each position will be utilized.</li>
			<li>However, key positions are used under 9 players:
				<ul>
					<li>6 players use positions C, P, 1<sup>st</sup>, 2<sup>nd</sup>, 3<sup>rd</sup>, SS</li>
					<li>7 players use positions C, P, 1<sup>st</sup>, 2<sup>nd</sup>, 3<sup>rd</sup>, SS, CF</li>
					<li>8 players use positions C, P, 1<sup>st</sup>, 2<sup>nd</sup>, 3<sup>rd</sup>, SS, LF, RF</li>
				</ul>
			</li>
		</ul>
	   <h4>Some problems you may face using over 9 players:</h4>
        <ul>
			<li>Players will sit out as typically only 9 are allowed on field.</li>
			<li>Player will each sit out the fair amount of times in 9 innings.</li>
			<li>Some players may sit out more than others unevenly. However, each player will sit out fairly unless there is an odd number of players or inning</li>
		</ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn bgclr-red clr-white" style="width:100%" data-dismiss="modal" >I UNDERSTAND</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
document.getElementById('playercount').onchange = function answers2() {    
	var player = document.getElementById("playercount");
	var answer2 = player.options[player.selectedIndex].value;
	if(answer2 != ""){document.getElementById("inningcount").disabled = false;}   
}

</script>

<script type="text/javascript">

document.getElementById('inningcount').onchange = function answers() {    
	var inning = document.getElementById("inningcount");
	var answer = inning.options[inning.selectedIndex].value;
	var player = document.getElementById("playercount");
	var answer2 = player.options[player.selectedIndex].value;
	
	if(answer != "9" && answer2 == "9"){
		$('#oddwarning').modal('show');
	}else if(answer == "9" && answer2 != "9"){
		$('#plywarning').modal('show');
	} else if(answer !="9" && answer2 !="9"){
		$('#plyinnwarning').modal('show');
	}
}


</script>

<?php require 'footer.php'; ?>
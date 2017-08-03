<?php 
require 'header.php';
//require 'pdfcrowd.php';
?>
<p class="no-print animated lightSpeedIn"><a href="/"><i class="fa fa-home fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;<span class="rock">Home</span></a><span style="float:right;"><a href="<?php echo HOME; ?>">Build another lineup</a></span></p>
<?php

if(isset($_POST['submit_main']) && isset($_POST['playercount']) && isset($_POST['inningcount'])){
	extract($_POST);
	$_SESSION['playercount'] = $playercount;
	$_SESSION['inningcount'] = $inningcount;
		for($z=1;$z<=$playercount;$z++){
			$t = 'p'.$z;
		$form_output .= '
			<div class="form-group row">
				<label for="'.$t.'" class="col-md-1 col-sm-12 col-form-label " style="">#'.$z.' </label>
				<div class="col-md-11 col-sm-12">
					<input type="text" class="form-control" id="'.$t.'" name="'.$t.'" value="'.(isset($_SESSION[$t]) ? $_SESSION[$t] : "").'">
				</div>
			</div>
		';		
	}
?>
	
 <div class="col-12"> 
		<div class="">
		  <form action="roster.php" method="POST" class="form"> 
		  <br>
		  <h2 class="tcenter">  ENTER PLAYER NAMES</h2><br>
		  
			 <?php echo $form_output ;?>

			<div class="modal-footer">
					<div class="col-md-12">
						<button type="submit" id="submit_players" name="submit_players" class="btn btn-warning">Save changes</button>
					</div>
			</div>
			</form>
		</div>
</div>

<!-- Modal -->
<div class="modal fade" id="progressbar" tabindex="-1" role="dialog" aria-labelledby="progressbarTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
  <div class="modal-header">
        <h5 class="modal-title">Building your lineup. It may take a second.</h5>
      </div>
  <div class="modal-content">
      <div class="modal-body">
            <div class="progress">
			  <div class="progress-bar progress-bar-striped progress-bar-animated bg-warning" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div> 
			</div>
      </div>
    </div>

  </div>
</div>
	
<?php	
}elseif(isset($_POST['submit_players'])){

extract($_POST);
function fairRotation($roster=[],$positions=[],$rotations=9,$off="<strong>X</strong>"){
	$start = microtime(true);
    $roster_count=sizeof($roster);
    $positions_count=sizeof($positions);
    //echo "<div>roster_count=$roster_count, positions_count=$positions_count</div>";
    $ons_avg=$positions_count/$roster_count;
    $ons_max=ceil($ons_avg);
    $ons_min=floor($ons_avg);
    //echo "<div>ons_avg=$ons_avg, ons_max=$ons_max, ons_min=$ons_min</div>";
    $off_avg=($roster_count-$positions_count)*$rotations/$roster_count;
    $off_max=ceil($off_avg);
    $off_min=floor($off_avg);
    //echo "<div>off_avg=$off_avg, off_max=$off_max, off_min=$off_min</div><br>";
    $positions=array_pad($positions,$roster_count,$off);  // sync positions with roster using sub identifier    
    $positions_count=sizeof($positions);    // overwrite with updated count
    for($r=0; $r<$rotations; ++$r){
        shuffle($positions);
        $result[$r]=$positions;
    }
    //$color_result=$result;
    // unfiltered result:
    /*echo "<table border=1>";
        echo "<tr><th>#</th><th>",implode("</th><th>",$roster),"</th></tr>";
        foreach($result as $key=>$row){
            echo "<tr><th>",($key+1),"</th><td>",implode("</td><td>",$row),"</td></tr>";
        }
    echo "</table>";*/    

    // Assess result and address conflicts...
    $iterations=0;
    $fair="?";
    $must_drop_count=0;
    $must_gain_count=0;
    while($fair!="true" && $iterations<1000){
        $must_gain=$must_drop=$may_gain=$may_drop=[];  // reset
        for($c=0; $c<$roster_count; ++$c){  // triage each column
            $col=array_column($result,$c);
            $val_counts[$c]=array_merge(array_fill_keys($positions,0),array_count_values($col));
            foreach($val_counts[$c] as $pos=>$cnt){
                if(($pos!=$off && $cnt<$ons_min) || ($pos==$off && $cnt<$off_min)){
                    $must_gain[$c][$pos]=array_keys($col,$pos);  // column/player must gain this position, but not where row = value(s)
                }elseif(($pos!=$off && $cnt>$ons_max) || ($pos==$off && $cnt>$off_max)){
                    $must_drop[$c][$pos]=array_keys($col,$pos);  // column/player must drop this position, but only where row = value(s)
                }elseif(($pos!=$off && $cnt<$ons_max) || ($pos==$off && $cnt<$off_max)){
                    $may_gain[$c][$pos]=array_keys($col,$pos);  // column/player may gain this position, but not where row = value(s)
                }elseif(($pos!=$off && $cnt>$ons_min) || ($pos==$off && $cnt>$off_min)){
                    $may_drop[$c][$pos]=array_keys($col,$pos);  // column/player may drop this position, but only where row = value(s)
                }
            }
        }

        if(sizeof($must_gain)==0 && sizeof($must_drop)==0){
            $fair="true";
        }elseif(sizeof($must_drop)==$must_drop_count && sizeof($must_gain)==$must_gain_count){
            shuffle($positions);
            if($must_drop_count>0){
                $result[current(current(current($must_drop)))]=$positions;
            }else{
                $result[current(current(current($must_gain)))]=$positions;
            }
        }else{
            $must_drop_count=sizeof($must_drop);
            $must_gain_count=sizeof($must_gain); 

            foreach($must_drop as $d1col=>$d1array){  
                ++$iterations;
				foreach($d1array as $d1pos=>$d1keys){
					foreach(array_diff_key($must_drop,array($d1col=>"")) as $d2col=>$d2array){  // dual-solution swap
						foreach($d2array as $d2pos=>$d2keys){
							if($d1pos!=$d2pos && (isset($must_gain[$d1col][$d2pos]) || isset($may_gain[$d1col][$d2pos]))){
								foreach($d2keys as $row){
									//echo "<div>checking {$roster[$d2col]}'s row($row) holding $d2pos vs {$roster[$d1col]}'s $d1pos ";
									//var_export($d1keys);
									//echo "</div>";
									if(in_array($row,$d1keys)){
										//echo "<div>row match on $row between {$roster[$d1col]} & {$roster[$d2col]}</div>";
										if(isset($must_gain[$d1col][$d2pos])){
											$result[$row][$d1col]=$d2pos;
											$result[$row][$d2col]=$d1pos;
											break(5);
										}elseif(isset($may_gain[$d1col][$d2pos])){
											$result[$row][$d1col]=$d2pos;
											$result[$row][$d2col]=$d1pos;
											break(5);
										}else{
											//echo "<div>No Eligible Swap: {$roster[$d1col]} doesn't need/want $d2pos @ row$row";
											//var_export(array_merge(array(),$must_gain[$d1col],$may_gain[$d1col]));
											//echo "</div>";
										}
									}
								}
							}
						}
					}                   
				}
            }
        }
    }
    if($fair=="true"){
        //echo "<div>FAIR! after $iterations adjustments</div>";
		/*$duration = microtime(true) - $start;
		$durationtime = date("s",$duration);
		if($durationtime < 1){
			echo 'It took less than a second to build';
		}elseif($durationtime == 1){
			echo 'It took about 1 second to build';
		}else{
			echo "It took $durationtime seconds to build.";			
		}*/
        return $result;  // $color_result for color
    }else{
        //echo "Runaway Deadlock, Call Full Rerun<br>";
        return false;
    }
}

if(isset($_SESSION['inningcount']) && isset($_SESSION['playercount'])){
		switch ($_SESSION['playercount']) {
			case 6 : $positions=array('P','C','1st','2nd','SS','3rd');
					 $roster=array($p1,$p2,$p3,$p4,$p5,$p6); 
					break;		
			case 7 : $roster=array($p1,$p2,$p3,$p4,$p5,$p6,$p7);
					 $positions=array('P','C','1st','2nd','SS','3rd','CF');
					break;
			case 8 : $roster=array($p1,$p2,$p3,$p4,$p5,$p6,$p7,$p8);
					 $positions=array('P','C','1st','2nd','SS','3rd','LF','RF');
					break;
			case 9 : $roster=array($p1,$p2,$p3,$p4,$p5,$p6,$p7,$p8,$p9);
					 $positions=array('P','C','1st','2nd','SS','3rd','LF','CF','RF');
					break;
			case 10 : $roster=array($p1,$p2,$p3,$p4,$p5,$p6,$p7,$p8,$p9,$p10);
					  $positions = array('P','C','1st','2nd','SS','3rd','LF','CF','RF');
					break;
			case 11 : $roster=array($p1,$p2,$p3,$p4,$p5,$p6,$p7,$p8,$p9,$p10,$p11);
					  $positions = array('P','C','1st','2nd','SS','3rd','LF','CF','RF');
					break;
			case 12 : $roster=array($p1,$p2,$p3,$p4,$p5,$p6,$p7,$p8,$p9,$p10,$p11,$p12);
					  $positions = array('P','C','1st','2nd','SS','3rd','LF','CF','RF');
					break;	
			case 13 : $roster=array($p1,$p2,$p3,$p4,$p5,$p6,$p7,$p8,$p9,$p10,$p11,$p12,$p13);
					  $positions = array('P','C','1st','2nd','SS','3rd','LF','CF','RF');
					break;	
			case 14 : $roster=array($p1,$p2,$p3,$p4,$p5,$p6,$p7,$p8,$p9,$p10,$p11,$p12,$p13,$p14);
					  $positions = array('P','C','1st','2nd','SS','3rd','LF','CF','RF');
					break;	
			case 15 : $roster=array($p1,$p2,$p3,$p4,$p5,$p6,$p7,$p8,$p9,$p10,$p11,$p12,$p13,$p14,$p15);
					  $positions = array('P','C','1st','2nd','SS','3rd','LF','CF','RF');
					break;	
			case 16 : $roster=array($p1,$p2,$p3,$p4,$p5,$p6,$p7,$p8,$p9,$p10,$p11,$p12,$p13,$p14,$p15,$p16);
					  $positions = array('P','C','1st','2nd','SS','3rd','LF','CF','RF');
					break;	
			case 17 : $roster=array($p1,$p2,$p3,$p4,$p5,$p6,$p7,$p8,$p9,$p10,$p11,$p12,$p13,$p14,$p15,$p16,$p17);
					  $positions = array('P','C','1st','2nd','SS','3rd','LF','CF','RF');
					break;	
			case 18 : $roster=array($p1,$p2,$p3,$p4,$p5,$p6,$p7,$p8,$p9,$p10,$p11,$p12,$p13,$p14,$p15,$p16,$p17,$p18);
					  $positions = array('P','C','1st','2nd','SS','3rd','LF','CF','RF');
					break;									
			default : break;
		}
		$rotations = $_SESSION['inningcount'];
		$result=false; 
		while(!$result){
			$result=fairRotation($roster,$positions,$rotations);
		}
		$tablehtml = '';
		$tablehtml .= '<div class="animated lightSpeedIn">
				<table class="table table-striped">
				<center><img class="img-responsive printonly" src="i/bplogo.png" alt="" width="30%" ></center>
			 ';
		$tablehtml .= "<tr><th>Inning</th><th>";
		$tablehtml .= implode("</th><th>",$roster);
		$tablehtml .= "</th></tr>";
			foreach($result as $key=>$row){
				$tablehtml .= "<tr><th>";
				$tablehtml .= ($key+1);
				$tablehtml .= "</th><td>";
				$tablehtml .= implode("</td><td>",$row);
				$tablehtml .= "</td></tr>";
			}
		$tablehtml .= "</table></div>";
		echo $tablehtml;

		if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){$maker = $_SERVER['HTTP_X_FORWARDED_FOR'];}elseif($_SERVER['REMOTE_ADDR']){$maker = $_SERVER['REMOTE_ADDR'];}
?>
		
				<button type="button" class="btn btn-danger no-print mousehand centersmall" data-toggle="modal" data-target="#playerList">UPDATE PLAYERS</button> &nbsp;&nbsp;
				<button type="button" class="btn btn-success no-print mousehand centersmall" onClick="window.print()">PRINT LINEUP</button><br class="clear">
		

<?php	
		if($_SESSION['thencode'] != ''){
			$ifcode = true;
			$thencode = $_SESSION['thencode'];
		}else{
			$ifcode = false;
			$thencode = '';
		}
		if($retslug = $data->addLineup($maker,$tablehtml,$ifcode,$thencode)){
			$_SESSION['thencode'] = $retslug;
			/* try{   
				// create an API client instance
				$client = new Pdfcrowd("jwjewhurst", "526ca58b11d17d70ba4822852c1099ff");

				// convert a web page and store the generated PDF into a $pdf variable
				$uriToConvert = HOME.$retslug;
				$pdf = $client->convertURI($uriToConvert);

				// set HTTP response headers
				header("Content-Type: application/pdf");
				header("Cache-Control: max-age=0");
				header("Accept-Ranges: none");
				header("Content-Disposition: attachment; filename=\"google_com.pdf\"");

				// send the generated PDF 
				//echo $pdf; 
			}
			catch(PdfcrowdException $why)
			{
				echo "Pdfcrowd Error: " . $why;
			}*/
			?>
			<br class="clear">
			<div class="row" style="width:100%;"><code id="code"><?php echo HOME.$retslug; ?></code>&nbsp;&nbsp;<button class="btn btn-secondary btn-sm mousehand no-print" data-clipboard-action="copy" data-clipboard-target="#code">Copy</button></div>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<span class="no-print" style="font-size:1.4em;">Share on&nbsp;&nbsp;</span><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo HOME.$retslug; ?>" target="_blank"><i class="fa fa-facebook fa-2x clr-facebook no-print mousehand" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="" style="" href="https://twitter.com/intent/tweet?text=View%20lineup%20at%20<?php echo HOME.$retslug; ?> #BaseballPosition" data-size="large" target="_blank"><i class="fa fa-twitter fa-2x clr-twitter no-print mousehand" aria-hidden="true"></i></a>
			<?php
		}else{
			echo '<br><div class="alert alert-warning no-print" role="alert"><strong>Warning!</strong> Something wen\'t wrong with creating link. <a href="'.HOME.'">Try again</a> or just <a onClick="window.print()">print this one</a></div>';
		}
	}else{
?> 
	<script type="text/javascript">
		window.location.href = '/?msg=error';
	</script>
<?php
	
}
	}else{
	          ?> 
              <script type="text/javascript">
                  window.location.href = '/?msg=nodata';
               </script>
          <?php
}
	
//////////////////////////////////////////////////////////////////////
	
for($z=1;$z<=$_SESSION['playercount'];$z++){
		$t = 'p'.$z;
	$form_output .= '
		<div class="form-group row">
			<label for="'.$t.'" class="col-1 col-form-label " style="float:right;">#'.$z.' </label>
			<div class="col">
				<input type="text" class="form-control" id="'.$t.'" name="'.$t.'" value="'.(isset($_POST[$t]) ? $_POST[$t] : "").'">
			</div>
		</div>
	';	
}
?>
<div class="modal fade" id="playerList" tabindex="-1" role="dialog" aria-labelledby="playerListTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="playerListTitle">PLAYER NAMES</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
	  <div class="col-md-12">
	  <form action="roster.php" method="POST" class="">
		<h2 class="tcenter">  ENTER PLAYER NAMES</h2><br>
		  
			 <?php echo $form_output ;?>

			<div class="modal-footer">
					<div class="col">
						<button type="submit" id="submit_players" name="submit_players" class="btn btn-warning">SAVE CHANGES</button>
					</div>
			</div>
		</form>
	</div>
      </div>
    </div>
  </div>
</div>

<!-- Trigger -->
<script type="text/javascript">
document.getElementById('submit_players').onclick = function progressbar() {    
   $('#progressbar').modal('show');
}
</script>
    <!-- 2. Include library -->
    <script src="js/clipboard.min.js"></script>

    <!-- 3. Instantiate clipboard -->
    <script>
    var clipboard = new Clipboard('.btn');
    clipboard.on('success', function(e) {
        console.log(e);
    });
    clipboard.on('error', function(e) {
        console.log(e);
    });
    </script>

<?php require 'footer.php';?>
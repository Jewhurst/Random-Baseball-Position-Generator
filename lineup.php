<?php require 'header.php';?>
<p class="no-print animated lightSpeedIn rock"><a href="/"><i class="fa fa-home fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;Home</a><span style="float:right;">Build another lineup, or <a href="<?php echo HOME; ?>">create your own</a></span></p>
<?php 
$stmt = $db->prepare('SELECT * FROM lineups WHERE slug = :slug');
$stmt->execute(array(':slug' => $_GET['lineup']));
$row = $stmt->fetch();
	//$output = $data->getLineup($getslug);
//if($_GET['lineup']!=""){
//	$getslug = $_GET['slug'];
//	$output = $data->getLineup($getslug);
//	diag(print_r($output,true));
//}

?>
  
		
		<?php echo $row['tablehtml'].'<br>'; ?>
		<p class="tcenter">Originally created on <?php echo fix_the_date($row['datemade']).' '.fix_the_time($row['datemade']); ?></p>
		<center><span class="no-print" style="font-size:1.4em;">Share on&nbsp;&nbsp;</span><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo getPageURL(); ?>" target="_blank"><i class="fa fa-facebook fa-2x clr-facebook no-print mousehand" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="" style="" href="https://twitter.com/intent/tweet?text=View%20lineup%20at%20<?php echo getPageURL(); ?> #BaseballPosition" data-size="large" target="_blank"><i class="fa fa-twitter fa-2x clr-twitter no-print mousehand" aria-hidden="true"></i></a></center>
		<br class="clear">
		
		
		


<?php
require 'footer.php';
?>
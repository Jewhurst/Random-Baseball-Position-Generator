<?php 
require 'header.php';


function fairRotation($roster=[],$positions=[],$rotations=9,$off="<b>X</b>"){
    $roster_count=sizeof($roster);
    $positions_count=sizeof($positions);
    echo "<div>roster_count=$roster_count, positions_count=$positions_count</div>";
    $ons_avg=$positions_count/$roster_count;
    $ons_max=ceil($ons_avg);
    $ons_min=floor($ons_avg);
    echo "<div>ons_avg=$ons_avg, ons_max=$ons_max, ons_min=$ons_min</div>";
    $off_avg=($roster_count-$positions_count)*$rotations/$roster_count;
    $off_max=ceil($off_avg);
    $off_min=floor($off_avg);
    echo "<div>off_avg=$off_avg, off_max=$off_max, off_min=$off_min</div><br>";
    $positions=array_pad($positions,$roster_count,$off);  // sync positions with roster using sub identifier    
    $positions_count=sizeof($positions);    // overwrite with updated count
    for($r=0; $r<$rotations; ++$r){
        shuffle($positions);
        $result[$r]=$positions;
    }
    //$color_result=$result;
    // unfiltered result:
    echo "<table border=1>";
        echo "<tr><th>#</th><th>",implode("</th><th>",$roster),"</th></tr>";
        foreach($result as $key=>$row){
            echo "<tr><th>",($key+1),"</th><td>",implode("</td><td>",$row),"</td></tr>";
        }
    echo "</table>";    

    // Assess result and address conflicts...
    $iterations=0;
    $fair="?";
    $must_drop_count=0;
    $must_gain_count=0;
    while($fair!="true" && $iterations<500){
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
            //var_export($must_drop);
            //echo "<br>Program Deadlock @ $iterations Iterations.";
            //echo "<br>Desperately reshuffle one of the deadlocked rows.";
            shuffle($positions);
            if($must_drop_count>0){
                $result[current(current(current($must_drop)))]=$positions;
                //$color_result[current(current(current($must_drop)))]=$positions;
            }else{
                $result[current(current(current($must_gain)))]=$positions;
                //$color_result[current(current(current($must_gain)))]=$positions;
            }
        }else{
            $must_drop_count=sizeof($must_drop);
            $must_gain_count=sizeof($must_gain);
            //echo "<br><div>MustDrop:$must_drop_count , MustGain:$must_gain_count</div>"; 

            foreach($must_drop as $d1col=>$d1array){  // $must_drop1[0]["SS"]=array(3,8);
                ++$iterations;
                //echo "<div>({$iterations}x): {$roster[$d1col]} must drop ";
                    //var_export($d1array);
                    foreach($d1array as $d1pos=>$d1keys){
                        foreach(array_diff_key($must_drop,array($d1col=>"")) as $d2col=>$d2array){  // dual-solution swap
                            foreach($d2array as $d2pos=>$d2keys){
                                //echo "<div>..seeking a new home for $d1pos</div>";
                                if($d1pos!=$d2pos && (isset($must_gain[$d1col][$d2pos]) || isset($may_gain[$d1col][$d2pos]))){
                                    //echo "<div>{$roster[$d1col]} may drop $d1pos for {$roster[$d2col]}'s $d2pos</div>";
                                    foreach($d2keys as $row){
                                        //echo "<div>checking {$roster[$d2col]}'s row($row) holding $d2pos vs {$roster[$d1col]}'s $d1pos ";
                                        //var_export($d1keys);
                                        //echo "</div>";
                                        if(in_array($row,$d1keys)){
                                            //echo "<div>row match on $row between {$roster[$d1col]} & {$roster[$d2col]}</div>";
                                            if(isset($must_gain[$d1col][$d2pos])){
                                                //echo "<div>Success: {$roster[$d1col]} must gain holds $d2pos</div>";
                                                //$color_result[$row][$d1col]="<span style='background-color:green;'>$d2pos</span>";
                                                //$color_result[$row][$d2col]="<span style='background-color:blue;'>$d1pos</span>";
                                                $result[$row][$d1col]=$d2pos;
                                                $result[$row][$d2col]=$d1pos;
                                                //var_export($result[$row]);
                                                //echo "<br>";
                                                //unset($must_drop[$d1col][$d1pos],$must_gain[$d2col][$d2pos],$must_gain[$d1col][$d2pos],$must_gain[$d2col][$d1pos]);
                                                break(5);
                                            }elseif(isset($may_gain[$d1col][$d2pos])){
                                                //echo "<div>Success: {$roster[$d1col]} may gain holds $d2pos</div>";
                                                //$color_result[$row][$d1col]="<span style='background-color:red;'>$d2pos</span>";
                                                //$color_result[$row][$d2col]="<span style='background-color:orange;'>$d1pos</span>";
                                                $result[$row][$d1col]=$d2pos;
                                                $result[$row][$d2col]=$d1pos;
                                                //var_export($result[$row]);
                                                //echo "<br>";
                                                //unset($must_drop[$d1col][$d1pos],$must_gain[$d2col][$d2pos],$may_gain[$d1col][$d2pos],$may_gain[$d2col][$d1pos]);
                                                break(5);
                                            }else{
                                                //echo "<div>No Eligible Swap: {$roster[$d1col]} doesn't need/want $d2pos @ row$row";
                                                //var_export(array_merge(array(),$must_gain[$d1col],$may_gain[$d1col]));
                                                //echo "</div>";
                                            }
                                        }
                                        //echo "<br>";
                                    }
                                }
                            }
                        }                   
                    }
                //echo "</div><br>";
            }

        }
    }
    if($fair=="true"){
        echo "<div>FAIR! after $iterations adjustments</div>";
        return $result;  // $color_result for color
    }else{
        echo "Runaway Deadlock, Call Full Rerun<br>";
        return false;
    }
}

//$roster=array("sub1","sub2","sub3","sub4","sub5","sub6","sub7","sub8","sub9","sub10","sub11","sub12","sub13","sub14","sub15","sub16","sub17","sub18");
$roster=array("sub1","sub2","sub3","sub4","sub5","sub6","sub7","sub8","sub9");
$positions=array("P","C","1st","2nd","SS","3rd","LF","CF","RF");
$rotations=9; // rotating each inning of a 9 inning baseball game
	diag(print_r($roster,true));
	diag(print_r($positions,true));
// roster array must be larger than positions array
// if roster is short, remove positions which will be vacant
// values in the positions array MUST NOT be purely numeric, due to array_fill_keys() type-feature/glitch
// subs/inactive assignments will be marked by an X, unless otherwise declared in the function call

$result=false;
while(!$result){
    $result=fairRotation($roster,$positions,$rotations);
}

echo "<table border=1>";
    echo "<tr><th>#</th><th>",implode("</th><th>",$roster),"</th></tr>";
    foreach($result as $key=>$row){
        echo "<tr><th>",($key+1),"</th><td>",implode("</td><td>",$row),"</td></tr>";
    }
echo "</table>";


require 'footer.php';

?>
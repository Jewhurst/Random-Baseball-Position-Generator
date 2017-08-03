<?php
function getTime($t_time){
	$pt = time() - $t_time;
	if ($pt>=86400)
		$p = date("F j, Y",$t_time);
	elseif ($pt>=3600)
		$p = (floor($pt/3600))."h ago";
	elseif ($pt>=60)
		$p = (floor($pt/60))."m ago";
	else
		$p = $pt."s ago";
	return $p;
}
function fix_the_date($d) {
		$newDate = new DateTime($d);
		return $newDate->format('F j, Y');
		
}
function fix_the_date_small($d) {
		$newDate = new DateTime($d);
		return $newDate->format('M j');
		
}
function fix_the_time($t) {
		$newTime = new DateTime($t);
		return $newTime->format('h:i a');
		
}
function fix_the_time_small($t) {
		$newTime = new DateTime($t);
		return $newTime->format('g:ia');
		
}
function diag($msg,$type = 3,$logfile = 'bplog'){
		$headers = 'From: <info@baseballposition.com>';
		$logfile_name = LOGPATH . $logfile .'-'.date('Y-m-d').'.txt';
		$msg = "\n[".(date('D M d, Y - H:i:s'))."]: ".$msg;
		switch($type){
			case 1 : error_log($msg,1,'info@baseballposition.com',$headers );
				break;
			case 2 : error_log($msg,0);
				break;
			case 3 : error_log($msg,3,$logfile_name);
				break;
			case 4 : error_log($msg,4);
				break;
			default : error_log($msg,0);
		}
		
	}
/*IMPORTANT SLUG FUNCTION*/
	function slug($text){ 
			$slug=strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $text));
			return $slug;
	}
/*TRUNCATE TEXT TO X CHARS*/ 
	function truncate($string,$length=150,$append="&hellip;") {
	  $string = trim($string);

	  if(strlen($string) > $length) {
		$string = wordwrap($string, $length);
		$string = explode("\n", $string, 2);
		$string = $string[0] . $append;
	  }

	  return $string;
	}

      
   function logout(){ 
        if(isset($_SESSION)){session_destroy();}
		header('Location: /');
   }
	/*GET CURRENT PAGE URL*/	
	function getPageURL() {
		 $pageURL = 'http';
		 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		 $pageURL .= "://";
		 if ($_SERVER["SERVER_PORT"] != "80") {
		  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		 } else {
		  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		 }
		 return $pageURL;
	}
	
	/*REDIRECT CODE*/
      function redirect($mode = 0, $url = '/') {

        switch ($mode) {
          case 2:
          ?> 
              <script type="text/javascript">
                  window.location.href = "<?php echo HOME.$url; ?>"
               </script>
          <?php 
            break;
          case 1:
          ?> 
              <script type="text/javascript">
                  window.location.href = "<?php echo $url; ?>"
               </script>
          <?php 
            break;

          default:
          ?> 
              <script type="text/javascript">
                  window.location.href = <?php echo HOME; ?>
               </script>
          <?php 
            break;
        }
    
   }
   
   /*ENCRYPT FUNCTION - WORKS WITH DECRYPT*/   	
	function enCrypt($mode,$val) {
          
          switch ($mode) {
            //base64 encoding
            case 0:
                $hashed_val = password_hash($val,PASSWORD_DEFAULT);
                return $hashed_val;
              break;
            //php5.5 default hashing
            case 1:
                  $key_val = base64_encode($val);
                  return $key_val;
              break;
            //md5 encoding 
            case 2:
                $ret_val = md5($val);
                return $ret_val;
              break;
            
            //  
            case 3:
                $ret_val = bin2hex($val);
                $ret_val = base64_encode($ret_val);
                return $ret_val;
              break;
            
            default:
                $hashed_val = password_hash($val,PASSWORD_DEFAULT);
                return $hashed_val;
              break;
          }

          
   }
/*DECRYPT FUNCTION - WORKS WIH ENCRYPT*/   
   function deCrypt($mode,$val,$hashed_val = ''){
          
          switch ($mode) {
            case 0:
                if(password_verify($val,$hashed_val)){ 
                       return true;
                }
              break;
            case 1:
                $key = base64_decode($val);
                return $key;
              break;
            // b64 -> md5 
            case 3:
				$ret_val = base64_decode($val);
                $ret_val = hex2bin($ret_val);
                return $ret_val;
              break;
            
            default:
                if(password_verify($val,$hashed_val)){ 
                       return true;
                }
              break;
          }

   }
   //http://php.net/manual/en/function.random-int.php#119670
   if (!function_exists('random_int')) {
    function random_int($min, $max) {
        if (!function_exists('mcrypt_create_iv')) {
            trigger_error(
                'mcrypt must be loaded for random_int to work', 
                E_USER_WARNING
            );
            return null;
        }
        
        if (!is_int($min) || !is_int($max)) {
            trigger_error('$min and $max must be integer values', E_USER_NOTICE);
            $min = (int)$min;
            $max = (int)$max;
        }
        
        if ($min > $max) {
            trigger_error('$max can\'t be lesser than $min', E_USER_WARNING);
            return null;
        }
        
        $range = $counter = $max - $min;
        $bits = 1;
        
        while ($counter >>= 1) {
            ++$bits;
        }
        
        $bytes = (int)max(ceil($bits/8), 1);
        $bitmask = pow(2, $bits) - 1;

        if ($bitmask >= PHP_INT_MAX) {
            $bitmask = PHP_INT_MAX;
        }

        do {
            $result = hexdec(
                bin2hex(
                    mcrypt_create_iv($bytes, MCRYPT_DEV_URANDOM)
                )
            ) & $bitmask;
        } while ($result > $range);

        return $result + $min;
    }
}	
	//http://stackoverflow.com/questions/4356289/php-random-string-generator/31107425#31107425
   function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'){ 
		$str = '';
		$max = mb_strlen($keyspace, '8bit') - 1;
		for ($i = 0; $i < $length; ++$i) {
			$str .= $keyspace[random_int(0, $max)];
		}
		return $str;
	}
   
   

function makeLinks($str) {
	$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
	$urls = array();
	$urlsToReplace = array();
	if(preg_match_all($reg_exUrl, $str, $urls)) {
		$numOfMatches = count($urls[0]);
		$numOfUrlsToReplace = 0;
		for($i=0; $i<$numOfMatches; $i++) {
			$alreadyAdded = false;
			$numOfUrlsToReplace = count($urlsToReplace);
			for($j=0; $j<$numOfUrlsToReplace; $j++) {
				if($urlsToReplace[$j] == $urls[0][$i]) {
					$alreadyAdded = true;
				}
			}
			if(!$alreadyAdded) {
				array_push($urlsToReplace, $urls[0][$i]);
			}
		}
		$numOfUrlsToReplace = count($urlsToReplace);
		for($i=0; $i<$numOfUrlsToReplace; $i++) {
			$str = str_replace($urlsToReplace[$i], '<a href="'.$urlsToReplace[$i].'" target="_blank">'.$urlsToReplace[$i].'</a> ', $str);
		}
		return $str;
	} else {
		return $str;
	}
}


class dbconnect {
	/*THIS IS THE INITIAL SETUP*/
    private $db; 
	/*
	THIS FUNCTION IS SO YOU CAN USE SINGLE CALLS OUTSIDE THE CLASS SECTION
	EXAMPLE: ON THE INDEX PAGE YOU WILL SEE AN EXAMPLE OF THE SAME EXACT CALL AS 
	BELOW BUT DONE ON THE FLY HARDCODED IN A PAGE	
	*/
    public function __construct()  {
          global $db;
          $this->db = $db;
    }    
	
	public function getLineup($slug){
		try{		
		
			diag($slug);
			$stmt = $this->db->prepare("SELECT * FROM lineups WHERE slug = :slug");
			$stmt->execute(array(':slug'=>$slug));
			$val=$stmt->fetchAll();
			diag(print_r($val,true));
			return $val;			
		} catch(PDOException $e) {
            echo $e->getMessage();
       } 
   }
   public function addLineup($maker,$tablehtml,$ifcode=false,$thencode = ''){ 
		try{		
			diag($maker.'<br><br>'.$tablehtml.'<br><br>'.$ifcode.'<br><br>'.$thencode);
			//$maker = mysql_real_escape_string($maker);
			//$tablehtml = mysql_real_escape_string($tablehtml);
			$datemade = date('Y-m-d H:i:s');
			if($ifcode == true){
				if($thencode != ''){
					$stmt = $this->db->prepare("UPDATE lineups SET maker=:maker, datemade=:datemade, tablehtml=:tablehtml WHERE slug=:thencode");
					$stmt->execute(array(':maker'=>$maker,':datemade'=>$datemade,':tablehtml'=>$tablehtml,':thencode'=>$thencode));
					return $thencode;
				}				
			}else{
				$slug = random_str(9);	
				$slug .= random_int(1,9999);
				$slug = str_shuffle($slug);		
				$check = $this->db->prepare("SELECT slug FROM lineups WHERE slug = :slug");
				$check->execute(array(':slug'=>$slug));
				if($check->rowCount() > 0){				
					$slug = random_int(1,9999);				
					$slug .= random_str(9);	
					$slug = str_shuffle($slug);					
				}//fuck it, should be good
				
				$stmt = $this->db->prepare("INSERT INTO lineups (maker,slug,datemade,tablehtml) VALUES (:maker, :slug, :datemade, :tablehtml)");
				$stmt->execute(array(':maker'=>$maker,':slug'=>$slug,':datemade'=>$datemade,':tablehtml'=>$tablehtml));
				return $slug;
			}						
		} catch(PDOException $e) {
            echo $e->getMessage();
       } 
   }
   
}
?>
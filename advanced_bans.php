
<?php
/**
 * Advanced Bans by cRaNnK
 *
 *	I am not responsible of this code.
 *	They made me write it, against my will.
 *	(c) 2015 Benny 'cRaNnK'  <crannk@my-run.de>
 *	crannk.de
 *
 */

$sql_host = ""; // leave blank for localhost
$sql_user = "bans";
$sql_pass = "123456";
$sql_db = "advanced_bans";

switch($_GET['s']){
	case '1' :
		$sname = 'Some CS 1.6 Server';
		$stable = 'advanced_bans_1';
		break;
        default:
		$sname = 'Some CS 1.6 Server';
		$stable = 'advanced_bans_1';
}

function min_to_time($minutes) { 

if( $minutes == 0 ) {
		return "Permanent Ban";
	}
	
     $days = floor($minutes / 1440); 
     $hours = floor($minutes % 1400 / 60); 
     $minutes = $minutes % 60; 

     return sprintf("%d Days, %02d Hours, %02d Minutes", $days, $hours, $minutes); 
 }
 
function GetSteamProfile( $steamid ) {
	return ( "http://steamcommunity.com/profiles/" . GetFriendID( $steamid ) );
}

function GetFriendID( $steamid ) {
	$pieces = explode( ":", str_ireplace( "STEAM_0:", "", $steamid ) );
	$server = $pieces[ 0 ];
	$authid = $pieces[ 1 ];
	
	return bcadd( bcmul( $authid, "2" ), bcadd( "76561197960265728", $server ) );
}
function MaskIP( $is_ip )
        {
				$ip = $is_ip;
				$iparr = split ("\.", $ip); 
				return $iparr[0].'.'.$iparr[1].'.***.***';
        }
		
if( $sql_host == "" ) {
	$sql_host = localhost;
}

$sql_conn = mysql_connect( $sql_host, $sql_user, $sql_pass );
mysql_select_db( $sql_db, $sql_conn );

	$zone="-14400"; //USA Time Zone
	$targetpage = "advanced_bans.php"; 	// Script Name
	$limit = 40;  // Bans Showed per page
	
	// Start Pagination
	$sql_query = "SELECT COUNT(*) as num FROM $stable";
	$total_pages = mysql_fetch_array(mysql_query($sql_query));
	$total_pages = $total_pages[num];
	
	$stages = 3;
	$page = mysql_escape_string($_GET['page']);
	if($page){
		$start = ($page - 1) * $limit; 
	}else{
		$start = 0;	
		}	
	
    // Get page data
	$query1 = "SELECT * FROM ".$stable." ORDER BY `ban_created` DESC LIMIT $start, $limit";
	$result = mysql_query($query1);
	
	// Initial page num setup
	if ($page == 0){$page = 1;}
	$prev = $page - 1;	
	$next = $page + 1;							
	$lastpage = ceil($total_pages/$limit);		
	$LastPagem1 = $lastpage - 1;					
	
	
	$paginate = '';
	if($lastpage > 1)
	{	
	

	
	
		$paginate .= "<div class='paginate'>";
		// Previous
		if ($page > 1){
			$paginate.= "<a href='$targetpage?s=".$_GET['s']."&page=$prev'>previous</a>";
		}else{
			$paginate.= "<span class='disabled'>previous</span>";	}
			

		
		// Pages	
		if ($lastpage < 7 + ($stages * 2))	// Not enough pages to breaking it up
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page){
					$paginate.= "<span class='current'>$counter</span>";
				}else{
					$paginate.= "<a href='$targetpage?s=".$_GET['s']."&page=$counter'>$counter</a>";}					
			}
		}
		elseif($lastpage > 5 + ($stages * 2))	// Enough pages to hide a few?
		{
			// Beginning only hide later pages
			if($page < 1 + ($stages * 2))		
			{
				for ($counter = 1; $counter < 4 + ($stages * 2); $counter++)
				{
					if ($counter == $page){
						$paginate.= "<span class='current'>$counter</span>";
					}else{
						$paginate.= "<a href='$targetpage?s=".$_GET['s']."&page=$counter'>$counter</a>";}					
				}
				$paginate.= "...";
				$paginate.= "<a href='$targetpage?s=".$_GET['s']."&page=$LastPagem1'>$LastPagem1</a>";
				$paginate.= "<a href='$targetpage?s=".$_GET['s']."&page=$lastpage'>$lastpage</a>";		
			}
			// Middle hide some front and some back
			elseif($lastpage - ($stages * 2) > $page && $page > ($stages * 2))
			{
				$paginate.= "<a href='$targetpage?s=".$_GET['s']."&page=1'>1</a>";
				$paginate.= "<a href='$targetpage?s=".$_GET['s']."&page=2'>2</a>";
				$paginate.= "...";
				for ($counter = $page - $stages; $counter <= $page + $stages; $counter++)
				{
					if ($counter == $page){
						$paginate.= "<span class='current'>$counter</span>";
					}else{
						$paginate.= "<a href='$targetpage?s=".$_GET['s']."&page=$counter'>$counter</a>";}					
				}
				$paginate.= "...";
				$paginate.= "<a href='$targetpage?s=".$_GET['s']."&page=$LastPagem1'>$LastPagem1</a>";
				$paginate.= "<a href='$targetpage?s=".$_GET['s']."&page=$lastpage'>$lastpage</a>";		
			}
			// End only hide early pages
			else
			{
				$paginate.= "<a href='$targetpage?s=".$_GET['s']."&page=1'>1</a>";
				$paginate.= "<a href='$targetpage?s=".$_GET['s']."&page=2'>2</a>";
				$paginate.= "...";
				for ($counter = $lastpage - (2 + ($stages * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page){
						$paginate.= "<span class='current'>$counter</span>";
					}else{
						$paginate.= "<a href='$targetpage?s=".$_GET['s']."&page=$counter'>$counter</a>";}					
				}
			}
		}
					
				// Next
		if ($page < $counter - 1){ 
			$paginate.= "<a href='$targetpage?s=".$_GET['s']."&page=$next'>next</a>";
		}else{
			$paginate.= "<span class='disabled'>next</span>";
			}
			
		$paginate.= "</div>";		
	
	
}

 
 // End Pagination
 
	echo "<html>\n<head>\n";
	echo '<title>'.$sname.'</title>';
	echo "<style>\n";
	echo '.paginate {
font-family:Arial, Helvetica, sans-serif;
	padding: 3px;
	margin: 3px;
}

.steamid a{
color: #000;
}
.steamid a:hover, .steamid a:active{
color: #45B6FD;
}
.paginate a {
	padding:2px 5px 2px 5px;
	margin:2px;
	border:1px solid #999;
	text-decoration:none;
	color: #999;
}
.paginate a:hover, .paginate a:active {
	border: 1px solid #999;
	color: #fff;
}
.paginate span.current {
    margin: 2px;
	padding: 2px 5px 2px 5px;
		border: 1px solid #999;
		
		font-weight: bold;
		background-color: #999;
		color: #FFF;
	}
	.paginate span.disabled {
		padding:2px 5px 2px 5px;
		margin:2px;
		border:1px solid #eee;
		color:#DDD;
	}
	.bans {
	color: #FFF;
}
	li{
		padding:4px;
		margin-bottom:3px;
		background-color:#FCC;
		list-style:none;}
		
	ul{margin:6px;
	padding:0px;}	';
	echo "body { background-color: #0a0a0a; }\n";
	echo "table { background-color: #7293B3; }\n";
	echo "body, table { font-family: Tahoma; font-size: 11px; color: #000000; }\n";
	echo "h1 { color: #7293B3; }\n";
	echo "a { color: grey; text-decoration: none; }\n";
	echo "a:hover { color: #FFFFFF; }\n";
	echo "a:visited { color: grey; }\n";
	echo "a:visited:hover { color: #ffffff; }\n";
	echo "</style>\n";
	echo "</head>\n";
	echo "<body>\n";
	echo "<div align=\"center\">\n<br />\n";
		echo '<form action="'.$targetpage.'" method="get">
  <select name="s">
<option selected="selected">Please Select a Server</option>
  <option value="1">Some CS 1.6 Server</option>
 </select>
  <input type="submit"/>
    </form>';
	echo "<h1>".$sname."</h1>\n";
	echo "<table style=\"border:1px #000000 solid;\" cellspacing=\"0\" cellpadding=\"4px\">\n";
	echo "<tr bgcolor=\"#7293B3\">\n";
	echo "<th style=\"text-align:center;\" width=\"10px\">#</th>\n";
	echo "<th style=\"text-align:left;\" width=\"205px\">&nbsp;&nbsp;Player Name</th>\n";
	echo "<th style=\"text-align:left;\" width=\"140px\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Player SteamID/IP</th>\n";
	echo "<th style=\"text-align:center;\" width=\"235px\">Ban Length</th>\n";
	echo "<th style=\"text-align:center;\" width=\"145px\">Unban Time</th>\n";
	echo "<th style=\"text-align:center;\" width=\"250px\">Reason</th>\n";
	echo "<th style=\"text-align:left;\" width=\"140px\">&nbsp;&nbsp;&nbsp;Invoked on</th>\n";
	echo "<th style=\"text-align:left;\" width=\"220px\">&nbsp;&nbsp;Admin Name</th>\n";
	echo "<th style=\"text-align:left;\" width=\"140px\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Admin SteamID</th>\n";
	echo "</tr>\n";
	

	while( ( $row = mysql_fetch_array( $result ) ) ) {
		
		echo "<tr".( ( $start % 2 ) == 0 ? " bgcolor=\"#FFFFFF\"" : " bgcolor=\"#E5E5E5\"" ) .">\n";
			
			echo "<td style=\"text-align:center\">";
			//echo ( $count + 1 );
			echo $start + 1;
			echo ".";
			echo "</td>\n";
			
			echo "<td style=\"text-align:left\">";
			echo "&nbsp;";
			echo htmlspecialchars( $row[ 'victim_name' ] );
			echo "</td>\n";
			
			$steamid = $row[ 'victim_steamid' ];
			$is_steamid = stripos( $steamid, "STEAM_0:" );
			
			echo "<td class=\"steamid\" style=\"text-align:left\">";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if( SubStr( $steamid, 0,7 ) == "STEAM_0" ) {
				//echo "<a href=\"" . GetSteamProfile( $steamid ) . "\" target=\"_blank\">";
				echo $steamid;
			}
			else{
				echo MaskIP($steamid);
				echo "</a>";
			}
			echo "</td>\n";
			
			echo "<td style=\"text-align:center\">";
			echo min_to_time( $row[ 'banlength' ] );
			echo "</td>\n";
			
			echo "<td style=\"text-align:center\">";
			echo $row[ 'unbantime' ];
			echo "</td>\n";
			
			echo "<td style=\"text-align:center\">";
			echo htmlspecialchars( $row[ 'reason' ] );
			echo "</td>\n";
			
			echo "<td style=\"text-align:left\">";
			echo $row['ban_created'] = gmdate("Y-m-d H:i", $row['ban_created'] + $zone);
			echo "</td>\n";
			
			echo "<td style=\"text-align:left\">";
			echo "&nbsp;";
			echo htmlspecialchars( $row[ 'admin_name' ] );
			echo "</td>\n";
			
			$steamid = $row[ 'admin_steamid' ];
			$is_steamid = stripos( $steamid, "STEAM_0:" );
			
			echo "<td style=\"text-align:left\">";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if( $is_steamid != false ) {
				echo "<a href=\"" . GetSteamProfile( $steamid ) . "\" target=\"_blank\">";
			}
			echo $steamid;
			if( $is_steamid != false ) {
				echo "</a>";
			}
			echo "</td>\n";
			
			echo "</tr>\n";
		
		
		$start++;
	}
	echo "</table>\n";
	echo "<table align=\"center\" style=\"border:1px #000000 solid;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4px\">\n";
	echo "<tr bgcolor=\"#7293B3\">\n";
	//echo "<th style=\"text-align:center;\" width=\"10px\"></th>\n";
	echo "<td align=\"center\" class=\"bans\" font=\"color:#FFFFFF;\">Total Bans ".$total_pages."</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo $paginate;
	echo '<div align="center"><font color="#45B6FD" size="-1">written by cRaNnK</font></div>';
	echo "</div>\n";
	echo "</body>\n";
	echo "</html>\n";

mysql_close( $sql_conn );

?>

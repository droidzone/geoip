<?php
require_once 'vendor/autoload.php';
use GeoIp2\Database\Reader;

// This creates the Reader object, which should be reused across
// lookups.

$myip=$ipA= $ipB = '';
$domain='';

function formatpage() {
	global $domain, $ip_to_find;
	print '
	<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../../assets/ico/favicon.ico">

    <title>IP Geolocator by Joel</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
	 <link href="jumbotron-narrow.css" rel="stylesheet">	
	<link href="css/grid.css" rel="stylesheet">		 
	
	</head>
  <body>
	
    <div class="container">
      <div class="header">
        <ul class="nav nav-pills pull-right">
          <li class="active"><a href="#">Home</a></li>
          <li><a href="#">About</a></li>
          <li><a href="#">Contact</a></li>
        </ul>
        <h3 class="text-muted">IP Geolocator</h3>
      </div>

      <div class="jumbotron">
        <h1>IP Geolocator</h1>
        <p class="lead">IP Geolocator is a free IP geolocation finder.</p>';
		
      
	  print '
		  <form class="form-horizontal">
			<fieldset>

			<div class="control-group">
			  <div class="controls">
				<p class="help-block" style="float:left">Enter IP to lookup geolocation</p>
				<input id="ipinput" name="ip" type="text" placeholder="Enter IP here" class="input-xlarge" required="" style="float:left; margin-left: 10px; margin-top: 8px;" value="';
				
				if ( isset($ip_to_find) ) {
					print $ip_to_find;
				}
				
				
				print '">
				<button id="singlebutton" name="" class="btn btn-success" style="padding: 0 8px;" type="submit" >Lookup</button>
			  </div>
			</div>			
			</fieldset>
			</form>
			
			<form class="form-horizontal">
			<fieldset>

			<div class="control-group">
			  <div class="controls">
				<p class="help-block" style="float:left">Enter domain</p>
				<input id="domaininput" name="domain" type="text" placeholder="Enter domain name" class="input-xlarge" required="" style="float:left; margin-left: 10px; margin-top: 8px;" value="'.$domain.'">
				<button id="singlebutton" name="" class="btn btn-success" style="padding: 0 8px;" type="submit" >Lookup</button>
			  </div>
			</div>			
			</fieldset>
			</form>
			
			
			
			';
	  
	getmyip();
	  print '</div>

      	
	';
  


}

function endpage() {
	print '	  
        
      <div class="footer">
        <p>&copy; Joel G Mathew 2014-16</p>
      </div>

    </div> 

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>    
    <script src="js/bootstrap.min.js"></script>
	
	</body>
</html>
';

}

function bprint($data) {
	print "<p>".$data."</p>";

}

function colprint($data1, $data2) {
	print '<div class="row">
		  <div class="col-md-4">'.$data1.'</div>';
	print '<div class="col-md-8">'.$data2.'</div>
			</div>
	';
}

function threecolprint ($data1, $data2, $data3) {
	print '<div class="row">
		  <div class="col-md-4">'.$data1.'</div>'.
		  '<div class="col-md-4">'.$data2.'</div>'.
		  '<div class="col-md-4">'.$data3.'</div>	
			</div>
	';
}

function printgeodata($ip) {
	colprint ("IP Address",$ip);
	$reader = new Reader('./GeoIP2-City.mmdb');
	$record = $reader->city($ip);

	colprint("Country Code",$record->country->isoCode); // 'US'
	colprint("Country Name",$record->country->name); // 'United States'
	colprint("Country locale",$record->country->names['zh-CN']); // '..'

	threecolprint("Region",$record->mostSpecificSubdivision->name, $record->mostSpecificSubdivision->isoCode); // 'Minnesota'
	
	//bprint($record->mostSpecificSubdivision->isoCode . "\n"); // 'MN'

	colprint("City", $record->city->name); // 'Minneapolis'
	colprint("Postal Code", $record->postal->code); // '55455'
	colprint("Latitude", $record->location->latitude); // 44.9733
	colprint("Longitude", $record->location->longitude . "\n"); // -93.2323
	printmapdata($record->location->latitude, $record->location->longitude);
}

function printmapdata($latitude, $longitude) {
	print '
	<iframe width="700" 
	  height="450"
	  frameborder="0" style="border:0"
	  src="https://www.google.com/maps/embed/v1/place?key=AIzaSyAX4TUvIQEbEXg41iTTrkcTSyUR6CTYzag&q='.$latitude.','.$longitude.'">
	</iframe>';

}

function getmyip() {
	global $myip,$ipA,$ipB;
	$PROXY_PRESENT = 0;
	$proxy_headers = array(
        'HTTP_VIA',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_FORWARDED_FOR',
        'HTTP_
        X_FORWARDED',
        'HTTP_FORWARDED',
        'HTTP_CLIENT_IP',
        'HTTP_FORWARDED_FOR_IP',
        'VIA',
        'X_FORWARDED_FOR',
        'FORWARDED_FOR',
        'X_FORWARDED',
        'FORWARDED',
        'CLIENT_IP',
        'FORWARDED_FOR_IP',
        'HTTP_PROXY_CONNECTION'
    );
    foreach($proxy_headers as $x){
        if (isset($_SERVER[$x])) {
            $PROXY_PRESENT = 1;
        }
    }
	$myip=getenv("REMOTE_ADDR") ;
	$ipA= $_SERVER['REMOTE_ADDR'];
	$ipB = $_SERVER['HTTP_X_FORWARDED_FOR'];
	printmyipinfo ();
	
}

function printmyipinfo () {
	global $myip,$ipA,$ipB;
	print '
		  <form class="form-horizontal">
			<fieldset>
			<div class="control-group">
			  <div class="controls">
				<p class="help-block" style="float:left">Your IP is </p>
				<input id="ipinput" name="ip" type="text" class="input-xlarge" required="" style="float:left; margin-left: 10px; margin-top: 8px;" value='.$myip.'>
				<button id="singlebutton" name="" class="btn btn-success" style="padding: 0 8px;" type="submit" >Lookup</button>
			  </div>
			</div>			
			</fieldset>
			</form>';
	if ( ($ipA !==  "") && ($ipA !==  $myip) ) {
		if ($ipA ===  "") {
			print "Null";
		}
		print '
		  <form class="form-horizontal">
			<fieldset>
			<div class="control-group">
			  <div class="controls">
				<p class="help-block" style="float:left">Or your IP may be </p>
				<input id="ipinput" name="ip" type="text" class="input-xlarge" required="" style="float:left; margin-left: 10px; margin-top: 8px;" value="1'.$ipA.'">
				<button id="singlebutton" name="" class="btn btn-success" style="padding: 0 8px;" type="submit" >Lookup</button>
			  </div>
			</div>			
			</fieldset>
			</form>';
	}
	if ( ($ipB !=  "") && ($ipB !=  $myip) ) {
		if ($ipB ==  "") {
			print "Null";
		}
		print '
		  <form class="form-horizontal">
			<fieldset>
			<div class="control-group">
			  <div class="controls">
				<p class="help-block" style="float:left">Or your IP may be </p>
				<input id="ipinput" name="ip" type="text" class="input-xlarge" required="" style="float:left; margin-left: 10px; margin-top: 8px;" value="2'.$ipB.'">
				<button id="singlebutton" name="" class="btn btn-success" style="padding: 0 8px;" type="submit" >Lookup</button>
			  </div>
			</div>			
			</fieldset>
			</form>';
	}
	

}

if($_GET) {
	// Replace "city" with the appropriate method for your database, e.g.,
	// "country".
	//$ip_to_find='128.101.101.101';	
    //else {
	//print "We received GET data\n";
	$ip_to_find=$_GET["ip"];
	if (isset($_GET["domain"])) {
		$domain=$_GET["domain"];
		$ip_to_find=gethostbyname ( $domain );
	}
}
	try {
		formatpage();
		printgeodata($ip_to_find);
		
		endpage();
	} catch (Exception $e) {
		colprint ('Info:','Enter some valid data');
		//,  $e->getMessage(), "\n";
	}
?>
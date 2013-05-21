<html>
<head>
<title>CNR Golf Tournament 2013</title>

<link href="css/main.css" rel="stylesheet" type="text/css">
</head>

<body>
<h1> Transaction Details for the CNR Golf Tournament 2013</h1>
<?php
//error_reporting(E_ALL);
$key = "cnrevt2010Jul";
//$sslhomepage = "https://webappdv.acs.ncsu.edu/scripts/nelnet/QuikPAY.pl";//dev
$sslhomepage = "https://www.acs.ncsu.edu/scripts/nelnet/QuikPAY.pl";//prod
     if ($_REQUEST['orderType'] != "CNR Events") { die("Error 1: There was a problem processing your purchase, please contact cnr_development@ncsu.edu"); }
      if ($_REQUEST['orderDescription'] != "CNR Golf Tournament") { die("Error 2: There was a problem processing your purchase, please contact cnr_development@ncsu.edu"); }

      if ($_REQUEST['transactionType'] != "1") { die("Error 3: There was a problem processing your purchase, please contact cnr_development@ncsu.edu"); }
      if ($_REQUEST['transactionStatus'] == "2") { die("Your credit card transaction has been denied for the following reason: Rejected credit card payment. " . "Please contact cnr_development@ncsu.edu"); }
      if ($_REQUEST['transactionStatus'] == "3") { die("Your credit card transaction has been denied for the following reason: Error in credit card payment. " . "Please contact cnr_development@ncsu.edu"); }
      if ($_REQUEST['transactionStatus'] == "4") { die("Your credit card transaction has been denied for the following reason: Unknown. " . "Please contact cnr_development@ncsu.edu"); }
      if ($_REQUEST['transactionStatus'] != "1") { die("Your credit card transaction has been denied for the following reason: Unknown error code. " . "Please contact cnr_development@ncsu.edu"); }

    $orderid = $_REQUEST['orderNumber'];
	  $transid = $_REQUEST['transactionId'];
	  $email = $_REQUEST['userChoice1'];
	  $players = $_REQUEST['userChoice2'];
	  $donation =  $_REQUEST['userChoice3'];
	  $phone = $_REQUEST['userChoice4'];
	  $startTime = $_REQUEST['userChoice5'];
	  $team = $_REQUEST['userChoice6'];
	  $grad = $_REQUEST['userChoice7'];
	  
	  
	  $transactiontotal = $_REQUEST['transactionTotalAmount'];
	  $timestamp = $_REQUEST['timestamp']; // for hash
	  $timestamp2 = time(); //for printing to screen
	  $time = date("F j, Y, g:i a", $timestamp2); //for printing to screen
	  //$dateOfTransaction = date("F j, Y", $timestamp2); //for printing to screen
	  $total = $transactiontotal/100;
	  $cash = "$". $total .".00";

		
		//echo "<p>hash testing...</p>";
		$valueForHash = $transactiontotal.$orderid.$timestamp.$key;
		//echo "<br />value for hash :".$valueForHash."<br />";
		$hash = md5($valueForHash);
		//echo "<br />hash :".$hash."<br />";
		$passedHash = $_REQUEST['hash'];
		//echo "<br />passed hash :".$passedHash."<br />";
	
?>




    <?php

  if ($passedHash=$hash){
		echo "<h2>Your transaction has been processed, thank you.</h2>\n<p><a href=\"#\" onclick=\"window.print();return false;\"><img src=\"img/print.gif\" alt=\"print icon\" /></a>Please <a href=\"#\" onclick=\"window.print();return false;\">print</a> this page for your records</p>";
		echo "<table border=\"1\" width=\"500px\"><tr><td>Transaction Amount</td><td>" . $cash . "</td></tr>";
		//echo "<tr><td>Order Number</td><td>" . $orderid . "</td></tr>"; //not sure what this one really means
		//echo "<tr><td>Transaction Type</td><td>" . $_REQUEST['transactionType'] . "</td></tr>";
		//echo "<tr><td>Transaction Status</td><td>" . $_REQUEST['transactionStatus'] . "</td></tr>";
		echo "<tr><td>Confirmation Number</td><td>" . $transid . "</td></tr>";
		// echo "<tr><td>Transaction Date</td><td>" . $dateOfTransaction . "</td></tr>";
		//echo "<tr><td>Transaction Description</td><td>" . $_REQUEST['transactionDescription'] . "</td></tr>";
		//echo "<tr><td>Transaction Result</td><td>" . $_REQUEST['TransactionResultCode'] . "</td></tr>"; // does not exist
		//echo "<tr><td>Payer Type</td><td>" . $_REQUEST['actualPayerType'] . "</td></tr>"; //not sure what this one really means
		//echo "<tr><td>Your Payer Identifier</td><td>" . $_REQUEST['actualPayerIdentifier'] . "</td></tr>"; //not sure what this one really means
		//echo "<tr><td>Your Name</td><td>" . $_REQUEST['actualPayerFullName'] . "</td></tr>"; // not what it appears to be
		echo "<tr><td>Your Name</td><td>" . $_REQUEST['accountHolderName'] . "</td></tr>"; //real name
		echo "<tr><td>Street Address </td><td>" . $_REQUEST['streetOne'] . $_REQUEST['streetTwo'] . "</td></tr>";
		echo "<tr><td>City</td><td>" . $_REQUEST['city'] . "</td></tr>";
		echo "<tr><td>State</td><td>" . $_REQUEST['state'] . "</td></tr>";
		echo "<tr><td>Zip Code</td><td>" . $_REQUEST['zip'] . "</td></tr>";
		echo "<tr><td>Country</td><td>" . $_REQUEST['country'] . "</td></tr>";
		echo "<tr><td>Transaction Time</td><td>" . $time . "</td></tr>";
		
		echo "<tr><td>Your purchased items</td><td><ul>";
		
		switch ($team){
			case "team":
				echo "<li>One team golf registration with these players:";
				echo "<br />". $players ."</li>";
				$purchItems = "\n\n- $400 One team golf registration including: ". $players;
				break;
		
			case "indiv":
				echo "<li>One individual golf registration</li>";
				$purchItems = "\n\n- $100 One individual golf registration";
				break;
				}
		echo "<li>Your requested start time: ".$startTime;
		$purchItems .= "\n- Requested start time of ".$startTime;
		if (isset($donation)){
			echo "<li>An additional donation through our Online Giving system</li>";
			$purchItems .= "\n- An additional donation through our Online Giving system";
			}
	
		echo "</ul></td></tr>";
		
		echo "</td></tr></table>\n <p>If you have any questions about this transaction please contact <a href=\"mailto:cnr_development@ncsu.edu\">cnr_development@ncsu.edu</a>.  A copy of this receipt has also been sent to your email (". $email . ").";
		
    }
    else{
		echo "<h2>Error</h2>";
		echo "<p>Problem processing your request, please contact cnr_development@ncsu.edu</p>"; //hash doesn't match, this is a security measure
    }
  	$message1 = "Successful transaction processed:\n\n Name : " . $_REQUEST['accountHolderName'] . "\n Email : " . $email . "\n Phone Number: ". $phone ."\n Street Address : " . $_REQUEST['streetOne'] . " " . $_REQUEST['streetTwo'] . "\n City : " . $_REQUEST['city'] . "\n State : " . $_REQUEST['state'] . "\n Zip Code : " . $_REQUEST['zip'] . "\n Country : " . $_REQUEST['country'] . "\n Timestamp : " . $time . "\n Purchased Items : " . $purchItems . " \n\n Total Amount : " . $cash . "";

	mail('andy_betz@ncsu.edu,janell_moretz@ncsu.edu', 'New Registration Processed for CNR Golf Tournament', $message1, 'From: naturalresources@ncsu.edu','-f naturalresources@ncsu.edu');
	//mail('cdmorris@ncsu.edu', 'New Registration Processed for Golf Tournament 2011', $message1);
	mail($email, 'CNR Golf Tournament Registration Receipt', $message1, 'From: naturalresources@ncsu.edu','-f naturalresources@ncsu.edu');
  
  $myFile = "../dev/output/golf_tournament.dat";
	$fh = fopen($myFile, 'a') or die("can't open file");
	$stringData = $timestamp2 . "\t" . $_REQUEST['accountHolderName'] . "\t" . $email . "\t" . $phone . "\t" . $_REQUEST['streetOne'] . " " . $_REQUEST['streetTwo'] . "\t" . $_REQUEST['city'] . "\t" . $_REQUEST['state'] . "\t" . $_REQUEST['zip'] . "\t" . $_REQUEST['country'] . "\t" . $cash . "\t" . $team . "\t" . $players . "\t" . $startTime . "\t" . $donation . "\n";
	fwrite($fh, $stringData);
	fclose($fh);
 
	  
  
  
//for testing
//var_dump($_REQUEST);
?>



</body>
</html>

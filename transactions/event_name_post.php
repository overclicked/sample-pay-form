<html>
<head>
<title>Event Name</title>

<link href="css/main.css" rel="stylesheet" type="text/css">
</head>

<body>
<h1> Transaction Details for Event Name</h1>
<?php
  $key = "cnrevt2010Jul";
  $sslhomepage = "https://www.acs.ncsu.edu/scripts/nelnet/QuikPAY.pl";//prod
  //$sslhomepage = "https://webappdv.acs.ncsu.edu/scripts/nelnet/QuikPAY.pl";//dev

  //Error reporting...
  if ($_REQUEST['orderType'] != "CNR Events") { die("Error 1: There was a problem processing your purchase, please contact cnr_development@ncsu.edu"); }
  if ($_REQUEST['orderDescription'] != "Event Name") { die("Error 2: There was a problem processing your purchase, please contact cnr_development@ncsu.edu"); }
  if ($_REQUEST['transactionType'] != "1") { die("Error 3: There was a problem processing your purchase, please contact cnr_development@ncsu.edu"); }
  if ($_REQUEST['transactionStatus'] == "2") { die("Your credit card transaction has been denied for the following reason: Rejected credit card payment. " . "Please contact cnr_development@ncsu.edu"); }
  if ($_REQUEST['transactionStatus'] == "3") { die("Your credit card transaction has been denied for the following reason: Error in credit card payment. " . "Please contact cnr_development@ncsu.edu"); }
  if ($_REQUEST['transactionStatus'] == "4") { die("Your credit card transaction has been denied for the following reason: Unknown. " . "Please contact cnr_development@ncsu.edu"); }
  if ($_REQUEST['transactionStatus'] != "1") { die("Your credit card transaction has been denied for the following reason: Unknown error code. " . "Please contact cnr_development@ncsu.edu"); }

  //Gathering data passed through QuikPay...
  $orderid = $_REQUEST['orderNumber'];
  $transid = $_REQUEST['transactionId'];
  $email = $_REQUEST['userChoice1'];
  $phone = $_REQUEST['userChoice2'];	  
	  
  $transactiontotal = $_REQUEST['transactionTotalAmount'];
  $timestamp = $_REQUEST['timestamp']; //Fetching the initial timestamp for hash security
  $timestamp2 = time(); //Getting the current time
  $time = date("F j, Y, g:i a", $timestamp2); // Converting the timestamp to something human-readable
  $total = $transactiontotal/100; //Converting $amountDue back into dollars
  $cash = "$". $total .".00";

  //Verifying a secure transfer of information...
  $valueForHash = $transactiontotal.$orderid.$timestamp.$key;
  $hash = md5($valueForHash);
  $passedHash = $_REQUEST['hash'];
	
?>

<?php
  if ($passedHash=$hash){
    //Registrant's personal details fetched from QuikPay...
    echo "<h2>Your transaction has been processed, thank you.</h2>\n<p><a href=\"#\" onclick=\"window.print();return false;\"><img src=\"img/print.gif\" alt=\"print icon\" /></a>Please <a href=\"#\" onclick=\"window.print();return false;\">print</a> this page for your records</p>";
    echo "<table border=\"1\" width=\"500px\"><tr><td>Transaction Amount</td><td>" . $cash . "</td></tr>";
    echo "<tr><td>Confirmation Number</td><td>" . $transid . "</td></tr>";
    echo "<tr><td>Your Name</td><td>" . $_REQUEST['accountHolderName'] . "</td></tr>"; //real name
    echo "<tr><td>Street Address </td><td>" . $_REQUEST['streetOne'] . $_REQUEST['streetTwo'] . "</td></tr>";
    echo "<tr><td>City</td><td>" . $_REQUEST['city'] . "</td></tr>";
    echo "<tr><td>State</td><td>" . $_REQUEST['state'] . "</td></tr>";
    echo "<tr><td>Zip Code</td><td>" . $_REQUEST['zip'] . "</td></tr>";
    echo "<tr><td>Country</td><td>" . $_REQUEST['country'] . "</td></tr>";
    echo "<tr><td>Transaction Time</td><td>" . $time . "</td></tr>";
		
    echo "<tr><td>Your purchased items</td><td><ul>";
    // Here, add a bulleted list of anything purchased (number of tickets, etc) pulled from the form data
    if (isset($donation)){
      echo "<li>An additional donation through our Online Giving system</li>";
      }
	
    echo "</ul></td></tr>";		
    echo "</td></tr></table>\n <p>If you have any questions about this transaction please contact <a href=\"mailto:cnr_development@ncsu.edu\">cnr_development@ncsu.edu</a>.  A copy of this receipt has also been sent to your email (". $email . ").";		
    }

    else{
      echo "<h2>Error</h2>";
      echo "<p>Problem processing your request, please contact cnr_development@ncsu.edu</p>"; //hash doesn't match, this is a security measure
    }
    
    //The body of the email message to send to the event coordinator and the registrant
    $message1 = "Successful transaction processed:\n\n Name : " . $_REQUEST['accountHolderName'] . "\n Email : " . $email . "\n Phone Number: ". $phone ."\n Street Address : " . $_REQUEST['streetOne'] . " " . $_REQUEST['streetTwo'] . "\n City : " . $_REQUEST['city'] . "\n State : " . $_REQUEST['state'] . "\n Zip Code : " . $_REQUEST['zip'] . "\n Country : " . $_REQUEST['country'] . "\n Timestamp : " . $time;

    //Mail the receipt to the event coordinator and the registrant.
    //Both will appear to come from "naturalresources@ncsu.edu"
    mail('test@ncsu.edu', 'New Registration Processed for Event Name', $message1, 'From: naturalresources@ncsu.edu','-f naturalresources@ncsu.edu');
    mail($email, 'Event Name Receipt', $message1, 'From: naturalresources@ncsu.edu','-f naturalresources@ncsu.edu');
  
    //Write the data to an event-specific .dat file on its own line...
    $myFile = "../dev/output/event_name.dat";
    $fh = fopen($myFile, 'a') or die("can't open file");
    $stringData = $timestamp2 . "\t" . $_REQUEST['accountHolderName'] . "\t" . $email . "\t" . $phone . "\t" . $_REQUEST['streetOne'] . " " . $_REQUEST['streetTwo'] . "\t" . $_REQUEST['city'] . "\t" . $_REQUEST['state'] . "\t" . $_REQUEST['zip'] . "\t" . $_REQUEST['country'] . "\t" . $cash . "\n";
    fwrite($fh, $stringData);
    fclose($fh);
 
?>

</body>
</html>

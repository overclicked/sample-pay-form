<?php

/* NOTE:

Change the SSL Homepage variable on the post page and
change the gateway on this page before making live (switch
both to prod variable)

This is ready.  Just need to get final word from Andy, Janell and Jennifer
before making it production.
*/

?><html>
<head>
<title>CNR Golf Tournament 2013</title>
<link href="css/main.css" rel="stylesheet" type="text/css">
</head>

<body>
<h1>CNR Golf Tournament 2013</h1>

<?php

//var_dump($_POST);
/*getting input from form*/
$orderName = $_POST['orderName'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$team = $_POST['team'];
$p2 = $_POST['play2'];
$p3 = $_POST['play3'];
$p4 = $_POST['play4'];

switch ($_POST['start-time']){
  case "8":
		$startTime = "8:00 AM";
		break;
	case "130":
		$startTime = "1:30 PM";
		break;
}

$key = "cnrevt2010Jul"; 
$orderType = "CNR Events";
$gateway = "https://www.acs.ncsu.edu/scripts/nelnet/QuikPAY.pl";//prod
//$gateway = "https://webappdv.acs.ncsu.edu/scripts/nelnet/QuikPAY.pl";//dev
$timestamp = time();
$orderNumber = 1;
$amountDue = 0;
?>
<p>Your registration is almost complete.  Please verify the following information and then click the button below to continue on to the credit card transaction processing on NCSU's secure servers.</p>
<table border="1" width="400px" >
<tr><td width="100">Name :</td><td width="293"><?php echo $orderName; ?></td></tr>
<tr><td>Phone : </td><td><?php echo $phone; ?></td></tr>
<tr><td>Email : </td><td><?php echo $email; ?></td></tr>
<?php
if($team=="team"){
?>
<tr><td colspan=2><strong>Your Team</strong>:</td></tr>
<tr><td>Player 2 : </td><td><?php echo $p2; ?></td></tr>
<tr><td>Player 3 : </td><td><?php echo $p3; ?></td></tr>
<tr><td>Player 4 : </td><td><?php echo $p4; ?></td></tr>
<tr><td>Start Time Requested:</td><td><?php echo $startTime; ?></td></tr>
<?php } ?>
</table>


<p>What you are purchasing: </p>
<table border="1" width="500px" >
<?php
$total = 0;

switch ($team) {
    case "indiv":
		$total = 100;
        echo "<tr><td>For registering as an indivudal golfer</td><td><strong>$100.00</strong> </td></tr>";
        break;
    case "team":
        $total = 400;
        echo "<tr><td>For registering a team of 4 golfers</td><td><strong>$400.00</strong> </td></tr>";
        break;
	case "none":
        break;
}

$amountDue = $total*100;
?>

<tr><td><strong>Total</strong></td><td class="important"><?php echo "<strong>$". $total . ".00</strong>";?></td></tr>
</table>

<?php

if (isset($_POST['donation'])){
	$donation = $_POST['donation'];
?>

<h3>Additional Donation</h3><p>To make an additional donation, please use <a href="https://ccfn.ncsu.edu/advancement-services/giving/NR/" target=_blank>our online giving form</a>.  Thank you for your donation!</p>

<?php

	}

?>


<p>To proceed with processing a credit card transaction for your purchase of <strong>$<?php echo $total ?>.00</strong>, please click the button below.  If there is something wrong with your order, please click the back button on your browser and make any necessary corrections and then click the "Register" button again.</p>

<form name='form1' method='post' action="<?php echo $gateway; ?>" enctype='application/x-www-form-urlencoded'>
  <input type='hidden' name='redirectUrl' value="https://ssl.ncsu.edu/cnr/transactions/golf_tournament_2013_post.php">
  <input type='hidden' name='amountDue' value="<?php echo $amountDue; ?>">
  <input type='hidden' name='orderDescription' value="CNR Golf Tournament">
  <input type='hidden' name='orderNumber' value="<?php echo $orderNumber; ?>">
  <input type='hidden' name='orderType' value="<?php echo $orderType; ?>">
  <input type='hidden' name='orderName' value="<?php echo $orderName; ?>">
  <input type='hidden' name='userChoice1' value="<?php echo $email; ?>">
  <input type='hidden' name='userChoice2' value="<?php echo $p2.", ".$p3.", ".$p4; ?>">
  <input type='hidden' name='userChoice3' value="<?php echo $donation; ?>">
  <input type='hidden' name='userChoice4' value="<?php echo $phone; ?>">
  <input type='hidden' name='userChoice5' value="<?php echo $startTime; ?>">
  <input type='hidden' name='userChoice6' value="<?php echo $team; ?>">

  <input type='hidden' name='timestamp' value="<?php echo $timestamp; ?>">
  
  <?php 
  $valueForHash = $amountDue.$orderNumber.$timestamp.$key;
  $hash = md5($valueForHash);

  ?>
  <input type='hidden' name='hash' value="<?php echo $hash; ?>">
  <br /><input type='submit' value="Proceed to secure payment processing site">
</form>

<?php
  $myFile = "../dev/output/prepay.dat";
	$fh = fopen($myFile, 'a') or die("can't open file");
	$stringData = "\nGolf Tournament 2013\t" . $orderName . "\t" . $email . "\t" . $phone . "\t" . $team . "\t" . $p2 . "\t" . $p3 . "\t" . $p4 . "\t" . $startTime . "\t" . $donation . "\t" . $total;
	fwrite($fh, $stringData);
	fclose($fh);
?>
</body>
</html>

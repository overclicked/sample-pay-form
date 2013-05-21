<?php

/* NOTE:

Change the SSL Homepage variable on the post page and
change the gateway on this page before making this form live 
(switch both to the prod variable)

*/

?><html>
<head>
<title>Event Title</title>
<!-- Load local CSS for confirmation & receipt pages -->
<link href="css/main.css" rel="stylesheet" type="text/css">
</head>

<body>
<h1>Event Title</h1>

<?php

/* Fetch the form data */
$First_Name = $_POST['First_Name'];
$Last_Name = $_POST['Last_Name'];
$phone = $_POST['phone'];
$email = $_POST['email'];

/* Combine first and last name into a single string for cleaner submission.
The orderName variable is a QuikPay default.  */
$orderName = $First_Name ." ". $Last_Name;

/* The following variables rarely change. 
Currently, only the Chancellors' Cup tournament uses a different key and orderType.
Both are QuikPay default variables.  They connect the payment to the right fiscal account. */
$key = "cnrevt2010Jul"; 
$orderType = "CNR Events";

/* The NelNet system has a production and a development system.  Just comment out whichever one you aren't using.
Payments submitted to the "dev" server aren't actually charged, though they otherwise behave like a real submission for testing purposes.
Always, always test your submissions before making any form live. */
$gateway = "https://www.acs.ncsu.edu/scripts/nelnet/QuikPAY.pl";//prod
//$gateway = "https://webappdv.acs.ncsu.edu/scripts/nelnet/QuikPAY.pl";//dev

/* The last of the QuikPay variables.
orderNumber is always 1 for our purposes.
amountDue is valued in pennies.  We'll get to that later. */
$timestamp = time();
$orderNumber = 1;
$amountDue = 0;
?>

<p>Your registration is almost complete.  Please verify the following information and then click the button below to continue on to the credit card transaction processing on NCSU's secure servers.</p>
<!-- A table of the submitted variables gathered from the initial form for the user to verify that they entered the right data. -->
<table border="1" width="400px" >
<tr><td width="100">Name :</td><td width="293"><?php echo $orderName; ?></td></tr>
<tr><td>Phone : </td><td><?php echo $phone; ?></td></tr>
<tr><td>Email : </td><td><?php echo $email; ?></td></tr>
</table>

<p>What you are purchasing: </p>
<!-- A list and value of all charges -->
<table border="1" width="500px" >
<?php
$total = 15; //The value in dollars of whatever the registrant is purchasing.
// $total will typically be calcuated based on the variables they selected in the initial form, like number of tickets.
// Once it's set, we convert it into $amountDue by converting dollars to pennies.
$amountDue = $total*100;
?>

<tr><td><strong>Total</strong></td><td class="important"><?php echo "<strong>$". $total . ".00</strong>";?></td></tr>
</table>

<?php
/* Most every form allows for an additional donation along with their purchase.
Donations have to go through the Online Giving system, linked below. */
if (isset($_POST['donation'])){
	$donation = $_POST['donation'];
?>

<h3>Additional Donation</h3><p>To make an additional donation, please use <a href="https://ccfn.ncsu.edu/advancement-services/giving/NR/" target=_blank>our online giving form</a>.  Thank you for your donation!</p>

<?php

	}

?>


<p>To proceed with processing a credit card transaction for your purchase of <strong>$<?php echo $total ?>.00</strong>, please click the button below.  If there is something wrong with your order, please click the back button on your browser and make any necessary corrections and then click the "Register" button again.</p>
<!-- The following submits all the information to the QuikPay system once the registrant confirms that it's all valid. -->
<form name='form1' method='post' action="<?php echo $gateway; ?>" enctype='application/x-www-form-urlencoded'>
  <!-- The redirectURL points to the _post page; the formal receipt for the submission -->
  <input type='hidden' name='redirectUrl' value="https://ssl.ncsu.edu/cnr/transactions/event_name_post.php">
  <input type='hidden' name='amountDue' value="<?php echo $amountDue; ?>">
  <!-- An arbitrary name that will need to be the same on this page and the _post page, so that the _post page pulls the correct results -->
  <input type='hidden' name='orderDescription' value="Event Name">
  <input type='hidden' name='orderNumber' value="<?php echo $orderNumber; ?>">
  <input type='hidden' name='orderType' value="<?php echo $orderType; ?>">
  <input type='hidden' name='orderName' value="<?php echo $orderName; ?>">
  <!-- QuikPay allows for 8 "userChoice" variables.  Use them for any remaining data you need to pass through the form.
  If you have more variables than that to pass, concatenate them together into a single, separated string.
  You can convert the string into an array on the _post page to ensure all the data gets through.
  If you're unfamiliar with the process, look up "explode" on http://php.net/ -->
  <input type='hidden' name='userChoice1' value="<?php echo $email; ?>">
  <input type='hidden' name='userChoice2' value="<?php echo $phone; ?>">
  <input type='hidden' name='timestamp' value="<?php echo $timestamp; ?>">
  
  <?php 
  //A security hash used to encrypt user data in transit.  Required for QuikPay.
  $valueForHash = $amountDue.$orderNumber.$timestamp.$key;
  $hash = md5($valueForHash);
  ?>
  <input type='hidden' name='hash' value="<?php echo $hash; ?>">
  <br />
  <input type='submit' value="Proceed to secure payment processing site">
</form>

<?php
  /*Data is captured prior to payment submission to a generic .dat file called "prepay.dat"
  We'll capture data to an event-specific file on the _post page, this is purely in case something goes wrong during payment processing.
  Use the same string here that you'll use on the _post page, with the addition of the event name at the front. */
  $myFile = "../dev/output/prepay.dat";
	$fh = fopen($myFile, 'a') or die("can't open file");
	//Keep the data tab-separated (\t) for easy translation to a spreadsheet for the event managers.
	$stringData = "\nEvent Name 2013\t" . $timestamp . "\t" . $orderName . "\t" . $email . "\t" . $phone . "\t" . $total;
	fwrite($fh, $stringData);
	fclose($fh);
?>
</body>
</html>

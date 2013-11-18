<form method="post" action="buyform">

<h5><?php echo $errors?></h5>
<h3>Please input:</h3>
<?php echo form_open('buytickets'); ?>

<h5>First Name</h5>
<input type="text" name="fname" value="" size="50" />

<h5>Last Name</h5>
<input type="text" name="lname" value="" size="50" />

<h5>Credit Card Number</h5>
<input type="text" name="ccnum" value="" size="50" />

<h5>Expiry Date (MM/YY)</h5>
<input type="text" name="ccexp" value="" size="5" />

<h5>Total: $6.00</h5>

<div><input type="submit" value="Submit" /></div>

</form>

</body>
</html>

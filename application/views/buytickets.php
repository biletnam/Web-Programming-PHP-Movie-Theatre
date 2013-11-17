
<h5>Subtotal:$10</h5>
<?php echo $this->validation->error_string; ?>

<?php echo form_open('form'); ?>

<h5>Credit Card Number</h5>
<input type="text" name="ccnum" value="" size="50" />

<h5>Expiry Date (MM/YY)</h5>
<input type="text" name="ccexp" value="" size="50" />

<h5>Password Confirm</h5>
<input type="text" name="passconf" value="" size="50" />

<h5>Email Address</h5>
<input type="text" name="email" value="" size="50" />

<div><input type="submit" value="Submit" /></div>

</form>

</body>
</html>

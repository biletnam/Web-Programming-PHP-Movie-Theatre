<html>



<h3>Well done!</h3>

<p>Thank You for purchasing a ticket!</p>
<?php
if(!empty($ticket)){
	$tmpl = array ( 'table_open'  => '<table border="1" cellpadding="2" cellspacing="1" class="mytable">' );
	$this->table->set_template($tmpl);
	echo $this->table->generate($ticket);
}; 

?>
<input type="button" onclick="window.print()" value="Print Ticket">
</body>


</html>

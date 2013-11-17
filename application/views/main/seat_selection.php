<!DOCTYPE html> 
<html>

<head>
	<title><?php echo "Pick a Seat"?></title>
	<link href="<?php echo base_url();?>css/default.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="<?php echo base_url();?>js/drawseats.js" ></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
</head>
<body>
	<h1> Choose your theater seat by clicking on a square in the layout below:</h1>
	
	<div class="seatholder">
	<span><img src="<?php echo base_url()?>css/images/seat_available.jpg" alt="seats" height="63" width="68" style="float:left"></span>
	<span><img src="<?php echo base_url()?>css/images/seat_available.jpg" alt="seats" height="63" width="68" style="float:left"></span>
	<span><img src="<?php echo base_url()?>css/images/seat_available.jpg" alt="seats" height="63" width="68" style="float:left"></span>
	</div>

	<div style="float:left; clear:both">
	<?php echo anchor('','Go to Checkout')?>
	</div>

	<script>
	var lastIndex  = -1;
	var seatsReserved = [];


	
	

	$(document).ready(function(){
		loadReserved(seatsReserved);
	});

	function loadReserved(seatsReserved){
		for (var i = 0; i < seatsReserved.length; i++){
			var index = seatsReserved[i] - 1;
			$(".seatholder span img:eq(" + index +")").attr("src", "<?php echo base_url()?>css/images/seat_taken.jpg");
		}
	}
		
	$(".seatholder span img").click(function() {
			if ($(this).attr("src")=="<?php echo base_url()?>css/images/seat_taken.jpg"){
				alert("Sorry this seat is taken!");
			}
			else {
				var seatNo = $(".seatholder span img").index(this);
				if (lastIndex != -1){
					$(".seatholder span img:eq(" + lastIndex +")").attr("src", "<?php echo base_url()?>css/images/seat_available.jpg");
				}
				$(this).attr("src", "<?php echo base_url()?>css/images/current_selected.jpg");
				lastIndex = seatNo;
			}
	});



	
	
	
	</script>
		
</body>
</html>

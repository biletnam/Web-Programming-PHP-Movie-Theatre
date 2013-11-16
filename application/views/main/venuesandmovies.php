<form method="post" action="getShowtimes">
Select by Venue<input name="movieven" type="radio" id="ven" value="No" checked="checked" />
Select by Movie<input name="movieven" type="radio" id="mov" value="Yes" />
<br />
<script type="text/javascript" 
src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript">
    $("#mov").click(function() {
        $("#movdrop").removeAttr("disabled");
        $("#vendrop").attr("disabled","disabled");
        $("#vendrop").val("Please Select a Venue:");

        $("#submitmov").removeAttr("disabled");
        $("#submitven").attr("disabled","disabled");
    });
    $("#ven").click(function() {
        $("#vendrop").removeAttr("disabled");
        $("#movdrop").attr("disabled","disabled");
        $("#movdrop").val("Please Select a Movie:");
        
        $("#submitven").removeAttr("disabled");
        $("#submitmov").attr("disabled","disabled");
    });
</script>
<?php
echo 'Select a Venue: ';
$js = 'id="vendrop"';
echo form_dropdown('venueDrop', $venues, '-1', $js);

$js = 'id="submitven"';
echo form_submit('submitven','Submit',$js). "<br />";

$js = 'id="movdrop"';
echo 'Select a Movie: ';
echo form_dropdown('movieDrop', $movies, '-1',$js);

$js = 'id="submitmov"';
echo form_submit('submitmov','Submit',$js). "<br />";
?>
<script>
document.getElementById("movdrop").disabled =true;
document.getElementById("submitmov").disabled =true;</script>
<form method="post" action="main/getVenue">
<?php
echo 'Select a date: ';
echo form_dropdown('dateDrop', $dates, '-1');
echo form_submit('submitdate','Submit');
?>




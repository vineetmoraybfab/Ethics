<!DOCTYPE html>
<html>
<head>
<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}

::-webkit-input-placeholder {
  text-align: center;
}

:-moz-placeholder {
  text-align: center;
}

</style>
</head>
<body>

<h2 style="text-align:center">Welcome to Vineet's Mobile device!!</h2>

<table>
  <tr>
    <th style="text-align:center" colspan="3">Termux Mobile Function</th>
    <th style="text-align:center" colspan="2">Function Result</th>
  </tr>
  <tr>
    <td> Battery </td>
    <form method="post">
    	<td style="text-align:center" colspan="2">
    		<input type="submit" id="id_bat" name="id_bat" value="Get status">
    	</td>
    </form>
    <td style="text-align:center" colspan="2">
    	<?php 
        	if(isset($_POST['id_bat'])) {
            	echo "Button clicked";
        	} else {
        		echo "Not Available";
            }
        ?>
    </td>
  </tr>
  <tr>
    <td> Take Photo </td>
    <td style="text-align:center;width:20%">
    	<input type="button" id="id_photo_f" name="id_photo_f" value="Front">
    </td>
    <td style="text-align:center;width:20%">
    	<input type="button" id="id_photo_r" name="id_photo_r" value="Rear">
    </td>
    <td style="text-align:center">
    	<?php 
        	echo "Not Available";
        ?>
    </td>
    <td style="text-align:center">
    	<?php 
        	echo "Not Available";
        ?>
    </td>
  </tr>
  <tr>
    <td> Send SMS </td>
    <td style="text-align:center" colspan="2">
    	<input type="text" id="id_sms_number" name="id_sms_number" placeholder="Indian Phone Number">
    </td>
    <td style="text-align:center" colspan="2">
    	<input type="button" id="id_sms_send" name="id_sms_send" value="Send">
    </td>
  </tr>
  <tr>
    <td> Make a call </td>
    <td style="text-align:center" colspan="2">
    	<input type="text" id="is_call_number" name="is_call_number" placeholder="Indian Phone Number">
    </td>
    <td style="text-align:center" colspan="2">
    	<input type="button" id="id_call" name="id_call" value="Call">
    </td>
  </tr>
  <tr>
    <td colspan="3"> Toggle Torch </td>
    <td style="text-align:center" colspan="2">
    	<input type="button" id="id_torch" name="id_torch" value="Toggle">
    </td>
  </tr>
</table>

</body>
</html>

<?php
	session_start();
	header("Expires: Fri, 28 Jul 1989 15:10:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
?>
<!DOCTYPE html>
<html>
<head>

<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-4453537651987229" crossorigin="anonymous">
</script>

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
        	if(isset($_SESSION['id_bat']) || isset($_POST['id_bat'])) {
			$_SESSION['id_bat'] = "1";
			$batfile = fopen("battery","w");
			fwrite($batfile,"1");
			fclose($batfile);
			if(file_exists("battery_result")) {
				$batfile = fopen("battery_result","r");
				$status = fread($batfile,filesize("battery_result"));
				echo "$status";
				fclose($batfile);
			} else {
				echo "Unknown";
			}
        	} else {
        		echo "Unknown";
      		}
        ?>
    </td>
  </tr>
  <tr>
    <td> Take Photo </td>
    <form method="post">
    	<td style="text-align:center;width:20%">
    		<input type="submit" id="id_photo_f" name="id_photo_f" value="Front">
    	</td>
    	<td style="text-align:center;width:20%">
    		<input type="submit" id="id_photo_r" name="id_photo_r" value="Rear">
    	</td>
    </form>
    <td style="text-align:center">
    	<?php 
        	if(isset($_SESSION['id_photo_f']) || isset($_POST['id_photo_f'])) {
			$_SESSION['id_photo_f'] = "1";
			$batfile = fopen("front_photo","w");
			fwrite($batfile,"1");
			fclose($batfile);
			if(file_exists("front_photo_result")) {
				echo "<img src='front_photo_result' style='border: 1px solid #000; max-width:200px; max-height:200px; object-fit:contain' >";
			} else {
        			echo "Not Available";
			}
        	} else {
        		echo "Not Available";
      		}
        ?>
    </td>
    <td style="text-align:center">
    	<?php 
        	if(isset($_SESSION['id_photo_r']) || isset($_POST['id_photo_r'])) {
			$_SESSION['id_photo_r'] = "1";
			$batfile = fopen("rear_photo","w");
			fwrite($batfile,"1");
			fclose($batfile);
			if(file_exists("rear_photo_result")) {
				echo "<img src='rear_photo_result' style='border: 1px solid #000; max-width:200px; max-height:200px; object-fit:contain' >";
			} else {
        			echo "Not Available";
			}
        	} else {
        		echo "Not Available";
      		}
        ?>
    </td>
  </tr>
  <tr>
    <td> Send SMS </td>
    <form method="post">
	    <td style="text-align:center" colspan="2">
    		<input type="text" id="id_sms_number" name="id_sms_number" placeholder="Indian Phone Number">
	    </td>
	    <td style="text-align:center">
    		<input type="submit" id="id_sms_send" name="id_sms_send" value="Send">
	    </td>
    </form>
    <td style="text-align:center">
    	<?php 
        	if(isset($_POST['id_sms_send'])) {
			if( !empty($_POST['id_sms_number']) && strlen($_POST['id_sms_number'])==10 ) {
				$batfile = fopen("send_sms","w");
				fwrite($batfile,"1 ".$_POST['id_sms_number']);
				fclose($batfile);
				echo "SMS request initiated!!";
			} else {
        			echo "Invalid input!!";
			}
        	} else {
        		echo "No Operation!!";
      		}
        ?>
    </td>
  </tr>
  <tr>
    <td> Make a call </td>
    <form method="post">
	    <td style="text-align:center" colspan="2">
    		<input type="text" id="id_call_number" name="id_call_number" placeholder="Indian Phone Number">
	    </td>
	    <td style="text-align:center">
    		<input type="submit" id="id_call" name="id_call" value="Call">
	    </td>
    </form>
    <td style="text-align:center">
    	<?php 
        	if(isset($_POST['id_call'])) {
			if( !empty($_POST['id_call_number']) && strlen($_POST['id_call_number'])==10 ) {
				$batfile = fopen("call_phone_number","w");
				fwrite($batfile,"1 ".$_POST['id_call_number']);
				fclose($batfile);
				echo "CALL request initiated!!";
			} else {
        			echo "Invalid input!!";
			}
        	} else {
        		echo "No Operation!!";
      		}
        ?>
    </td>
  </tr>
  <tr>
    <td colspan="3"> Toggle Torch </td>
    <form method="post">
	    <td style="text-align:center">
    		<input type="submit" id="id_torch" name="id_torch" value="Toggle">
	    </td>
    </form>
    <td style="text-align:center">
    	<?php 
        	if(isset($_POST['id_torch'])) {
			$batfile = fopen("torch","w");
			fwrite($batfile,"1 ");
			fclose($batfile);
			echo "TORCH Toggle request initiated!!";
        	} else {
        		echo "No Operation!!";
      		}
        ?>
    </td>
  </tr>
</table>

</body>
</html>

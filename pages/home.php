<?php include("../snippets/_header.php"); ?>
		<div class="container mb-5 mt-5 content" id="container">
			<div class="card" style="background-color: rgba(0,0,0,0.2);">
				<div class="card-body">
					<center>
						<span class="badge bg- badge-outline-success">CVV: <span id="cLive">0</span></span>&nbsp;
						<span class="badge bg- badge-outline-warning">CCN: <span id="cWarn">0</span></span>&nbsp;
						<span class="badge bg- badge-outline-danger">Dead: <span id="cDie">0</span></span>&nbsp;
						<span class="badge bg- badge-outline-light">Checked: <span id="checked">0</span></span>&nbsp;
						<span class="badge bg- badge-outline-info">Total: <span id="total">0</span></span>&nbsp;
					</center>
					<br>
					<textarea type="text" class="md-textarea form-control" style="border-color: #B0CCF0; background: transparent; color: #FFFFFF; text-align: center;" id="lista" rows="5" placeholder="xxxxxxxxxxxxxxxx|xx|xxxx|xxx" required=""></textarea>
					<br>
					<select name="gate" id="gate" class="form-control" style="margin-bottom: 20px;background: transparent;color: #fff;border-color:yellow;">
						<option style="background:#151519;color:white" value="apis/chk1.php">Useless NON-SK gate (sometimes good for CCN) [GATE ARGON]</option>
						<option style="background:#151519;color:white" value="apis/chk2.php">Useless NON-SK gate (sometimes good for CCN) [GATE NEON]</option>
						<option style="background:#151519;color:white" value="apis/chk3.php">New Gate</option>
						<option style="background:#151519;color:white" value="apis/chk4.php" selected>Stripe Intents Gate</option>
            <option style="background:#151519;color:white" value="apis/chk5.php" >Woocommerce 5$</option>
						<option style="background:#151519;color:white" value="apis/sk2.php" disabled>Work in Progress...</option>
					</select>
					<center>
						<button type="button" class="btn btn-outline-light" id="testar" onclick="start()">Start</button>
					</center>
				</div>
			</div>
			<br>
			<div class="col-md-auto">
				<div class="card" style="background:#2c2e36;background-color: rgba(0,0,0,0.2);">
					<div style="position: absolute;top: 0;right: 0">
						<button id="mostra" class="btn btn-outline-light btn-block btn-sm" style="border-color: #B0CCF0;" data-bs-toggle="collapse" data-bs-target="#cvvCollapse">SHOW/HIDE</button><br>
					</div>
					<div class="card-body">
						<h6>
							<p style="text-align:left; color: #fff;" class="card-title"><span class="badge bg- badge-outline-success">CVV: &nbsp; <span id="cLive2">0</span></span></p>
						</h6>
						<div class="collapse" id="cvvCollapse">
              				<span id="cvvCards" class="aprovadas"></span>
						</div>
					</div>
				</div>
			</div>
			<br>
			<div class="col-md-auto">
				<div class="card" style="background:#2c2e36;background-color: rgba(0,0,0,0.2);">
					<div style="position: absolute;top: 0;right: 0">
						<button id="mostra3" class="btn btn-outline-light btn-block btn-sm" style="border-color: #B0CCF0;" data-bs-toggle="collapse" data-bs-target="#ccnCollapse">SHOW/HIDE</button><br>
					</div>
					<div class="card-body">
						<h6>
							<p style="text-align:left; color: #fff;" class="card-title"><span class="badge bg- badge-outline-warning">CCN: &nbsp; <span id="cWarn2">0</span></span> </p>
						</h6>
						<div class="collapse" id="ccnCollapse">
              <span id="ccnCards" class="edrovadas"></span>
						</div>
					</div>
				</div>
			</div>
			<br>
			<div class="col-md-auto">
				<div class="card" style="background:#2c2e36;background-color: rgba(0,0,0,0.2);">
					<div style="position: absolute;top: 0;right: 0">
						<button id="mostra2" class="btn btn-outline-light btn-block btn-sm" style="border-color: #B0CCF0;" data-bs-toggle="collapse" data-bs-target="#deadCollapse">SHOW/HIDE</button><br>
					</div>
					<div class="card-body">
						<h6>
							<p style="text-align:left; color: #fff;" class="card-title"><span class="badge bg- badge-outline-danger">Dead: &nbsp; <span id="cDie2">0</span></span></p>
						</h6>
						<div class="collapse" id="deadCollapse">
              				<span id="deadCards" class="reprovadas"></span>
						</div>
					</div>
				</div>
			</div>
		</div>
	
	<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	<script>
		function start() {
			var CVVs = 0,
				CCNs = 0,
				DEADs = 0,
				Checked = 0;
			var ccarray = [];
			var gate = document.getElementById("gate").value;
			var inputTextareaValue = document.getElementById("lista").value;
			ccarray = inputTextareaValue.split(/\r?\n/);
			document.getElementById("total").innerHTML = ccarray.length;
			var i = 0;
			while (i < ccarray.length) {
				const xmlhttp = new XMLHttpRequest;
				xmlhttp.onload = function() {
					var response = xmlhttp.responseText;
					if (response.includes('#CVV')) {
						document.getElementById("cvvCards").innerHTML += xmlhttp.responseText + "<br>";
						CVVs++;
						document.getElementById('cLive').innerHTML = CVVs;
						document.getElementById('cLive2').innerHTML = CVVs;
					} else if (response.includes('#CCN')) {
						document.getElementById("ccnCards").innerHTML += xmlhttp.responseText + "<br>";
						CCNs++;
						document.getElementById('cWarn').innerHTML = CCNs;
						document.getElementById('cWarn2').innerHTML = CCNs;
					} else {
						document.getElementById("deadCards").innerHTML += xmlhttp.responseText + "<br>";
						DEADs++;
						document.getElementById('cDie').innerHTML = DEADs;
						document.getElementById('cDie2').innerHTML = DEADs;
					}
					ccarray.shift();
					ccstring = ccarray.join("\n");
					document.getElementById("lista").value = ccstring;
					Checked++;
					document.getElementById("checked").innerHTML = Checked;
				}
				xmlhttp.open("GET", gate + "?lista=" + ccarray[i]);
				xmlhttp.send();
				i++;
			}
		}

		function cleardead() {
			document.getElementById("deadCards").innerHTML = "";
		}
  </script>
<?php include("../snippets/_footer.php"); ?>
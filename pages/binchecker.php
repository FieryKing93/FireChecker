<?php include("../snippets/_header.php") ?>
        <div class="container mb-5 mt-5 content" id="container">
			<div class="card" style="background-color: rgba(0,0,0,0.2); color: white;">
				<div class="card-body">
                    <center>
                        <h2 class="card-title mb-5">Bin Checker</h2>
                    </center>
                    <label id="binLabel">Enter BIN below</label>
                    <textarea type="text" class="md-textarea form-control" id="binTextarea" style="border-color: #B0CCF0; background: transparent; color: #FFFFFF; text-align: center;" rows="1" placeholder="xxxxxx" required></textarea>
                    <br>
					<select name="binGate" id="binGate" onchange="binSelectChange()" class="form-select" style="margin-bottom: 20px;background: transparent;color: #fff; border-color:yellow;">
                        <option style="background:#151519;color:white" value="single">Single Bin Checker</option>
                        <option style="background:#151519;color:white" value="multi" disabled>Multi Bin Checker</option>
					</select>
                    <br>
                    <button class="btn btn-outline-primary" onclick="binCheck()">Check BIN</button>
                </div>
            </div>
            <div class="card" style="background:#2c2e36; background-color: rgba(0,0,0,0.2); color: #fff">
                <div id="resultContainer">
                    <div class="card-body">
                        <div class="row my-4">
                            <div class="col-md-6">Type: <span id="type"></span></div>
                            <div class="col-md-6">Scheme: <span id="scheme"></span></div>
                        </div>
                        <div class="row my-4">
                            <div class="col-md-6">Country: <span id="country"></span></div>
                            <div class="col-md-6">Currency: <span id="currency"></span></div>
                        </div>
                        <div class="row my-4">
                            <div class="col-md-6">Bank Name: <span id="bankName"></span></div>
                            <div class="col-md-6">Bank Phone Number: <span id="bankPhone"></span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            function binSelectChange(){
                if(document.getElementById('binGate').value == 'multi'){
                    document.getElementById('binTextarea').rows = "6";
                    document.getElementById('binLabel').innerHTML = 'Enter BINs below';
                    document.getElementById('resultContainer').innerHTML = '';
                } else if(document.getElementById('binGate').value == 'single'){
                    document.getElementById('binTextarea').rows = "1";
                    document.getElementById('binLabel').innerHTML = 'Enter BIN below';
                    document.getElementById('resultContainer').innerHTML = '<div class="card-body"> <div class="row my-4"> <div class="col-md-6">Type: <span id="type"></span></div><div class="col-md-6">Scheme: <span id="scheme"></span></div></div><div class="row my-4"> <div class="col-md-6">Country: <span id="country"></span></div><div class="col-md-6">Currency: <span id="currency"></span></div></div><div class="row my-4"> <div class="col-md-6">Bank Name: <span id="bankName"></span></div><div class="col-md-6">Bank Phone Number: <span id="bankPhone"></span></div></div></div>'
                }
            }
            function binCheck(){
                if(document.getElementById("binGate").value == 'single'){
                    var url = 'https://lookup.binlist.net/' + document.getElementById("binTextarea").value;
                    const xhttp = new XMLHttpRequest;
                    xhttp.onload = function(){
                        var res = xhttp.responseText;
                        res = JSON.parse(res);
                        console.log(res);
                        document.getElementById("type").innerHTML = res.type;
                        document.getElementById("scheme").innerHTML = res.scheme;
                        document.getElementById("country").innerHTML = res.country.name + ' (' + res.country.alpha2 + ')';
                        document.getElementById("currency").innerHTML = res.country.currency;
                        document.getElementById("bankName").innerHTML = res.bank.name;
                        document.getElementById("bankPhone").innerHTML = res.bank.phone;
                    }
                    xhttp.open("GET", url);
                    xhttp.send();
                }
            }
        </script>
<?php include("../snippets/_footer.php"); ?>
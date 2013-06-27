<?php
include ("inc/db.php");

$reptype = 4;

$quarter = $_GET["quarter"];
$mapno = $_GET["mapno"];

$officeSingle = false;
$singquery = "select SingleTenant from property where id = $mapno";
$singresult = mysql_query($singquery);
if ($singrow = mysql_fetch_array($singresult)) {
	$singleTenant = $singrow["SingleTenant"];
	if ($singleTenant == "1") {
		$officeSingle = true;
	}
}


$query = "select p.id, p.BuildingName, p.GeoNumber, p.GeoAddress
from property p
where p.id = $mapno";

$result = mysql_query($query);
if ($row = mysql_fetch_array($result)) {
	$buildingName = $row["BuildingName"];
	$geoNumber = $row["GeoNumber"];
	$geoAddress = $row["GeoAddress"];
	$proploc = $geoNumber . " " . $geoAddress;
}


$transTypeOpts = "";
$query0 = "select id, Description from cmu_transaction_type order by Description";
$result0 = mysql_query($query0);
while ($row0 = mysql_fetch_array($result0)) {
	$id = $row0["id"];
	$desc = $row0["Description"];
	$transTypeOpts .= "<option value=\"$id\">$desc</option>";
}


?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>Westchase District: Quarterly Commercial Market Update: Leases</title>
    <link href="styles/cmu.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="scripts/cmu.js"></script>
	<script type="text/javascript" src="scripts/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="scripts/jquery.validate.min.js"></script>
	<script type="text/javascript" src="scripts/jquery.maskedinput.js"></script>


	<script type="text/javascript">
	function addTransRow() {
		var transBody = document.getElementById("transactions");

		var transRow = document.createElement("tr");

		var td3 = document.createElement("td");

		var transType = document.createElement("select");
		transType.setAttribute("name", "transType[]");
		//transType.innerHTML = '<option value="3">Expansion</option><option value="1">New</option><option value="2">Renewal</option><option value="4">Vacated</option>';

		transType.options.length = 0;
		transType.options[0] = new Option("Expansion", "3");
		transType.options[1] = new Option("New", "1");
		transType.options[2] = new Option("Renewal", "2");
		transType.options[3] = new Option("Vacated", "4");

		td3.appendChild(transType);
		transRow.appendChild(td3);

		var td2 = document.createElement("td");
		var sqftElem = document.createElement("input");
		sqftElem.setAttribute("type", "text");
		sqftElem.setAttribute("name", "transSqFt[]");
		sqftElem.setAttribute("size", "6");
		sqftElem.setAttribute("maxlength", "15");
		td2.appendChild(sqftElem);
		transRow.appendChild(td2);

		var td1 = document.createElement("td");
		var tenInput = document.createElement("input");
		tenInput.setAttribute("type", "text");
		tenInput.setAttribute("name", "transTenant[]");
		tenInput.setAttribute("size", "30");
		tenInput.setAttribute("maxlength", "255");
		td1.appendChild(tenInput);
		transRow.appendChild(td1);

		// var td4 = document.createElement("td");
		// var transOwner = document.createElement("input");
		// transOwner.setAttribute("type", "text");
		// transOwner.setAttribute("name", "transOwnerRep[]");
		// transOwner.setAttribute("size", "35");
		// transOwner.setAttribute("maxlength", "255");
		// td4.appendChild(transOwner);
		// transRow.appendChild(td4);

		var td5 = document.createElement("td");
		var transTenant = document.createElement("input");
		transTenant.setAttribute("type", "text");
		transTenant.setAttribute("name", "transTenantRep[]");
		transTenant.setAttribute("size", "75");
		transTenant.setAttribute("maxlength", "255");
		td5.appendChild(transTenant);
		transRow.appendChild(td5);

		transBody.appendChild(transRow);
	}
	</script>

	<script type="text/javascript">
	jQuery.validator.addMethod("percent", function(value, element) {
		return this.optional(element) || /^100\%?$/i.test(value) || /^[1-9]?[0-9]\.?[0-9]*\%?$/i.test(value);
	}, "Please enter a valid percent between 0 and 100. ex. 25 or 25.2%");

	jQuery.validator.addMethod("firstsqft", function(value, element) {
		return checkTransactions();
	}, "");


	$().ready(function() {
		$(".required").after("<span class='requiredfield'>*</span>");

	  	$("input.phone").mask("(999) 999-9999");

		$("#cmuform").validate({
		});
	});
	
	function validateForm() {
		var errors = "";
		var occupancyInt = parseInt($("#occ_rate").val());
		if (occupancyInt < 0 || occupancyInt > 100) {
			errors += "Occupancy, " + occupancyInt + ", is outside range (0 - 100)\n";
		}
		if (errors.length > 0) {
			alert("Invalid input detected:\n\n" + errors);
			return false;
		}
		return true;
	}

	function checkTransactions() {
		var hasTrans = false;
		var transBody = document.getElementById("transactions");
		if (transBody) {
			var transRows = transBody.getElementsByTagName("tr");
			if (transRows) {
				for (var i = 0; i < transRows.length; i++) {
					var transRow = transRows[i];
					var transRowInputs = transRow.getElementsByTagName("input");
					if (transRowInputs && transRowInputs[0].value && transRowInputs[0].value != "") {
						hasTrans = true;
						break;
					}
				}
			}
		}
		if (!hasTrans) {
			return confirm("You have not entered any transactions.  Is that correct?");
		}
		return true;
	}
	
	
	</script>

</head>

<body>

<div class="header">
	<div class="container">
    	<h1>Westchase District</h1>
        <h2>Quarterly Commercial Market Update</h2>
    </div>
</div>

<div class="body">
    <div class="container">
      	<div class="content">

<?php
	if (!empty($error)) {
    	echo "<div class=\"error\">
        	<p>$error</p>
            <p>Please Contact Jonathan Lowe at <a href=\"mailto:jlowe@westchasedistrict.com\">jlowe@westchasedistrict.com</a> or 713-780-9434</p>
        </div>";
    } else {
?>


<h3 class="mapno">Map No: <span class="value"><?php echo $mapno ?></span></h3>
<h4 class="propname">Property Name: <span class="value"><?php echo $buildingName ?></span></h4>
<h4 class="proploc">Property Location: <span class="value"><?php echo $proploc ?></span></h4>


<form action="process.php" method="post" id="cmuform">
<div>
<input type="hidden" name="quarter" value="<?php echo $quarter ?>" />
<input type="hidden" name="cmutype" value="5" />
<input type="hidden" name="mapno" value="<?php echo $mapno ?>" />


<?php if (!$officeSingle) { ?>

	<h5>Note: This information is withheld from public reports and is for internal use only.</h5>
	
	<table class="transactioninfos">
	<caption>During the quarter, any new leases, renewals, expansions, or move-outs?  Please complete below.</caption>
	<thead><tr><th>Type</th><th>Sq. Ft.</th><th>Tenant Name</th><th>Tenants Rep - Broker & Co</th></tr></thead>
	<tbody id="transactions">
	<tr>
	    <td><select name="transType[]"><?php echo $transTypeOpts ?></select></td>
		<td><input type="text" name="transSqFt[]" size="6" maxlength="15" class="firstsqft" /></td>
		<td><input type="text" name="transTenant[]" size="30"  maxlength="255" /></td>
	   	<!-- removed 2013-03-21 meeting
	   		<td><input type="text" name="transOwnerRep[]" size="35" maxlength="255" /></td>
	   	-->
	   	<td><input type="text" name="transTenantRep[]" size="75"  maxlength="255" /></td>
	</tr>
	<tr>
	    <td><select name="transType[]"><?php echo $transTypeOpts ?></select></td>
		<td><input type="text" name="transSqFt[]" size="6" maxlength="15" class="firstsqft" /></td>
		<td><input type="text" name="transTenant[]" size="30"  maxlength="255" /></td>
	   	<!-- removed 2013-03-21 meeting
	   		<td><input type="text" name="transOwnerRep[]" size="35" maxlength="255" /></td>
	   	-->
	   	<td><input type="text" name="transTenantRep[]" size="75"  maxlength="255" /></td>
	</tr>
	<tr>
	    <td><select name="transType[]"><?php echo $transTypeOpts ?></select></td>
		<td><input type="text" name="transSqFt[]" size="6" maxlength="15" class="firstsqft" /></td>
		<td><input type="text" name="transTenant[]" size="30"  maxlength="255" /></td>
	   	<!-- removed 2013-03-21 meeting
	   		<td><input type="text" name="transOwnerRep[]" size="35" maxlength="255" /></td>
	   	-->
	   	<td><input type="text" name="transTenantRep[]" size="75"  maxlength="255" /></td>
	</tr>
	</tbody>
	</table>
	<p><a href="javascript:addTransRow()">Add Transaction</a></p>

<?php } else { ?>
		
	<p>Leases not available for this property.</p>
	
<?php } ?>

  <!-- InstanceEndEditable -->

</div>

<input type="submit" value="Submit" id="submit_bttn" />

</form>

<?php } ?>

		</div><!-- end .content -->
	</div> <!-- end .container -->
</div> <!-- end .body -->

<div class="footer">
	<div class="container">
		<p><a href="http://www.westchasedistrict.com/app/cmu/">Westchase District Quarterly Market Update</a></p>
		<p><a href="http://www.westchasedistrict.com/">WestchaseDistrict.com</a></p>
    </div>
</div>

</body>
<!-- InstanceEnd --></html>
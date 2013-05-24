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

$transTypeOpts = "";
$query0 = "select id, Description from cmu_transaction_type order by Description";
$result0 = mysql_query($query0);
while ($row0 = mysql_fetch_array($result0)) {
	$id = $row0["id"];
	$desc = $row0["Description"];
	$transTypeOpts .= "<option value=\"$id\">$desc</option>";
}

$manager = "";
$email = "";
$wkPhone = "";
$faxPhone = "";
$mgrCompany = "";
$mgrCompanyAddress = "";

$query = "select p.id, p.BuildingName, p.GeoNumber, p.GeoAddress, p.GeoCity, p.GeoState, p.GeoZipcode, pb.FirstName, pb.LastName, pb.Email, pb.WkPhone, pb.Wkext, pb.FaxPhone,
			c.Company, c.StNumber, c.StAddress, c.RoomNo, c.City, c.State, c.ZipCode, p.BuildingSize
from phone_book pb
	inner join company c on pb.companyid = c.id
	inner join phone_book_category pbc on pb.id = pbc.phonebookid
	inner join phone_book_relation pbr on pb.id = pbr.phone_book
	inner join property p on pbr.property = p.id
where pbc.categorycode = 'bm'
	and p.id = $mapno
group by p.id";

$result = mysql_query($query);
if ($row = mysql_fetch_array($result)) {
	$buildingName = $row["BuildingName"];
	$geoNumber = $row["GeoNumber"];
	$geoAddress = $row["GeoAddress"];
	$proploc = $geoNumber . " " . $geoAddress;
	
	$propLocFull = $proploc;
	$propLocFull .= ' ' . $row["GeoCity"] . ', ' . $row["GeoState"] . ' ' . $row["GeoZipcode"];
	

	$buildingSize = $row['BuildingSize'];

	$firstName = $row["FirstName"];
	$lastName = $row["LastName"];
	$manager = $firstName . " " . $lastName;
	$email = $row["Email"];
	$wkPhone = $row["WkPhone"];
	$wkExt = $row["Wkext"];
	if (!empty($wkExt)) $wkPhone .= " x" . $wkExt;
	$faxPhone = $row["FaxPhone"];

	$mgrCompany = $row["Company"];

	$stNumber = $row["StNumber"];
	$stAddress = $row["StAddress"];
	$roomNo = $row["RoomNo"];
	$city = $row["City"];
	$state = $row["State"];
	$zipCode = $row["ZipCode"];
	$mgrCompanyAddress = $stNumber . " " . $stAddress . " ";
	if (!empty($roomNo)) {
		// todo: add # if roomNo doesn't start with suite
		$mgrCompanyAddress .= $roomNo . " ";
	}
	$mgrCompanyAddress .= $city . ", " . $state . " " . $zipCode;
} else {
	// $error = "Unable to locate data for this property.";

	$query = "select p.id, p.BuildingName, p.GeoNumber, p.GeoAddress, p.GeoCity, p.GeoState, p.GeoZipcode, p.BuildingSize
	from property p
	where p.id = $mapno";

	$result = mysql_query($query);
	if ($row = mysql_fetch_array($result)) {
		$buildingName = $row["BuildingName"];
		$geoNumber = $row["GeoNumber"];
		$geoAddress = $row["GeoAddress"];
		$proploc = $geoNumber . " " . $geoAddress;
	
		$propLocFull = $proploc;
		$propLocFull .= ' ' . $row["GeoCity"] . ', ' . $row["GeoState"] . ' ' . $row["GeoZipcode"];
		
		
		$buildingSize = $row['BuildingSize'];
	}

}

if (!empty($buildingSize)) {
	$buildingSize = number_format($buildingSize);
} else {
	$queryBS = "select sq_ft_for_lease from cmu_office_retail_svc where property = $mapno and quarter = ($quarter - 1)";
	$resultBS = mysql_query($queryBS);
	if ($rowBS = mysql_fetch_array($resultBS)) {
		$buildingSize = number_format($rowBS["sq_ft_for_lease"]);
	}
	
}

// define these since they might not exist in the DB (to avoid warning)
$agent = "";
$agentEmail = "";
$agentWkPhone = "";
$agentFaxPhone = "";
$agentCompany = "";
$companyAddress = "";


// query for leasing company
$query2 = "select pb.FirstName, pb.LastName, c.Company, c.StNumber, c.StAddress, c.RoomNo, c.City, c.State, c.ZipCode, pb.WkPhone, pb.Wkext, pb.FaxPhone, pb.Email
from phone_book_category ca
inner join phone_book_relation pbr on ca.phonebookid = pbr.phone_book
inner join phone_book pb on pbr.phone_book = pb.id
inner join company c on pb.companyid = c.id
where ca.categorycode = 'la'
and pbr.property = $mapno";
$result2 = mysql_query($query2);
if ($row2 = mysql_fetch_array($result2)) {
	$agentFirstName = $row2["FirstName"];
	$agentLastName = $row2["LastName"];
	$agent = $agentFirstName . " " . $agentLastName;
	$agentEmail = $row2["Email"];
	$agentWkPhone = $row2["WkPhone"];
	$agentWkExt = $row2["Wkext"];
	if (!empty($agentWkExt)) $agentWkPhone .= " x" . $agentWkExt;
	$agentFaxPhone = $row2["FaxPhone"];

	$agentCompany = $row2["Company"];

	$stNumber = $row2["StNumber"];
	$stAddress = $row2["StAddress"];
	$roomNo = $row2["RoomNo"];
	$city = $row2["City"];
	$state = $row2["State"];
	$zipCode = $row2["ZipCode"];
	$companyAddress = $stNumber . " " . $stAddress . " ";
	if (!empty($roomNo)) {
		# todo add # if roomNo doesn't start with suite
		$companyAddress .= $roomNo . " ";	
	}
	$companyAddress .= $city . ", " . $state . " " . $zipCode;
}


if (empty($mgrCompany) || empty($mgrCompanyAddress) || empty($manager) || empty($wkPhone) ||
    empty($faxPhone) || empty($email) || empty($agentCompany) || empty($companyAddress) ||
    empty($agent) || empty($agentEmail) || empty($agentWkPhone) || empty($agentFaxPhone)) {

	// last quarter results (to fill missing fields)
	$lastQuarter = $quarter - 1;
	$query3 = "select * from cmu_office_retail_svc where property = $mapno and quarter = $lastQuarter";
	$result3 = mysql_query($query3);
	if ($row3 = mysql_fetch_array($result3)) {
		if (empty($mgrCompany)) {
			$mgrCompany = $row3["mgmt_company"];
		}
		if (empty($mgrCompanyAddress)) {
			$mgrCompanyAddress = $row3["mgmt_company_addr"];
		}
		if (empty($manager)) {
			$manager = $row3["property_mgr"];
		}
		if (empty($wkPhone)) {
			$wkPhone = $row3["property_mgr_phone"];
		}
		if (empty($faxPhone)) {
			$faxPhone = $row3["property_mgr_fax"];
		}
		if (empty($email)) {
			$email = $row3["property_mgr_email"];
		}
		if (empty($agentCompany)) {
			$agentCompany = $row3["leasing_company"];
		}
		if (empty($companyAddress)) {
			$companyAddress = $row3["leasing_company_addr"];
		}
		if (empty($agent)) {
			$agent = $row3["leasing_agent"];
		}
		if (empty($agentEmail)) {
			$agentEmail = $row3["leasing_agent_email"];
		}
		if (empty($agentWkPhone)) {
			$agentWkPhone = $row3["leasing_agent_phone"];
		}
		if (empty($agentFaxPhone)) {
			$agentFaxPhone = $row3["leasing_agent_fax"];
		}
	}
}

?>

<!DOCTYPE html>
<html><!-- InstanceBegin template="/Templates/default.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>Westchase District: Quarterly Commercial Market Update: Office/Retail/Service Center</title>
    <link href="styles/cmu.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="scripts/cmu.js"></script>
	<script type="text/javascript" src="scripts/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="scripts/jquery.validate.min.js"></script>
	<script type="text/javascript" src="scripts/jquery.maskedinput.js"></script>


	<script type="text/javascript">
	function toggleForSale() {
		var disp = "block";
		var forSale = document.getElementById("for-sale").value;
		if (forSale == "0") disp = "none";
		document.getElementById("for-sale-name-row").style.display = disp;
		document.getElementById("for-sale-phone-row").style.display = disp;
	}
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
	
	
	function handleOnsiteChange() {
		if ($('#is-onsite').is(':checked')) {
		   $("#mgmt-addr").attr("readonly", "true");
		   $("#mgmt-addr").addClass("readonly");
		   $("#mgmt-addr").val('<?= $propLocFull ?>');
		} else {
		   $("#mgmt-addr").removeAttr("readonly");
		   $("#mgmt-addr").removeClass("readonly");
		   $("#mgmt-addr").val('<?= $mgrCompanyAddress ?>');
		} 
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

<h5 class="required-fields-desc">All Fields Required</h5>

<form action="process.php" method="post" id="cmuform">
<div>
<input type="hidden" name="quarter" value="<?php echo $quarter ?>" />
<input type="hidden" name="cmutype" value="<?php echo $reptype ?>" />
<input type="hidden" name="mapno" value="<?php echo $mapno ?>" />

<table>
	<tbody>
    	<tr><th><label for="completed_by">Name of Person filling out this form</label></th>
     	   <td><input type="text" name="completedBy" id="completed_by" size="60" maxlength="255" class="required" minlength="2" /></td></tr>
    </tbody>
</table>

<table>
	<caption>At End of Quarter</caption>
	<tbody>
    	<tr><th><label id="sq-ft-avail">Total Building Size (sq. ft)</label></th>
        	<td><input type="text" name="sqFtAvail" id="sq-ft-avail" class="required" value="<?= $buildingSize ?>" /></td></tr>
        <?php if ($officeSingle) { ?>
    	<tr><th>Occupancy Status</th>
     	   <td>
     	   		<label>Occupied <input type="radio" name="occupancy" value="100" checked="checked"/></label>
     	   		<label>Vacant <input type="radio" name="occupancy" value="0" /></label>
     	   </td></tr>
        <?php } else { ?>
    	<tr><th><label id="occ-rate">Occupancy Rate</label><span class="format">Example: 33% or 33</span></th>
     	   <td><input type="text" name="occupancy" id="occ_rate" class="required percent"/>
     	   </td></tr>
     	<input type="hidden" name="occupied" value="0" />
     	<!--
    	<tr><th><label id="occ-space">Occupied Space</label><span class="format">sq. ft</span></th>
     	   <td><input type="text" name="occupied" id="occ_space" class="required"/>
     	   </td></tr>
     	  -->
     	<?php } 
        if (!$officeSingle) { ?>
    	<tr><th><label id="largest-space">Largest Contiguous Space Available (sq. ft)</label></th>
        	<td><input type="text" name="largestSpace" id="largest-space" class="required" /></td></tr>
        <?php } ?>
        <!-- removed on 2013-03-21 meeting
    	<tr><th><label id="largest-space6">Largest Contiguous Space Available (within 6 months)</label></th>
        	<td><input type="text" name="largestSpace6" id="largest-space6" />sf.</td></tr>
    	<tr><th><label id="largest-space12">Largest Contiguous Space Available (within 12 months)</label></th>
        	<td><input type="text" name="largestSpace12" id="largest-space12" />sf.</td></tr>
        -->

    </tbody>
</table>

<table>
	<caption>Management Company</caption>
    <tbody>
    	<tr><th><label for="mgmt-name">Company Name</label></th>
        	<td><input type="text" name="mgmtCompany" id="mgmt-name" size="50" maxlength="255" value="<?php echo $mgrCompany ?>" class="required" /></td></tr>

        <tr><th><label for="prop-mgr">Building Manager</label></th>
            <td><input type="text" name="propertyMgr" id="prop-mgr" size="40" maxlength="255" value="<?php echo $manager ?>" class="required" /></td></tr>
            
    	<tr><th><label for="is-onsite">Onsite?</label></th>
        	<td><input type="checkbox" name="isOnsite" id="is-onsite" onchange="handleOnsiteChange()" /></td></tr>
        	
        
    	<tr><th><label for="mgmt-addr">Address</label></th>
        	<td><input type="text" name="mgmtCompanyAddr" id="mgmt-addr" size="75" maxlength="255" value="<?php echo $mgrCompanyAddress ?>" class="required" /></td></tr>

        <tr><th><label for="prop-mgr-phone">Phone</label></th>
            <td><input type="text" name="propertyMgrPhone" id="prop-mgr-phone" size="20" maxlength="20" value="<?php echo $wkPhone ?>" class="required phone" /></td></tr>
        <!--  removed on 2013-03-21 meeting
        	<tr><th><label for="prop-mgr-fax">Fax</label></th>
            <td><input type="text" name="propertyMgrFax" id="prop-mgr-fax" size="20" maxlength="20" value="<?php echo $faxPhone ?>" class="phone"/></td></tr>-->
        <tr><th><label for="prop-mgr-email">Email</label></th>
            <td><input type="text" name="propertyMgrEmail" id="prop-mgr-email" size="60" maxlength="255" value="<?php echo $email ?>" class="required email" /></td></tr>
    </tbody>
</table>

<table class="forsale">
	<tbody>
    	<tr><th><label for="for-sale">Building For Sale</label></th>
            <td><select name="forSale" id="for-sale" onchange="toggleForSale()">
            		<option value="0" selected="selected">No</option>
                    <option value="1">Yes</option></select></td></tr>
         <tr id="for-sale-name-row" style="display: none"><th><label for="for-sale-name">Contact</label></th>
         							<td><input type="text" name="forSaleContact" id="for-sale-name" maxlength="255" /></td></tr>
         <tr id="for-sale-phone-row" style="display: none"><th><label for="for-sale-phone">Phone</label></th>
         							<td><input type="text" name="forSalePhone" id="for-sale-phone" size="20" maxlength="20" class="phone" /></td></tr>
    </tbody>
</table>

<?php if (!$officeSingle) { ?>

<table>
    <caption>Leasing Company</caption>
    <tbody>
        <tr><th><label for="lease-comp">Name</label></th>
            <td><input type="text" name="leasingCompany" id="lease-comp" size="50" maxlength="255" value="<?php echo $agentCompany ?>" class="required" /></td></tr>
        <tr><th><label for="lease-comp-addr">Address</label></th>
            <td><input type="text" name="leasingCompanyAddr" id="lease-comp-addr" size="75" maxlength="255" value="<?php echo $companyAddress ?>" class="required" /></td></tr>
        <tr><th><label for="lease-comp-agent">Agent</label></th>
            <td><input type="text" name="leasingCompanyAgent" id="lease-comp-agent" size="40" maxlength="255" value="<?php echo $agent ?>" class="required" /></td></tr>
        <tr><th><label for="lease-comp-email">Email</label></th>
            <td><input type="text" name="leasingCompanyEmail" id="lease-comp-email" siZe="60" maxlength="255" value="<?php echo $agentEmail ?>" class="required email" /></td></tr>
        <tr><th><label for="lease-comp-phone">Phone</label></th>
            <td><input type="text" name="leasingCompanyPhone" id="lease-comp-phone" size="20" maxlength="20" value="<?php echo $agentWkPhone ?>" class="required phone" /></td></tr>
        <!-- removed on 2013-03-21 meeting
        	<tr><th><label for="lease-comp-fax">Fax</label></th>
            <td><input type="text" name="leasingCompanyFax" id="lease-comp-fax" size="20" maxlength="20" value="<?php echo $agentFaxPhone ?>" class="phone" /></td></tr>-->
    </tbody>
</table>


<hr />
<h5>Optional: This information is withheld from public reports and is for internal use only.</h5>

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

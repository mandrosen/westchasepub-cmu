<?php
include ("inc/db.php");

$reptype = 3;

$quarter = $_GET["quarter"];
$mapno = $_GET["mapno"];

$query = "select p.id, p.BuildingName, p.GeoNumber, p.GeoAddress
from property p
where p.businesstype = 'Hotel'
	and p.id = $mapno";

$result = mysql_query($query);
if ($row = mysql_fetch_array($result)) {
	$buildingName = $row["BuildingName"];
	$geoNumber = $row["GeoNumber"];
	$geoAddress = $row["GeoAddress"];
	$proploc = $geoNumber . " " . $geoAddress;
}

// last quarter results (to fill missing fields)
$lastQuarter = $quarter - 1;
$genMgrName = "";
$query3 = "select pb.FirstName, pb.LastName, pb.WkPhone, pb.Wkext, pb.Email 
from phone_book_category ca 
  inner join phone_book_relation pbr on ca.phonebookid = pbr.phone_book
  inner join phone_book pb on pbr.phone_book = pb.id
  inner join company c on pb.companyid = c.id
where ca.categorycode = 'hc'
  and pbr.property = $mapno";
$result3 = mysql_query($query3);
if ($row3 = mysql_fetch_array($result3)) {
	if (!empty($row3["FirstName"])) {
		$genMgrName = $row3["FirstName"] . " " . $row3["LastName"];
	}
	$genMgrEmail = $row3["Email"];
	$genMgrPhone = $row3["WkPhone"];
	if (!empty($row3["Wkext"])) {
		$genMgrPhone .= " x" + $row3["Wkext"];
	}
} 
if (empty($genMgrName)) {
	$query3a = "select * from cmu_hotel where property = $mapno and quarter = $lastQuarter";
	$result3a = mysql_query($query3a);
	if ($row3a = mysql_fetch_array($result3a)) {
		$genMgrName = $row3a["general_mgr"];
		$genMgrEmail = $row3a["general_mgr_email"];
		$genMgrPhone = $row3a["general_mgr_phone"];
	}
}
?>

<!DOCTYPE html>
<html><!-- InstanceBegin template="/Templates/default.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>Westchase District: Quarterly Commercial Market Update: Hotel</title>
    <link href="styles/cmu.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="scripts/cmu.js"></script>
	<script type="text/javascript" src="scripts/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="scripts/jquery.validate.min.js"></script>
	<script type="text/javascript" src="scripts/jquery.maskedinput.js"></script>


    <script type="text/javascript">
	jQuery.validator.addMethod("percent", function(value, element) {
		return this.optional(element) || /^100\%?$/i.test(value) || /^[1-9]?[0-9]\.?[0-9]*\%?$/i.test(value);
	}, "Please enter a valid percent between 0 and 100. ex. 25 or 25.2%");


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
	
	$().ready(function() {
		$(".required").after("<span class='requiredfield'>*</span>");

		$("input.phone").mask("(999) 999-9999");

		$("#cmuform").validate({
		});
	});
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

<form action="process.php" method="post" id="cmuform" onsubmit="return validateForm();">
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
	<tbody>
    	<tr><th><label id="occ-rate">Occupancy Rate</label><span class="format">Example: 33% or 33</span></th>
     	   <td><input type="text" name="occupancy" id="occ_rate" class="required percent"/></td></tr>
    </tbody>
</table>

<div id="static-info">
	
	<table>
		<tbody>
	    	<tr>
	    		<td id="static-info-correct">
	    			<span>Is the following information correct?</span>
	    			<label>Yes<input type="radio" name="staticInfoCorrect" value="yes" checked="checked" /></label>
	    			<label>No<input type="radio" name="staticInfoCorrect" value="no" /></label>
	    		</td>
	    	</tr>
	    </tbody>
	</table>

	<table>
	    <caption>General Manager</caption>
	    <tbody>
	        <tr><th><label for="gen_mgr">Name</label></th>
	            <td><?php echo $genMgrName ?></td></tr>
	        <tr><th><label for="gen_mgr_email">Email</label></th>
	            <td><?php echo $genMgrEmail ?></td></tr>
	        <tr><th><label for="gen_mgr_phone">Phone</label></th>
	            <td><?php echo $genMgrPhone ?></td></tr>
	    </tbody>
	</table>

</div>

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

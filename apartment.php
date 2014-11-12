<?php
include ("inc/db.php");

$reptype = 1;

$quarter = $_GET["quarter"];
$mapno = $_GET["mapno"];

$query = "select p.id, p.BuildingName, p.GeoNumber, p.GeoAddress, p.Owner
from property p
where p.id = $mapno";

$result = mysql_query($query);
if ($row = mysql_fetch_array($result)) {
	$buildingName = $row["BuildingName"];
	$geoNumber = $row["GeoNumber"];
	$geoAddress = $row["GeoAddress"];
	$proploc = $geoNumber . " " . $geoAddress;

	$owner = $row["Owner"];


} else {
	$error = "Unable to locate data for this property.";
}

$query1 = "select pb.FirstName, pb.LastName, pb.WkPhone, pb.Wkext, pb.FaxPhone, pb.Email
from phone_book_category ca
inner join phone_book_relation pbr on ca.phonebookid = pbr.phone_book
inner join phone_book pb on pbr.phone_book = pb.id
where ca.categorycode = 'am'
and pbr.property = $mapno";
$result1 = mysql_query($query1);
if ($row1 = mysql_fetch_array($result1)) {
	$firstName = $row1["FirstName"];
	$lastName = $row1["LastName"];
	$manager = $firstName . " " . $lastName;
	$email = $row1["Email"];
	$wkPhone = $row1["WkPhone"];
	$wkExt = $row1["Wkext"];
	if (!empty($wkExt)) $wkPhone .= " x" . $wkExt;

	$faxPhone = $row["FaxPhone"];
}

$query2 = "select pb.FirstName, pb.LastName, c.Company, c.StNumber, c.StAddress, c.RoomNo, c.City, c.State, c.ZipCode, pb.WkPhone, pb.Wkext, pb.FaxPhone, pb.Email
from phone_book_category ca
inner join phone_book_relation pbr on ca.phonebookid = pbr.phone_book
inner join phone_book pb on pbr.phone_book = pb.id
inner join company c on pb.companyid = c.id
where ca.categorycode = 'as'
and pbr.property = $mapno";
$result2 = mysql_query($query2);
if ($row2 = mysql_fetch_array($result2)) {
	$firstName = $row2["FirstName"];
	$lastName = $row2["LastName"];
	$supervisor = $firstName . " " . $lastName;
	$supEmail = $row2["Email"];

	$company = $row2["Company"];

	$stNumber = $row2["StNumber"];
	$stAddress = $row2["StAddress"];
	$roomNo = $row2["RoomNo"];
	$city = $row2["City"];
	$state = $row2["State"];
	$zipCode = $row2["ZipCode"];
	$companyAddress = $stNumber . " " . $stAddress . " ";
	if (!empty($roomNo)) $companyAddress .= "#" . $roomNo . " ";
	$companyAddress .= $city . ", " . $state . " " . $zipCode;

	$supPhone = $row2["WkPhone"];
	$wkExt = $row2["Wkext"];
	if (!empty($wkExt)) $supPhone .= " x" . $wkExt;

	$supFaxPhone = $row["FaxPhone"];
}


?>

<!DOCTYPE html>
<html><!-- InstanceBegin template="/Templates/default.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>Westchase District: Quarterly Commercial Market Update: Apartment</title>
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
    	<tr><th><label for="occ-rate">Occupancy Rate</label><span class="format">Example: 33% or 33</span></th>
     	   <td><input type="text" name="occupancy" id="occ-rate" class="required percent" /></td></tr>
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
		<caption>Management Company</caption>
	    <tbody>
	    	<tr><th><label for="mgmt-name">Name</label></th>
	        	<td><?php echo $company ?></td></tr>
	    </tbody>
	</table>
	
	<table>
	    <caption>Community Manager</caption>
	    <tbody>
	        <tr><th><label for="comm-mgr">Name</label></th>
	            <td><?php echo $manager ?></td></tr>
	        <tr><th><label for="comm-mgr-email">Email</label></th>
	            <td><?php echo $email ?></td></tr>
	        <tr><th><label for="comm-mgr-phone">Phone</label></th>
	            <td><?php echo $wkPhone ?></td></tr>
	    </tbody>
	</table>
	
	<table>
	    <caption>Supervisor</caption>
	    <tbody>
	        <tr><th><label for="super-name">Name</label></th>
	            <td><?php echo $supervisor ?></td></tr>
	        <tr><th><label for="super-email">Email</label></th>
	            <td><?php echo $supEmail ?></td></tr>
	        <tr><th><label for="super-phone">Phone</label></th>
	            <td><?php echo $supPhone ?></td></tr>
	    	<tr><th><label for="super-addr">Address</label></th>
	        	<td><?php echo $companyAddress ?></td></tr>
	    </tbody>
	</table>
	
	<table>
	    <caption>Owner</caption>
	    <tbody>
	        <tr><th><label for="owner-name">Name</label></th>
	            <td><?php echo $owner ?></td></tr>
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

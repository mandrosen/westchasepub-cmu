<?php
include ("inc/db.php");

$reptype = 2;

$quarter = $_GET["quarter"];
$mapno = $_GET["mapno"];

$query = "select p.id, p.BuildingName, p.GeoNumber, p.GeoAddress, p.Acreage, p.Frontage, p.WillDivide, p.PriceSqFt, p.Restrictions
from property p
where p.businesstype = 'Development Site'
	and p.id = $mapno";

$result = mysql_query($query);
if ($row = mysql_fetch_array($result)) {
	$buildingName = $row["BuildingName"];
	$geoNumber = $row["GeoNumber"];
	$geoAddress = $row["GeoAddress"];
	$proploc = $geoNumber . " " . $geoAddress;

	$acreage = $row["Acreage"];
	$frontage = $row["Frontage"];
	$priceSqFt = $row["PriceSqFt"];
	$restrictions = $row["Restrictions"];
}

//if (empty($acreage) || empty($frontage) || empty($contact) ||
//    empty($company) || empty($phone) || empty($fax) || empty($email)) {

	// last quarter results (to fill missing fields)
	$lastQuarter = $quarter - 1;
	$query3 = "select * from cmu_devsite where property = $mapno and quarter = $lastQuarter";
	$result3 = mysql_query($query3);
	if ($row3 = mysql_fetch_array($result3)) {
		if (empty($acreage)) {
			$acreage = $row3["site_size"];
		}
		if (empty($frontage)) {
			$frontage = $row3["frontage"];
		}

		if (empty($contact)) {
			$contact = $row3["contact"];
		}
		if (empty($company)) {
			$company = $row3["company"];
		}
		if (empty($phone)) {
			$phone = $row3["phone"];
		}
		if (empty($fax)) {
			$fax = $row3["fax"];
		}
		if (empty($email)) {
			$email = $row3["email"];
		}
	}

//}
?>

<!DOCTYPE html>
<html><!-- InstanceBegin template="/Templates/default.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>Westchase District: Quarterly Commercial Market Update: Development Site</title>
    <link href="styles/cmu.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="scripts/cmu.js"></script>
	<script type="text/javascript" src="scripts/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="scripts/jquery.validate.min.js"></script>
	<script type="text/javascript" src="scripts/jquery.maskedinput.js"></script>

    <script type="text/javascript">

	jQuery.validator.addMethod("acreage", function(value, element) {
		return this.optional(element) || /^\d*\.\d*$/i.test(value);
	}, "Please enter a valid size in acres.");

	
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

  <!-- InstanceBeginEditable name="formarea" -->

<table>
	<tbody>
    	<tr><th><label for="site-size">Size</label></th>
        	<td><input type="text" name="siteSize" id="site-size" size="10" maxlength="20" value="<?php echo $acreage ?>" class="required acreage" /></td></tr>
    	<tr><th><label for="site-frontage">Frontage</label></th>
        	<td><input type="text" name="frontage" id="site-frontage" size="50" maxlength="50" value="<?php echo $frontage ?>" class="required" /></td></tr>
    	<tr><th><label for="site-contact">Contact</label></th>
        	<td><input type="text" name="contact" id="site-contact" size="40" maxlength="255" class="required" value="<?php echo contact ?>" /></td></tr>
    	<tr><th><label for="site-company">Company</label></th>
        	<td><input type="text" name="company" id="site-company" size="60" maxlength="255" class="required" value="<?php echo company ?>" /></td></tr>
    	<tr><th><label for="site-phone">Phone</label></th>
        	<td><input type="text" name="phone" id="site-phone" size="20" maxlength="20" class="required phone" value="<?php echo phone ?>" /></td></tr>
    	<tr><th><label for="site-fax">Fax</label></th>
        	<td><input type="text" name="fax" id="site-fax" size="20" maxlength="20" class="phone" value="<?php echo fax ?>" /></td></tr>
    	<tr><th><label for="site-email">Email</label></th>
        	<td><input type="text" name="email" id="site-email" maxlength="255" class="required email" value="<?php echo email ?>" /></td></tr>
    	<tr><th>Will Divide</th>
        	<td><label>Yes<input type="radio" name="divide" value="1" /></label>
            <label>No<input type="radio" name="divide" value="0" /></label>
            <label>N/A<input type="radio" name="divide" value="-1" checked="checked" /></label></td></tr>
    	<tr><th><label for="price-sq-ft">Price per sq. foot</label></th>
        	<td><input type="text" name="priceSqFt" id="price-sq-ft" value="<?php echo $priceSqFt ?>" class="price" /></td></tr>
    	<tr><th><label for="site-restr">Restrictions</label></th>
        	<td><input type="text" name="restrictions" id="site-restr" maxlength="255" value="<?php echo $restrictions ?>" /></td></tr>
        <tr><th><label for="site-comment">Comments</label></th>
        	<td><textarea name="comments" id="site-comment" rows="3" cols="50"></textarea></td></tr>
	</tbody>
</table>

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

<?php include ("inc/db.php");

$currentQuarterNum = 4;
$currentQuarterYear = 2011;

$dateArray = getdate();
$year = $dateArray["year"];
$month = $dateArray["mon"];

if ($month >= 1 and $month <= 3) {
	$currentQuarterNum = 4;
	$currentQuarterYear = $year - 1;
} else if ($month >= 4 and $month <= 5) {
	$currentQuarterNum = 1;
	$currentQuarterYear = $year;
} else if ($month >= 6 and $month < 9) {
	$currentQuarterNum = 2;
	$currentQuarterYear = $year;
} else {
	$currentQuarterNum = 3;
	$currentQuarterYear = $year;
}


$quarterQuery = "select * from cmu_quarter where id < 999999999";
$quarterResult = mysql_query($quarterQuery);

$quarterSelect = "";
while ($quarterRow = mysql_fetch_array($quarterResult)) {
	$quarterSelect .= "<option value=\"{$quarterRow["id"]}\"";

	if ($quarterRow["year"] == $currentQuarterYear && $quarterRow["quarter_num"] == $currentQuarterNum) {
		$quarterSelect .= " selected=\"selected\"";
	}

	$quarterSelect .= ">{$quarterRow["description"]}</option>";
}

$error = "";
if (isset($_POST["mapno"]) && !empty($_POST["mapno"])) {
	$quarter = $_POST["quarter"];
	$mapno = $_POST["mapno"];

	$query = "select BusinessType from property where id = $mapno";
	$result = mysql_query($query);
	if ($row = mysql_fetch_array($result)) {
		$businessType = $row["BusinessType"];
		if ((stristr($businessType, "Retail") != FALSE) ||
			(stristr($businessType, "Office") != FALSE) ||
			(stristr($businessType, "Service") != FALSE) ||
			(stristr($businessType, "Ind") != FALSE)) {
			$location = "officeRetailService.php";
		} else if (stristr($businessType, "Development") != FALSE) {
			$location = "devsite.php";
		} else if (stristr($businessType, "Multi-Family") != FALSE) {
			$location = "apartment.php";
		} else if (stristr($businessType, "Hotel") != FALSE) {
			$location = "hotel.php";
		}
	}

	if (!empty($location)) {
//		header("Location: $location" . "?mapno=$mapno&quarter=$quarter");
   echo "<meta http-equiv=\"refresh\" content=\"0; url=$location?mapno=$mapno&quarter=$quarter\">";

	} else {
		$error = "Invalid MapNo.  Please check your MapNo and try again.";
	}
}

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Westchase District: Quarterly Commercial Market Update: Welcome</title>

<link href="styles/cmu.css" rel="stylesheet" type="text/css" />
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
            <p>Please contact Jonathan Lowe at <a href=\"mailto:jlowe@westchasedistrict.com\">jlowe@westchasedistrict.com</a> or 713-780-9434</p>
        </div>";
	} ?>

			<div class="initialform">
                <form action="." method="post">
                	<div>
                        <label for="mapno">Please enter the MapNo of your property</label>
                        <input type="text" name="mapno" id="mapno" size="3" maxlength="4" />

                        <label for="quarter">Which reporting quarter?</label>
                        <select name="quarter" id="quarter">
                        	<!--
                        	<option value="999999999">9th Quarter 9</option>
                        	<option value="1">3rd Quarter 2010</option>
                        	<option value="2" selected="selected">4th Quarter 2010</option>
                        	-->
                        	<?php echo $quarterSelect ?>
                        </select>
                        <input type="submit" value="Submit" />
                    </div>
                </form>
			</div>
		</div>
	</div>
</div>

<div class="footer">
	<div class="container">
		<p><a href="http://www.westchasedistrict.com/app/cmu/">Westchase District Quarterly Market Update</a></p>
		<p><a href="http://www.westchasedistrict.com/">WestchaseDistrict.com</a></p>
    </div>
</div>

</body>

</html>
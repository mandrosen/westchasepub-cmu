<?php
include ("constants.php");
$connection = mysql_connect("localhost", "westchase", "x");
// $connection = mysql_connect("localhost", "root", "");
mysql_select_db("westchasepub") or die("unable to select db");

function canInsertApartment($quarter, $mapno) {
	$id = 0;
	$query = "select id from cmu_apartment where quarter = " . $quarter . " and property = $mapno";
	$result = mysql_query($query);
	if ($row = mysql_fetch_array($result)) {
		$id = $row["id"];
	}
	return $id <= 0;
}
function canInsertDevsite($quarter, $mapno) {
	$id = 0;
	$query = "select id from cmu_devsite where quarter = " . $quarter . " and property = $mapno";
	$result = mysql_query($query);
	if ($row = mysql_fetch_array($result)) {
		$id = $row["id"];
	}
	return $id <= 0;
}
function canInsertHotel($quarter, $mapno) {
	$id = 0;
	$query = "select id from cmu_hotel where quarter = " . $quarter . " and property = $mapno";
	$result = mysql_query($query);
	if ($row = mysql_fetch_array($result)) {
		$id = $row["id"];
	}
	return $id <= 0;
}
function canInsertOfficeRetailService($quarter, $mapno) {
	$id = 0;
	$query = "select id from cmu_office_retail_svc where quarter = " . $quarter . " and property = $mapno";
	$result = mysql_query($query);
	if ($row = mysql_fetch_array($result)) {
		$id = $row["id"];
	}
	return $id <= 0;
}

function canInsertOfficeRetailServiceLeases($quarter, $mano) {	
	$id = 0;
	$query = "select id from cmu_office_retail_svc where quarter = " . $quarter . " and property = $mapno";
	$rowFound = TRUE;
	$result = mysql_query($query) or $rowFound = FALSE; 
	if ($rowFound && $row = mysql_fetch_array($result)) {
		$id = $row["id"];
	}
	if ($id > 0) {
		$leaseCount = 0;
		$query = "select count(id) as leaseCount from cmu_lease where quarter = " . $quarter . " and property = $mapno";
		$result = mysql_query($query);
		if ($row = mysql_fetch_array($result)) {
			$leaseCount = $row["leaseCount"];
		}
		return $leaseCount == 0;
	}
	return FALSE;
}


function replaceBadNumChars($num) {
	$num = str_replace(',', '', $num);
	$num = str_replace(' ', '', $num);
	return $num;	
}

function formatNumForQuery($num) {
	// could just cast to int, but this is used for non-int columns
	// for now, just remove the comma (that is causing an issue)
	// for future queries, use formatIntForQuery or formatFloatForQuery
	if (empty($num) && $num != "0") {
		return "NULL";
	}
	if ($num == 0) return 0;
	return replaceBadNumChars($num);
}
function formatIntForQuery($num) {
	if (empty($num) && $num != "0") {
		return "NULL";
	}
	if ($num == 0) return 0;
	return intval(replaceBadNumChars($num));
}
function formatFloatForQuery($num) {
	if (empty($num) && $num != "0") {
		return "NULL";
	}
	if ($num == 0) return 0;
	return floatval(replaceBadNumChars($num));
}

function formatStrForQuery($str) {
	if (empty($str)) {
		return "NULL";
	}
	return "'" . mysql_escape_string($str) . "'";
}
?>
<?php
include ("constants.php");
//$connection = mysql_connect("localhost", "westchase", "xxxx");
$connection = mysql_connect("localhost", "root", "");
mysql_select_db("westchasepub") or die("unable to select db");

//$mysqli = new mysql("localhost", "westchase", "wc!user", "westchasepub");

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

function formatNumForQuery($num) {
	if (empty($num) && $num != "0") {
		return "NULL";
	}
	if ($num == 0) return 0;
	return $num;
}
function formatStrForQuery($str) {
	if (empty($str)) {
		return "NULL";
	}
	return "'" . mysql_escape_string($str) . "'";
}
?>
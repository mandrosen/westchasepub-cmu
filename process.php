<?php
include ("inc/db.php");

$alreadyUpdated = "Updates have already been saved for this quarter.  If you would like to make updates, please contact Jonathan Lowe at <a href=\"mailto:jlowe@westchasedistrict.com\">jlowe@westchasedistrict.com</a> or 713-780-9434.";

$error = "";
if (isset($_POST["quarter"]) && !empty($_POST["quarter"]) && isset($_POST["cmutype"]) && !empty($_POST["cmutype"]) && isset($_POST["mapno"]) && !empty($_POST["mapno"])) {
	$quarter = $_POST["quarter"];
	$type = $_POST["cmutype"];
	$mapno = $_POST["mapno"];
	$completedBy = $_POST["completedBy"];
	switch ($type) {
		case 1: // apartments

			if (canInsertApartment($quarter, $mapno)) {

				$occupancy = $_POST["occupancy"];
				if (!empty($occupancy)) {
					$occupancy = str_replace('%', '', $occupancy);
				}

				$mgmtCompany = $_POST["mgmtCompany"];
				$mgmtAddress = $_POST["mgmtAddress"];

				$communityMgr = $_POST["communityMgr"];
				$communityMgrEmail = $_POST["communityMgrEmail"];
				$communityMgrPhone = $_POST["communityMgrPhone"];
				$communityMgrFax = $_POST["communityMgrFax"];

				$supervisor = $_POST["supervisor"];
				$supervisorEmail = $_POST["supervisorEmail"];
				$supervisorPhone = $_POST["supervisorPhone"];
				$supervisorFax = $_POST["supervisorFax"];

				$owner = $_POST["owner"];
				$ownerAddress = $_POST["ownerAddress"];
				$ownerPhone = $_POST["ownerPhone"];
				$ownerFax = $_POST["ownerFax"];

				$comments = $_POST["comments"];

				$query = "insert into cmu_apartment(quarter, property, completed_by, occupancy_rate, community_mgr, community_mgr_email, community_mgr_phone, community_mgr_fax, mgmt_company, mgmt_company_addr, supervisor, supervisor_email, supervisor_phone, supervisor_fax, owner, owner_address, owner_phone, owner_fax, comments) values (" . $quarter . ", $mapno, " . formatStrForQuery($completedBy) . ",
				$occupancy, " . formatStrForQuery($communityMgr) . "," . formatStrForQuery($communityMgrEmail) . "," .
				formatStrForQuery($communityMgrPhone) . "," . formatStrForQuery($communityMgrFax) . "," .
				formatStrForQuery($mgmtCompany) . "," . formatStrForQuery($mgmtAddress) . "," .
				formatStrForQuery($supervisor) . "," . formatStrForQuery($supervisorEmail) . "," .
				formatStrForQuery($supervisorPhone) . "," . formatStrForQuery($supervisorFax) . "," .
				formatStrForQuery($owner) . "," . formatStrForQuery($ownerAddress) . "," .
				formatStrForQuery($ownerPhone) . "," . formatStrForQuery($ownerFax) . "," . formatStrForQuery($comments) . ")";

				$result = mysql_query($query);
				if (!$result) {
					$error = "There was a problem with your submission.";
					if (DEBUG) {
						$error .= mysql_error();
					} else {
						mail("mandrosen@gmail.com", "Westchase query error", mysql_error() . "<br>" . $query);
					}
				}

			} else {
				$error = $alreadyUpdated;
			}

			break;

		case 2: // devsite

			if (canInsertDevsite($quarter, $mapno)) {
				$siteSize = $_POST["siteSize"];
				$frontage = $_POST["frontage"];
				$contact = $_POST["contact"];
				$company = $_POST["company"];
				$phone = $_POST["phone"];
				$fax = $_POST["fax"];
				$email = $_POST["email"];
				$divide = $_POST["divide"];
				if (empty($divide)) $divide = "-1";
				$priceSqFt = $_POST["priceSqFt"];
				$restrictions = $_POST["restrictions"];
				$comments = $_POST["comments"];

				$query = "insert into cmu_devsite(quarter, property, completed_by, site_size, frontage, contact, company, phone, fax, email, divide, price_sq_ft,
				restrictions, comments) values (" . $quarter . ", $mapno, " . formatStrForQuery($completedBy) . ", " .
				formatNumForQuery($siteSize) . "," .
				formatStrForQuery($frontage) . "," .
				formatStrForQuery($contact) . "," .
				formatStrForQuery($company) . "," .
				formatStrForQuery($phone) . "," .
				formatStrForQuery($fax) . "," .
				formatStrForQuery($email) . ", $divide, " .
				formatStrForQuery($priceSqFt) . "," .
				formatStrForQuery($restrictions) . ", " . formatStrForQuery($comments) . ")";

				$result = mysql_query($query);
				if (!$result) {
					$error = "There was a problem with your submission.";
					if (DEBUG) {
						$error .= mysql_error();
					} else {
						mail("mandrosen@gmail.com", "Westchase query error", mysql_error() . "<br>" . $query);
					}
				}
			} else {
				$error = $alreadyUpdated;
			}

			break;

		case 3: // hotel

			if (canInsertHotel($quarter, $mapno)) {
				$generalMgr = $_POST["generalMgr"];
				$generalMgrEmail = $_POST["generalMgrEmail"];
				$generalMgrPhone = $_POST["generalMgrPhone"];
				$occupancy = $_POST["occupancy"];
				if (!empty($occupancy)) {
					$occupancy = str_replace('%', '', $occupancy);
				}
				$comments = $_POST["comments"];

				$query = "insert into cmu_hotel(quarter, property, completed_by, general_mgr, general_mgr_email, general_mgr_phone, occupancy, comments) values (" . $quarter . ", $mapno, " . formatStrForQuery($completedBy) . ", " .
				formatStrForQuery($generalMgr) . "," .
				formatStrForQuery($generalMgrEmail) . "," .
				formatStrForQuery($generalMgrPhone) . "," .
				formatNumForQuery($occupancy) . "," .
				formatStrForQuery($comments) . ")";

				$result = mysql_query($query);
				if (!$result) {
					$error = "There was a problem with your submission.";
					if (DEBUG) {
						$error .= mysql_error();
					} else {
						mail("mandrosen@gmail.com", "Westchase query error", mysql_error() . "<br>" . $query);
					}
				}

			} else {
				$error = $alreadyUpdated;
			}

			break;

		case 4: // office, retail, service center

			if (canInsertOfficeRetailService($quarter, $mapno)) {
				$propertyMgr = $_POST["propertyMgr"];
				$propertyMgrPhone = $_POST["propertyMgrPhone"];
				$propertyMgrFax = $_POST["propertyMgrFax"];
				$propertyMgrEmail = $_POST["propertyMgrEmail"];

				$mgmtCompany = $_POST["mgmtCompany"];
				$mgmtCompanyAddr = $_POST["mgmtCompanyAddr"];

				$forSale = $_POST["forSale"];
				$forSaleContact = "";
				if (isset($_POST["forSaleContact"])) $forSaleContact = $_POST["forSaleContact"];
				$forSalePhone = "";
				if (isset($_POST["forSalePhone"])) $forSalePhone = $_POST["forSalePhone"];


				$leasingCompany = $_POST["leasingCompany"];
				$leasingCompanyAddr = $_POST["leasingCompanyAddr"];
				$leasingCompanyAgent = $_POST["leasingCompanyAgent"];
				$leasingCompanyEmail = $_POST["leasingCompanyEmail"];
				$leasingCompanyPhone = $_POST["leasingCompanyPhone"];
				$leasingCompanyFax = $_POST["leasingCompanyFax"];


				$sqFtAvail = str_replace(',', '', $_POST["sqFtAvail"]);
				$occupancy = $_POST["occupancy"];
				if (!empty($occupancy)) {
					$occupancy = str_replace('%', '', $occupancy);
				}
				$occupied = $_POST['occupied'];
				
				
				$largestSpace = $_POST["largestSpace"];
				$largestSpace6 = $_POST["largestSpace6"];
				$largestSpace12 = $_POST["largestSpace12"];

				$transTenantArray = $_POST["transTenant"];
				$transSqFtArray = $_POST["transSqFt"];
				$transTypeArray = $_POST["transType"];
				$transOwnerRepArray = $_POST["transOwnerRep"];
				$transTenantRepArray = $_POST["transTenantRep"];

				$query = "insert into cmu_office_retail_svc(quarter, property, completed_by, for_sale, for_sale_contact, for_sale_phone, sq_ft_for_lease, occupancy, largest_space, largest_space_6mths, largest_space_12mths, property_mgr, property_mgr_phone, property_mgr_fax, property_mgr_email, mgmt_company, mgmt_company_addr, leasing_company, leasing_company_addr, leasing_agent, leasing_agent_phone, leasing_agent_fax, leasing_agent_email, comments, occupied) values (" . $quarter . ", $mapno, " . formatStrForQuery($completedBy) . ", $forSale, " .
				formatStrForQuery($forSaleContact) . "," .
				formatStrForQuery($forSalePhone) . "," .
				formatNumForQuery($sqFtAvail) . "," .
				formatNumForQuery($occupancy) . "," .
				formatNumForQuery($largestSpace) . "," .
				formatNumForQuery($largestSpace6) . "," .
				formatNumForQuery($largestSpace12) . "," .
				formatStrForQuery($propertyMgr) . "," .
				formatStrForQuery($propertyMgrPhone) . "," .
				formatStrForQuery($propertyMgrFax) . "," .
				formatStrForQuery($propertyMgrEmail) . "," .
				formatStrForQuery($mgmtCompany) . "," .
				formatStrForQuery($mgmtCompanyAddr) . "," .
				formatStrForQuery($leasingCompany) . "," .
				formatStrForQuery($leasingCompanyAddr) . "," .
				formatStrForQuery($leasingCompanyAgent) . "," .
				formatStrForQuery($leasingCompanyPhone) . "," .
				formatStrForQuery($leasingCompanyFax) . "," .
				formatStrForQuery($leasingCompanyEmail) . "," .
				formatStrForQuery($comments) . "," .
				formatNumForQuery($occupied) . ")";

				$result = mysql_query($query);


				if (!$result) {
					$error = "There was a problem with your submission.";
					echo $query;
					if (DEBUG) {
						$error .= mysql_error();
					} else {
						mail("mandrosen@gmail.com", "Westchase query error", mysql_error() . "<br>" . $query);
					}
				} else {
					if (!empty($transTypeArray)) {
						for ($i = 0; $i < sizeof($transTypeArray); $i++) {
							$transTenant = $transTenantArray[$i];
							$transSqFt = $transSqFtArray[$i];
							if (!empty($transTenant) && !empty($transSqFt)) {
								$tquery = "insert into cmu_lease(quarter, property, tenant_name, sq_ft, lease_trans_type, owners_rep, tenants_rep) values (" . $quarter . ", $mapno, " . formatStrForQuery($transTenantArray[$i]) . "," .
								formatNumForQuery($transSqFtArray[$i]) . "," .
								$transTypeArray[$i] . "," .
								formatStrForQuery($transOwnerRepArray[$i]) . "," .
								formatStrForQuery($transTenantRepArray[$i]) . ")";
								$tresult = mysql_query($tquery);
								if (!$tresult) {
									$error = "There was a problem with your submission.";
									if (DEBUG) {
										$error .= mysql_error();
									} else {
										mail("mandrosen@gmail.com", "Westchase query error", mysql_error() . "<br>" . $tquery);
									}
								}
							}
						}
					}
				}

			} else {
				$error = $alreadyUpdated;
			}

			break;

		default:

			$error = "Invalid data received.";
			break;
	}

} else {
	$error = "Missing required data.";
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Westchase District: Quarterly Commercial Market Update: Thank You</title>

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
        	<p>Commons problems including number formats (extra/invalid characters) or missing data.</p>
            <p>Please contact Jonathan Lowe at <a href=\"mailto:jlowe@westchasedistrict.com\">jlowe@westchasedistrict.com</a> or 713-780-9434</p>
        </div>";
    } else {
?>
	<h2>Thank you</h2>
    <p>Thank you for submitting your quarterly updates.</p>
	<p>If you have any questions, please contact Jonathan Lowe at <a href=\"mailto:jlowe@westchasedistrict.com\">jlowe@westchasedistrict.com</a> or 713-780-9434</p>
<?php } ?>

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
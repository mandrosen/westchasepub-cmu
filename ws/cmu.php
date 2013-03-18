<?php
require_once('lib/nusoap.php');
require_once('../inc/db.php');

$server = new soap_server();
//$server->debug_flag = true;

//$namespace = 'http://www.westchasedistrict.com/app/cmu/ws/cmu.php';
$namespace = 'urn:cmu';

$server->configureWSDL('CMU', $namespace);
$server->wsdl->schemaTargetNamespace = $namespace;

$server->wsdl->addComplexType(
 'IntArray',
 'complexType',
 'array',
 '',
 'SOAP-ENC:Array',
 array(),
 array(
 	array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'xsd:int[]')
 ),
 'xsd:int'
);
$server->wsdl->addComplexType(
    'CmuApartment',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'id' => array('name' => 'id', 'type' => 'xsd:long'),
        'quarter' => array('name' => 'quarter', 'type' => 'xsd:int'),
        'property' => array('name' => 'property', 'type' => 'xsd:int'),
        'completed_by' => array('name' => 'completed_by', 'type' => 'xsd:string'),
        'occupancy_rate' => array('name' => 'occupancy_rate', 'type' => 'xsd:float'),
        'community_mgr' => array('name' => 'community_mgr', 'type' => 'xsd:string'),
        'community_mgr_email' => array('name' => 'community_mgr_email', 'type' => 'xsd:string'),
        'community_mgr_phone' => array('name' => 'community_mgr_phone', 'type' => 'xsd:string'),
        'community_mgr_fax' => array('name' => 'community_mgr_fax', 'type' => 'xsd:string'),
        'mgmt_company' => array('name' => 'mgmt_company', 'type' => 'xsd:string'),
        'mgmt_company_addr' => array('name' => 'mgmt_company_addr', 'type' => 'xsd:string'),
        'supervisor' => array('name' => 'supervisor', 'type' => 'xsd:string'),
        'supervisor_email' => array('name' => 'supervisor_email', 'type' => 'xsd:string'),
        'supervisor_phone' => array('name' => 'supervisor_phone', 'type' => 'xsd:string'),
        'supervisor_fax' => array('name' => 'supervisor_fax', 'type' => 'xsd:string'),
        'owner' => array('name' => 'owner', 'type' => 'xsd:string'),
        'owner_address' => array('name' => 'owner_address', 'type' => 'xsd:string'),
        'owner_phone' => array('name' => 'owner_phone', 'type' => 'xsd:string'),
        'owner_fax' => array('name' => 'owner_fax', 'type' => 'xsd:string'),
        'comments' => array('name' => 'comments', 'type' => 'xsd:string')
    )
);
$server->wsdl->addComplexType(
    'CmuApartmentArray',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(
        array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:CmuApartment[]')
    ),
    'tns:CmuApartment'
);
$server->wsdl->addComplexType(
    'CmuDevsite',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'id' => array('name' => 'id', 'type' => 'xsd:long'),
        'quarter' => array('name' => 'quarter', 'type' => 'xsd:int'),
        'property' => array('name' => 'property', 'type' => 'xsd:int'),
        'completed_by' => array('name' => 'completed_by', 'type' => 'xsd:string'),
        'site_size' => array('name' => 'site_size', 'type' => 'xsd:float'),
        'frontage' => array('name' => 'frontage', 'type' => 'xsd:string'),
        'contact' => array('name' => 'contact', 'type' => 'xsd:string'),
        'company' => array('name' => 'company', 'type' => 'xsd:string'),
        'phone' => array('name' => 'phone', 'type' => 'xsd:string'),
        'fax' => array('name' => 'fax', 'type' => 'xsd:string'),
        'email' => array('name' => 'email', 'type' => 'xsd:string'),
        'divide' => array('name' => 'divide', 'type' => 'xsd:int'),
        'price_sq_ft' => array('name' => 'price_sq_ft', 'type' => 'xsd:string'),
        'restrictions' => array('name' => 'restrictions', 'type' => 'xsd:string'),
        'comments' => array('name' => 'comments', 'type' => 'xsd:string')
    )
);
$server->wsdl->addComplexType(
    'CmuDevsiteArray',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(
        array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:CmuDevsite[]')
    ),
    'tns:CmuDevsite'
);
$server->wsdl->addComplexType(
    'CmuHotel',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'id' => array('name' => 'id', 'type' => 'xsd:long'),
        'quarter' => array('name' => 'quarter', 'type' => 'xsd:int'),
        'property' => array('name' => 'property', 'type' => 'xsd:int'),
        'completed_by' => array('name' => 'completed_by', 'type' => 'xsd:string'),
        'general_mgr' => array('name' => 'general_mgr', 'type' => 'xsd:string'),
        'general_mgr_email' => array('name' => 'general_mgr_email', 'type' => 'xsd:string'),
        'general_mgr_phone' => array('name' => 'general_mgr_phone', 'type' => 'xsd:string'),
        'occupancy' => array('name' => 'occupancy', 'type' => 'xsd:float'),
        'comments' => array('name' => 'comments', 'type' => 'xsd:string')
    )
);
$server->wsdl->addComplexType(
    'CmuHotelArray',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(
        array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:CmuHotel[]')
    ),
    'tns:CmuHotel'
);
$server->wsdl->addComplexType(
    'CmuLease',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'id' => array('name' => 'id', 'type' => 'xsd:long'),
        'quarter' => array('name' => 'quarter', 'type' => 'xsd:int'),
        'property' => array('name' => 'property', 'type' => 'xsd:int'),
        'tenant_name' => array('name' => 'tenant_name', 'type' => 'xsd:string'),
        'sq_ft' => array('name' => 'sq_ft', 'type' => 'xsd:float'),
        'lease_trans_type' => array('name' => 'lease_trans_type', 'type' => 'xsd:int'),
        'owners_rep' => array('name' => 'owners_rep', 'type' => 'xsd:string'),
        'tenants_rep' => array('name' => 'tenants_rep', 'type' => 'xsd:string')
    )
);
$server->wsdl->addComplexType(
    'CmuLeaseArray',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(
        array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:CmuLease[]')
    ),
    'tns:CmuLease'
);
$server->wsdl->addComplexType(
    'CmuOfficeRetailSvc',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'id' => array('name' => 'id', 'type' => 'xsd:long'),
        'quarter' => array('name' => 'quarter', 'type' => 'xsd:int'),
        'property' => array('name' => 'property', 'type' => 'xsd:int'),
        'completed_by' => array('name' => 'completed_by', 'type' => 'xsd:string'),
        'for_sale' => array('name' => 'for_sale', 'type' => 'xsd:int'),
        'for_sale_contact' => array('name' => 'for_sale_contact', 'type' => 'xsd:string'),
        'for_sale_phone' => array('name' => 'for_sale_phone', 'type' => 'xsd:string'),
        'sq_ft_for_lease' => array('name' => 'sq_ft_for_lease', 'type' => 'xsd:float'),
        'occupancy' => array('name' => 'occupancy', 'type' => 'xsd:float'),
        'largest_space' => array('name' => 'largest_space', 'type' => 'xsd:float'),
        'largest_space_6mths' => array('name' => 'largest_space_6mths', 'type' => 'xsd:float'),
        'largest_space_12mths' => array('name' => 'largest_space_12mths', 'type' => 'xsd:float'),
        'property_mgr' => array('name' => 'property_mgr', 'type' => 'xsd:string'),
        'property_mgr_phone' => array('name' => 'property_mgr_phone', 'type' => 'xsd:string'),
        'property_mgr_fax' => array('name' => 'property_mgr_fax', 'type' => 'xsd:string'),
        'property_mgr_email' => array('name' => 'property_mgr_email', 'type' => 'xsd:string'),
        'mgmt_company' => array('name' => 'mgmt_company', 'type' => 'xsd:string'),
        'mgmt_company_addr' => array('name' => 'mgmt_company_addr', 'type' => 'xsd:string'),
        'leasing_company' => array('name' => 'leasing_company', 'type' => 'xsd:string'),
        'leasing_company_addr' => array('name' => 'leasing_company_addr', 'type' => 'xsd:string'),
        'leasing_agent' => array('name' => 'leasing_agent', 'type' => 'xsd:string'),
        'leasing_agent_phone' => array('name' => 'leasing_agent_phone', 'type' => 'xsd:string'),
        'leasing_agent_fax' => array('name' => 'leasing_agent_fax', 'type' => 'xsd:string'),
        'leasing_agent_email' => array('name' => 'leasing_agent_email', 'type' => 'xsd:string'),
        'comments' => array('name' => 'comments', 'type' => 'xsd:string')
    )
);
$server->wsdl->addComplexType(
    'CmuOfficeRetailSvcArray',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(
        array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:CmuOfficeRetailSvc[]')
    ),
    'tns:CmuOfficeRetailSvc'
);
$server->register('getNewApartments',                // method name
    array(),        // input parameters
    array('return' => 'tns:CmuApartmentArray'),      // output parameters
    $namespace,                      // namespace
    $namespace . '#getNewApartments',                // soapaction
    'rpc',                                // style
    'encoded',                            // use
    'returns new cmu apartments'            // documentation
);
$server->register('getNewDevsites',                // method name
    array(),        // input parameters
    array('return' => 'tns:CmuDevsiteArray'),      // output parameters
    $namespace,                      // namespace
    $namespace . '#getNewDevsites',                // soapaction
    'rpc',                                // style
    'encoded',                            // use
    'returns new cmu devsites'            // documentation
);
$server->register('getNewHotels',                // method name
    array(),        // input parameters
    array('return' => 'tns:CmuHotelArray'),      // output parameters
    $namespace,                      // namespace
    $namespace . '#getNewHotels',                // soapaction
    'rpc',                                // style
    'encoded',                            // use
    'returns new cmu hotels'            // documentation
);
$server->register('getNewLeases',                // method name
    array(),        // input parameters
    array('return' => 'tns:CmuLeaseArray'),      // output parameters
    $namespace,                      // namespace
    $namespace . '#getNewLeases',                // soapaction
    'rpc',                                // style
    'encoded',                            // use
    'returns new cmu leases'            // documentation
);
$server->register('getNewOfficeRetailSvcs',                // method name
    array(),        // input parameters
    array('return' => 'tns:CmuOfficeRetailSvcArray'),      // output parameters
    $namespace,                      // namespace
    $namespace . '#getNewOfficeRetailSvcs',                // soapaction
    'rpc',                                // style
    'encoded',                            // use
    'returns new cmu office retail svcs'            // documentation
);
$server->register('setTransferred',                // method name
    array('table' => 'xsd:string', 'ids' => 'tns:IntArray'),        // input parameters
    array(),      // output parameters
    $namespace,                      // namespace
    $namespace . '#runQuery',                // soapaction
    'rpc',                                // style
    'encoded',                            // use
    'runs the specified query'            // documentation
);
function getNewApartments() {
	$apts = Array();
	$query = "select * from cmu_apartment where transferred = 0";
	$result = mysql_query($query);
	while ($row = mysql_fetch_array($result)) {
		$apts[] = Array(
			'id' => $row["id"],
			'quarter' => $row["quarter"],
			'property' => $row["property"],
			'completed_by' =>$row["completed_by"],
			'occupancy_rate' => $row["occupancy_rate"],
			'community_mgr' => 	$row["community_mgr"],
			'community_mgr_email' =>$row["community_mgr_email"],
			'community_mgr_phone' =>$row["community_mgr_phone"],
			'community_mgr_fax' => 	$row["community_mgr_fax"],
			'mgmt_company' => $row["mgmt_company"],
			'mgmt_company_addr' => $row["mgmt_company_addr"],
			'supervisor' =>$row["supervisor"],
			'supervisor_email' =>$row["supervisor_email"],
			'supervisor_phone' => $row["supervisor_phone"],
			'supervisor_fax' => $row["supervisor_fax"],
			'owner' => $row["owner"],
			'owner_address' =>$row["owner_address"],
			'owner_phone' =>$row["owner_phone"],
			'owner_fax' => $row["owner_fax"],
			'comments' => $row["comments"]
		);
	}
	return $apts;
}
function getNewDevsites() {
	$sites = Array();
	$query = "select * from cmu_devsite where transferred = 0";
	$result = mysql_query($query);
	while ($row = mysql_fetch_array($result)) {
		$sites[] = Array(
			'id' => $row["id"],
			'quarter' => $row["quarter"],
			'property' => $row["property"],
			'completed_by' =>$row["completed_by"],
			'site_size' =>$row["site_size"],
			'frontage' =>$row["frontage"],
			'contact' =>$row["contact"],
			'company' =>$row["company"],
			'phone' =>$row["phone"],
			'fax' =>$row["fax"],
			'email' =>$row["email"],
			'divide' =>$row["divide"],
			'price_sq_ft' =>$row["price_sq_ft"],
			'restrictions' =>$row["restrictions"],
			'comments' =>$row["comments"]
		);
	}
	return $sites;
}
function getNewHotels() {
	$hotels = Array();
	$query = "select * from cmu_hotel where transferred = 0";
	$result = mysql_query($query);
	while ($row = mysql_fetch_array($result)) {
		$hotels[] = Array(
			'id' => $row["id"],
			'quarter' => $row["quarter"],
			'property' => $row["property"],
			'completed_by' =>$row["completed_by"],
			'general_mgr' =>$row["general_mgr"],
			'general_mgr_email' =>$row["general_mgr_email"],
			'general_mgr_phone' =>$row["general_mgr_phone"],
			'occupancy' =>$row["occupancy"],
			'comments' =>$row["comments"]
		);
	}
	return $hotels;
}
function getNewLeases() {
	$leases = Array();
	$query = "select * from cmu_lease where transferred = 0";
	$result = mysql_query($query);
	while ($row = mysql_fetch_array($result)) {
		$leases[] = Array(
			'id' => $row["id"],
			'quarter' => $row["quarter"],
			'property' => $row["property"],
			'tenant_name' =>$row["tenant_name"],
			'sq_ft' =>$row["sq_ft"],
			'lease_trans_type' =>$row["lease_trans_type"],
			'owners_rep' =>$row["owners_rep"],
			'tenants_rep' =>$row["tenants_rep"]
		);
	}
	return $leases;
}
function getNewOfficeRetailSvcs() {
	$ors = Array();
	$query = "select * from cmu_office_retail_svc where transferred = 0";
	$result = mysql_query($query);
	while ($row = mysql_fetch_array($result)) {
		$ors[] = Array(
			'id' => $row["id"],
			'quarter' => $row["quarter"],
			'property' => $row["property"],
			'completed_by' =>$row["completed_by"],
			'for_sale' =>$row["for_sale"],
			'for_sale_contact' =>$row["for_sale_contact"],
			'for_sale_phone' =>$row["for_sale_phone"],
			'sq_ft_for_lease' =>$row["sq_ft_for_lease"],
			'occupancy' =>$row["occupancy"],
			'largest_space' =>$row["largest_space"],
			'largest_space_6mths' =>$row["largest_space_6mths"],
			'largest_space_12mths' =>$row["largest_space_12mths"],
			'property_mgr' =>$row["property_mgr"],
			'property_mgr_phone' =>$row["property_mgr_phone"],
			'property_mgr_fax' =>$row["property_mgr_fax"],
			'property_mgr_email' =>$row["property_mgr_email"],
			'mgmt_company' =>$row["mgmt_company"],
			'mgmt_company_addr' =>$row["mgmt_company_addr"],
			'leasing_company' =>$row["leasing_company"],
			'leasing_company_addr' =>$row["leasing_company_addr"],
			'leasing_agent' =>$row["leasing_agent"],
			'leasing_agent_phone' =>$row["leasing_agent_phone"],
			'leasing_agent_fax' =>$row["leasing_agent_fax"],
			'leasing_agent_email' =>$row["leasing_agent_email"],
			'comments' =>$row["comments"]
		);
	}
	return $ors;
}
function setTransferred($table, $ids) {
	if (!empty($ids) && ($table == "cmu_apartment" ||
						$table == "cmu_devsite" ||
						$table == "cmu_hotel" ||
						$table == "cmu_lease" ||
						$table == "cmu_office_retail_svc")) {
		$idstr = implode(",", $ids);
		$query = "update $table set transferred = 1 where id in ($idstr)";
		mysql_query($query);
	}
}
$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);
?>
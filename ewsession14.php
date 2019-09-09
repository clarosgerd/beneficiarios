<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "userfn14.php" ?>
<?php
ew_Header(FALSE);
$session = new cewsession;
$session->Page_Main();

//
// Page class for session
//
class cewsession {

	// Page ID
	var $PageID = "session";

	// Project ID
	var $ProjectID = "{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}";

	// Page object name
	var $PageObjName = "session";

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		return ew_CurrentPage() . "?";
	}

	// Main
	// - Uncomment ** for database connectivity / Page_Loading / Page_Unloaded server event
	function Page_Main() {
		global $conn;
		$GLOBALS["Page"] = &$this;

		//**$conn = ew_Connect();
		// Global Page Loading event (in userfn*.php)
		//**Page_Loading();

		if (ob_get_length())
			ob_end_clean();
		$time = time();
		$_SESSION["EW_LAST_REFRESH_TIME"] = $time;
		echo ew_Encrypt($time);

		// Global Page Unloaded event (in userfn*.php)
		//**Page_Unloaded();
		// Close connection
		//**ew_CloseConn();

	}
}
?>

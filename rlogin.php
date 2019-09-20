<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start();
?>
<?php include_once "rcfg11.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "phprptinc/ewmysql.php") ?>
<?php include_once "rphpfn11.php" ?>
<?php include_once "rusrfn11.php" ?>
<?php

//
// Page class
//

$rptlogin = NULL; // Initialize page object first

class crrptlogin {

	// Page ID
	var $PageID = 'rptlogin';

	// Project ID
	var $ProjectID = "{707530BA-BEB7-415A-B683-2C9753B31FA3}";

	// Page object name
	var $PageObjName = 'rptlogin';

	// Page headings
	var $Heading = '';
	var $Subheading = '';

	// Page heading
	function PageHeading() {
		global $ReportLanguage;
		if ($this->Heading <> "")
			return $this->Heading;
		if (method_exists($this, "TableCaption"))
			return $this->TableCaption();
		return "";
	}

	// Page subheading
	function PageSubheading() {
		global $ReportLanguage;
		if ($this->Subheading <> "")
			return $this->Subheading;
		return "";
	}

	// Page name
	function PageName() {
		return ewr_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ewr_CurrentPage() . "?";
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EWR_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ewr_AddMessage($_SESSION[EWR_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EWR_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ewr_AddMessage($_SESSION[EWR_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EWR_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ewr_AddMessage($_SESSION[EWR_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EWR_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ewr_AddMessage($_SESSION[EWR_SESSION_WARNING_MESSAGE], $v);
	}

		// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EWR_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EWR_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EWR_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EWR_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") // Header exists, display
			echo $sHeader;
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") // Fotoer exists, display
			echo $sFooter;
	}

	// Validate page request
	function IsPageRequest() {
		return TRUE;
	}
	var $Token = "";
	var $CheckToken = EWR_CHECK_TOKEN;
	var $CheckTokenFn = "ewr_CheckToken";
	var $CreateTokenFn = "ewr_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ewr_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EWR_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EWR_TOKEN_NAME]);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $grToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$grToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $ReportLanguage;
		global $UserTable, $UserTableConn;

		// Language object
		$ReportLanguage = new crLanguage();

		// Page ID
		if (!defined("EWR_PAGE_ID"))
			define("EWR_PAGE_ID", 'rptlogin', TRUE);

		// Start timer
		if (!isset($GLOBALS["grTimer"]))
			$GLOBALS["grTimer"] = new crTimer();

		// Debug message
		ewr_LoadDebugMsg();

		// Open connection
		if (!isset($conn)) $conn = ewr_Connect();

		// User table object (usuario)
		if (!isset($UserTable)) {
			$UserTable = new crusuario();
			$UserTableConn = ReportConn($UserTable->DBID);
		}
	}

	//
	// Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsExportFile, $gsEmailContentType, $ReportLanguage, $Security, $UserProfile;
		global $gsCustomExport;

		// User profile
		$UserProfile = new crUserProfile();

		// Security
		$Security = new crAdvancedSecurity();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $ReportLanguage->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $ReportLanguage, $EWR_EXPORT, $gsExportFile;
		global $grDashboardReport;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Close connection
		ewr_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EWR_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			ewr_SaveDebugMsg();
			header("Location: " . $url);
		}
		exit();
	}
	var $Username;
	var $LoginType;

	//
	// Page main
	//
	function Page_Main() {
		global $Security, $ReportLanguage, $grFormError, $ReportBreadcrumb, $UserProfile;
		$url = substr(ewr_CurrentUrl(), strrpos(ewr_CurrentUrl(), "/")+1);
		$ReportBreadcrumb = new crBreadcrumb;
		$ReportBreadcrumb->Add("rptlogin", "LoginPage", $url, "", "", TRUE);
		$this->Heading = $ReportLanguage->Phrase("LoginPage");
		$this->Username = ""; // Initialize
		$sPassword = "";
		$sLastUrl = $Security->LastUrl(); // Get last URL
		if ($sLastUrl == "")
			$sLastUrl = "index.php";

		// If session expired, show session expired message
		if (@$_GET["expired"] == "1")
			$this->setFailureMessage($ReportLanguage->Phrase("SessionExpired"));
		if (@$_GET["provider"] <> "") { // OAuth provider
			$provider = ucfirst(strtolower(trim($_GET["provider"]))); // e.g. Google, Facebook
			$bValidate = $Security->ValidateUser($this->Username, $sPassword, FALSE, FALSE, $provider); // Authenticate by provider
			$bValidPwd = $bValidate;
			if ($bValidate) {
				$this->Username = $UserProfile->Get("email");
				if (!$Security->IsLoggedIn() && EWR_DEBUG_ENABLED) { // Show debug message
					$bValidPwd = FALSE;
					$this->setFailureMessage(str_replace("%u", $this->Username, $ReportLanguage->Phrase("UserNotFound")));
				}
			} else {
				$this->setFailureMessage(str_replace("%p", $provider, $ReportLanguage->Phrase("LoginFailed")));
			}
		} else { // Normal login
			if (!$Security->IsLoggedIn())
				$Security->AutoLogin();
			$Security->LoadUserLevel(); // Load user level
			$encrypted = FALSE;
			if (isset($_POST["username"])) {
				$this->Username = ewr_RemoveXSS($_POST["username"]);
				$sPassword = ewr_RemoveXSS(@$_POST["password"]);
				$this->LoginType = strtolower(ewr_RemoveXSS(@$_POST["type"]));
			} else if (EWR_ALLOW_LOGIN_BY_URL && isset($_GET["username"])) {
				$this->Username = ewr_RemoveXSS($_GET["username"]);
				$sPassword = ewr_RemoveXSS(@$_GET["password"]);
				$this->LoginType = strtolower(ewr_RemoveXSS(@$_GET["type"]));
				$encrypted = !empty($_GET["encrypted"]);
			}
			if ($this->Username <> "") {
				$bValidate = $this->ValidateForm($this->Username, $sPassword);
				if (!$bValidate)
					$this->setFailureMessage($grFormError);
				$_SESSION[EWR_SESSION_USER_LOGIN_TYPE] = $this->LoginType; // Save user login type
			} else {
				if ($Security->IsLoggedIn()) {
					if ($this->getFailureMessage() == "")
						$this->Page_Terminate($sLastUrl); // Return to last accessed page
				}
				$bValidate = FALSE;

				// Restore settings
				if (@$_COOKIE[EWR_PROJECT_NAME]['Checksum'] == strval(crc32(md5(EWR_RANDOM_KEY))))
					$this->Username = ewr_Decrypt(@$_COOKIE[EWR_PROJECT_NAME]['Username'], EWR_RANDOM_KEY);
				if (@$_COOKIE[EWR_PROJECT_NAME]['AutoLogin'] == "autologin") {
					$this->LoginType = "a";
				} elseif (@$_COOKIE[EWR_PROJECT_NAME]['AutoLogin'] == "rememberusername") {
					$this->LoginType = "u";
				} else {
					$this->LoginType = "";
				}
			}
			$bValidPwd = FALSE;
			if ($bValidate) {

				// Call Logging In event
				$bValidate = $this->User_LoggingIn($this->Username, $sPassword);
				if ($bValidate) {
					$bValidPwd = $Security->ValidateUser($this->Username, $sPassword, FALSE, $encrypted); // Manual login
					if (!$bValidPwd) {
						if ($this->getFailureMessage() == "")
							$this->setFailureMessage($ReportLanguage->Phrase("InvalidUidPwd")); // Invalid user id/password
					}
				} else {
					if ($this->getFailureMessage() == "")
						$this->setFailureMessage($ReportLanguage->Phrase("LoginCancelled")); // Login cancelled
				}
			}
		}

		// After login
		if ($bValidPwd) {

			// Write cookies
			if ($this->LoginType == "a") { // Auto login
				setcookie(EWR_PROJECT_NAME . '[AutoLogin]', "autologin", EWR_COOKIE_EXPIRY_TIME); // Set autologin cookie
				setcookie(EWR_PROJECT_NAME . '[Username]', ewr_Encrypt($this->Username, EWR_RANDOM_KEY), EWR_COOKIE_EXPIRY_TIME); // Set user name cookie
				setcookie(EWR_PROJECT_NAME . '[Password]', ewr_Encrypt($sPassword, EWR_RANDOM_KEY), EWR_COOKIE_EXPIRY_TIME); // Set password cookie
				setcookie(EWR_PROJECT_NAME . '[Checksum]', crc32(md5(EWR_RANDOM_KEY)), EWR_COOKIE_EXPIRY_TIME);
			} elseif ($this->LoginType == "u") { // Remember user name
				setcookie(EWR_PROJECT_NAME . '[AutoLogin]', "rememberusername", EWR_COOKIE_EXPIRY_TIME); // Set remember user name cookie
				setcookie(EWR_PROJECT_NAME . '[Username]', ewr_Encrypt($this->Username, EWR_RANDOM_KEY), EWR_COOKIE_EXPIRY_TIME); // Set user name cookie
				setcookie(EWR_PROJECT_NAME . '[Checksum]', crc32(md5(EWR_RANDOM_KEY)), EWR_COOKIE_EXPIRY_TIME);
			} else {
				setcookie(EWR_PROJECT_NAME . '[AutoLogin]', "", EWR_COOKIE_EXPIRY_TIME); // Clear auto login cookie
			}

			// Call loggedin event
			$this->User_LoggedIn($this->Username);
			$this->Page_Terminate($sLastUrl); // Return to last accessed URL
		} elseif ($this->Username <> "" && $sPassword <> "") {

			// Call user login error event
			$this->User_LoginError($this->Username, $sPassword);
		}
	}

	//
	// Validate form
	//
	function ValidateForm($usr, $pwd) {
		global $ReportLanguage, $grFormError;

		// Initialize form error message
		$grFormError = "";

		// Check if validation required
		if (!EWR_SERVER_VALIDATE)
			return TRUE;
		if (trim($usr) == "") {
			ewr_AddMessage($grFormError, $ReportLanguage->Phrase("EnterUid"));
		}
		if (trim($pwd) == "") {
			ewr_AddMessage($grFormError, $ReportLanguage->Phrase("EnterPwd"));
		}

		// Return validate result
		$ValidateForm = ($grFormError == "");

		// Call Form Custom Validate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ewr_AddMessage($grFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}

	// User Logging In event
	function User_LoggingIn($usr, &$pwd) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// User Logged In event
	function User_LoggedIn($usr) {

		//echo "User Logged In";
	}

	// User Login Error event
	function User_LoginError($usr, $pwd) {

		//echo "User Login Error";
	}

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php

// Create page object
if (!isset($rptlogin)) $rptlogin = new crrptlogin();
if (isset($Page)) $OldPage = $Page;
$Page = &$rptlogin;

// Page init
$Page->Page_Init();

// Page main
$Page->Page_Main();

// Global Page Rendering event (in ewrusrfn*.php)
Page_Rendering();

// Page Rendering event
$Page->Page_Render();
?>
<?php include_once "phprptinc/header.php" ?>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<script type="text/javascript">
var frptlogin = new ewr_Form("frptlogin");

// Validate method
frptlogin.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if (!ewr_HasValue(fobj.username))
		return this.OnError(fobj.username, ewLanguage.Phrase("EnterUid"));
	if (!ewr_HasValue(fobj.password))
		return this.OnError(fobj.password, ewLanguage.Phrase("EnterPwd"));

	// Call Form Custom Validate event
	if (!this.Form_CustomValidate(fobj)) return false;
	return true;
}

// Form_CustomValidate method
frptlogin.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Requires js validation
<?php if (EWR_CLIENT_VALIDATE) { ?>
frptlogin.ValidateRequired = true;
<?php } else { ?>
frptlogin.ValidateRequired = false;
<?php } ?>
</script>
<!-- div class="ewToolbar">
<div class="clearfix"></div>
</div -->
<?php $Page->ShowPageHeader(); ?>
<?php $Page->ShowMessage(); ?>
<form name="frptlogin" id="frptlogin" class="ewForm ewLoginForm" action="<?php echo ewr_CurrentPage() ?>" method="post">
<?php if ($Page->CheckToken) { ?>
<input type="hidden" name="<?php echo EWR_TOKEN_NAME ?>" value="<?php echo $Page->Token ?>">
<?php } ?>
<div class="login-box ewLoginBox">
<div class="login-box-body">
<p class="login-box-msg"><?php echo $ReportLanguage->Phrase("Login") ?></p>
	<div class="form-group">
		<div><input type="text" name="username" id="username" class="form-control ewControl" value="<?php echo ewr_HtmlEncode($rptlogin->Username) ?>" placeholder="<?php echo ewr_HtmlEncode($ReportLanguage->Phrase("Username")) ?>"></div>
	</div>
	<div class="form-group">
		<div><input type="password" name="password" id="password" class="form-control ewControl" placeholder="<?php echo ewr_HtmlEncode($ReportLanguage->Phrase("Password")) ?>"></div>
	</div>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $ReportLanguage->Phrase("Login") ?></button>
<?php

	// OAuth login
	$providers = $EWR_AUTH_CONFIG["providers"];
	$cntProviders = 0;
	foreach ($providers as $id => $provider) {
		if ($provider["enabled"])
			$cntProviders++;
	}
	if ($cntProviders > 0) {
?>
	<div class="social-auth-links text-center">
		<p><?php echo $ReportLanguage->Phrase("LoginOr") ?></p>
<?php
		foreach ($providers as $id => $provider) {
			if ($provider["enabled"]) {
?>
			<a href="rlogin.php?provider=<?php echo $id ?>" class="btn btn-block btn-social btn-flat btn-<?php echo strtolower($id) ?>"><i class="fa fa-<?php echo strtolower($id) ?>"></i><?php echo $ReportLanguage->Phrase("Login" . $id) ?></a>
<?php
			}
		}
?>
	</div>
<?php
	}
?>
</div>
</div>
</form>
<script type="text/javascript">
frptlogin.Init();
</script>
<?php
$Page->ShowPageFooter();
if (EWR_DEBUG_ENABLED)
	echo ewr_DebugMsg();
?>
<script type="text/javascript">

// Write your startup script here
// console.log("page loaded");

</script>
<?php include_once "phprptinc/footer.php" ?>
<?php
$Page->Page_Terminate();
if (isset($OldPage)) $Page = $OldPage;
?>

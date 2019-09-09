<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "userlevelsinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$userpriv = NULL; // Initialize page object first

class cuserpriv extends cuserlevels {

	// Page ID
	var $PageID = 'userpriv';

	// Project ID
	var $ProjectID = '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}';

	// Page object name
	var $PageObjName = 'userpriv';

	// Page headings
	var $Heading = '';
	var $Subheading = '';

	// Page heading
	function PageHeading() {
		global $Language;
		if ($this->Heading <> "")
			return $this->Heading;
		if (method_exists($this, "TableCaption"))
			return $this->TableCaption();
		return "";
	}

	// Page subheading
	function PageSubheading() {
		global $Language;
		if ($this->Subheading <> "")
			return $this->Subheading;
		return "";
	}

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		return TRUE;
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		global $UserTable, $UserTableConn;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (userlevels)
		if (!isset($GLOBALS["userlevels"]) || get_class($GLOBALS["userlevels"]) == "cuserlevels") {
			$GLOBALS["userlevels"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["userlevels"];
		}
		if (!isset($GLOBALS["userlevels"])) $GLOBALS["userlevels"] = &$this;

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'userpriv', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"]))
			$GLOBALS["gTimer"] = new cTimer();

		// Debug message
		ew_LoadDebugMsg();

		// Open connection
		if (!isset($conn))
			$conn = ew_Connect($this->DBID);

		// User table object (usuario)
		if (!isset($UserTable)) {
			$UserTable = new cusuario();
			$UserTableConn = Conn($UserTable->DBID);
		}
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// User profile
		$UserProfile = new cUserProfile();

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel(CurrentProjectID() . 'userlevels');
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanAdmin()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}

		// NOTE: Security object may be needed in other part of the script, skip set to Nothing
		// 
		// Security = null;
		// 

		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
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
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		$this->Page_Redirecting($url);

		// Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			ew_SaveDebugMsg();
			header("Location: " . $url);
		}
		exit();
	}
	var $Disabled;
	var $TableNameCount;
	var $ReportLanguage;
	var $Privileges = array();
	var $UserLevelList = array();
	var $UserLevelPrivList = array();
	var $TableList = array();

	//
	// Page main
	//
	function Page_Main() {
		global $Security, $Language;
		global $EW_RELATED_LANGUAGE_FOLDER;
		global $Breadcrumb;
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb = new cBreadcrumb;
		$Breadcrumb->Add("list", "userlevels", "userlevelslist.php", "", "userlevels");
		$Breadcrumb->Add("userpriv", "UserLevelPermission", $url);
		$this->Heading = $Language->Phrase("UserLevelPermission");

		// Try to load PHP Report Maker language file
		// Note: The langauge IDs must be the same in both projects

		$Security->LoadUserLevelFromConfigFile($this->UserLevelList, $this->UserLevelPrivList, $this->TableList, TRUE);
		if ($EW_RELATED_LANGUAGE_FOLDER <> "")
			$this->ReportLanguage = new cLanguage($EW_RELATED_LANGUAGE_FOLDER);
		$this->TableNameCount = count($this->TableList);

		// Get action
		if (@$_POST["a_edit"] == "") {
			$this->CurrentAction = "I"; // Display with input box

			// Load key from QueryString
			if (@$_GET["userlevelid"] <> "") {
				$this->userlevelid->setQueryStringValue($_GET["userlevelid"]);
			} else {
				$this->Page_Terminate("userlevelslist.php"); // Return to list
			}
			if ($this->userlevelid->QueryStringValue == "-1") {
				$this->Disabled = " disabled";
			} else {
				$this->Disabled = "";
			}
		} else {
			$this->CurrentAction = $_POST["a_edit"];

			// Get fields from form
			$this->userlevelid->setFormValue($_POST["x_userlevelid"]);
			for ($i = 0; $i < $this->TableNameCount; $i++) {
				if (isset($_POST["Table_" . $i])) {
					if (defined("EW_USER_LEVEL_COMPAT")) {
						$this->Privileges[$i] = intval(@$_POST["Add_" . $i]) +
							intval(@$_POST["Delete_" . $i]) + intval(@$_POST["Edit_" . $i]) +
							intval(@$_POST["List_" . $i]);
					} else {
						$this->Privileges[$i] = intval(@$_POST["Add_" . $i]) +
							intval(@$_POST["Delete_" . $i]) + intval(@$_POST["Edit_" . $i]) +
							intval(@$_POST["List_" . $i]) + intval(@$_POST["View_" . $i]) +
							intval(@$_POST["Search_" . $i]);
					}
				}
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Display
				if (!$Security->SetupUserLevelEx()) // Get all User Level info
					$this->Page_Terminate("userlevelslist.php"); // Return to list
				$ar = array();
				for ($i = 0; $i < $this->TableNameCount; $i++) {
					$tempPriv = $Security->GetUserLevelPrivEx($this->TableList[$i][4] . $this->TableList[$i][0], $this->userlevelid->CurrentValue);
					$ar[] = array("table" => ew_ConvertToUtf8($this->GetTableCaption($i)), "index" => $i, "permission" => $tempPriv);
				}
				$this->Privileges["disabled"] = $this->Disabled;
				$this->Privileges["permissions"] = $ar;
				$this->Privileges["EW_ALLOW_ADD"] = 1; // Add
				$this->Privileges["EW_ALLOW_DELETE"] = 2; // Delete
				$this->Privileges["EW_ALLOW_EDIT"] = 4; // Edit
				$this->Privileges["EW_ALLOW_LIST"] = 8; // List
				$this->Privileges["EW_USER_LEVEL_COMPAT"] = defined("EW_USER_LEVEL_COMPAT"); // EW_USER_LEVEL_COMPAT
				if (defined("EW_USER_LEVEL_COMPAT")) {
					$this->Privileges["EW_ALLOW_VIEW"] = 8; // View
					$this->Privileges["EW_ALLOW_SEARCH"] = 8; // Search
				} else {
					$this->Privileges["EW_ALLOW_VIEW"] = 32; // View
					$this->Privileges["EW_ALLOW_SEARCH"] = 64; // Search
				}
				$this->Privileges["EW_ALLOW_REPORT"] = 8; // Report
				$this->Privileges["EW_ALLOW_ADMIN"] = 16; // Admin
				break;
			case "U": // Update
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Set up update success message

					// Alternatively, comment out the following line to go back to this page
					$this->Page_Terminate("userlevelslist.php"); // Return to list
				}
		}
	}

	// Update privileges
	function EditRow() {
		global $Security;
		$c = &Conn(EW_USER_LEVEL_PRIV_DBID);
		foreach ($this->Privileges as $i => $privilege) {
			$Sql = "SELECT * FROM " . EW_USER_LEVEL_PRIV_TABLE . " WHERE " .
				EW_USER_LEVEL_PRIV_TABLE_NAME_FIELD . " = '" . ew_AdjustSql($this->TableList[$i][4] . $this->TableList[$i][0], EW_USER_LEVEL_PRIV_DBID) . "' AND " .
				EW_USER_LEVEL_PRIV_USER_LEVEL_ID_FIELD . " = " . $this->userlevelid->CurrentValue;
			$rs = $c->Execute($Sql);
			if ($rs && !$rs->EOF) {
				$Sql = "UPDATE " . EW_USER_LEVEL_PRIV_TABLE . " SET " . EW_USER_LEVEL_PRIV_PRIV_FIELD . " = " . $privilege . " WHERE " .
					EW_USER_LEVEL_PRIV_TABLE_NAME_FIELD . " = '" . ew_AdjustSql($this->TableList[$i][4] . $this->TableList[$i][0], EW_USER_LEVEL_PRIV_DBID) . "' AND " .
					EW_USER_LEVEL_PRIV_USER_LEVEL_ID_FIELD . " = " . $this->userlevelid->CurrentValue;
				$c->Execute($Sql);
			} else {
				$Sql = "INSERT INTO " . EW_USER_LEVEL_PRIV_TABLE . " (" . EW_USER_LEVEL_PRIV_TABLE_NAME_FIELD . ", " . EW_USER_LEVEL_PRIV_USER_LEVEL_ID_FIELD . ", " . EW_USER_LEVEL_PRIV_PRIV_FIELD . ") VALUES ('" . ew_AdjustSql($this->TableList[$i][4] . $this->TableList[$i][0], EW_USER_LEVEL_PRIV_DBID) . "', " . $this->userlevelid->CurrentValue . ", " . $privilege . ")";
				$c->Execute($Sql);
			}
			if ($rs)
				$rs->Close();
		}
		$Security->SetupUserLevel();
		return TRUE;
	}

	// Get table caption
	function GetTableCaption($i) {
		global $Language, $EW_RELATED_PROJECT_ID;
		$caption = "";
		if ($i < $this->TableNameCount) {
			$report = ($this->TableList[$i][4] == $EW_RELATED_PROJECT_ID);
			$other = (!$report && $this->TableList[$i][4] <> CurrentProjectID());
			if (!$report && !$other)
				$caption = $Language->TablePhrase($this->TableList[$i][1], "TblCaption");
			if ($report && is_object($this->ReportLanguage))
				$caption = $this->ReportLanguage->TablePhrase($this->TableList[$i][1], "TblCaption");
			if ($caption == "")
				$caption = $this->TableList[$i][2];
			if ($caption == "") {
				$caption = $this->TableList[$i][0];
				$caption = preg_replace('/^\{\w{8}-\w{4}-\w{4}-\w{4}-\w{12}\}/', '', $caption); // Remove project id
			}
			if ($report)
				$caption .= "<span class=\"ewUserprivProject\"> (" . $Language->Phrase("Report") . ")</span>";
			if ($other) {
				if ($this->TableList[$i][5] <> "") {
					$pathinfo = pathinfo($this->TableList[$i][5]);
					$ext = $pathinfo['extension'];
					$project = basename($this->TableList[$i][5], "." . $ext);
				} else {
					$project = $this->TableList[$i][4];
				}

				//$project = $this->TableList[$i][4]; // ** Uncomment to use project id
				$caption .= "<span class=\"ewUserprivProject\"> (" . $project . ")</span>";
			}
		}
		return $caption;
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'
	function Message_Showing(&$msg, $type) {

		// Example:
		//if ($type == 'success') $msg = "your success message";

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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($userpriv)) $userpriv = new cuserpriv();

// Page init
$userpriv->Page_Init();

// Page main
$userpriv->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$userpriv->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "userpriv";
var CurrentForm = fuserpriv = new ew_Form("fuserpriv", "userpriv");

// Form_CustomValidate event
fuserpriv.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fuserpriv.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php
$userpriv->ShowMessage();
?>
<script type="text/javascript">
var fuserpriv = new ew_Form("fuserpriv");
</script>
<form name="fuserpriv" id="fuserpriv" class="form-inline ewForm ewUserprivForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($userpriv->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $userpriv->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="userlevels">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<input type="hidden" name="x_userlevelid" id="x_userlevelid" value="<?php echo $userlevels->userlevelid->CurrentValue ?>">
<div class="ewDesktop">
<div class="box ewBox ewGrid">
<div class="box-header with-border">
	<h3 class="box-title"><?php echo $Language->Phrase("UserLevel") ?><?php echo $Security->GetUserLevelName($userlevels->userlevelid->CurrentValue) ?> (<?php echo $userlevels->userlevelid->CurrentValue ?>)</h3>
	<div class="box-tools pull-right">
		<div class="has-feedback">
			<input type="text" name="tableName" id="tableName" class="form-control input-sm" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>" autocomplete="off">
			<span class="glyphicon glyphicon-search form-control-feedback"></span>
		</div>
	</div>
</div>
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel"></div>
</div>
<div class="ewDesktopButton">
<button class="btn btn-primary ewButton" name="btnSubmit" id="btnSubmit" type="submit"<?php echo $userpriv->Disabled ?>><?php echo $Language->Phrase("Update") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $userpriv->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</div>
</form>
<script type="text/javascript">
var priv = <?php echo ew_ConvertFromUtf8(json_encode($userpriv->Privileges)) ?>;
var chkClass = (priv.disabled) ? "ewPriv disabled" : "ewPriv";

function getDisplayFn(name, trueValue) {
	return function(data) {
		var row = data.record, id = name + '_' + row.index,
			checked = (row.permission & trueValue) == trueValue;
		row.checked = checked;
		return '<input type="checkbox" class="ewPriv ewMultiSelect" name="' + id + '" id="' + id +
			'" value="' + trueValue + '" data-index="' + row.index + '"' +
			((checked) ? ' checked' : '') +
			((priv.disabled) ? ' disabled' : '') + '>';
	};
}

function displayTableName(data) {
	var row = data.record;
	return row.table + '<input type="hidden" name="Table_' + row.index + '" value="1">';
};

function getRecords(data, params) {
	var rows = priv.permissions.slice(0);
	if (data && data.table) {
		var table = data.table.toLowerCase();
		rows = jQuery.map(rows, function(row) {
			if (row.table.toLowerCase().includes(table))
				return row;
			return null;
		});
	}
	if (params && params.sorting) {
		var asc = params.sorting.match(/ASC$/);
		rows.sort(function(a, b) { // Case-insensitive
			if (b.table.toLowerCase() > a.table.toLowerCase())
				return (asc) ? -1 : 1;
			else if (b.table.toLowerCase() === a.table.toLowerCase())
				return 0
			else if (b.table.toLowerCase() < a.table.toLowerCase())
				return (asc) ? 1 : -1;
		});
	}
	return {
		Result: "OK",
		Params: jQuery.extend({}, data, params),
		Records: rows
	};
}
var _fields = {
	table: {
		title: ewLanguage.Phrase("TableOrView"),
		display: displayTableName,
		sorting: true
	},
	add: {
		title: '<label><input type="checkbox" class="' + chkClass + '" name="Add" id="Add" onclick="ew_SelectAll(this);"> ' + ewLanguage.Phrase("PermissionAddCopy") + '</label>',
		display: getDisplayFn("Add", priv.EW_ALLOW_ADD),
		sorting: false
	},
	delete: {
		title: '<label><input type="checkbox" class="' + chkClass + '" name="Delete" id="Delete" onclick="ew_SelectAll(this);"> ' + ewLanguage.Phrase("PermissionDelete") + '</label>',
		display: getDisplayFn("Delete", priv.EW_ALLOW_DELETE),
		sorting: false
	},
	edit: {
		title: '<label><input type="checkbox" class="' + chkClass + '" name="Edit" id="Edit" onclick="ew_SelectAll(this);"> ' + ewLanguage.Phrase("PermissionEdit") + '</label>',
		display: getDisplayFn("Edit", priv.EW_ALLOW_EDIT),
		sorting: false
	},
	list: {
		title: '<label><input type="checkbox" class="' + chkClass + '" name="List" id="List" onclick="ew_SelectAll(this);"> ' +
			ewLanguage.Phrase(priv.EW_USER_LEVEL_COMPAT ? "PermissionListSearchView" : "PermissionList") +
			'</label>',
		display: getDisplayFn("List", priv.EW_ALLOW_LIST),
		sorting: false
	}
};
if (!priv.EW_USER_LEVEL_COMPAT) {
	$.extend(_fields, {
		view: {
			title: '<label><input type="checkbox" class="' + chkClass + '" name="View" id="View" onclick="ew_SelectAll(this);"> ' + ewLanguage.Phrase("PermissionView") + '</label>',
			display: getDisplayFn("View", priv.EW_ALLOW_VIEW),
			sorting: false
		},
		search: {
			title: '<label><input type="checkbox" class="' + chkClass + '" name="Search" id="Search" onclick="ew_SelectAll(this);"> ' + ewLanguage.Phrase("PermissionSearch") + '</label>',
			display: getDisplayFn("Search", priv.EW_ALLOW_SEARCH),
			sorting: false
		}
	});
}
$(".ewGrid:first .ewGridMiddlePanel").ewjtable({
	paging: false,
	sorting: true,
	defaultSorting: "table ASC",
	fields: _fields,
	actions: { listAction: getRecords },
	rowInserted: function(event, data) {
		var $row = data.row;
		$row.find("input[type=checkbox]").on("click", function() {
			var $this = $(this), index = parseInt($this.data("index"), 10), value = parseInt($this.data("value"), 10);
			if (this.checked)
				priv.permissions[index].permission = priv.permissions[index].permission | value;
			else
				priv.permissions[index].permission = priv.permissions[index].permission ^ value;
		});
	},
	recordsLoaded: function(event, data) {
		var $ = jQuery, sorting = data.serverResponse.Params.sorting,
			$c = $(this).find(".ewjtable-column-header-container:first");
		if (!$c.find(".ewTableHeaderSort")[0])
			$c.append('<span class="ewTableHeaderSort"><span class="caret"></span></span>');
		$c.find(".ewTableHeaderSort .caret").toggleClass("ewSortUp", !!sorting.match(/ASC$/));
		ew_InitMultiSelectCheckboxes();
	}
});

// Re-load records when user click 'Search' button.
var _timer;
$("#tableName").on("keydown keypress cut paste", function(e) {
	if (_timer)
		_timer.cancel();
	_timer = $.later(200, null, function() {
		$(".ewGrid:first .ewGridMiddlePanel").ewjtable("load", {
			table: $("#tableName").val()
		});
	});
});

// Load all records
$("#tableName").keydown();

// Init form
fuserpriv.Init();
</script>
<script type="text/javascript">

// Write your startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$userpriv->Page_Terminate();
?>

<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start();
?>
<?php include_once "rcfg11.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "phprptinc/ewmysql.php") ?>
<?php include_once "rphpfn11.php" ?>
<?php include_once "rusrfn11.php" ?>
<?php include_once "viewdocenterptinfo.php" ?>
<?php

//
// Page class
//

$viewdocente_rpt = NULL; // Initialize page object first

class crviewdocente_rpt extends crviewdocente {

	// Page ID
	var $PageID = 'rpt';

	// Project ID
	var $ProjectID = "{707530BA-BEB7-415A-B683-2C9753B31FA3}";

	// Page object name
	var $PageObjName = 'viewdocente_rpt';

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
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Export URLs
	var $ExportPrintUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportPdfUrl;
	var $ReportTableClass;
	var $ReportTableStyle = "";

	// Custom export
	var $ExportPrintCustom = FALSE;
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

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
		if ($this->UseTokenInUrl) {
			if (ewr_IsHttpPost())
				return ($this->TableVar == @$_POST("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == @$_GET["t"]);
		} else {
			return TRUE;
		}
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

		// Parent constuctor
		parent::__construct();

		// Table object (viewdocente)
		if (!isset($GLOBALS["viewdocente"])) {
			$GLOBALS["viewdocente"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["viewdocente"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";

		// Page ID
		if (!defined("EWR_PAGE_ID"))
			define("EWR_PAGE_ID", 'rpt', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EWR_TABLE_NAME"))
			define("EWR_TABLE_NAME", 'viewdocente', TRUE);

		// Start timer
		if (!isset($GLOBALS["grTimer"]))
			$GLOBALS["grTimer"] = new crTimer();

		// Debug message
		ewr_LoadDebugMsg();

		// Open connection
		if (!isset($conn)) $conn = ewr_Connect($this->DBID);

		// User table object (usuario)
		if (!isset($UserTable)) {
			$UserTable = new crusuario();
			$UserTableConn = ReportConn($UserTable->DBID);
		}

		// Export options
		$this->ExportOptions = new crListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Search options
		$this->SearchOptions = new crListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Filter options
		$this->FilterOptions = new crListOptions();
		$this->FilterOptions->Tag = "div";
		$this->FilterOptions->TagClassName = "ewFilterOption fviewdocenterpt";

		// Generate report options
		$this->GenerateOptions = new crListOptions();
		$this->GenerateOptions->Tag = "div";
		$this->GenerateOptions->TagClassName = "ewGenerateOption";
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

		// Process generate request
		$this->ProcessGenRequest();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin(); // Auto login
		$Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . 'viewdocente');
		$Security->TablePermission_Loaded();
		if (!$Security->CanList()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ewr_DeniedMsg()); // Set no permission
			$this->Page_Terminate(ewr_GetUrl("index.php"));
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();
		if ($Security->IsLoggedIn() && strval($Security->CurrentUserID()) == "") {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ewr_DeniedMsg()); // Set no permission
			$this->Page_Terminate(ewr_GetUrl("login.php"));
		}

		// Generate request URL
		if ($this->GenerateRequestUrl()) {
			$this->Page_Terminate();
			exit();
		}

		// Get export parameters
		if (@$_GET["export"] <> "")
			$this->Export = strtolower($_GET["export"]);
		elseif (@$_POST["export"] <> "")
			$this->Export = strtolower($_POST["export"]);
		$gsExport = $this->Export; // Get export parameter, used in header
		$gsExportFile = $this->TableVar; // Get export file, used in header
		$gsEmailContentType = @$_POST["contenttype"]; // Get email content type

		// Setup placeholder
		$this->nrodiscapacidad->PlaceHolder = $this->nrodiscapacidad->FldCaption();
		$this->ci->PlaceHolder = $this->ci->FldCaption();
		$this->materias->PlaceHolder = $this->materias->FldCaption();

		// Setup export options
		$this->SetupExportOptions();

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

	// Generate request URL
	function GenerateRequestUrl() {
		global $Security, $ReportLanguage, $grLanguage, $ReportOptions;
		global $UserTableConn;
		$post = $_POST;

		// Check if create URL request
		if (count($post) == 0 || @$post["generateurl"] <> "1" || @$post["reporttype"] == "") {
			$UserNameList = array();
			if ($Security->IsSysAdmin())
				$UserNameList["@@admin"] = $ReportLanguage->Phrase("ReportFormUserDefault");

			// Get list of user names
			$sSql = EWR_LOGIN_SELECT_SQL;
			if ($rs = $UserTableConn->Execute($sSql)) {
				while (!$rs->EOF) {
					$usr = $rs->fields('login');
					$userid = $rs->fields('login');
					if (!$Security->IsSysAdmin() && !in_array(strval($userid), $Security->UserID))
						$usr = "";
					$lvl = $rs->fields('id_rol');
					$priv = $Security->GetUserLevelPrivEx($this->ProjectID . $this->TableName, $lvl);
					if (($priv & EWR_ALLOW_REPORT) <> EWR_ALLOW_REPORT)
						$usr = "";
					if ($usr <> "")
						$UserNameList[$usr] = $usr;
					$rs->MoveNext();
				}
			}
			$ReportOptions["UserNameList"] = $UserNameList;
			$ReportOptions["ShowFilter"] = TRUE;
			return FALSE;
		}

		// Check if login
		if (!$Security->IsLoggedIn())
			return FALSE;

		// Get username/password
		$usr = @$post["username"];
		$pwd = "";
		if ($usr == "@@admin") {
			$usr = EWR_ADMIN_USER_NAME;
			$pwd = EWR_ADMIN_PASSWORD;
		} else {
			$sFilter = str_replace("%u", ewr_AdjustSql($usr, EWR_USER_TABLE_DBID), EWR_USER_NAME_FILTER);
			$sSql = EWR_LOGIN_SELECT_SQL . " WHERE " . $sFilter;
			if ($rs = $UserTableConn->Execute($sSql)) {
				if (!$rs->EOF)
					$pwd = $rs->fields('password');
			}
		}
		if ($usr == "" || $pwd == "") // No user specified
			return FALSE;
		$usr = ewr_Encrypt($usr, EWR_REPORT_LOG_ENCRYPT_KEY);
		$pwd = ewr_Encrypt($pwd, EWR_REPORT_LOG_ENCRYPT_KEY);

		// Set report parameters
		$reportType = @$post["reporttype"];
		$genKey = ewr_Encrypt($this->TableVar, EWR_REPORT_LOG_ENCRYPT_KEY);
		$url = ewr_FullUrl();
		$url .= "?reporttype=" . $reportType . "&k=" . urlencode($genKey) . "&u=" . urlencode($usr) . "&p=" . urlencode($pwd);
		if ($reportType == "email") {
			$sender = @$post["sender"];
			$recipient = @$post["recipient"];
			$cc = @$post["cc"];
			$bcc = @$post["bcc"];
			$subject = @$post["subject"];
			if ($sender == "" || $recipient == "" || $subject == "")
				return FALSE;
			$url .= "&sender=" . urlencode($sender) . "&recipient=" . urlencode($recipient) . "&subject=" . urlencode($subject);
			if ($cc <> "") $url .= "&cc=" . urlencode($cc);
			if ($bcc <> "") $url .= "&bcc=" . urlencode($bcc);
		}
		$pageOption = @$post["pageoption"];
		$url .= ($pageOption == "all") ? "&exportall=1" : "&exportall=0&start=1"; // All pages / First page

		// Set report filter
		$filterName = @$post["filtername"];
		if ($filterName == "")
			$filterName = "_none";
		elseif ($filterName == "@@current")
			$filterName = "_user";
		$url .= "&filtername=" . urlencode($filterName);
		$filter = json_decode(@$post["filter"], TRUE);
		if (is_array($filter)) {
			foreach ($filter as $key => $val)
				$url .= "&" . $key . "=" . urlencode($val);
		}

		// Set response type
		$responseType = @$post["responsetype"];
		$url .= "&responsetype=" . urlencode($responseType);

		// Set show current filter
		$showCurrentFilter = @$post["showcurrentfilter"];
		$url .= "&showfilter=" . ($showCurrentFilter == "1" ? "1" : "0");
		echo json_encode(array("url" => $url));
		return TRUE;
	}

	// Process generate request
	function ProcessGenRequest() {
		global $Security, $ReportLanguage;
		$ar = ewr_IsHttpPost() ? $_POST : $_GET;
		$genType = @$ar["reporttype"];
		$genKey = @$ar["k"];
		if (array_key_exists("k", $ar)) // Remove key
			unset($ar["k"]);
		if (ewr_Decrypt($genKey, EWR_REPORT_LOG_ENCRYPT_KEY) == $this->TableVar && $genType <> "") {
			$usr = @$ar["u"];
			$usr = ewr_Decrypt($usr, EWR_REPORT_LOG_ENCRYPT_KEY);
			if (array_key_exists("u", $ar)) // Update actual user name
				$ar["u"] = $usr;
			$pwd = @$ar["p"];
			if (array_key_exists("p", $ar)) // Remove password
				unset($ar["p"]);
			$pwd = ewr_Decrypt($pwd, EWR_REPORT_LOG_ENCRYPT_KEY);
			$encrypted = @$ar["encrypted"] == "1";
			$bLogin = $Security->ValidateUser($usr, $pwd, FALSE, $encrypted); // Manual login
			if (!$bLogin) {
				echo ewr_DeniedMsg();
				exit();
			} else {
				if ($genType == "html") $genType = "print";
				$this->Export = $genType;
				$this->ShowCurrentFilter = FALSE;
				if (@$ar["exportall"] <> "") // Export all option specified
					$this->ExportAll = $ar["exportall"] == "1";
				$this->GenOptions = $this->GetGenOptions($ar); // Set up generate options
				$this->SetupGenFilterList($ar); // Update filter list
			}
		}
	}

	// Generate file extension
	function GenFileExt($genType) {
		if ($genType == "print" || $genType == "html")
			return "html";
		elseif ($genType == "excel")
			return "xls";
		elseif ($genType == "word")
			return "doc";
		elseif ($genType == "pdf")
			return "pdf";
		else
			return $genType;
	}

	// Get Generate options
	function GetGenOptions($ar) {
		$options = array();
		$options["parms"] = json_encode($ar);

		// Set up gen type / filename
		$genType = @$ar["reporttype"];
		$options["reporttype"] = $genType;
		$options["filtername"] = @$ar["filtername"];
		$options["responsetype"] = @$ar["responsetype"];
		if ($genType == "email") { // Email
			$options["sender"] = @$ar["sender"];
			$options["recipient"] = @$ar["recipient"];
			$options["subject"] = @$ar["subject"];
		} else {
			$options["folder"] = (@$ar["folder"] <> "") ? $ar["folder"] : EWR_UPLOAD_DEST_PATH;
			$options["filename"] = (@$ar["filename"] <> "") ? $ar["filename"] : $this->TableVar . "_" . ewr_RandomGuid() . "." . $this->GenFileExt($genType);
		}

		// Paging
		$options["start"] = @$ar["start"]; // Start
		$options["pageno"] = @$ar["pageno"]; // Page number
		$options["grpperpage"] = @$ar["grpperpage"]; // Group per page

		// Sort
		$options["order"] = @$ar["order"]; // Order by
		$options["ordertype"] = @$ar["ordertype"]; // ASC/DESC
		if ($options["order"] == "") // Reset sort if not specified
			$options["resetsort"] = "1";
		$options["showfilter"] = @$ar["showfilter"]; // Show current filter
		return $options;
	}

	// Set up generate filter
	function SetupGenFilterList($ar) {
		$filter = array();
		$keys = preg_grep('/^(sv|sv2|so|so2|sc|sel)_/', array_keys($ar));
		foreach ($keys as $key) {
			$filter[$key] = @$ar[$key];
		}
		return $this->SetupFilterList($filter); 
	}

	// Write generate response
	function WriteGenResponse($genurl) {
		if ($genurl <> "") {
			$genType = @$this->GenOptions["reporttype"];
			$responseType = @$this->GenOptions["responsetype"];
			if ($responseType == "json" || $genType == "email")
				echo json_encode(array("url" => $genurl));
		}
	}

	// Set up export options
	function SetupExportOptions() {
		global $Security, $ReportLanguage, $ReportOptions;
		$exportid = session_id();
		$ReportTypes = array();

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a class=\"ewrExportLink ewPrint\" title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("PrinterFriendly", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("PrinterFriendly", TRUE)) . "\" href=\"" . $this->ExportPrintUrl . "\">" . $ReportLanguage->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = TRUE;
		$ReportTypes["print"] = $item->Visible ? $ReportLanguage->Phrase("ReportFormPrint") : "";

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a class=\"ewrExportLink ewExcel\" title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToExcel", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToExcel", TRUE)) . "\" href=\"" . $this->ExportExcelUrl . "\">" . $ReportLanguage->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;
		$ReportTypes["excel"] = $item->Visible ? $ReportLanguage->Phrase("ReportFormExcel") : "";

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a class=\"ewrExportLink ewWord\" title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToWord", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToWord", TRUE)) . "\" href=\"" . $this->ExportWordUrl . "\">" . $ReportLanguage->Phrase("ExportToWord") . "</a>";
		$item->Visible = TRUE;
		$ReportTypes["word"] = $item->Visible ? $ReportLanguage->Phrase("ReportFormWord") : "";

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a class=\"ewrExportLink ewPdf\" title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToPDF", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToPDF", TRUE)) . "\" href=\"" . $this->ExportPdfUrl . "\">" . $ReportLanguage->Phrase("ExportToPDF") . "</a>";
		$item->Visible = FALSE;

		// Uncomment codes below to show export to Pdf link
//		$item->Visible = TRUE;

		$ReportTypes["pdf"] = $item->Visible ? $ReportLanguage->Phrase("ReportFormPdf") : "";

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$url = $this->PageUrl() . "export=email";
		$item->Body = "<a class=\"ewrExportLink ewEmail\" title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToEmail", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToEmail", TRUE)) . "\" id=\"emf_viewdocente\" href=\"javascript:void(0);\" onclick=\"ewr_EmailDialogShow({lnk:'emf_viewdocente',hdr:ewLanguage.Phrase('ExportToEmail'),url:'$url',exportid:'$exportid',el:this});\">" . $ReportLanguage->Phrase("ExportToEmail") . "</a>";
		$item->Visible = FALSE;
		$ReportTypes["email"] = $item->Visible ? $ReportLanguage->Phrase("ReportFormEmail") : "";
		$ReportOptions["ReportTypes"] = $ReportTypes;

		// Drop down button for export
		$this->ExportOptions->UseDropDownButton = TRUE;
		$this->ExportOptions->UseButtonGroup = TRUE;
		$this->ExportOptions->UseImageAndText = $this->ExportOptions->UseDropDownButton;
		$this->ExportOptions->DropDownButtonPhrase = $ReportLanguage->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Filter button
		$item = &$this->FilterOptions->Add("savecurrentfilter");
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fviewdocenterpt\" href=\"#\">" . $ReportLanguage->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fviewdocenterpt\" href=\"#\">" . $ReportLanguage->Phrase("DeleteFilter") . "</a>";
		$item->Visible = TRUE;
		$this->FilterOptions->UseDropDownButton = TRUE;
		$this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton; // v8
		$this->FilterOptions->DropDownButtonPhrase = $ReportLanguage->Phrase("Filters");

		// Add group option item
		$item = &$this->FilterOptions->Add($this->FilterOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Button to create generate URL
		$item = &$this->GenerateOptions->Add("generateurl");
		$item->Body = "<a type=\"button\" title=\"" . $ReportLanguage->Phrase("GenerateReportUrl", TRUE) . "\" onclick=\"ewr_ModalGenerateUrlShow(event);\" class=\"ewGenerateUrlBtn btn btn-default\"><span class=\"glyphicon glyphicon-link ewIcon\"></span></a>";
		$item->Visible = $Security->IsLoggedIn(); // Check if logged in
		$this->GenerateOptions->UseButtonGroup = TRUE;

		// Add group option item
		$item = &$this->GenerateOptions->Add($this->GenerateOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Set up options (extended)
		$this->SetupExportOptionsExt();

		// Hide options for export
		if ($this->Export <> "") {
			$this->ExportOptions->HideAllOptions();
			$this->FilterOptions->HideAllOptions();
		}

		// Set up table class
		if ($this->Export == "word" || $this->Export == "excel" || $this->Export == "pdf")
			$this->ReportTableClass = "ewTable";
		else
			$this->ReportTableClass = "table ewTable";
	}

	// Set up search options
	function SetupSearchOptions() {
		global $ReportLanguage;

		// Filter panel button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = $this->FilterApplied ? " active" : " active";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $ReportLanguage->Phrase("SearchBtn", TRUE) . "\" data-caption=\"" . $ReportLanguage->Phrase("SearchBtn", TRUE) . "\" data-toggle=\"button\" data-form=\"fviewdocenterpt\">" . $ReportLanguage->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Reset filter
		$item = &$this->SearchOptions->Add("resetfilter");
		$item->Body = "<button type=\"button\" class=\"btn btn-default\" title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ResetAllFilter", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ResetAllFilter", TRUE)) . "\" onclick=\"location='" . ewr_CurrentPage() . "?cmd=reset'\">" . $ReportLanguage->Phrase("ResetAllFilter") . "</button>";
		$item->Visible = TRUE && $this->FilterApplied;

		// Button group for reset filter
		$this->SearchOptions->UseButtonGroup = TRUE;

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide options for export
		if ($this->Export <> "")
			$this->SearchOptions->HideAllOptions();
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

		// Export
		if ($this->Export <> "" && array_key_exists($this->Export, $EWR_EXPORT)) {
			$sContent = ob_get_contents();
			if (ob_get_length())
				ob_end_clean();

			// Remove all <div data-tagid="..." id="orig..." class="hide">...</div> (for customviewtag export, except "googlemaps")
			if (preg_match_all('/<div\s+data-tagid=[\'"]([\s\S]*?)[\'"]\s+id=[\'"]orig([\s\S]*?)[\'"]\s+class\s*=\s*[\'"]hide[\'"]>([\s\S]*?)<\/div\s*>/i', $sContent, $divmatches, PREG_SET_ORDER)) {
				foreach ($divmatches as $divmatch) {
					if ($divmatch[1] <> "googlemaps")
						$sContent = str_replace($divmatch[0], '', $sContent);
				}
			}
			$fn = $EWR_EXPORT[$this->Export];
			if ($this->Export == "email") { // Email
				if (@$this->GenOptions["reporttype"] == "email") {
					$saveResponse = $this->$fn($sContent, $this->GenOptions);
					$this->WriteGenResponse($saveResponse);
				} else {
					echo $this->$fn($sContent, array());
				}
				$url = ""; // Avoid redirect
			} else {
				$saveToFile = $this->$fn($sContent, $this->GenOptions);
				if (@$this->GenOptions["reporttype"] <> "") {
					$saveUrl = ($saveToFile <> "") ? ewr_FullUrl($saveToFile, "genurl") : $ReportLanguage->Phrase("GenerateSuccess");
					$this->WriteGenResponse($saveUrl);
					$url = ""; // Avoid redirect
				}
			}
		}

		// Close connection if not in dashboard
		if (!$grDashboardReport)
			ewr_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EWR_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			ewr_SaveDebugMsg();
			header("Location: " . $url);
		}
		if (!$grDashboardReport)
			exit();
	}

	// Initialize common variables
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $FilterOptions; // Filter options

	// Paging variables
	var $RecIndex = 0; // Record index
	var $RecCount = 0; // Record count
	var $StartGrp = 0; // Start group
	var $StopGrp = 0; // Stop group
	var $TotalGrps = 0; // Total groups
	var $GrpCount = 0; // Group count
	var $GrpCounter = array(); // Group counter
	var $DisplayGrps = 3; // Groups per page
	var $GrpRange = 10;
	var $Sort = "";
	var $Filter = "";
	var $PageFirstGroupFilter = "";
	var $UserIDFilter = "";
	var $DrillDown = FALSE;
	var $DrillDownInPanel = FALSE;
	var $DrillDownList = "";

	// Clear field for ext filter
	var $ClearExtFilter = "";
	var $PopupName = "";
	var $PopupValue = "";
	var $FilterApplied;
	var $SearchCommand = FALSE;
	var $ShowHeader;
	var $GrpColumnCount = 0;
	var $SubGrpColumnCount = 0;
	var $DtlColumnCount = 0;
	var $Cnt, $Col, $Val, $Smry, $Mn, $Mx, $GrandCnt, $GrandSmry, $GrandMn, $GrandMx;
	var $TotCount;
	var $GrandSummarySetup = FALSE;
	var $GrpIdx;
	var $DetailRows = array();
	var $TopContentClass = "col-sm-12 ewTop";
	var $LeftContentClass = "ewLeft";
	var $CenterContentClass = "col-sm-12 ewCenter";
	var $RightContentClass = "ewRight";
	var $BottomContentClass = "col-sm-12 ewBottom";

	//
	// Page main
	//
	function Page_Main() {
		global $rs;
		global $rsgrp;
		global $Security;
		global $grFormError;
		global $grDrillDownInPanel;
		global $ReportBreadcrumb;
		global $ReportLanguage;
		global $grDashboardReport;

		// Set field visibility for detail fields
		$this->deoartamento->SetVisibility();
		$this->unidadeducativa->SetVisibility();
		$this->nombres->SetVisibility();
		$this->apellidopaterno->SetVisibility();
		$this->apellidomaterno->SetVisibility();
		$this->nrodiscapacidad->SetVisibility();
		$this->ci->SetVisibility();
		$this->fechanacimiento->SetVisibility();
		$this->sexo->SetVisibility();
		$this->celular->SetVisibility();
		$this->materias->SetVisibility();
		$this->discapacidad->SetVisibility();
		$this->tipodiscapacidad->SetVisibility();
		$this->nombreinstitucion->SetVisibility();

		// Handle drill down
		$sDrillDownFilter = $this->GetDrillDownFilter();
		$grDrillDownInPanel = $this->DrillDownInPanel;
		if ($this->DrillDown)
			ewr_AddFilter($this->Filter, $sDrillDownFilter);

		// Aggregate variables
		// 1st dimension = no of groups (level 0 used for grand total)
		// 2nd dimension = no of fields

		$nDtls = 15;
		$nGrps = 1;
		$this->Val = &ewr_InitArray($nDtls, 0);
		$this->Cnt = &ewr_Init2DArray($nGrps, $nDtls, 0);
		$this->Smry = &ewr_Init2DArray($nGrps, $nDtls, 0);
		$this->Mn = &ewr_Init2DArray($nGrps, $nDtls, NULL);
		$this->Mx = &ewr_Init2DArray($nGrps, $nDtls, NULL);
		$this->GrandCnt = &ewr_InitArray($nDtls, 0);
		$this->GrandSmry = &ewr_InitArray($nDtls, 0);
		$this->GrandMn = &ewr_InitArray($nDtls, NULL);
		$this->GrandMx = &ewr_InitArray($nDtls, NULL);

		// Set up array if accumulation required: array(Accum, SkipNullOrZero)
		$this->Col = array(array(FALSE, FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE));

		// Set up groups per page dynamically
		$this->SetUpDisplayGrps();

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();
		$this->deoartamento->SelectionList = "";
		$this->deoartamento->DefaultSelectionList = "";
		$this->deoartamento->ValueList = "";
		$this->unidadeducativa->SelectionList = "";
		$this->unidadeducativa->DefaultSelectionList = "";
		$this->unidadeducativa->ValueList = "";
		$this->nombres->SelectionList = "";
		$this->nombres->DefaultSelectionList = "";
		$this->nombres->ValueList = "";
		$this->apellidopaterno->SelectionList = "";
		$this->apellidopaterno->DefaultSelectionList = "";
		$this->apellidopaterno->ValueList = "";
		$this->apellidomaterno->SelectionList = "";
		$this->apellidomaterno->DefaultSelectionList = "";
		$this->apellidomaterno->ValueList = "";
		$this->nrodiscapacidad->SelectionList = "";
		$this->nrodiscapacidad->DefaultSelectionList = "";
		$this->nrodiscapacidad->ValueList = "";
		$this->fechanacimiento->SelectionList = "";
		$this->fechanacimiento->DefaultSelectionList = "";
		$this->fechanacimiento->ValueList = "";
		$this->sexo->SelectionList = "";
		$this->sexo->DefaultSelectionList = "";
		$this->sexo->ValueList = "";
		$this->celular->SelectionList = "";
		$this->celular->DefaultSelectionList = "";
		$this->celular->ValueList = "";
		$this->materias->SelectionList = "";
		$this->materias->DefaultSelectionList = "";
		$this->materias->ValueList = "";
		$this->discapacidad->SelectionList = "";
		$this->discapacidad->DefaultSelectionList = "";
		$this->discapacidad->ValueList = "";
		$this->tipodiscapacidad->SelectionList = "";
		$this->tipodiscapacidad->DefaultSelectionList = "";
		$this->tipodiscapacidad->ValueList = "";
		$this->nombreinstitucion->SelectionList = "";
		$this->nombreinstitucion->DefaultSelectionList = "";
		$this->nombreinstitucion->ValueList = "";

		// Check if search command
		$this->SearchCommand = (@$_GET["cmd"] == "search");

		// Load default filter values
		$this->LoadDefaultFilters();

		// Load custom filters
		$this->Page_FilterLoad();

		// Set up popup filter
		$this->SetupPopup();

		// Load group db values if necessary
		$this->LoadGroupDbValues();

		// Handle Ajax popup
		$this->ProcessAjaxPopup();

		// Extended filter
		$sExtendedFilter = "";

		// Restore filter list
		$this->RestoreFilterList();

		// Build extended filter
		$sExtendedFilter = $this->GetExtendedFilter();
		ewr_AddFilter($this->Filter, $sExtendedFilter);

		// Build popup filter
		$sPopupFilter = $this->GetPopupFilter();

		//ewr_SetDebugMsg("popup filter: " . $sPopupFilter);
		ewr_AddFilter($this->Filter, $sPopupFilter);

		// Check if filter applied
		$this->FilterApplied = $this->CheckFilter();

		// Call Page Selecting event
		$this->Page_Selecting($this->Filter);

		// Search options
		$this->SetupSearchOptions();

		// Get sort
		$this->Sort = $this->GetSort($this->GenOptions);

		// Get total count
		$sSql = ewr_BuildReportSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), "", $this->Filter, ""); // No need for ORDER BY for total count
		$this->TotalGrps = $this->GetCnt($sSql);
		if ($this->DisplayGrps <= 0 || $this->DrillDown || $grDashboardReport) // Display all groups
			$this->DisplayGrps = $this->TotalGrps;
		$this->StartGrp = 1;

		// Show header
		$this->ShowHeader = TRUE;

		// Set up start position if not export all
		if ($this->ExportAll && $this->Export <> "")
			$this->DisplayGrps = $this->TotalGrps;
		else
			$this->SetUpStartGroup($this->GenOptions);

		// Set no record found message
		if ($this->TotalGrps == 0) {
			if ($Security->CanList()) {
				if ($this->Filter == "0=101") {
					$this->setWarningMessage($ReportLanguage->Phrase("EnterSearchCriteria"));
				} else {
					$this->setWarningMessage($ReportLanguage->Phrase("NoRecord"));
				}
			} else {
				$this->setWarningMessage(ewr_DeniedMsg());
			}
		}

		// Hide export options if export/dashboard report
		if ($this->Export <> "" || $grDashboardReport)
			$this->ExportOptions->HideAllOptions();

		// Hide search/filter options if export/drilldown/dashboard report
		if ($this->Export <> "" || $this->DrillDown || $grDashboardReport) {
			$this->SearchOptions->HideAllOptions();
			$this->FilterOptions->HideAllOptions();
			$this->GenerateOptions->HideAllOptions();
		}

		// Get current page records
		$sSql = ewr_BuildReportSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(), $this->Filter, $this->Sort);
		$rs = $this->GetRs($sSql, $this->StartGrp, $this->DisplayGrps);
		$this->SetupFieldCount();
	}

	// Accummulate summary
	function AccumulateSummary() {
		$cntx = count($this->Smry);
		for ($ix = 0; $ix < $cntx; $ix++) {
			$cnty = count($this->Smry[$ix]);
			for ($iy = 1; $iy < $cnty; $iy++) {
				if ($this->Col[$iy][0]) { // Accumulate required
					$valwrk = $this->Val[$iy];
					if (is_null($valwrk)) {
						if (!$this->Col[$iy][1])
							$this->Cnt[$ix][$iy]++;
					} else {
						$accum = (!$this->Col[$iy][1] || !is_numeric($valwrk) || $valwrk <> 0);
						if ($accum) {
							$this->Cnt[$ix][$iy]++;
							if (is_numeric($valwrk)) {
								$this->Smry[$ix][$iy] += $valwrk;
								if (is_null($this->Mn[$ix][$iy])) {
									$this->Mn[$ix][$iy] = $valwrk;
									$this->Mx[$ix][$iy] = $valwrk;
								} else {
									if ($this->Mn[$ix][$iy] > $valwrk) $this->Mn[$ix][$iy] = $valwrk;
									if ($this->Mx[$ix][$iy] < $valwrk) $this->Mx[$ix][$iy] = $valwrk;
								}
							}
						}
					}
				}
			}
		}
		$cntx = count($this->Smry);
		for ($ix = 0; $ix < $cntx; $ix++) {
			$this->Cnt[$ix][0]++;
		}
	}

	// Reset level summary
	function ResetLevelSummary($lvl) {

		// Clear summary values
		$cntx = count($this->Smry);
		for ($ix = $lvl; $ix < $cntx; $ix++) {
			$cnty = count($this->Smry[$ix]);
			for ($iy = 1; $iy < $cnty; $iy++) {
				$this->Cnt[$ix][$iy] = 0;
				if ($this->Col[$iy][0]) {
					$this->Smry[$ix][$iy] = 0;
					$this->Mn[$ix][$iy] = NULL;
					$this->Mx[$ix][$iy] = NULL;
				}
			}
		}
		$cntx = count($this->Smry);
		for ($ix = $lvl; $ix < $cntx; $ix++) {
			$this->Cnt[$ix][0] = 0;
		}

		// Reset record count
		$this->RecCount = 0;
	}

	// Accummulate grand summary
	function AccumulateGrandSummary() {
		$this->TotCount++;
		$cntgs = count($this->GrandSmry);
		for ($iy = 1; $iy < $cntgs; $iy++) {
			if ($this->Col[$iy][0]) {
				$valwrk = $this->Val[$iy];
				if (is_null($valwrk) || !is_numeric($valwrk)) {
					if (!$this->Col[$iy][1])
						$this->GrandCnt[$iy]++;
				} else {
					if (!$this->Col[$iy][1] || $valwrk <> 0) {
						$this->GrandCnt[$iy]++;
						$this->GrandSmry[$iy] += $valwrk;
						if (is_null($this->GrandMn[$iy])) {
							$this->GrandMn[$iy] = $valwrk;
							$this->GrandMx[$iy] = $valwrk;
						} else {
							if ($this->GrandMn[$iy] > $valwrk) $this->GrandMn[$iy] = $valwrk;
							if ($this->GrandMx[$iy] < $valwrk) $this->GrandMx[$iy] = $valwrk;
						}
					}
				}
			}
		}
	}

	// Get count
	function GetCnt($sql) {
		return $this->getRecordCount($sql);
	}

	// Get recordset
	function GetRs($wrksql, $start, $grps) {
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EWR_ERROR_FN"];
		$rswrk = $conn->SelectLimit($wrksql, $grps, $start - 1);
		$conn->raiseErrorFn = '';
		return $rswrk;
	}

	// Get row values
	function GetRow($opt) {
		global $rs;
		if (!$rs)
			return;
		if ($opt == 1) { // Get first row
				$this->FirstRowData = array();
				$this->FirstRowData['deoartamento'] = ewr_Conv($rs->fields('deoartamento'), 200);
				$this->FirstRowData['unidadeducativa'] = ewr_Conv($rs->fields('unidadeducativa'), 200);
				$this->FirstRowData['nombres'] = ewr_Conv($rs->fields('nombres'), 200);
				$this->FirstRowData['apellidopaterno'] = ewr_Conv($rs->fields('apellidopaterno'), 200);
				$this->FirstRowData['apellidomaterno'] = ewr_Conv($rs->fields('apellidomaterno'), 200);
				$this->FirstRowData['nrodiscapacidad'] = ewr_Conv($rs->fields('nrodiscapacidad'), 200);
				$this->FirstRowData['ci'] = ewr_Conv($rs->fields('ci'), 200);
				$this->FirstRowData['fechanacimiento'] = ewr_Conv($rs->fields('fechanacimiento'), 133);
				$this->FirstRowData['sexo'] = ewr_Conv($rs->fields('sexo'), 3);
				$this->FirstRowData['celular'] = ewr_Conv($rs->fields('celular'), 200);
				$this->FirstRowData['materias'] = ewr_Conv($rs->fields('materias'), 200);
				$this->FirstRowData['discapacidad'] = ewr_Conv($rs->fields('discapacidad'), 200);
				$this->FirstRowData['tipodiscapacidad'] = ewr_Conv($rs->fields('tipodiscapacidad'), 200);
				$this->FirstRowData['nombreinstitucion'] = ewr_Conv($rs->fields('nombreinstitucion'), 200);
		} else { // Get next row
			$rs->MoveNext();
		}
		if (!$rs->EOF) {
			$this->deoartamento->setDbValue($rs->fields('deoartamento'));
			$this->unidadeducativa->setDbValue($rs->fields('unidadeducativa'));
			$this->nombres->setDbValue($rs->fields('nombres'));
			$this->apellidopaterno->setDbValue($rs->fields('apellidopaterno'));
			$this->apellidomaterno->setDbValue($rs->fields('apellidomaterno'));
			$this->nrodiscapacidad->setDbValue($rs->fields('nrodiscapacidad'));
			$this->ci->setDbValue($rs->fields('ci'));
			$this->fechanacimiento->setDbValue($rs->fields('fechanacimiento'));
			$this->sexo->setDbValue($rs->fields('sexo'));
			$this->celular->setDbValue($rs->fields('celular'));
			$this->materias->setDbValue($rs->fields('materias'));
			$this->discapacidad->setDbValue($rs->fields('discapacidad'));
			$this->tipodiscapacidad->setDbValue($rs->fields('tipodiscapacidad'));
			$this->nombreinstitucion->setDbValue($rs->fields('nombreinstitucion'));
			$this->Val[1] = $this->deoartamento->CurrentValue;
			$this->Val[2] = $this->unidadeducativa->CurrentValue;
			$this->Val[3] = $this->nombres->CurrentValue;
			$this->Val[4] = $this->apellidopaterno->CurrentValue;
			$this->Val[5] = $this->apellidomaterno->CurrentValue;
			$this->Val[6] = $this->nrodiscapacidad->CurrentValue;
			$this->Val[7] = $this->ci->CurrentValue;
			$this->Val[8] = $this->fechanacimiento->CurrentValue;
			$this->Val[9] = $this->sexo->CurrentValue;
			$this->Val[10] = $this->celular->CurrentValue;
			$this->Val[11] = $this->materias->CurrentValue;
			$this->Val[12] = $this->discapacidad->CurrentValue;
			$this->Val[13] = $this->tipodiscapacidad->CurrentValue;
			$this->Val[14] = $this->nombreinstitucion->CurrentValue;
		} else {
			$this->deoartamento->setDbValue("");
			$this->unidadeducativa->setDbValue("");
			$this->nombres->setDbValue("");
			$this->apellidopaterno->setDbValue("");
			$this->apellidomaterno->setDbValue("");
			$this->nrodiscapacidad->setDbValue("");
			$this->ci->setDbValue("");
			$this->fechanacimiento->setDbValue("");
			$this->sexo->setDbValue("");
			$this->celular->setDbValue("");
			$this->materias->setDbValue("");
			$this->discapacidad->setDbValue("");
			$this->tipodiscapacidad->setDbValue("");
			$this->nombreinstitucion->setDbValue("");
		}
	}

	// Set up starting group
	function SetUpStartGroup($options = array()) {

		// Exit if no groups
		if ($this->DisplayGrps == 0)
			return;
		$startGrp = (@$options["start"] <> "") ? $options["start"] : @$_GET[EWR_TABLE_START_GROUP];
		$pageNo = (@$options["pageno"] <> "") ? $options["pageno"] : @$_GET["pageno"];

		// Check for a 'start' parameter
		if ($startGrp != "") {
			$this->StartGrp = $startGrp;
			$this->setStartGroup($this->StartGrp);
		} elseif ($pageNo != "") {
			$nPageNo = $pageNo;
			if (is_numeric($nPageNo)) {
				$this->StartGrp = ($nPageNo-1)*$this->DisplayGrps+1;
				if ($this->StartGrp <= 0) {
					$this->StartGrp = 1;
				} elseif ($this->StartGrp >= intval(($this->TotalGrps-1)/$this->DisplayGrps)*$this->DisplayGrps+1) {
					$this->StartGrp = intval(($this->TotalGrps-1)/$this->DisplayGrps)*$this->DisplayGrps+1;
				}
				$this->setStartGroup($this->StartGrp);
			} else {
				$this->StartGrp = $this->getStartGroup();
			}
		} else {
			$this->StartGrp = $this->getStartGroup();
		}

		// Check if correct start group counter
		if (!is_numeric($this->StartGrp) || $this->StartGrp == "") { // Avoid invalid start group counter
			$this->StartGrp = 1; // Reset start group counter
			$this->setStartGroup($this->StartGrp);
		} elseif (intval($this->StartGrp) > intval($this->TotalGrps)) { // Avoid starting group > total groups
			$this->StartGrp = intval(($this->TotalGrps-1)/$this->DisplayGrps) * $this->DisplayGrps + 1; // Point to last page first group
			$this->setStartGroup($this->StartGrp);
		} elseif (($this->StartGrp-1) % $this->DisplayGrps <> 0) {
			$this->StartGrp = intval(($this->StartGrp-1)/$this->DisplayGrps) * $this->DisplayGrps + 1; // Point to page boundary
			$this->setStartGroup($this->StartGrp);
		}
	}

	// Load group db values if necessary
	function LoadGroupDbValues() {
		$conn = &$this->Connection();
	}

	// Process Ajax popup
	function ProcessAjaxPopup() {
		global $ReportLanguage;
		$conn = &$this->Connection();
		$fld = NULL;
		if (@$_GET["popup"] <> "") {
			$popupname = $_GET["popup"];

			// Check popup name
			// Build distinct values for deoartamento

			if ($popupname == 'viewdocente_deoartamento') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->deoartamento, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->deoartamento->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->deoartamento->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->deoartamento->setDbValue($rswrk->fields[0]);
					$this->deoartamento->ViewValue = @$rswrk->fields[1];
					if (is_null($this->deoartamento->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->deoartamento->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->deoartamento->ValueList, $this->deoartamento->CurrentValue, $this->deoartamento->ViewValue, FALSE, $this->deoartamento->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->deoartamento->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->deoartamento->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->deoartamento;
			}

			// Build distinct values for unidadeducativa
			if ($popupname == 'viewdocente_unidadeducativa') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->unidadeducativa, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->unidadeducativa->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->unidadeducativa->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->unidadeducativa->setDbValue($rswrk->fields[0]);
					$this->unidadeducativa->ViewValue = @$rswrk->fields[1];
					if (is_null($this->unidadeducativa->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->unidadeducativa->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->unidadeducativa->ValueList, $this->unidadeducativa->CurrentValue, $this->unidadeducativa->ViewValue, FALSE, $this->unidadeducativa->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->unidadeducativa->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->unidadeducativa->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->unidadeducativa;
			}

			// Build distinct values for nombres
			if ($popupname == 'viewdocente_nombres') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->nombres, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->nombres->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->nombres->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->nombres->setDbValue($rswrk->fields[0]);
					$this->nombres->ViewValue = @$rswrk->fields[1];
					if (is_null($this->nombres->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->nombres->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->nombres->ValueList, $this->nombres->CurrentValue, $this->nombres->ViewValue, FALSE, $this->nombres->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->nombres->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->nombres->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->nombres;
			}

			// Build distinct values for apellidopaterno
			if ($popupname == 'viewdocente_apellidopaterno') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->apellidopaterno, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->apellidopaterno->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->apellidopaterno->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->apellidopaterno->setDbValue($rswrk->fields[0]);
					$this->apellidopaterno->ViewValue = @$rswrk->fields[1];
					if (is_null($this->apellidopaterno->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->apellidopaterno->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->apellidopaterno->ValueList, $this->apellidopaterno->CurrentValue, $this->apellidopaterno->ViewValue, FALSE, $this->apellidopaterno->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->apellidopaterno->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->apellidopaterno->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->apellidopaterno;
			}

			// Build distinct values for apellidomaterno
			if ($popupname == 'viewdocente_apellidomaterno') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->apellidomaterno, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->apellidomaterno->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->apellidomaterno->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->apellidomaterno->setDbValue($rswrk->fields[0]);
					$this->apellidomaterno->ViewValue = @$rswrk->fields[1];
					if (is_null($this->apellidomaterno->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->apellidomaterno->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->apellidomaterno->ValueList, $this->apellidomaterno->CurrentValue, $this->apellidomaterno->ViewValue, FALSE, $this->apellidomaterno->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->apellidomaterno->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->apellidomaterno->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->apellidomaterno;
			}

			// Build distinct values for nrodiscapacidad
			if ($popupname == 'viewdocente_nrodiscapacidad') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->nrodiscapacidad, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->nrodiscapacidad->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->nrodiscapacidad->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->nrodiscapacidad->setDbValue($rswrk->fields[0]);
					$this->nrodiscapacidad->ViewValue = @$rswrk->fields[1];
					if (is_null($this->nrodiscapacidad->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->nrodiscapacidad->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->nrodiscapacidad->ValueList, $this->nrodiscapacidad->CurrentValue, $this->nrodiscapacidad->ViewValue, FALSE, $this->nrodiscapacidad->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->nrodiscapacidad->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->nrodiscapacidad->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->nrodiscapacidad;
			}

			// Build distinct values for fechanacimiento
			if ($popupname == 'viewdocente_fechanacimiento') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->fechanacimiento, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->fechanacimiento->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->fechanacimiento->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->fechanacimiento->setDbValue($rswrk->fields[0]);
					$this->fechanacimiento->ViewValue = @$rswrk->fields[1];
					if (is_null($this->fechanacimiento->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->fechanacimiento->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->fechanacimiento->ValueList, $this->fechanacimiento->CurrentValue, $this->fechanacimiento->ViewValue, FALSE, $this->fechanacimiento->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->fechanacimiento->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->fechanacimiento->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->fechanacimiento;
			}

			// Build distinct values for sexo
			if ($popupname == 'viewdocente_sexo') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->sexo, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->sexo->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->sexo->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->sexo->setDbValue($rswrk->fields[0]);
					$this->sexo->ViewValue = @$rswrk->fields[1];
					if (is_null($this->sexo->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->sexo->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->sexo->ValueList, $this->sexo->CurrentValue, $this->sexo->ViewValue, FALSE, $this->sexo->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->sexo->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->sexo->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->sexo;
			}

			// Build distinct values for celular
			if ($popupname == 'viewdocente_celular') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->celular, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->celular->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->celular->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->celular->setDbValue($rswrk->fields[0]);
					$this->celular->ViewValue = @$rswrk->fields[1];
					if (is_null($this->celular->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->celular->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->celular->ValueList, $this->celular->CurrentValue, $this->celular->ViewValue, FALSE, $this->celular->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->celular->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->celular->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->celular;
			}

			// Build distinct values for materias
			if ($popupname == 'viewdocente_materias') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->materias, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->materias->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->materias->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->materias->setDbValue($rswrk->fields[0]);
					$this->materias->ViewValue = @$rswrk->fields[1];
					if (is_null($this->materias->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->materias->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->materias->ValueList, $this->materias->CurrentValue, $this->materias->ViewValue, FALSE, $this->materias->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->materias->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->materias->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->materias;
			}

			// Build distinct values for discapacidad
			if ($popupname == 'viewdocente_discapacidad') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->discapacidad, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->discapacidad->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->discapacidad->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->discapacidad->setDbValue($rswrk->fields[0]);
					$this->discapacidad->ViewValue = @$rswrk->fields[1];
					if (is_null($this->discapacidad->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->discapacidad->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->discapacidad->ValueList, $this->discapacidad->CurrentValue, $this->discapacidad->ViewValue, FALSE, $this->discapacidad->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->discapacidad->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->discapacidad->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->discapacidad;
			}

			// Build distinct values for tipodiscapacidad
			if ($popupname == 'viewdocente_tipodiscapacidad') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->tipodiscapacidad, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->tipodiscapacidad->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->tipodiscapacidad->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->tipodiscapacidad->setDbValue($rswrk->fields[0]);
					$this->tipodiscapacidad->ViewValue = @$rswrk->fields[1];
					if (is_null($this->tipodiscapacidad->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->tipodiscapacidad->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->tipodiscapacidad->ValueList, $this->tipodiscapacidad->CurrentValue, $this->tipodiscapacidad->ViewValue, FALSE, $this->tipodiscapacidad->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->tipodiscapacidad->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->tipodiscapacidad->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->tipodiscapacidad;
			}

			// Build distinct values for nombreinstitucion
			if ($popupname == 'viewdocente_nombreinstitucion') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->nombreinstitucion, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->nombreinstitucion->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->nombreinstitucion->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->nombreinstitucion->setDbValue($rswrk->fields[0]);
					$this->nombreinstitucion->ViewValue = @$rswrk->fields[1];
					if (is_null($this->nombreinstitucion->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->nombreinstitucion->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->nombreinstitucion->ValueList, $this->nombreinstitucion->CurrentValue, $this->nombreinstitucion->ViewValue, FALSE, $this->nombreinstitucion->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->nombreinstitucion->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->nombreinstitucion->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->nombreinstitucion;
			}

			// Output data as Json
			if (!is_null($fld)) {
				$jsdb = ewr_GetJsDb($fld, $fld->FldType);
				if (ob_get_length())
					ob_end_clean();
				echo $jsdb;
				exit();
			}
		}
	}

	// Set up popup
	function SetupPopup() {
		global $ReportLanguage;
		$conn = &$this->Connection();
		if ($this->DrillDown)
			return;

		// Process post back form
		if (ewr_IsHttpPost()) {
			$sName = @$_POST["popup"]; // Get popup form name
			if ($sName <> "") {
				$cntValues = (is_array(@$_POST["sel_$sName"])) ? count($_POST["sel_$sName"]) : 0;
				if ($cntValues > 0) {
					$arValues = $_POST["sel_$sName"];
					if (trim($arValues[0]) == "") // Select all
						$arValues = EWR_INIT_VALUE;
					$this->PopupName = $sName;
					if (ewr_IsAdvancedFilterValue($arValues) || $arValues == EWR_INIT_VALUE)
						$this->PopupValue = $arValues;
					if (!ewr_MatchedArray($arValues, $_SESSION["sel_$sName"])) {
						if ($this->HasSessionFilterValues($sName))
							$this->ClearExtFilter = $sName; // Clear extended filter for this field
					}
					$_SESSION["sel_$sName"] = $arValues;
					$_SESSION["rf_$sName"] = @$_POST["rf_$sName"];
					$_SESSION["rt_$sName"] = @$_POST["rt_$sName"];
					$this->ResetPager();
				}
			}

		// Get 'reset' command
		} elseif (@$_GET["cmd"] <> "") {
			$sCmd = $_GET["cmd"];
			if (strtolower($sCmd) == "reset") {
				$this->ClearSessionSelection('deoartamento');
				$this->ClearSessionSelection('unidadeducativa');
				$this->ClearSessionSelection('nombres');
				$this->ClearSessionSelection('apellidopaterno');
				$this->ClearSessionSelection('apellidomaterno');
				$this->ClearSessionSelection('nrodiscapacidad');
				$this->ClearSessionSelection('fechanacimiento');
				$this->ClearSessionSelection('sexo');
				$this->ClearSessionSelection('celular');
				$this->ClearSessionSelection('materias');
				$this->ClearSessionSelection('discapacidad');
				$this->ClearSessionSelection('tipodiscapacidad');
				$this->ClearSessionSelection('nombreinstitucion');
				$this->ResetPager();
			}
		}

		// Load selection criteria to array
		// Get deoartamento selected values

		if (is_array(@$_SESSION["sel_viewdocente_deoartamento"])) {
			$this->LoadSelectionFromSession('deoartamento');
		} elseif (@$_SESSION["sel_viewdocente_deoartamento"] == EWR_INIT_VALUE) { // Select all
			$this->deoartamento->SelectionList = "";
		}

		// Get unidadeducativa selected values
		if (is_array(@$_SESSION["sel_viewdocente_unidadeducativa"])) {
			$this->LoadSelectionFromSession('unidadeducativa');
		} elseif (@$_SESSION["sel_viewdocente_unidadeducativa"] == EWR_INIT_VALUE) { // Select all
			$this->unidadeducativa->SelectionList = "";
		}

		// Get nombres selected values
		if (is_array(@$_SESSION["sel_viewdocente_nombres"])) {
			$this->LoadSelectionFromSession('nombres');
		} elseif (@$_SESSION["sel_viewdocente_nombres"] == EWR_INIT_VALUE) { // Select all
			$this->nombres->SelectionList = "";
		}

		// Get apellidopaterno selected values
		if (is_array(@$_SESSION["sel_viewdocente_apellidopaterno"])) {
			$this->LoadSelectionFromSession('apellidopaterno');
		} elseif (@$_SESSION["sel_viewdocente_apellidopaterno"] == EWR_INIT_VALUE) { // Select all
			$this->apellidopaterno->SelectionList = "";
		}

		// Get apellidomaterno selected values
		if (is_array(@$_SESSION["sel_viewdocente_apellidomaterno"])) {
			$this->LoadSelectionFromSession('apellidomaterno');
		} elseif (@$_SESSION["sel_viewdocente_apellidomaterno"] == EWR_INIT_VALUE) { // Select all
			$this->apellidomaterno->SelectionList = "";
		}

		// Get nrodiscapacidad selected values
		if (is_array(@$_SESSION["sel_viewdocente_nrodiscapacidad"])) {
			$this->LoadSelectionFromSession('nrodiscapacidad');
		} elseif (@$_SESSION["sel_viewdocente_nrodiscapacidad"] == EWR_INIT_VALUE) { // Select all
			$this->nrodiscapacidad->SelectionList = "";
		}

		// Get fechanacimiento selected values
		if (is_array(@$_SESSION["sel_viewdocente_fechanacimiento"])) {
			$this->LoadSelectionFromSession('fechanacimiento');
		} elseif (@$_SESSION["sel_viewdocente_fechanacimiento"] == EWR_INIT_VALUE) { // Select all
			$this->fechanacimiento->SelectionList = "";
		}

		// Get sexo selected values
		if (is_array(@$_SESSION["sel_viewdocente_sexo"])) {
			$this->LoadSelectionFromSession('sexo');
		} elseif (@$_SESSION["sel_viewdocente_sexo"] == EWR_INIT_VALUE) { // Select all
			$this->sexo->SelectionList = "";
		}

		// Get celular selected values
		if (is_array(@$_SESSION["sel_viewdocente_celular"])) {
			$this->LoadSelectionFromSession('celular');
		} elseif (@$_SESSION["sel_viewdocente_celular"] == EWR_INIT_VALUE) { // Select all
			$this->celular->SelectionList = "";
		}

		// Get materias selected values
		if (is_array(@$_SESSION["sel_viewdocente_materias"])) {
			$this->LoadSelectionFromSession('materias');
		} elseif (@$_SESSION["sel_viewdocente_materias"] == EWR_INIT_VALUE) { // Select all
			$this->materias->SelectionList = "";
		}

		// Get discapacidad selected values
		if (is_array(@$_SESSION["sel_viewdocente_discapacidad"])) {
			$this->LoadSelectionFromSession('discapacidad');
		} elseif (@$_SESSION["sel_viewdocente_discapacidad"] == EWR_INIT_VALUE) { // Select all
			$this->discapacidad->SelectionList = "";
		}

		// Get tipodiscapacidad selected values
		if (is_array(@$_SESSION["sel_viewdocente_tipodiscapacidad"])) {
			$this->LoadSelectionFromSession('tipodiscapacidad');
		} elseif (@$_SESSION["sel_viewdocente_tipodiscapacidad"] == EWR_INIT_VALUE) { // Select all
			$this->tipodiscapacidad->SelectionList = "";
		}

		// Get nombreinstitucion selected values
		if (is_array(@$_SESSION["sel_viewdocente_nombreinstitucion"])) {
			$this->LoadSelectionFromSession('nombreinstitucion');
		} elseif (@$_SESSION["sel_viewdocente_nombreinstitucion"] == EWR_INIT_VALUE) { // Select all
			$this->nombreinstitucion->SelectionList = "";
		}
	}

	// Reset pager
	function ResetPager() {

		// Reset start position (reset command)
		$this->StartGrp = 1;
		$this->setStartGroup($this->StartGrp);
	}

	// Set up number of groups displayed per page
	function SetUpDisplayGrps() {
		$sWrk = @$_GET[EWR_TABLE_GROUP_PER_PAGE];
		if ($sWrk <> "") {
			if (is_numeric($sWrk)) {
				$this->DisplayGrps = intval($sWrk);
			} else {
				if (strtoupper($sWrk) == "ALL") { // Display all groups
					$this->DisplayGrps = -1;
				} else {
					$this->DisplayGrps = 3; // Non-numeric, load default
				}
			}
			$this->setGroupPerPage($this->DisplayGrps); // Save to session

			// Reset start position (reset command)
			$this->StartGrp = 1;
			$this->setStartGroup($this->StartGrp);
		} else {
			if ($this->getGroupPerPage() <> "") {
				$this->DisplayGrps = $this->getGroupPerPage(); // Restore from session
			} else {
				$this->DisplayGrps = 3; // Load default
			}
		}
	}

	// Render row
	function RenderRow() {
		global $rs, $Security, $ReportLanguage;
		$conn = &$this->Connection();
		if (!$this->GrandSummarySetup) { // Get Grand total
			$bGotCount = FALSE;
			$bGotSummary = FALSE;

			// Get total count from sql directly
			$sSql = ewr_BuildReportSql($this->getSqlSelectCount(), $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), "", $this->Filter, "");
			$rstot = $conn->Execute($sSql);
			if ($rstot) {
				$this->TotCount = ($rstot->RecordCount()>1) ? $rstot->RecordCount() : $rstot->fields[0];
				$rstot->Close();
				$bGotCount = TRUE;
			} else {
				$this->TotCount = 0;
			}
		$bGotSummary = TRUE;

			// Accumulate grand summary from detail records
			if (!$bGotCount || !$bGotSummary) {
				$sSql = ewr_BuildReportSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), "", $this->Filter, "");
				$rs = $conn->Execute($sSql);
				if ($rs) {
					$this->GetRow(1);
					while (!$rs->EOF) {
						$this->AccumulateGrandSummary();
						$this->GetRow(2);
					}
					$rs->Close();
				}
			}
			$this->GrandSummarySetup = TRUE; // No need to set up again
		}

		// Call Row_Rendering event
		$this->Row_Rendering();

		//
		// Render view codes
		//

		if ($this->RowType == EWR_ROWTYPE_TOTAL && !($this->RowTotalType == EWR_ROWTOTAL_GROUP && $this->RowTotalSubType == EWR_ROWTOTAL_HEADER)) { // Summary row
			ewr_PrependClass($this->RowAttrs["class"], ($this->RowTotalType == EWR_ROWTOTAL_PAGE || $this->RowTotalType == EWR_ROWTOTAL_GRAND) ? "ewRptGrpAggregate" : ""); // Set up row class

			// deoartamento
			$this->deoartamento->HrefValue = "";

			// unidadeducativa
			$this->unidadeducativa->HrefValue = "";

			// nombres
			$this->nombres->HrefValue = "";

			// apellidopaterno
			$this->apellidopaterno->HrefValue = "";

			// apellidomaterno
			$this->apellidomaterno->HrefValue = "";

			// nrodiscapacidad
			$this->nrodiscapacidad->HrefValue = "";

			// ci
			$this->ci->HrefValue = "";

			// fechanacimiento
			$this->fechanacimiento->HrefValue = "";

			// sexo
			$this->sexo->HrefValue = "";

			// celular
			$this->celular->HrefValue = "";

			// materias
			$this->materias->HrefValue = "";

			// discapacidad
			$this->discapacidad->HrefValue = "";

			// tipodiscapacidad
			$this->tipodiscapacidad->HrefValue = "";

			// nombreinstitucion
			$this->nombreinstitucion->HrefValue = "";
		} else {
			if ($this->RowTotalType == EWR_ROWTOTAL_GROUP && $this->RowTotalSubType == EWR_ROWTOTAL_HEADER) {
			} else {
			}

			// deoartamento
			$this->deoartamento->ViewValue = $this->deoartamento->CurrentValue;
			$this->deoartamento->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// unidadeducativa
			$this->unidadeducativa->ViewValue = $this->unidadeducativa->CurrentValue;
			$this->unidadeducativa->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// nombres
			$this->nombres->ViewValue = $this->nombres->CurrentValue;
			$this->nombres->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// apellidopaterno
			$this->apellidopaterno->ViewValue = $this->apellidopaterno->CurrentValue;
			$this->apellidopaterno->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// apellidomaterno
			$this->apellidomaterno->ViewValue = $this->apellidomaterno->CurrentValue;
			$this->apellidomaterno->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// nrodiscapacidad
			$this->nrodiscapacidad->ViewValue = $this->nrodiscapacidad->CurrentValue;
			$this->nrodiscapacidad->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// ci
			$this->ci->ViewValue = $this->ci->CurrentValue;
			$this->ci->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// fechanacimiento
			$this->fechanacimiento->ViewValue = $this->fechanacimiento->CurrentValue;
			$this->fechanacimiento->ViewValue = ewr_FormatDateTime($this->fechanacimiento->ViewValue, 0);
			$this->fechanacimiento->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// sexo
			$this->sexo->ViewValue = $this->sexo->CurrentValue;
			$this->sexo->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// celular
			$this->celular->ViewValue = $this->celular->CurrentValue;
			$this->celular->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// materias
			$this->materias->ViewValue = $this->materias->CurrentValue;
			$this->materias->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// discapacidad
			$this->discapacidad->ViewValue = $this->discapacidad->CurrentValue;
			$this->discapacidad->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// tipodiscapacidad
			$this->tipodiscapacidad->ViewValue = $this->tipodiscapacidad->CurrentValue;
			$this->tipodiscapacidad->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// nombreinstitucion
			$this->nombreinstitucion->ViewValue = $this->nombreinstitucion->CurrentValue;
			$this->nombreinstitucion->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// deoartamento
			$this->deoartamento->HrefValue = "";

			// unidadeducativa
			$this->unidadeducativa->HrefValue = "";

			// nombres
			$this->nombres->HrefValue = "";

			// apellidopaterno
			$this->apellidopaterno->HrefValue = "";

			// apellidomaterno
			$this->apellidomaterno->HrefValue = "";

			// nrodiscapacidad
			$this->nrodiscapacidad->HrefValue = "";

			// ci
			$this->ci->HrefValue = "";

			// fechanacimiento
			$this->fechanacimiento->HrefValue = "";

			// sexo
			$this->sexo->HrefValue = "";

			// celular
			$this->celular->HrefValue = "";

			// materias
			$this->materias->HrefValue = "";

			// discapacidad
			$this->discapacidad->HrefValue = "";

			// tipodiscapacidad
			$this->tipodiscapacidad->HrefValue = "";

			// nombreinstitucion
			$this->nombreinstitucion->HrefValue = "";
		}

		// Call Cell_Rendered event
		if ($this->RowType == EWR_ROWTYPE_TOTAL) { // Summary row
		} else {

			// deoartamento
			$CurrentValue = $this->deoartamento->CurrentValue;
			$ViewValue = &$this->deoartamento->ViewValue;
			$ViewAttrs = &$this->deoartamento->ViewAttrs;
			$CellAttrs = &$this->deoartamento->CellAttrs;
			$HrefValue = &$this->deoartamento->HrefValue;
			$LinkAttrs = &$this->deoartamento->LinkAttrs;
			$this->Cell_Rendered($this->deoartamento, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// unidadeducativa
			$CurrentValue = $this->unidadeducativa->CurrentValue;
			$ViewValue = &$this->unidadeducativa->ViewValue;
			$ViewAttrs = &$this->unidadeducativa->ViewAttrs;
			$CellAttrs = &$this->unidadeducativa->CellAttrs;
			$HrefValue = &$this->unidadeducativa->HrefValue;
			$LinkAttrs = &$this->unidadeducativa->LinkAttrs;
			$this->Cell_Rendered($this->unidadeducativa, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// nombres
			$CurrentValue = $this->nombres->CurrentValue;
			$ViewValue = &$this->nombres->ViewValue;
			$ViewAttrs = &$this->nombres->ViewAttrs;
			$CellAttrs = &$this->nombres->CellAttrs;
			$HrefValue = &$this->nombres->HrefValue;
			$LinkAttrs = &$this->nombres->LinkAttrs;
			$this->Cell_Rendered($this->nombres, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// apellidopaterno
			$CurrentValue = $this->apellidopaterno->CurrentValue;
			$ViewValue = &$this->apellidopaterno->ViewValue;
			$ViewAttrs = &$this->apellidopaterno->ViewAttrs;
			$CellAttrs = &$this->apellidopaterno->CellAttrs;
			$HrefValue = &$this->apellidopaterno->HrefValue;
			$LinkAttrs = &$this->apellidopaterno->LinkAttrs;
			$this->Cell_Rendered($this->apellidopaterno, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// apellidomaterno
			$CurrentValue = $this->apellidomaterno->CurrentValue;
			$ViewValue = &$this->apellidomaterno->ViewValue;
			$ViewAttrs = &$this->apellidomaterno->ViewAttrs;
			$CellAttrs = &$this->apellidomaterno->CellAttrs;
			$HrefValue = &$this->apellidomaterno->HrefValue;
			$LinkAttrs = &$this->apellidomaterno->LinkAttrs;
			$this->Cell_Rendered($this->apellidomaterno, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// nrodiscapacidad
			$CurrentValue = $this->nrodiscapacidad->CurrentValue;
			$ViewValue = &$this->nrodiscapacidad->ViewValue;
			$ViewAttrs = &$this->nrodiscapacidad->ViewAttrs;
			$CellAttrs = &$this->nrodiscapacidad->CellAttrs;
			$HrefValue = &$this->nrodiscapacidad->HrefValue;
			$LinkAttrs = &$this->nrodiscapacidad->LinkAttrs;
			$this->Cell_Rendered($this->nrodiscapacidad, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// ci
			$CurrentValue = $this->ci->CurrentValue;
			$ViewValue = &$this->ci->ViewValue;
			$ViewAttrs = &$this->ci->ViewAttrs;
			$CellAttrs = &$this->ci->CellAttrs;
			$HrefValue = &$this->ci->HrefValue;
			$LinkAttrs = &$this->ci->LinkAttrs;
			$this->Cell_Rendered($this->ci, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// fechanacimiento
			$CurrentValue = $this->fechanacimiento->CurrentValue;
			$ViewValue = &$this->fechanacimiento->ViewValue;
			$ViewAttrs = &$this->fechanacimiento->ViewAttrs;
			$CellAttrs = &$this->fechanacimiento->CellAttrs;
			$HrefValue = &$this->fechanacimiento->HrefValue;
			$LinkAttrs = &$this->fechanacimiento->LinkAttrs;
			$this->Cell_Rendered($this->fechanacimiento, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// sexo
			$CurrentValue = $this->sexo->CurrentValue;
			$ViewValue = &$this->sexo->ViewValue;
			$ViewAttrs = &$this->sexo->ViewAttrs;
			$CellAttrs = &$this->sexo->CellAttrs;
			$HrefValue = &$this->sexo->HrefValue;
			$LinkAttrs = &$this->sexo->LinkAttrs;
			$this->Cell_Rendered($this->sexo, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// celular
			$CurrentValue = $this->celular->CurrentValue;
			$ViewValue = &$this->celular->ViewValue;
			$ViewAttrs = &$this->celular->ViewAttrs;
			$CellAttrs = &$this->celular->CellAttrs;
			$HrefValue = &$this->celular->HrefValue;
			$LinkAttrs = &$this->celular->LinkAttrs;
			$this->Cell_Rendered($this->celular, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// materias
			$CurrentValue = $this->materias->CurrentValue;
			$ViewValue = &$this->materias->ViewValue;
			$ViewAttrs = &$this->materias->ViewAttrs;
			$CellAttrs = &$this->materias->CellAttrs;
			$HrefValue = &$this->materias->HrefValue;
			$LinkAttrs = &$this->materias->LinkAttrs;
			$this->Cell_Rendered($this->materias, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// discapacidad
			$CurrentValue = $this->discapacidad->CurrentValue;
			$ViewValue = &$this->discapacidad->ViewValue;
			$ViewAttrs = &$this->discapacidad->ViewAttrs;
			$CellAttrs = &$this->discapacidad->CellAttrs;
			$HrefValue = &$this->discapacidad->HrefValue;
			$LinkAttrs = &$this->discapacidad->LinkAttrs;
			$this->Cell_Rendered($this->discapacidad, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// tipodiscapacidad
			$CurrentValue = $this->tipodiscapacidad->CurrentValue;
			$ViewValue = &$this->tipodiscapacidad->ViewValue;
			$ViewAttrs = &$this->tipodiscapacidad->ViewAttrs;
			$CellAttrs = &$this->tipodiscapacidad->CellAttrs;
			$HrefValue = &$this->tipodiscapacidad->HrefValue;
			$LinkAttrs = &$this->tipodiscapacidad->LinkAttrs;
			$this->Cell_Rendered($this->tipodiscapacidad, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// nombreinstitucion
			$CurrentValue = $this->nombreinstitucion->CurrentValue;
			$ViewValue = &$this->nombreinstitucion->ViewValue;
			$ViewAttrs = &$this->nombreinstitucion->ViewAttrs;
			$CellAttrs = &$this->nombreinstitucion->CellAttrs;
			$HrefValue = &$this->nombreinstitucion->HrefValue;
			$LinkAttrs = &$this->nombreinstitucion->LinkAttrs;
			$this->Cell_Rendered($this->nombreinstitucion, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);
		}

		// Call Row_Rendered event
		$this->Row_Rendered();
		$this->SetupFieldCount();
	}

	// Setup field count
	function SetupFieldCount() {
		$this->GrpColumnCount = 0;
		$this->SubGrpColumnCount = 0;
		$this->DtlColumnCount = 0;
		if ($this->deoartamento->Visible) $this->DtlColumnCount += 1;
		if ($this->unidadeducativa->Visible) $this->DtlColumnCount += 1;
		if ($this->nombres->Visible) $this->DtlColumnCount += 1;
		if ($this->apellidopaterno->Visible) $this->DtlColumnCount += 1;
		if ($this->apellidomaterno->Visible) $this->DtlColumnCount += 1;
		if ($this->nrodiscapacidad->Visible) $this->DtlColumnCount += 1;
		if ($this->ci->Visible) $this->DtlColumnCount += 1;
		if ($this->fechanacimiento->Visible) $this->DtlColumnCount += 1;
		if ($this->sexo->Visible) $this->DtlColumnCount += 1;
		if ($this->celular->Visible) $this->DtlColumnCount += 1;
		if ($this->materias->Visible) $this->DtlColumnCount += 1;
		if ($this->discapacidad->Visible) $this->DtlColumnCount += 1;
		if ($this->tipodiscapacidad->Visible) $this->DtlColumnCount += 1;
		if ($this->nombreinstitucion->Visible) $this->DtlColumnCount += 1;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $ReportBreadcrumb;
		$ReportBreadcrumb = new crBreadcrumb();
		$url = substr(ewr_CurrentUrl(), strrpos(ewr_CurrentUrl(), "/")+1);
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$ReportBreadcrumb->Add("rpt", $this->TableVar, $url, "", $this->TableVar, TRUE);
	}

	function SetupExportOptionsExt() {
		global $ReportLanguage, $ReportOptions;
		$ReportTypes = $ReportOptions["ReportTypes"];
		$item =& $this->ExportOptions->GetItem("pdf");
		$item->Visible = TRUE;
		if ($item->Visible)
			$ReportTypes["pdf"] = $ReportLanguage->Phrase("ReportFormPdf");
		$exportid = session_id();
		$url = $this->ExportPdfUrl;
		$item->Body = "<a class=\"ewrExportLink ewPdf\" title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToPDF", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToPDF", TRUE)) . "\" href=\"javascript:void(0);\" onclick=\"ewr_ExportCharts(this, '" . $url . "', '" . $exportid . "');\">" . $ReportLanguage->Phrase("ExportToPDF") . "</a>";
		$ReportOptions["ReportTypes"] = $ReportTypes;
	}

	// Return extended filter
	function GetExtendedFilter() {
		global $grFormError;
		$sFilter = "";
		if ($this->DrillDown)
			return "";
		$bPostBack = ewr_IsHttpPost();
		$bRestoreSession = TRUE;
		$bSetupFilter = FALSE;

		// Reset extended filter if filter changed
		if ($bPostBack) {

			// Set/clear dropdown for field deoartamento
			if ($this->PopupName == 'viewdocente_deoartamento' && $this->PopupValue <> "") {
				if ($this->PopupValue == EWR_INIT_VALUE)
					$this->deoartamento->DropDownValue = EWR_ALL_VALUE;
				else
					$this->deoartamento->DropDownValue = $this->PopupValue;
				$bRestoreSession = FALSE; // Do not restore
			} elseif ($this->ClearExtFilter == 'viewdocente_deoartamento') {
				$this->SetSessionDropDownValue(EWR_INIT_VALUE, '', 'deoartamento');
			}

			// Set/clear dropdown for field unidadeducativa
			if ($this->PopupName == 'viewdocente_unidadeducativa' && $this->PopupValue <> "") {
				if ($this->PopupValue == EWR_INIT_VALUE)
					$this->unidadeducativa->DropDownValue = EWR_ALL_VALUE;
				else
					$this->unidadeducativa->DropDownValue = $this->PopupValue;
				$bRestoreSession = FALSE; // Do not restore
			} elseif ($this->ClearExtFilter == 'viewdocente_unidadeducativa') {
				$this->SetSessionDropDownValue(EWR_INIT_VALUE, '', 'unidadeducativa');
			}

			// Set/clear dropdown for field nombres
			if ($this->PopupName == 'viewdocente_nombres' && $this->PopupValue <> "") {
				if ($this->PopupValue == EWR_INIT_VALUE)
					$this->nombres->DropDownValue = EWR_ALL_VALUE;
				else
					$this->nombres->DropDownValue = $this->PopupValue;
				$bRestoreSession = FALSE; // Do not restore
			} elseif ($this->ClearExtFilter == 'viewdocente_nombres') {
				$this->SetSessionDropDownValue(EWR_INIT_VALUE, '', 'nombres');
			}

			// Set/clear dropdown for field apellidopaterno
			if ($this->PopupName == 'viewdocente_apellidopaterno' && $this->PopupValue <> "") {
				if ($this->PopupValue == EWR_INIT_VALUE)
					$this->apellidopaterno->DropDownValue = EWR_ALL_VALUE;
				else
					$this->apellidopaterno->DropDownValue = $this->PopupValue;
				$bRestoreSession = FALSE; // Do not restore
			} elseif ($this->ClearExtFilter == 'viewdocente_apellidopaterno') {
				$this->SetSessionDropDownValue(EWR_INIT_VALUE, '', 'apellidopaterno');
			}

			// Set/clear dropdown for field apellidomaterno
			if ($this->PopupName == 'viewdocente_apellidomaterno' && $this->PopupValue <> "") {
				if ($this->PopupValue == EWR_INIT_VALUE)
					$this->apellidomaterno->DropDownValue = EWR_ALL_VALUE;
				else
					$this->apellidomaterno->DropDownValue = $this->PopupValue;
				$bRestoreSession = FALSE; // Do not restore
			} elseif ($this->ClearExtFilter == 'viewdocente_apellidomaterno') {
				$this->SetSessionDropDownValue(EWR_INIT_VALUE, '', 'apellidomaterno');
			}

			// Clear extended filter for field nrodiscapacidad
			if ($this->ClearExtFilter == 'viewdocente_nrodiscapacidad')
				$this->SetSessionFilterValues('', '=', 'AND', '', '=', 'nrodiscapacidad');

			// Clear extended filter for field materias
			if ($this->ClearExtFilter == 'viewdocente_materias')
				$this->SetSessionFilterValues('', '=', 'AND', '', '=', 'materias');

			// Set/clear dropdown for field discapacidad
			if ($this->PopupName == 'viewdocente_discapacidad' && $this->PopupValue <> "") {
				if ($this->PopupValue == EWR_INIT_VALUE)
					$this->discapacidad->DropDownValue = EWR_ALL_VALUE;
				else
					$this->discapacidad->DropDownValue = $this->PopupValue;
				$bRestoreSession = FALSE; // Do not restore
			} elseif ($this->ClearExtFilter == 'viewdocente_discapacidad') {
				$this->SetSessionDropDownValue(EWR_INIT_VALUE, '', 'discapacidad');
			}

			// Set/clear dropdown for field tipodiscapacidad
			if ($this->PopupName == 'viewdocente_tipodiscapacidad' && $this->PopupValue <> "") {
				if ($this->PopupValue == EWR_INIT_VALUE)
					$this->tipodiscapacidad->DropDownValue = EWR_ALL_VALUE;
				else
					$this->tipodiscapacidad->DropDownValue = $this->PopupValue;
				$bRestoreSession = FALSE; // Do not restore
			} elseif ($this->ClearExtFilter == 'viewdocente_tipodiscapacidad') {
				$this->SetSessionDropDownValue(EWR_INIT_VALUE, '', 'tipodiscapacidad');
			}

			// Set/clear dropdown for field nombreinstitucion
			if ($this->PopupName == 'viewdocente_nombreinstitucion' && $this->PopupValue <> "") {
				if ($this->PopupValue == EWR_INIT_VALUE)
					$this->nombreinstitucion->DropDownValue = EWR_ALL_VALUE;
				else
					$this->nombreinstitucion->DropDownValue = $this->PopupValue;
				$bRestoreSession = FALSE; // Do not restore
			} elseif ($this->ClearExtFilter == 'viewdocente_nombreinstitucion') {
				$this->SetSessionDropDownValue(EWR_INIT_VALUE, '', 'nombreinstitucion');
			}

		// Reset search command
		} elseif (@$_GET["cmd"] == "reset") {

			// Load default values
			$this->SetSessionDropDownValue($this->deoartamento->DropDownValue, $this->deoartamento->SearchOperator, 'deoartamento'); // Field deoartamento
			$this->SetSessionDropDownValue($this->unidadeducativa->DropDownValue, $this->unidadeducativa->SearchOperator, 'unidadeducativa'); // Field unidadeducativa
			$this->SetSessionDropDownValue($this->nombres->DropDownValue, $this->nombres->SearchOperator, 'nombres'); // Field nombres
			$this->SetSessionDropDownValue($this->apellidopaterno->DropDownValue, $this->apellidopaterno->SearchOperator, 'apellidopaterno'); // Field apellidopaterno
			$this->SetSessionDropDownValue($this->apellidomaterno->DropDownValue, $this->apellidomaterno->SearchOperator, 'apellidomaterno'); // Field apellidomaterno
			$this->SetSessionFilterValues($this->nrodiscapacidad->SearchValue, $this->nrodiscapacidad->SearchOperator, $this->nrodiscapacidad->SearchCondition, $this->nrodiscapacidad->SearchValue2, $this->nrodiscapacidad->SearchOperator2, 'nrodiscapacidad'); // Field nrodiscapacidad
			$this->SetSessionFilterValues($this->ci->SearchValue, $this->ci->SearchOperator, $this->ci->SearchCondition, $this->ci->SearchValue2, $this->ci->SearchOperator2, 'ci'); // Field ci
			$this->SetSessionFilterValues($this->materias->SearchValue, $this->materias->SearchOperator, $this->materias->SearchCondition, $this->materias->SearchValue2, $this->materias->SearchOperator2, 'materias'); // Field materias
			$this->SetSessionDropDownValue($this->discapacidad->DropDownValue, $this->discapacidad->SearchOperator, 'discapacidad'); // Field discapacidad
			$this->SetSessionDropDownValue($this->tipodiscapacidad->DropDownValue, $this->tipodiscapacidad->SearchOperator, 'tipodiscapacidad'); // Field tipodiscapacidad
			$this->SetSessionDropDownValue($this->nombreinstitucion->DropDownValue, $this->nombreinstitucion->SearchOperator, 'nombreinstitucion'); // Field nombreinstitucion

			//$bSetupFilter = TRUE; // No need to set up, just use default
		} else {
			$bRestoreSession = !$this->SearchCommand;

			// Field deoartamento
			if ($this->GetDropDownValue($this->deoartamento)) {
				$bSetupFilter = TRUE;
			} elseif ($this->deoartamento->DropDownValue <> EWR_INIT_VALUE && !isset($_SESSION['sv_viewdocente_deoartamento'])) {
				$bSetupFilter = TRUE;
			}

			// Field unidadeducativa
			if ($this->GetDropDownValue($this->unidadeducativa)) {
				$bSetupFilter = TRUE;
			} elseif ($this->unidadeducativa->DropDownValue <> EWR_INIT_VALUE && !isset($_SESSION['sv_viewdocente_unidadeducativa'])) {
				$bSetupFilter = TRUE;
			}

			// Field nombres
			if ($this->GetDropDownValue($this->nombres)) {
				$bSetupFilter = TRUE;
			} elseif ($this->nombres->DropDownValue <> EWR_INIT_VALUE && !isset($_SESSION['sv_viewdocente_nombres'])) {
				$bSetupFilter = TRUE;
			}

			// Field apellidopaterno
			if ($this->GetDropDownValue($this->apellidopaterno)) {
				$bSetupFilter = TRUE;
			} elseif ($this->apellidopaterno->DropDownValue <> EWR_INIT_VALUE && !isset($_SESSION['sv_viewdocente_apellidopaterno'])) {
				$bSetupFilter = TRUE;
			}

			// Field apellidomaterno
			if ($this->GetDropDownValue($this->apellidomaterno)) {
				$bSetupFilter = TRUE;
			} elseif ($this->apellidomaterno->DropDownValue <> EWR_INIT_VALUE && !isset($_SESSION['sv_viewdocente_apellidomaterno'])) {
				$bSetupFilter = TRUE;
			}

			// Field nrodiscapacidad
			if ($this->GetFilterValues($this->nrodiscapacidad)) {
				$bSetupFilter = TRUE;
			}

			// Field ci
			if ($this->GetFilterValues($this->ci)) {
				$bSetupFilter = TRUE;
			}

			// Field materias
			if ($this->GetFilterValues($this->materias)) {
				$bSetupFilter = TRUE;
			}

			// Field discapacidad
			if ($this->GetDropDownValue($this->discapacidad)) {
				$bSetupFilter = TRUE;
			} elseif ($this->discapacidad->DropDownValue <> EWR_INIT_VALUE && !isset($_SESSION['sv_viewdocente_discapacidad'])) {
				$bSetupFilter = TRUE;
			}

			// Field tipodiscapacidad
			if ($this->GetDropDownValue($this->tipodiscapacidad)) {
				$bSetupFilter = TRUE;
			} elseif ($this->tipodiscapacidad->DropDownValue <> EWR_INIT_VALUE && !isset($_SESSION['sv_viewdocente_tipodiscapacidad'])) {
				$bSetupFilter = TRUE;
			}

			// Field nombreinstitucion
			if ($this->GetDropDownValue($this->nombreinstitucion)) {
				$bSetupFilter = TRUE;
			} elseif ($this->nombreinstitucion->DropDownValue <> EWR_INIT_VALUE && !isset($_SESSION['sv_viewdocente_nombreinstitucion'])) {
				$bSetupFilter = TRUE;
			}
			if (!$this->ValidateForm()) {
				$this->setFailureMessage($grFormError);
				return $sFilter;
			}
		}

		// Restore session
		if ($bRestoreSession) {
			$this->GetSessionDropDownValue($this->deoartamento); // Field deoartamento
			$this->GetSessionDropDownValue($this->unidadeducativa); // Field unidadeducativa
			$this->GetSessionDropDownValue($this->nombres); // Field nombres
			$this->GetSessionDropDownValue($this->apellidopaterno); // Field apellidopaterno
			$this->GetSessionDropDownValue($this->apellidomaterno); // Field apellidomaterno
			$this->GetSessionFilterValues($this->nrodiscapacidad); // Field nrodiscapacidad
			$this->GetSessionFilterValues($this->ci); // Field ci
			$this->GetSessionFilterValues($this->materias); // Field materias
			$this->GetSessionDropDownValue($this->discapacidad); // Field discapacidad
			$this->GetSessionDropDownValue($this->tipodiscapacidad); // Field tipodiscapacidad
			$this->GetSessionDropDownValue($this->nombreinstitucion); // Field nombreinstitucion
		}

		// Call page filter validated event
		$this->Page_FilterValidated();

		// Build SQL
		$this->BuildDropDownFilter($this->deoartamento, $sFilter, $this->deoartamento->SearchOperator, FALSE, TRUE); // Field deoartamento
		$this->BuildDropDownFilter($this->unidadeducativa, $sFilter, $this->unidadeducativa->SearchOperator, FALSE, TRUE); // Field unidadeducativa
		$this->BuildDropDownFilter($this->nombres, $sFilter, $this->nombres->SearchOperator, FALSE, TRUE); // Field nombres
		$this->BuildDropDownFilter($this->apellidopaterno, $sFilter, $this->apellidopaterno->SearchOperator, FALSE, TRUE); // Field apellidopaterno
		$this->BuildDropDownFilter($this->apellidomaterno, $sFilter, $this->apellidomaterno->SearchOperator, FALSE, TRUE); // Field apellidomaterno
		$this->BuildExtendedFilter($this->nrodiscapacidad, $sFilter, FALSE, TRUE); // Field nrodiscapacidad
		$this->BuildExtendedFilter($this->ci, $sFilter, FALSE, TRUE); // Field ci
		$this->BuildExtendedFilter($this->materias, $sFilter, FALSE, TRUE); // Field materias
		$this->BuildDropDownFilter($this->discapacidad, $sFilter, $this->discapacidad->SearchOperator, FALSE, TRUE); // Field discapacidad
		$this->BuildDropDownFilter($this->tipodiscapacidad, $sFilter, $this->tipodiscapacidad->SearchOperator, FALSE, TRUE); // Field tipodiscapacidad
		$this->BuildDropDownFilter($this->nombreinstitucion, $sFilter, $this->nombreinstitucion->SearchOperator, FALSE, TRUE); // Field nombreinstitucion

		// Save parms to session
		$this->SetSessionDropDownValue($this->deoartamento->DropDownValue, $this->deoartamento->SearchOperator, 'deoartamento'); // Field deoartamento
		$this->SetSessionDropDownValue($this->unidadeducativa->DropDownValue, $this->unidadeducativa->SearchOperator, 'unidadeducativa'); // Field unidadeducativa
		$this->SetSessionDropDownValue($this->nombres->DropDownValue, $this->nombres->SearchOperator, 'nombres'); // Field nombres
		$this->SetSessionDropDownValue($this->apellidopaterno->DropDownValue, $this->apellidopaterno->SearchOperator, 'apellidopaterno'); // Field apellidopaterno
		$this->SetSessionDropDownValue($this->apellidomaterno->DropDownValue, $this->apellidomaterno->SearchOperator, 'apellidomaterno'); // Field apellidomaterno
		$this->SetSessionFilterValues($this->nrodiscapacidad->SearchValue, $this->nrodiscapacidad->SearchOperator, $this->nrodiscapacidad->SearchCondition, $this->nrodiscapacidad->SearchValue2, $this->nrodiscapacidad->SearchOperator2, 'nrodiscapacidad'); // Field nrodiscapacidad
		$this->SetSessionFilterValues($this->ci->SearchValue, $this->ci->SearchOperator, $this->ci->SearchCondition, $this->ci->SearchValue2, $this->ci->SearchOperator2, 'ci'); // Field ci
		$this->SetSessionFilterValues($this->materias->SearchValue, $this->materias->SearchOperator, $this->materias->SearchCondition, $this->materias->SearchValue2, $this->materias->SearchOperator2, 'materias'); // Field materias
		$this->SetSessionDropDownValue($this->discapacidad->DropDownValue, $this->discapacidad->SearchOperator, 'discapacidad'); // Field discapacidad
		$this->SetSessionDropDownValue($this->tipodiscapacidad->DropDownValue, $this->tipodiscapacidad->SearchOperator, 'tipodiscapacidad'); // Field tipodiscapacidad
		$this->SetSessionDropDownValue($this->nombreinstitucion->DropDownValue, $this->nombreinstitucion->SearchOperator, 'nombreinstitucion'); // Field nombreinstitucion

		// Setup filter
		if ($bSetupFilter) {

			// Field deoartamento
			$sWrk = "";
			$this->BuildDropDownFilter($this->deoartamento, $sWrk, $this->deoartamento->SearchOperator);
			ewr_LoadSelectionFromFilter($this->deoartamento, $sWrk, $this->deoartamento->SelectionList, $this->deoartamento->DropDownValue);
			$_SESSION['sel_viewdocente_deoartamento'] = ($this->deoartamento->SelectionList == "") ? EWR_INIT_VALUE : $this->deoartamento->SelectionList;

			// Field unidadeducativa
			$sWrk = "";
			$this->BuildDropDownFilter($this->unidadeducativa, $sWrk, $this->unidadeducativa->SearchOperator);
			ewr_LoadSelectionFromFilter($this->unidadeducativa, $sWrk, $this->unidadeducativa->SelectionList, $this->unidadeducativa->DropDownValue);
			$_SESSION['sel_viewdocente_unidadeducativa'] = ($this->unidadeducativa->SelectionList == "") ? EWR_INIT_VALUE : $this->unidadeducativa->SelectionList;

			// Field nombres
			$sWrk = "";
			$this->BuildDropDownFilter($this->nombres, $sWrk, $this->nombres->SearchOperator);
			ewr_LoadSelectionFromFilter($this->nombres, $sWrk, $this->nombres->SelectionList, $this->nombres->DropDownValue);
			$_SESSION['sel_viewdocente_nombres'] = ($this->nombres->SelectionList == "") ? EWR_INIT_VALUE : $this->nombres->SelectionList;

			// Field apellidopaterno
			$sWrk = "";
			$this->BuildDropDownFilter($this->apellidopaterno, $sWrk, $this->apellidopaterno->SearchOperator);
			ewr_LoadSelectionFromFilter($this->apellidopaterno, $sWrk, $this->apellidopaterno->SelectionList, $this->apellidopaterno->DropDownValue);
			$_SESSION['sel_viewdocente_apellidopaterno'] = ($this->apellidopaterno->SelectionList == "") ? EWR_INIT_VALUE : $this->apellidopaterno->SelectionList;

			// Field apellidomaterno
			$sWrk = "";
			$this->BuildDropDownFilter($this->apellidomaterno, $sWrk, $this->apellidomaterno->SearchOperator);
			ewr_LoadSelectionFromFilter($this->apellidomaterno, $sWrk, $this->apellidomaterno->SelectionList, $this->apellidomaterno->DropDownValue);
			$_SESSION['sel_viewdocente_apellidomaterno'] = ($this->apellidomaterno->SelectionList == "") ? EWR_INIT_VALUE : $this->apellidomaterno->SelectionList;

			// Field nrodiscapacidad
			$sWrk = "";
			$this->BuildExtendedFilter($this->nrodiscapacidad, $sWrk);
			ewr_LoadSelectionFromFilter($this->nrodiscapacidad, $sWrk, $this->nrodiscapacidad->SelectionList);
			$_SESSION['sel_viewdocente_nrodiscapacidad'] = ($this->nrodiscapacidad->SelectionList == "") ? EWR_INIT_VALUE : $this->nrodiscapacidad->SelectionList;

			// Field materias
			$sWrk = "";
			$this->BuildExtendedFilter($this->materias, $sWrk);
			ewr_LoadSelectionFromFilter($this->materias, $sWrk, $this->materias->SelectionList);
			$_SESSION['sel_viewdocente_materias'] = ($this->materias->SelectionList == "") ? EWR_INIT_VALUE : $this->materias->SelectionList;

			// Field discapacidad
			$sWrk = "";
			$this->BuildDropDownFilter($this->discapacidad, $sWrk, $this->discapacidad->SearchOperator);
			ewr_LoadSelectionFromFilter($this->discapacidad, $sWrk, $this->discapacidad->SelectionList, $this->discapacidad->DropDownValue);
			$_SESSION['sel_viewdocente_discapacidad'] = ($this->discapacidad->SelectionList == "") ? EWR_INIT_VALUE : $this->discapacidad->SelectionList;

			// Field tipodiscapacidad
			$sWrk = "";
			$this->BuildDropDownFilter($this->tipodiscapacidad, $sWrk, $this->tipodiscapacidad->SearchOperator);
			ewr_LoadSelectionFromFilter($this->tipodiscapacidad, $sWrk, $this->tipodiscapacidad->SelectionList, $this->tipodiscapacidad->DropDownValue);
			$_SESSION['sel_viewdocente_tipodiscapacidad'] = ($this->tipodiscapacidad->SelectionList == "") ? EWR_INIT_VALUE : $this->tipodiscapacidad->SelectionList;

			// Field nombreinstitucion
			$sWrk = "";
			$this->BuildDropDownFilter($this->nombreinstitucion, $sWrk, $this->nombreinstitucion->SearchOperator);
			ewr_LoadSelectionFromFilter($this->nombreinstitucion, $sWrk, $this->nombreinstitucion->SelectionList, $this->nombreinstitucion->DropDownValue);
			$_SESSION['sel_viewdocente_nombreinstitucion'] = ($this->nombreinstitucion->SelectionList == "") ? EWR_INIT_VALUE : $this->nombreinstitucion->SelectionList;
		}

		// Field deoartamento
		ewr_LoadDropDownList($this->deoartamento->DropDownList, $this->deoartamento->DropDownValue);

		// Field unidadeducativa
		ewr_LoadDropDownList($this->unidadeducativa->DropDownList, $this->unidadeducativa->DropDownValue);

		// Field nombres
		ewr_LoadDropDownList($this->nombres->DropDownList, $this->nombres->DropDownValue);

		// Field apellidopaterno
		ewr_LoadDropDownList($this->apellidopaterno->DropDownList, $this->apellidopaterno->DropDownValue);

		// Field apellidomaterno
		ewr_LoadDropDownList($this->apellidomaterno->DropDownList, $this->apellidomaterno->DropDownValue);

		// Field discapacidad
		ewr_LoadDropDownList($this->discapacidad->DropDownList, $this->discapacidad->DropDownValue);

		// Field tipodiscapacidad
		ewr_LoadDropDownList($this->tipodiscapacidad->DropDownList, $this->tipodiscapacidad->DropDownValue);

		// Field nombreinstitucion
		ewr_LoadDropDownList($this->nombreinstitucion->DropDownList, $this->nombreinstitucion->DropDownValue);
		return $sFilter;
	}

	// Build dropdown filter
	function BuildDropDownFilter(&$fld, &$FilterClause, $FldOpr, $Default = FALSE, $SaveFilter = FALSE) {
		$FldVal = ($Default) ? $fld->DefaultDropDownValue : $fld->DropDownValue;
		$sSql = "";
		if (is_array($FldVal)) {
			foreach ($FldVal as $val) {
				$sWrk = $this->GetDropDownFilter($fld, $val, $FldOpr);

				// Call Page Filtering event
				if (substr($val, 0, 2) <> "@@")
					$this->Page_Filtering($fld, $sWrk, "dropdown", $FldOpr, $val);
				if ($sWrk <> "") {
					if ($sSql <> "")
						$sSql .= " OR " . $sWrk;
					else
						$sSql = $sWrk;
				}
			}
		} else {
			$sSql = $this->GetDropDownFilter($fld, $FldVal, $FldOpr);

			// Call Page Filtering event
			if (substr($FldVal, 0, 2) <> "@@")
				$this->Page_Filtering($fld, $sSql, "dropdown", $FldOpr, $FldVal);
		}
		if ($sSql <> "") {
			ewr_AddFilter($FilterClause, $sSql);
			if ($SaveFilter) $fld->CurrentFilter = $sSql;
		}
	}

	function GetDropDownFilter(&$fld, $FldVal, $FldOpr) {
		$FldName = $fld->FldName;
		$FldExpression = $fld->FldExpression;
		$FldDataType = $fld->FldDataType;
		$FldDelimiter = $fld->FldDelimiter;
		$FldVal = strval($FldVal);
		if ($FldOpr == "") $FldOpr = "=";
		$sWrk = "";
		if (ewr_SameStr($FldVal, EWR_NULL_VALUE)) {
			$sWrk = $FldExpression . " IS NULL";
		} elseif (ewr_SameStr($FldVal, EWR_NOT_NULL_VALUE)) {
			$sWrk = $FldExpression . " IS NOT NULL";
		} elseif (ewr_SameStr($FldVal, EWR_EMPTY_VALUE)) {
			$sWrk = $FldExpression . " = ''";
		} elseif (ewr_SameStr($FldVal, EWR_ALL_VALUE)) {
			$sWrk = "1 = 1";
		} else {
			if (substr($FldVal, 0, 2) == "@@") {
				$sWrk = $this->GetCustomFilter($fld, $FldVal, $this->DBID);
			} elseif ($FldDelimiter <> "" && trim($FldVal) <> "" && ($FldDataType == EWR_DATATYPE_STRING || $FldDataType == EWR_DATATYPE_MEMO)) {
				$sWrk = ewr_GetMultiSearchSql($FldExpression, trim($FldVal), $this->DBID);
			} else {
				if ($FldVal <> "" && $FldVal <> EWR_INIT_VALUE) {
					if ($FldDataType == EWR_DATATYPE_DATE && $FldOpr <> "") {
						$sWrk = ewr_DateFilterString($FldExpression, $FldOpr, $FldVal, $FldDataType, $this->DBID);
					} else {
						$sWrk = ewr_FilterString($FldOpr, $FldVal, $FldDataType, $this->DBID);
						if ($sWrk <> "") $sWrk = $FldExpression . $sWrk;
					}
				}
			}
		}
		return $sWrk;
	}

	// Get custom filter
	function GetCustomFilter(&$fld, $FldVal, $dbid = 0) {
		$sWrk = "";
		if (is_array($fld->AdvancedFilters)) {
			foreach ($fld->AdvancedFilters as $filter) {
				if ($filter->ID == $FldVal && $filter->Enabled) {
					$sFld = $fld->FldExpression;
					$sFn = $filter->FunctionName;
					$wrkid = (substr($filter->ID, 0, 2) == "@@") ? substr($filter->ID,2) : $filter->ID;
					if ($sFn <> "")
						$sWrk = $sFn($sFld, $dbid);
					else
						$sWrk = "";
					$this->Page_Filtering($fld, $sWrk, "custom", $wrkid);
					break;
				}
			}
		}
		return $sWrk;
	}

	// Build extended filter
	function BuildExtendedFilter(&$fld, &$FilterClause, $Default = FALSE, $SaveFilter = FALSE) {
		$sWrk = ewr_GetExtendedFilter($fld, $Default, $this->DBID);
		if (!$Default)
			$this->Page_Filtering($fld, $sWrk, "extended", $fld->SearchOperator, $fld->SearchValue, $fld->SearchCondition, $fld->SearchOperator2, $fld->SearchValue2);
		if ($sWrk <> "") {
			ewr_AddFilter($FilterClause, $sWrk);
			if ($SaveFilter) $fld->CurrentFilter = $sWrk;
		}
	}

	// Get drop down value from querystring
	function GetDropDownValue(&$fld) {
		$parm = substr($fld->FldVar, 2);
		if (ewr_IsHttpPost())
			return FALSE; // Skip post back
		if (isset($_GET["so_$parm"]))
			$fld->SearchOperator = @$_GET["so_$parm"];
		if (isset($_GET["sv_$parm"])) {
			$fld->DropDownValue = @$_GET["sv_$parm"];
			return TRUE;
		}
		return FALSE;
	}

	// Get filter values from querystring
	function GetFilterValues(&$fld) {
		$parm = substr($fld->FldVar, 2);
		if (ewr_IsHttpPost())
			return; // Skip post back
		$got = FALSE;
		if (isset($_GET["sv_$parm"])) {
			$fld->SearchValue = @$_GET["sv_$parm"];
			$got = TRUE;
		}
		if (isset($_GET["so_$parm"])) {
			$fld->SearchOperator = @$_GET["so_$parm"];
			$got = TRUE;
		}
		if (isset($_GET["sc_$parm"])) {
			$fld->SearchCondition = @$_GET["sc_$parm"];
			$got = TRUE;
		}
		if (isset($_GET["sv2_$parm"])) {
			$fld->SearchValue2 = @$_GET["sv2_$parm"];
			$got = TRUE;
		}
		if (isset($_GET["so2_$parm"])) {
			$fld->SearchOperator2 = $_GET["so2_$parm"];
			$got = TRUE;
		}
		return $got;
	}

	// Set default ext filter
	function SetDefaultExtFilter(&$fld, $so1, $sv1, $sc, $so2, $sv2) {
		$fld->DefaultSearchValue = $sv1; // Default ext filter value 1
		$fld->DefaultSearchValue2 = $sv2; // Default ext filter value 2 (if operator 2 is enabled)
		$fld->DefaultSearchOperator = $so1; // Default search operator 1
		$fld->DefaultSearchOperator2 = $so2; // Default search operator 2 (if operator 2 is enabled)
		$fld->DefaultSearchCondition = $sc; // Default search condition (if operator 2 is enabled)
	}

	// Apply default ext filter
	function ApplyDefaultExtFilter(&$fld) {
		$fld->SearchValue = $fld->DefaultSearchValue;
		$fld->SearchValue2 = $fld->DefaultSearchValue2;
		$fld->SearchOperator = $fld->DefaultSearchOperator;
		$fld->SearchOperator2 = $fld->DefaultSearchOperator2;
		$fld->SearchCondition = $fld->DefaultSearchCondition;
	}

	// Check if Text Filter applied
	function TextFilterApplied(&$fld) {
		return (strval($fld->SearchValue) <> strval($fld->DefaultSearchValue) ||
			strval($fld->SearchValue2) <> strval($fld->DefaultSearchValue2) ||
			(strval($fld->SearchValue) <> "" &&
				strval($fld->SearchOperator) <> strval($fld->DefaultSearchOperator)) ||
			(strval($fld->SearchValue2) <> "" &&
				strval($fld->SearchOperator2) <> strval($fld->DefaultSearchOperator2)) ||
			strval($fld->SearchCondition) <> strval($fld->DefaultSearchCondition));
	}

	// Check if Non-Text Filter applied
	function NonTextFilterApplied(&$fld) {
		if (is_array($fld->DropDownValue)) {
			if (is_array($fld->DefaultDropDownValue)) {
				if (count($fld->DefaultDropDownValue) <> count($fld->DropDownValue))
					return TRUE;
				else
					return (count(array_diff($fld->DefaultDropDownValue, $fld->DropDownValue)) <> 0);
			} else {
				return TRUE;
			}
		} else {
			if (is_array($fld->DefaultDropDownValue))
				return TRUE;
			else
				$v1 = strval($fld->DefaultDropDownValue);
			if ($v1 == EWR_INIT_VALUE)
				$v1 = "";
			$v2 = strval($fld->DropDownValue);
			if ($v2 == EWR_INIT_VALUE || $v2 == EWR_ALL_VALUE)
				$v2 = "";
			return ($v1 <> $v2);
		}
	}

	// Get dropdown value from session
	function GetSessionDropDownValue(&$fld) {
		$parm = substr($fld->FldVar, 2);
		$this->GetSessionValue($fld->DropDownValue, 'sv_viewdocente_' . $parm);
		$this->GetSessionValue($fld->SearchOperator, 'so_viewdocente_' . $parm);
	}

	// Get filter values from session
	function GetSessionFilterValues(&$fld) {
		$parm = substr($fld->FldVar, 2);
		$this->GetSessionValue($fld->SearchValue, 'sv_viewdocente_' . $parm);
		$this->GetSessionValue($fld->SearchOperator, 'so_viewdocente_' . $parm);
		$this->GetSessionValue($fld->SearchCondition, 'sc_viewdocente_' . $parm);
		$this->GetSessionValue($fld->SearchValue2, 'sv2_viewdocente_' . $parm);
		$this->GetSessionValue($fld->SearchOperator2, 'so2_viewdocente_' . $parm);
	}

	// Get value from session
	function GetSessionValue(&$sv, $sn) {
		if (array_key_exists($sn, $_SESSION))
			$sv = $_SESSION[$sn];
	}

	// Set dropdown value to session
	function SetSessionDropDownValue($sv, $so, $parm) {
		$_SESSION['sv_viewdocente_' . $parm] = $sv;
		$_SESSION['so_viewdocente_' . $parm] = $so;
	}

	// Set filter values to session
	function SetSessionFilterValues($sv1, $so1, $sc, $sv2, $so2, $parm) {
		$_SESSION['sv_viewdocente_' . $parm] = $sv1;
		$_SESSION['so_viewdocente_' . $parm] = $so1;
		$_SESSION['sc_viewdocente_' . $parm] = $sc;
		$_SESSION['sv2_viewdocente_' . $parm] = $sv2;
		$_SESSION['so2_viewdocente_' . $parm] = $so2;
	}

	// Check if has Session filter values
	function HasSessionFilterValues($parm) {
		return ((@$_SESSION['sv_' . $parm] <> "" && @$_SESSION['sv_' . $parm] <> EWR_INIT_VALUE) ||
			(@$_SESSION['sv_' . $parm] <> "" && @$_SESSION['sv_' . $parm] <> EWR_INIT_VALUE) ||
			(@$_SESSION['sv2_' . $parm] <> "" && @$_SESSION['sv2_' . $parm] <> EWR_INIT_VALUE));
	}

	// Dropdown filter exist
	function DropDownFilterExist(&$fld, $FldOpr) {
		$sWrk = "";
		$this->BuildDropDownFilter($fld, $sWrk, $FldOpr);
		return ($sWrk <> "");
	}

	// Extended filter exist
	function ExtendedFilterExist(&$fld) {
		$sExtWrk = "";
		$this->BuildExtendedFilter($fld, $sExtWrk);
		return ($sExtWrk <> "");
	}

	// Validate form
	function ValidateForm() {
		global $ReportLanguage, $grFormError;

		// Initialize form error message
		$grFormError = "";

		// Check if validation required
		if (!EWR_SERVER_VALIDATE)
			return ($grFormError == "");

		// Return validate result
		$ValidateForm = ($grFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			$grFormError .= ($grFormError <> "") ? "<p>&nbsp;</p>" : "";
			$grFormError .= $sFormCustomError;
		}
		return $ValidateForm;
	}

	// Clear selection stored in session
	function ClearSessionSelection($parm) {
		$_SESSION["sel_viewdocente_$parm"] = "";
		$_SESSION["rf_viewdocente_$parm"] = "";
		$_SESSION["rt_viewdocente_$parm"] = "";
	}

	// Load selection from session
	function LoadSelectionFromSession($parm) {
		$fld = &$this->FieldByParm($parm);
		$fld->SelectionList = @$_SESSION["sel_viewdocente_$parm"];
		$fld->RangeFrom = @$_SESSION["rf_viewdocente_$parm"];
		$fld->RangeTo = @$_SESSION["rt_viewdocente_$parm"];
	}

	// Load default value for filters
	function LoadDefaultFilters() {
		/**
		* Set up default values for non Text filters
		*/

		// Field deoartamento
		$this->deoartamento->DefaultDropDownValue = EWR_INIT_VALUE;
		if (!$this->SearchCommand) $this->deoartamento->DropDownValue = $this->deoartamento->DefaultDropDownValue;
		$sWrk = "";
		$this->BuildDropDownFilter($this->deoartamento, $sWrk, $this->deoartamento->SearchOperator, TRUE);
		ewr_LoadSelectionFromFilter($this->deoartamento, $sWrk, $this->deoartamento->DefaultSelectionList);
		if (!$this->SearchCommand) $this->deoartamento->SelectionList = $this->deoartamento->DefaultSelectionList;

		// Field unidadeducativa
		$this->unidadeducativa->DefaultDropDownValue = EWR_INIT_VALUE;
		if (!$this->SearchCommand) $this->unidadeducativa->DropDownValue = $this->unidadeducativa->DefaultDropDownValue;
		$sWrk = "";
		$this->BuildDropDownFilter($this->unidadeducativa, $sWrk, $this->unidadeducativa->SearchOperator, TRUE);
		ewr_LoadSelectionFromFilter($this->unidadeducativa, $sWrk, $this->unidadeducativa->DefaultSelectionList);
		if (!$this->SearchCommand) $this->unidadeducativa->SelectionList = $this->unidadeducativa->DefaultSelectionList;

		// Field nombres
		$this->nombres->DefaultDropDownValue = EWR_INIT_VALUE;
		if (!$this->SearchCommand) $this->nombres->DropDownValue = $this->nombres->DefaultDropDownValue;
		$sWrk = "";
		$this->BuildDropDownFilter($this->nombres, $sWrk, $this->nombres->SearchOperator, TRUE);
		ewr_LoadSelectionFromFilter($this->nombres, $sWrk, $this->nombres->DefaultSelectionList);
		if (!$this->SearchCommand) $this->nombres->SelectionList = $this->nombres->DefaultSelectionList;

		// Field apellidopaterno
		$this->apellidopaterno->DefaultDropDownValue = EWR_INIT_VALUE;
		if (!$this->SearchCommand) $this->apellidopaterno->DropDownValue = $this->apellidopaterno->DefaultDropDownValue;
		$sWrk = "";
		$this->BuildDropDownFilter($this->apellidopaterno, $sWrk, $this->apellidopaterno->SearchOperator, TRUE);
		ewr_LoadSelectionFromFilter($this->apellidopaterno, $sWrk, $this->apellidopaterno->DefaultSelectionList);
		if (!$this->SearchCommand) $this->apellidopaterno->SelectionList = $this->apellidopaterno->DefaultSelectionList;

		// Field apellidomaterno
		$this->apellidomaterno->DefaultDropDownValue = EWR_INIT_VALUE;
		if (!$this->SearchCommand) $this->apellidomaterno->DropDownValue = $this->apellidomaterno->DefaultDropDownValue;
		$sWrk = "";
		$this->BuildDropDownFilter($this->apellidomaterno, $sWrk, $this->apellidomaterno->SearchOperator, TRUE);
		ewr_LoadSelectionFromFilter($this->apellidomaterno, $sWrk, $this->apellidomaterno->DefaultSelectionList);
		if (!$this->SearchCommand) $this->apellidomaterno->SelectionList = $this->apellidomaterno->DefaultSelectionList;

		// Field discapacidad
		$this->discapacidad->DefaultDropDownValue = EWR_INIT_VALUE;
		if (!$this->SearchCommand) $this->discapacidad->DropDownValue = $this->discapacidad->DefaultDropDownValue;
		$sWrk = "";
		$this->BuildDropDownFilter($this->discapacidad, $sWrk, $this->discapacidad->SearchOperator, TRUE);
		ewr_LoadSelectionFromFilter($this->discapacidad, $sWrk, $this->discapacidad->DefaultSelectionList);
		if (!$this->SearchCommand) $this->discapacidad->SelectionList = $this->discapacidad->DefaultSelectionList;

		// Field tipodiscapacidad
		$this->tipodiscapacidad->DefaultDropDownValue = EWR_INIT_VALUE;
		if (!$this->SearchCommand) $this->tipodiscapacidad->DropDownValue = $this->tipodiscapacidad->DefaultDropDownValue;
		$sWrk = "";
		$this->BuildDropDownFilter($this->tipodiscapacidad, $sWrk, $this->tipodiscapacidad->SearchOperator, TRUE);
		ewr_LoadSelectionFromFilter($this->tipodiscapacidad, $sWrk, $this->tipodiscapacidad->DefaultSelectionList);
		if (!$this->SearchCommand) $this->tipodiscapacidad->SelectionList = $this->tipodiscapacidad->DefaultSelectionList;

		// Field nombreinstitucion
		$this->nombreinstitucion->DefaultDropDownValue = EWR_INIT_VALUE;
		if (!$this->SearchCommand) $this->nombreinstitucion->DropDownValue = $this->nombreinstitucion->DefaultDropDownValue;
		$sWrk = "";
		$this->BuildDropDownFilter($this->nombreinstitucion, $sWrk, $this->nombreinstitucion->SearchOperator, TRUE);
		ewr_LoadSelectionFromFilter($this->nombreinstitucion, $sWrk, $this->nombreinstitucion->DefaultSelectionList);
		if (!$this->SearchCommand) $this->nombreinstitucion->SelectionList = $this->nombreinstitucion->DefaultSelectionList;
		/**
		* Set up default values for extended filters
		* function SetDefaultExtFilter(&$fld, $so1, $sv1, $sc, $so2, $sv2)
		* Parameters:
		* $fld - Field object
		* $so1 - Default search operator 1
		* $sv1 - Default ext filter value 1
		* $sc - Default search condition (if operator 2 is enabled)
		* $so2 - Default search operator 2 (if operator 2 is enabled)
		* $sv2 - Default ext filter value 2 (if operator 2 is enabled)
		*/

		// Field nrodiscapacidad
		$this->SetDefaultExtFilter($this->nrodiscapacidad, "LIKE", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->nrodiscapacidad);
		$sWrk = "";
		$this->BuildExtendedFilter($this->nrodiscapacidad, $sWrk, TRUE);
		ewr_LoadSelectionFromFilter($this->nrodiscapacidad, $sWrk, $this->nrodiscapacidad->DefaultSelectionList);
		if (!$this->SearchCommand) $this->nrodiscapacidad->SelectionList = $this->nrodiscapacidad->DefaultSelectionList;

		// Field ci
		$this->SetDefaultExtFilter($this->ci, "LIKE", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->ci);

		// Field materias
		$this->SetDefaultExtFilter($this->materias, "LIKE", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->materias);
		$sWrk = "";
		$this->BuildExtendedFilter($this->materias, $sWrk, TRUE);
		ewr_LoadSelectionFromFilter($this->materias, $sWrk, $this->materias->DefaultSelectionList);
		if (!$this->SearchCommand) $this->materias->SelectionList = $this->materias->DefaultSelectionList;
		/**
		* Set up default values for popup filters
		*/

		// Field deoartamento
		// $this->deoartamento->DefaultSelectionList = array("val1", "val2");
		// Field unidadeducativa
		// $this->unidadeducativa->DefaultSelectionList = array("val1", "val2");
		// Field nombres
		// $this->nombres->DefaultSelectionList = array("val1", "val2");
		// Field apellidopaterno
		// $this->apellidopaterno->DefaultSelectionList = array("val1", "val2");
		// Field apellidomaterno
		// $this->apellidomaterno->DefaultSelectionList = array("val1", "val2");
		// Field nrodiscapacidad
		// $this->nrodiscapacidad->DefaultSelectionList = array("val1", "val2");
		// Field fechanacimiento
		// $this->fechanacimiento->DefaultSelectionList = array("val1", "val2");
		// Field sexo
		// $this->sexo->DefaultSelectionList = array("val1", "val2");
		// Field celular
		// $this->celular->DefaultSelectionList = array("val1", "val2");
		// Field materias
		// $this->materias->DefaultSelectionList = array("val1", "val2");
		// Field discapacidad
		// $this->discapacidad->DefaultSelectionList = array("val1", "val2");
		// Field tipodiscapacidad
		// $this->tipodiscapacidad->DefaultSelectionList = array("val1", "val2");
		// Field nombreinstitucion
		// $this->nombreinstitucion->DefaultSelectionList = array("val1", "val2");

	}

	// Check if filter applied
	function CheckFilter() {

		// Check deoartamento extended filter
		if ($this->NonTextFilterApplied($this->deoartamento))
			return TRUE;

		// Check deoartamento popup filter
		if (!ewr_MatchedArray($this->deoartamento->DefaultSelectionList, $this->deoartamento->SelectionList))
			return TRUE;

		// Check unidadeducativa extended filter
		if ($this->NonTextFilterApplied($this->unidadeducativa))
			return TRUE;

		// Check unidadeducativa popup filter
		if (!ewr_MatchedArray($this->unidadeducativa->DefaultSelectionList, $this->unidadeducativa->SelectionList))
			return TRUE;

		// Check nombres extended filter
		if ($this->NonTextFilterApplied($this->nombres))
			return TRUE;

		// Check nombres popup filter
		if (!ewr_MatchedArray($this->nombres->DefaultSelectionList, $this->nombres->SelectionList))
			return TRUE;

		// Check apellidopaterno extended filter
		if ($this->NonTextFilterApplied($this->apellidopaterno))
			return TRUE;

		// Check apellidopaterno popup filter
		if (!ewr_MatchedArray($this->apellidopaterno->DefaultSelectionList, $this->apellidopaterno->SelectionList))
			return TRUE;

		// Check apellidomaterno extended filter
		if ($this->NonTextFilterApplied($this->apellidomaterno))
			return TRUE;

		// Check apellidomaterno popup filter
		if (!ewr_MatchedArray($this->apellidomaterno->DefaultSelectionList, $this->apellidomaterno->SelectionList))
			return TRUE;

		// Check nrodiscapacidad text filter
		if ($this->TextFilterApplied($this->nrodiscapacidad))
			return TRUE;

		// Check nrodiscapacidad popup filter
		if (!ewr_MatchedArray($this->nrodiscapacidad->DefaultSelectionList, $this->nrodiscapacidad->SelectionList))
			return TRUE;

		// Check ci text filter
		if ($this->TextFilterApplied($this->ci))
			return TRUE;

		// Check fechanacimiento popup filter
		if (!ewr_MatchedArray($this->fechanacimiento->DefaultSelectionList, $this->fechanacimiento->SelectionList))
			return TRUE;

		// Check sexo popup filter
		if (!ewr_MatchedArray($this->sexo->DefaultSelectionList, $this->sexo->SelectionList))
			return TRUE;

		// Check celular popup filter
		if (!ewr_MatchedArray($this->celular->DefaultSelectionList, $this->celular->SelectionList))
			return TRUE;

		// Check materias text filter
		if ($this->TextFilterApplied($this->materias))
			return TRUE;

		// Check materias popup filter
		if (!ewr_MatchedArray($this->materias->DefaultSelectionList, $this->materias->SelectionList))
			return TRUE;

		// Check discapacidad extended filter
		if ($this->NonTextFilterApplied($this->discapacidad))
			return TRUE;

		// Check discapacidad popup filter
		if (!ewr_MatchedArray($this->discapacidad->DefaultSelectionList, $this->discapacidad->SelectionList))
			return TRUE;

		// Check tipodiscapacidad extended filter
		if ($this->NonTextFilterApplied($this->tipodiscapacidad))
			return TRUE;

		// Check tipodiscapacidad popup filter
		if (!ewr_MatchedArray($this->tipodiscapacidad->DefaultSelectionList, $this->tipodiscapacidad->SelectionList))
			return TRUE;

		// Check nombreinstitucion extended filter
		if ($this->NonTextFilterApplied($this->nombreinstitucion))
			return TRUE;

		// Check nombreinstitucion popup filter
		if (!ewr_MatchedArray($this->nombreinstitucion->DefaultSelectionList, $this->nombreinstitucion->SelectionList))
			return TRUE;
		return FALSE;
	}

	// Show list of filters
	function ShowFilterList($showDate = FALSE) {
		global $ReportLanguage;

		// Initialize
		$sFilterList = "";

		// Field deoartamento
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildDropDownFilter($this->deoartamento, $sExtWrk, $this->deoartamento->SearchOperator);
		if (is_array($this->deoartamento->SelectionList))
			$sWrk = ewr_JoinArray($this->deoartamento->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->deoartamento->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field unidadeducativa
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildDropDownFilter($this->unidadeducativa, $sExtWrk, $this->unidadeducativa->SearchOperator);
		if (is_array($this->unidadeducativa->SelectionList))
			$sWrk = ewr_JoinArray($this->unidadeducativa->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->unidadeducativa->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field nombres
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildDropDownFilter($this->nombres, $sExtWrk, $this->nombres->SearchOperator);
		if (is_array($this->nombres->SelectionList))
			$sWrk = ewr_JoinArray($this->nombres->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->nombres->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field apellidopaterno
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildDropDownFilter($this->apellidopaterno, $sExtWrk, $this->apellidopaterno->SearchOperator);
		if (is_array($this->apellidopaterno->SelectionList))
			$sWrk = ewr_JoinArray($this->apellidopaterno->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->apellidopaterno->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field apellidomaterno
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildDropDownFilter($this->apellidomaterno, $sExtWrk, $this->apellidomaterno->SearchOperator);
		if (is_array($this->apellidomaterno->SelectionList))
			$sWrk = ewr_JoinArray($this->apellidomaterno->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->apellidomaterno->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field nrodiscapacidad
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->nrodiscapacidad, $sExtWrk);
		if (is_array($this->nrodiscapacidad->SelectionList))
			$sWrk = ewr_JoinArray($this->nrodiscapacidad->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->nrodiscapacidad->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field ci
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->ci, $sExtWrk);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->ci->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field fechanacimiento
		$sExtWrk = "";
		$sWrk = "";
		if (is_array($this->fechanacimiento->SelectionList))
			$sWrk = ewr_JoinArray($this->fechanacimiento->SelectionList, ", ", EWR_DATATYPE_DATE, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->fechanacimiento->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field sexo
		$sExtWrk = "";
		$sWrk = "";
		if (is_array($this->sexo->SelectionList))
			$sWrk = ewr_JoinArray($this->sexo->SelectionList, ", ", EWR_DATATYPE_NUMBER, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->sexo->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field celular
		$sExtWrk = "";
		$sWrk = "";
		if (is_array($this->celular->SelectionList))
			$sWrk = ewr_JoinArray($this->celular->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->celular->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field materias
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->materias, $sExtWrk);
		if (is_array($this->materias->SelectionList))
			$sWrk = ewr_JoinArray($this->materias->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->materias->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field discapacidad
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildDropDownFilter($this->discapacidad, $sExtWrk, $this->discapacidad->SearchOperator);
		if (is_array($this->discapacidad->SelectionList))
			$sWrk = ewr_JoinArray($this->discapacidad->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->discapacidad->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field tipodiscapacidad
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildDropDownFilter($this->tipodiscapacidad, $sExtWrk, $this->tipodiscapacidad->SearchOperator);
		if (is_array($this->tipodiscapacidad->SelectionList))
			$sWrk = ewr_JoinArray($this->tipodiscapacidad->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->tipodiscapacidad->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field nombreinstitucion
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildDropDownFilter($this->nombreinstitucion, $sExtWrk, $this->nombreinstitucion->SearchOperator);
		if (is_array($this->nombreinstitucion->SelectionList))
			$sWrk = ewr_JoinArray($this->nombreinstitucion->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->nombreinstitucion->FldCaption() . "</span>" . $sFilter . "</div>";
		$divstyle = "";
		$divdataclass = "";

		// Show Filters
		if ($sFilterList <> "" || $showDate) {
			$sMessage = "<div" . $divstyle . $divdataclass . "><div id=\"ewrFilterList\" class=\"alert alert-info\">";
			if ($showDate)
				$sMessage .= "<div id=\"ewrCurrentDate\">" . $ReportLanguage->Phrase("ReportGeneratedDate") . ewr_FormatDateTime(date("Y-m-d H:i:s"), 1) . "</div>";
			if ($sFilterList <> "")
				$sMessage .= "<div id=\"ewrCurrentFilters\">" . $ReportLanguage->Phrase("CurrentFilters") . "</div>" . $sFilterList;
			$sMessage .= "</div></div>";
			$this->Message_Showing($sMessage, "");
			echo $sMessage;
		}
	}

	// Get list of filters
	function GetFilterList() {

		// Initialize
		$sFilterList = "";

		// Field deoartamento
		$sWrk = "";
		$sWrk = ($this->deoartamento->DropDownValue <> EWR_INIT_VALUE) ? $this->deoartamento->DropDownValue : "";
		if (is_array($sWrk))
			$sWrk = implode("||", $sWrk);
		if ($sWrk <> "")
			$sWrk = "\"sv_deoartamento\":\"" . ewr_JsEncode2($sWrk) . "\"";
		if ($sWrk == "") {
			$sWrk = ($this->deoartamento->SelectionList <> EWR_INIT_VALUE) ? $this->deoartamento->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_deoartamento\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field unidadeducativa
		$sWrk = "";
		$sWrk = ($this->unidadeducativa->DropDownValue <> EWR_INIT_VALUE) ? $this->unidadeducativa->DropDownValue : "";
		if (is_array($sWrk))
			$sWrk = implode("||", $sWrk);
		if ($sWrk <> "")
			$sWrk = "\"sv_unidadeducativa\":\"" . ewr_JsEncode2($sWrk) . "\"";
		if ($sWrk == "") {
			$sWrk = ($this->unidadeducativa->SelectionList <> EWR_INIT_VALUE) ? $this->unidadeducativa->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_unidadeducativa\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field nombres
		$sWrk = "";
		$sWrk = ($this->nombres->DropDownValue <> EWR_INIT_VALUE) ? $this->nombres->DropDownValue : "";
		if (is_array($sWrk))
			$sWrk = implode("||", $sWrk);
		if ($sWrk <> "")
			$sWrk = "\"sv_nombres\":\"" . ewr_JsEncode2($sWrk) . "\"";
		if ($sWrk == "") {
			$sWrk = ($this->nombres->SelectionList <> EWR_INIT_VALUE) ? $this->nombres->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_nombres\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field apellidopaterno
		$sWrk = "";
		$sWrk = ($this->apellidopaterno->DropDownValue <> EWR_INIT_VALUE) ? $this->apellidopaterno->DropDownValue : "";
		if (is_array($sWrk))
			$sWrk = implode("||", $sWrk);
		if ($sWrk <> "")
			$sWrk = "\"sv_apellidopaterno\":\"" . ewr_JsEncode2($sWrk) . "\"";
		if ($sWrk == "") {
			$sWrk = ($this->apellidopaterno->SelectionList <> EWR_INIT_VALUE) ? $this->apellidopaterno->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_apellidopaterno\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field apellidomaterno
		$sWrk = "";
		$sWrk = ($this->apellidomaterno->DropDownValue <> EWR_INIT_VALUE) ? $this->apellidomaterno->DropDownValue : "";
		if (is_array($sWrk))
			$sWrk = implode("||", $sWrk);
		if ($sWrk <> "")
			$sWrk = "\"sv_apellidomaterno\":\"" . ewr_JsEncode2($sWrk) . "\"";
		if ($sWrk == "") {
			$sWrk = ($this->apellidomaterno->SelectionList <> EWR_INIT_VALUE) ? $this->apellidomaterno->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_apellidomaterno\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field nrodiscapacidad
		$sWrk = "";
		if ($this->nrodiscapacidad->SearchValue <> "" || $this->nrodiscapacidad->SearchValue2 <> "") {
			$sWrk = "\"sv_nrodiscapacidad\":\"" . ewr_JsEncode2($this->nrodiscapacidad->SearchValue) . "\"," .
				"\"so_nrodiscapacidad\":\"" . ewr_JsEncode2($this->nrodiscapacidad->SearchOperator) . "\"," .
				"\"sc_nrodiscapacidad\":\"" . ewr_JsEncode2($this->nrodiscapacidad->SearchCondition) . "\"," .
				"\"sv2_nrodiscapacidad\":\"" . ewr_JsEncode2($this->nrodiscapacidad->SearchValue2) . "\"," .
				"\"so2_nrodiscapacidad\":\"" . ewr_JsEncode2($this->nrodiscapacidad->SearchOperator2) . "\"";
		}
		if ($sWrk == "") {
			$sWrk = ($this->nrodiscapacidad->SelectionList <> EWR_INIT_VALUE) ? $this->nrodiscapacidad->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_nrodiscapacidad\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field ci
		$sWrk = "";
		if ($this->ci->SearchValue <> "" || $this->ci->SearchValue2 <> "") {
			$sWrk = "\"sv_ci\":\"" . ewr_JsEncode2($this->ci->SearchValue) . "\"," .
				"\"so_ci\":\"" . ewr_JsEncode2($this->ci->SearchOperator) . "\"," .
				"\"sc_ci\":\"" . ewr_JsEncode2($this->ci->SearchCondition) . "\"," .
				"\"sv2_ci\":\"" . ewr_JsEncode2($this->ci->SearchValue2) . "\"," .
				"\"so2_ci\":\"" . ewr_JsEncode2($this->ci->SearchOperator2) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field fechanacimiento
		$sWrk = "";
		if ($sWrk == "") {
			$sWrk = ($this->fechanacimiento->SelectionList <> EWR_INIT_VALUE) ? $this->fechanacimiento->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_fechanacimiento\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field sexo
		$sWrk = "";
		if ($sWrk == "") {
			$sWrk = ($this->sexo->SelectionList <> EWR_INIT_VALUE) ? $this->sexo->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_sexo\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field celular
		$sWrk = "";
		if ($sWrk == "") {
			$sWrk = ($this->celular->SelectionList <> EWR_INIT_VALUE) ? $this->celular->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_celular\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field materias
		$sWrk = "";
		if ($this->materias->SearchValue <> "" || $this->materias->SearchValue2 <> "") {
			$sWrk = "\"sv_materias\":\"" . ewr_JsEncode2($this->materias->SearchValue) . "\"," .
				"\"so_materias\":\"" . ewr_JsEncode2($this->materias->SearchOperator) . "\"," .
				"\"sc_materias\":\"" . ewr_JsEncode2($this->materias->SearchCondition) . "\"," .
				"\"sv2_materias\":\"" . ewr_JsEncode2($this->materias->SearchValue2) . "\"," .
				"\"so2_materias\":\"" . ewr_JsEncode2($this->materias->SearchOperator2) . "\"";
		}
		if ($sWrk == "") {
			$sWrk = ($this->materias->SelectionList <> EWR_INIT_VALUE) ? $this->materias->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_materias\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field discapacidad
		$sWrk = "";
		$sWrk = ($this->discapacidad->DropDownValue <> EWR_INIT_VALUE) ? $this->discapacidad->DropDownValue : "";
		if (is_array($sWrk))
			$sWrk = implode("||", $sWrk);
		if ($sWrk <> "")
			$sWrk = "\"sv_discapacidad\":\"" . ewr_JsEncode2($sWrk) . "\"";
		if ($sWrk == "") {
			$sWrk = ($this->discapacidad->SelectionList <> EWR_INIT_VALUE) ? $this->discapacidad->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_discapacidad\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field tipodiscapacidad
		$sWrk = "";
		$sWrk = ($this->tipodiscapacidad->DropDownValue <> EWR_INIT_VALUE) ? $this->tipodiscapacidad->DropDownValue : "";
		if (is_array($sWrk))
			$sWrk = implode("||", $sWrk);
		if ($sWrk <> "")
			$sWrk = "\"sv_tipodiscapacidad\":\"" . ewr_JsEncode2($sWrk) . "\"";
		if ($sWrk == "") {
			$sWrk = ($this->tipodiscapacidad->SelectionList <> EWR_INIT_VALUE) ? $this->tipodiscapacidad->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_tipodiscapacidad\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field nombreinstitucion
		$sWrk = "";
		$sWrk = ($this->nombreinstitucion->DropDownValue <> EWR_INIT_VALUE) ? $this->nombreinstitucion->DropDownValue : "";
		if (is_array($sWrk))
			$sWrk = implode("||", $sWrk);
		if ($sWrk <> "")
			$sWrk = "\"sv_nombreinstitucion\":\"" . ewr_JsEncode2($sWrk) . "\"";
		if ($sWrk == "") {
			$sWrk = ($this->nombreinstitucion->SelectionList <> EWR_INIT_VALUE) ? $this->nombreinstitucion->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_nombreinstitucion\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Return filter list in json
		if ($sFilterList <> "")
			return "{" . $sFilterList . "}";
		else
			return "null";
	}

	// Restore list of filters
	function RestoreFilterList() {

		// Return if not reset filter
		if (@$_POST["cmd"] <> "resetfilter")
			return FALSE;
		$filter = json_decode(@$_POST["filter"], TRUE);
		return $this->SetupFilterList($filter);
	}

	// Setup list of filters
	function SetupFilterList($filter) {
		if (!is_array($filter))
			return FALSE;

		// Field deoartamento
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_deoartamento", $filter)) {
			$sWrk = $filter["sv_deoartamento"];
			if (strpos($sWrk, "||") !== FALSE)
				$sWrk = explode("||", $sWrk);
			$this->SetSessionDropDownValue($sWrk, @$filter["so_deoartamento"], "deoartamento");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_deoartamento", $filter)) {
			$sWrk = $filter["sel_deoartamento"];
			$sWrk = explode("||", $sWrk);
			$this->deoartamento->SelectionList = $sWrk;
			$_SESSION["sel_viewdocente_deoartamento"] = $sWrk;
			$this->SetSessionDropDownValue(EWR_INIT_VALUE, "", "deoartamento"); // Clear drop down
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionDropDownValue(EWR_INIT_VALUE, "", "deoartamento");
			$this->deoartamento->SelectionList = "";
			$_SESSION["sel_viewdocente_deoartamento"] = "";
		}

		// Field unidadeducativa
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_unidadeducativa", $filter)) {
			$sWrk = $filter["sv_unidadeducativa"];
			if (strpos($sWrk, "||") !== FALSE)
				$sWrk = explode("||", $sWrk);
			$this->SetSessionDropDownValue($sWrk, @$filter["so_unidadeducativa"], "unidadeducativa");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_unidadeducativa", $filter)) {
			$sWrk = $filter["sel_unidadeducativa"];
			$sWrk = explode("||", $sWrk);
			$this->unidadeducativa->SelectionList = $sWrk;
			$_SESSION["sel_viewdocente_unidadeducativa"] = $sWrk;
			$this->SetSessionDropDownValue(EWR_INIT_VALUE, "", "unidadeducativa"); // Clear drop down
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionDropDownValue(EWR_INIT_VALUE, "", "unidadeducativa");
			$this->unidadeducativa->SelectionList = "";
			$_SESSION["sel_viewdocente_unidadeducativa"] = "";
		}

		// Field nombres
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_nombres", $filter)) {
			$sWrk = $filter["sv_nombres"];
			if (strpos($sWrk, "||") !== FALSE)
				$sWrk = explode("||", $sWrk);
			$this->SetSessionDropDownValue($sWrk, @$filter["so_nombres"], "nombres");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_nombres", $filter)) {
			$sWrk = $filter["sel_nombres"];
			$sWrk = explode("||", $sWrk);
			$this->nombres->SelectionList = $sWrk;
			$_SESSION["sel_viewdocente_nombres"] = $sWrk;
			$this->SetSessionDropDownValue(EWR_INIT_VALUE, "", "nombres"); // Clear drop down
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionDropDownValue(EWR_INIT_VALUE, "", "nombres");
			$this->nombres->SelectionList = "";
			$_SESSION["sel_viewdocente_nombres"] = "";
		}

		// Field apellidopaterno
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_apellidopaterno", $filter)) {
			$sWrk = $filter["sv_apellidopaterno"];
			if (strpos($sWrk, "||") !== FALSE)
				$sWrk = explode("||", $sWrk);
			$this->SetSessionDropDownValue($sWrk, @$filter["so_apellidopaterno"], "apellidopaterno");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_apellidopaterno", $filter)) {
			$sWrk = $filter["sel_apellidopaterno"];
			$sWrk = explode("||", $sWrk);
			$this->apellidopaterno->SelectionList = $sWrk;
			$_SESSION["sel_viewdocente_apellidopaterno"] = $sWrk;
			$this->SetSessionDropDownValue(EWR_INIT_VALUE, "", "apellidopaterno"); // Clear drop down
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionDropDownValue(EWR_INIT_VALUE, "", "apellidopaterno");
			$this->apellidopaterno->SelectionList = "";
			$_SESSION["sel_viewdocente_apellidopaterno"] = "";
		}

		// Field apellidomaterno
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_apellidomaterno", $filter)) {
			$sWrk = $filter["sv_apellidomaterno"];
			if (strpos($sWrk, "||") !== FALSE)
				$sWrk = explode("||", $sWrk);
			$this->SetSessionDropDownValue($sWrk, @$filter["so_apellidomaterno"], "apellidomaterno");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_apellidomaterno", $filter)) {
			$sWrk = $filter["sel_apellidomaterno"];
			$sWrk = explode("||", $sWrk);
			$this->apellidomaterno->SelectionList = $sWrk;
			$_SESSION["sel_viewdocente_apellidomaterno"] = $sWrk;
			$this->SetSessionDropDownValue(EWR_INIT_VALUE, "", "apellidomaterno"); // Clear drop down
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionDropDownValue(EWR_INIT_VALUE, "", "apellidomaterno");
			$this->apellidomaterno->SelectionList = "";
			$_SESSION["sel_viewdocente_apellidomaterno"] = "";
		}

		// Field nrodiscapacidad
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_nrodiscapacidad", $filter) || array_key_exists("so_nrodiscapacidad", $filter) ||
			array_key_exists("sc_nrodiscapacidad", $filter) ||
			array_key_exists("sv2_nrodiscapacidad", $filter) || array_key_exists("so2_nrodiscapacidad", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_nrodiscapacidad"], @$filter["so_nrodiscapacidad"], @$filter["sc_nrodiscapacidad"], @$filter["sv2_nrodiscapacidad"], @$filter["so2_nrodiscapacidad"], "nrodiscapacidad");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_nrodiscapacidad", $filter)) {
			$sWrk = $filter["sel_nrodiscapacidad"];
			$sWrk = explode("||", $sWrk);
			$this->nrodiscapacidad->SelectionList = $sWrk;
			$_SESSION["sel_viewdocente_nrodiscapacidad"] = $sWrk;
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "nrodiscapacidad"); // Clear extended filter
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "nrodiscapacidad");
			$this->nrodiscapacidad->SelectionList = "";
			$_SESSION["sel_viewdocente_nrodiscapacidad"] = "";
		}

		// Field ci
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_ci", $filter) || array_key_exists("so_ci", $filter) ||
			array_key_exists("sc_ci", $filter) ||
			array_key_exists("sv2_ci", $filter) || array_key_exists("so2_ci", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_ci"], @$filter["so_ci"], @$filter["sc_ci"], @$filter["sv2_ci"], @$filter["so2_ci"], "ci");
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "ci");
		}

		// Field fechanacimiento
		$bRestoreFilter = FALSE;
		if (array_key_exists("sel_fechanacimiento", $filter)) {
			$sWrk = $filter["sel_fechanacimiento"];
			$sWrk = explode("||", $sWrk);
			$this->fechanacimiento->SelectionList = $sWrk;
			$_SESSION["sel_viewdocente_fechanacimiento"] = $sWrk;
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
		}

		// Field sexo
		$bRestoreFilter = FALSE;
		if (array_key_exists("sel_sexo", $filter)) {
			$sWrk = $filter["sel_sexo"];
			$sWrk = explode("||", $sWrk);
			$this->sexo->SelectionList = $sWrk;
			$_SESSION["sel_viewdocente_sexo"] = $sWrk;
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
		}

		// Field celular
		$bRestoreFilter = FALSE;
		if (array_key_exists("sel_celular", $filter)) {
			$sWrk = $filter["sel_celular"];
			$sWrk = explode("||", $sWrk);
			$this->celular->SelectionList = $sWrk;
			$_SESSION["sel_viewdocente_celular"] = $sWrk;
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
		}

		// Field materias
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_materias", $filter) || array_key_exists("so_materias", $filter) ||
			array_key_exists("sc_materias", $filter) ||
			array_key_exists("sv2_materias", $filter) || array_key_exists("so2_materias", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_materias"], @$filter["so_materias"], @$filter["sc_materias"], @$filter["sv2_materias"], @$filter["so2_materias"], "materias");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_materias", $filter)) {
			$sWrk = $filter["sel_materias"];
			$sWrk = explode("||", $sWrk);
			$this->materias->SelectionList = $sWrk;
			$_SESSION["sel_viewdocente_materias"] = $sWrk;
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "materias"); // Clear extended filter
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "materias");
			$this->materias->SelectionList = "";
			$_SESSION["sel_viewdocente_materias"] = "";
		}

		// Field discapacidad
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_discapacidad", $filter)) {
			$sWrk = $filter["sv_discapacidad"];
			if (strpos($sWrk, "||") !== FALSE)
				$sWrk = explode("||", $sWrk);
			$this->SetSessionDropDownValue($sWrk, @$filter["so_discapacidad"], "discapacidad");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_discapacidad", $filter)) {
			$sWrk = $filter["sel_discapacidad"];
			$sWrk = explode("||", $sWrk);
			$this->discapacidad->SelectionList = $sWrk;
			$_SESSION["sel_viewdocente_discapacidad"] = $sWrk;
			$this->SetSessionDropDownValue(EWR_INIT_VALUE, "", "discapacidad"); // Clear drop down
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionDropDownValue(EWR_INIT_VALUE, "", "discapacidad");
			$this->discapacidad->SelectionList = "";
			$_SESSION["sel_viewdocente_discapacidad"] = "";
		}

		// Field tipodiscapacidad
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_tipodiscapacidad", $filter)) {
			$sWrk = $filter["sv_tipodiscapacidad"];
			if (strpos($sWrk, "||") !== FALSE)
				$sWrk = explode("||", $sWrk);
			$this->SetSessionDropDownValue($sWrk, @$filter["so_tipodiscapacidad"], "tipodiscapacidad");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_tipodiscapacidad", $filter)) {
			$sWrk = $filter["sel_tipodiscapacidad"];
			$sWrk = explode("||", $sWrk);
			$this->tipodiscapacidad->SelectionList = $sWrk;
			$_SESSION["sel_viewdocente_tipodiscapacidad"] = $sWrk;
			$this->SetSessionDropDownValue(EWR_INIT_VALUE, "", "tipodiscapacidad"); // Clear drop down
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionDropDownValue(EWR_INIT_VALUE, "", "tipodiscapacidad");
			$this->tipodiscapacidad->SelectionList = "";
			$_SESSION["sel_viewdocente_tipodiscapacidad"] = "";
		}

		// Field nombreinstitucion
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_nombreinstitucion", $filter)) {
			$sWrk = $filter["sv_nombreinstitucion"];
			if (strpos($sWrk, "||") !== FALSE)
				$sWrk = explode("||", $sWrk);
			$this->SetSessionDropDownValue($sWrk, @$filter["so_nombreinstitucion"], "nombreinstitucion");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_nombreinstitucion", $filter)) {
			$sWrk = $filter["sel_nombreinstitucion"];
			$sWrk = explode("||", $sWrk);
			$this->nombreinstitucion->SelectionList = $sWrk;
			$_SESSION["sel_viewdocente_nombreinstitucion"] = $sWrk;
			$this->SetSessionDropDownValue(EWR_INIT_VALUE, "", "nombreinstitucion"); // Clear drop down
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionDropDownValue(EWR_INIT_VALUE, "", "nombreinstitucion");
			$this->nombreinstitucion->SelectionList = "";
			$_SESSION["sel_viewdocente_nombreinstitucion"] = "";
		}
		return TRUE;
	}

	// Return popup filter
	function GetPopupFilter() {
		$sWrk = "";
		if ($this->DrillDown)
			return "";
		if (!$this->DropDownFilterExist($this->deoartamento, $this->deoartamento->SearchOperator)) {
			if (is_array($this->deoartamento->SelectionList)) {
				$sFilter = ewr_FilterSql($this->deoartamento, "`deoartamento`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->deoartamento, $sFilter, "popup");
				$this->deoartamento->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
		if (!$this->DropDownFilterExist($this->unidadeducativa, $this->unidadeducativa->SearchOperator)) {
			if (is_array($this->unidadeducativa->SelectionList)) {
				$sFilter = ewr_FilterSql($this->unidadeducativa, "`unidadeducativa`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->unidadeducativa, $sFilter, "popup");
				$this->unidadeducativa->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
		if (!$this->DropDownFilterExist($this->nombres, $this->nombres->SearchOperator)) {
			if (is_array($this->nombres->SelectionList)) {
				$sFilter = ewr_FilterSql($this->nombres, "`nombres`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->nombres, $sFilter, "popup");
				$this->nombres->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
		if (!$this->DropDownFilterExist($this->apellidopaterno, $this->apellidopaterno->SearchOperator)) {
			if (is_array($this->apellidopaterno->SelectionList)) {
				$sFilter = ewr_FilterSql($this->apellidopaterno, "`apellidopaterno`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->apellidopaterno, $sFilter, "popup");
				$this->apellidopaterno->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
		if (!$this->DropDownFilterExist($this->apellidomaterno, $this->apellidomaterno->SearchOperator)) {
			if (is_array($this->apellidomaterno->SelectionList)) {
				$sFilter = ewr_FilterSql($this->apellidomaterno, "`apellidomaterno`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->apellidomaterno, $sFilter, "popup");
				$this->apellidomaterno->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
		if (!$this->ExtendedFilterExist($this->nrodiscapacidad)) {
			if (is_array($this->nrodiscapacidad->SelectionList)) {
				$sFilter = ewr_FilterSql($this->nrodiscapacidad, "`nrodiscapacidad`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->nrodiscapacidad, $sFilter, "popup");
				$this->nrodiscapacidad->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
			if (is_array($this->fechanacimiento->SelectionList)) {
				$sFilter = ewr_FilterSql($this->fechanacimiento, "`fechanacimiento`", EWR_DATATYPE_DATE, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->fechanacimiento, $sFilter, "popup");
				$this->fechanacimiento->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
			if (is_array($this->sexo->SelectionList)) {
				$sFilter = ewr_FilterSql($this->sexo, "`sexo`", EWR_DATATYPE_NUMBER, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->sexo, $sFilter, "popup");
				$this->sexo->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
			if (is_array($this->celular->SelectionList)) {
				$sFilter = ewr_FilterSql($this->celular, "`celular`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->celular, $sFilter, "popup");
				$this->celular->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		if (!$this->ExtendedFilterExist($this->materias)) {
			if (is_array($this->materias->SelectionList)) {
				$sFilter = ewr_FilterSql($this->materias, "`materias`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->materias, $sFilter, "popup");
				$this->materias->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
		if (!$this->DropDownFilterExist($this->discapacidad, $this->discapacidad->SearchOperator)) {
			if (is_array($this->discapacidad->SelectionList)) {
				$sFilter = ewr_FilterSql($this->discapacidad, "`discapacidad`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->discapacidad, $sFilter, "popup");
				$this->discapacidad->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
		if (!$this->DropDownFilterExist($this->tipodiscapacidad, $this->tipodiscapacidad->SearchOperator)) {
			if (is_array($this->tipodiscapacidad->SelectionList)) {
				$sFilter = ewr_FilterSql($this->tipodiscapacidad, "`tipodiscapacidad`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->tipodiscapacidad, $sFilter, "popup");
				$this->tipodiscapacidad->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
		if (!$this->DropDownFilterExist($this->nombreinstitucion, $this->nombreinstitucion->SearchOperator)) {
			if (is_array($this->nombreinstitucion->SelectionList)) {
				$sFilter = ewr_FilterSql($this->nombreinstitucion, "`nombreinstitucion`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->nombreinstitucion, $sFilter, "popup");
				$this->nombreinstitucion->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
		return $sWrk;
	}

	// Return drill down filter
	function GetDrillDownFilter() {
		global $ReportLanguage;
		$sFilterList = "";
		$filter = "";
		$post = $_POST;
		$opt = @$post["d"];
		if ($opt == "1" || $opt == "2") {
			$mastertable = @$post["s"]; // Get source table
			$sql = @$post["deoartamento"];

			// Fusioncharts do not support "-" in drill down link. The encrypted data uses "$" instead of "-". Change back to "-" for decrypt.
			// https://www.fusioncharts.com/dev/advanced-chart-configurations/drill-down/using-javascript-functions-as-links.html
			// - Special characters like (, ), -, % and , cannot be passed as a parameter while function call.

			$sql = str_replace("$", "-", $sql);
			$sql = ewr_Decrypt($sql);
			$sql = str_replace("@deoartamento", "`deoartamento`", $sql);
			if ($sql <> "") {
				if ($filter <> "") $filter .= " AND ";
				$filter .= $sql;
				if ($sql <> "1=1")
					$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->deoartamento->FldCaption() . "</span><span class=\"ewFilterValue\">$sql</span></div>";
			}
			$sql = @$post["unidadeducativa"];

			// Fusioncharts do not support "-" in drill down link. The encrypted data uses "$" instead of "-". Change back to "-" for decrypt.
			// https://www.fusioncharts.com/dev/advanced-chart-configurations/drill-down/using-javascript-functions-as-links.html
			// - Special characters like (, ), -, % and , cannot be passed as a parameter while function call.

			$sql = str_replace("$", "-", $sql);
			$sql = ewr_Decrypt($sql);
			$sql = str_replace("@unidadeducativa", "`unidadeducativa`", $sql);
			if ($sql <> "") {
				if ($filter <> "") $filter .= " AND ";
				$filter .= $sql;
				if ($sql <> "1=1")
					$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->unidadeducativa->FldCaption() . "</span><span class=\"ewFilterValue\">$sql</span></div>";
			}
			$sql = @$post["nombres"];

			// Fusioncharts do not support "-" in drill down link. The encrypted data uses "$" instead of "-". Change back to "-" for decrypt.
			// https://www.fusioncharts.com/dev/advanced-chart-configurations/drill-down/using-javascript-functions-as-links.html
			// - Special characters like (, ), -, % and , cannot be passed as a parameter while function call.

			$sql = str_replace("$", "-", $sql);
			$sql = ewr_Decrypt($sql);
			$sql = str_replace("@nombres", "`nombres`", $sql);
			if ($sql <> "") {
				if ($filter <> "") $filter .= " AND ";
				$filter .= $sql;
				if ($sql <> "1=1")
					$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->nombres->FldCaption() . "</span><span class=\"ewFilterValue\">$sql</span></div>";
			}
			$sql = @$post["apellidopaterno"];

			// Fusioncharts do not support "-" in drill down link. The encrypted data uses "$" instead of "-". Change back to "-" for decrypt.
			// https://www.fusioncharts.com/dev/advanced-chart-configurations/drill-down/using-javascript-functions-as-links.html
			// - Special characters like (, ), -, % and , cannot be passed as a parameter while function call.

			$sql = str_replace("$", "-", $sql);
			$sql = ewr_Decrypt($sql);
			$sql = str_replace("@apellidopaterno", "`apellidopaterno`", $sql);
			if ($sql <> "") {
				if ($filter <> "") $filter .= " AND ";
				$filter .= $sql;
				if ($sql <> "1=1")
					$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->apellidopaterno->FldCaption() . "</span><span class=\"ewFilterValue\">$sql</span></div>";
			}
			$sql = @$post["apellidomaterno"];

			// Fusioncharts do not support "-" in drill down link. The encrypted data uses "$" instead of "-". Change back to "-" for decrypt.
			// https://www.fusioncharts.com/dev/advanced-chart-configurations/drill-down/using-javascript-functions-as-links.html
			// - Special characters like (, ), -, % and , cannot be passed as a parameter while function call.

			$sql = str_replace("$", "-", $sql);
			$sql = ewr_Decrypt($sql);
			$sql = str_replace("@apellidomaterno", "`apellidomaterno`", $sql);
			if ($sql <> "") {
				if ($filter <> "") $filter .= " AND ";
				$filter .= $sql;
				if ($sql <> "1=1")
					$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->apellidomaterno->FldCaption() . "</span><span class=\"ewFilterValue\">$sql</span></div>";
			}
			$sql = @$post["nrodiscapacidad"];

			// Fusioncharts do not support "-" in drill down link. The encrypted data uses "$" instead of "-". Change back to "-" for decrypt.
			// https://www.fusioncharts.com/dev/advanced-chart-configurations/drill-down/using-javascript-functions-as-links.html
			// - Special characters like (, ), -, % and , cannot be passed as a parameter while function call.

			$sql = str_replace("$", "-", $sql);
			$sql = ewr_Decrypt($sql);
			$sql = str_replace("@nrodiscapacidad", "`nrodiscapacidad`", $sql);
			if ($sql <> "") {
				if ($filter <> "") $filter .= " AND ";
				$filter .= $sql;
				if ($sql <> "1=1")
					$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->nrodiscapacidad->FldCaption() . "</span><span class=\"ewFilterValue\">$sql</span></div>";
			}
			$sql = @$post["ci"];

			// Fusioncharts do not support "-" in drill down link. The encrypted data uses "$" instead of "-". Change back to "-" for decrypt.
			// https://www.fusioncharts.com/dev/advanced-chart-configurations/drill-down/using-javascript-functions-as-links.html
			// - Special characters like (, ), -, % and , cannot be passed as a parameter while function call.

			$sql = str_replace("$", "-", $sql);
			$sql = ewr_Decrypt($sql);
			$sql = str_replace("@ci", "`ci`", $sql);
			if ($sql <> "") {
				if ($filter <> "") $filter .= " AND ";
				$filter .= $sql;
				if ($sql <> "1=1")
					$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->ci->FldCaption() . "</span><span class=\"ewFilterValue\">$sql</span></div>";
			}
			$sql = @$post["materias"];

			// Fusioncharts do not support "-" in drill down link. The encrypted data uses "$" instead of "-". Change back to "-" for decrypt.
			// https://www.fusioncharts.com/dev/advanced-chart-configurations/drill-down/using-javascript-functions-as-links.html
			// - Special characters like (, ), -, % and , cannot be passed as a parameter while function call.

			$sql = str_replace("$", "-", $sql);
			$sql = ewr_Decrypt($sql);
			$sql = str_replace("@materias", "`materias`", $sql);
			if ($sql <> "") {
				if ($filter <> "") $filter .= " AND ";
				$filter .= $sql;
				if ($sql <> "1=1")
					$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->materias->FldCaption() . "</span><span class=\"ewFilterValue\">$sql</span></div>";
			}
			$sql = @$post["nombreinstitucion"];

			// Fusioncharts do not support "-" in drill down link. The encrypted data uses "$" instead of "-". Change back to "-" for decrypt.
			// https://www.fusioncharts.com/dev/advanced-chart-configurations/drill-down/using-javascript-functions-as-links.html
			// - Special characters like (, ), -, % and , cannot be passed as a parameter while function call.

			$sql = str_replace("$", "-", $sql);
			$sql = ewr_Decrypt($sql);
			$sql = str_replace("@nombreinstitucion", "`nombreinstitucion`", $sql);
			if ($sql <> "") {
				if ($filter <> "") $filter .= " AND ";
				$filter .= $sql;
				if ($sql <> "1=1")
					$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->nombreinstitucion->FldCaption() . "</span><span class=\"ewFilterValue\">$sql</span></div>";
			}

			// Save to session
			$_SESSION[EWR_PROJECT_NAME . "_" . $this->TableVar . "_" . EWR_TABLE_MASTER_TABLE] = $mastertable;
			$_SESSION['do_viewdocente'] = $opt;
			$_SESSION['df_viewdocente'] = $filter;
			$_SESSION['dl_viewdocente'] = $sFilterList;
		} elseif (@$_GET["cmd"] == "resetdrilldown") { // Clear drill down
			$_SESSION[EWR_PROJECT_NAME . "_" . $this->TableVar . "_" . EWR_TABLE_MASTER_TABLE] = "";
			$_SESSION['do_viewdocente'] = "";
			$_SESSION['df_viewdocente'] = "";
			$_SESSION['dl_viewdocente'] = "";
		} else { // Restore from Session
			$opt = @$_SESSION['do_viewdocente'];
			$filter = @$_SESSION['df_viewdocente'];
			$sFilterList = @$_SESSION['dl_viewdocente'];
		}
		if ($opt == "1" || $opt == "2")
			$this->DrillDown = TRUE;
		if ($opt == "1") {
			$this->DrillDownInPanel = TRUE;
			$GLOBALS["gbSkipHeaderFooter"] = TRUE;
		}
		if ($filter <> "") {
			if ($sFilterList == "")
				$sFilterList = "<div><span class=\"ewFilterValue\">" . $ReportLanguage->Phrase("DrillDownAllRecords") . "</span></div>";
			$this->DrillDownList = "<div id=\"ewrDrillDownFilters\">" . $ReportLanguage->Phrase("DrillDownFilters") . "</div>" . $sFilterList;
		}
		return $filter;
	}

	// Show drill down filters
	function ShowDrillDownList() {
		$divstyle = "";
		$divdataclass = "";
		if ($this->DrillDownList <> "") {
			$sMessage = "<div id=\"ewrDrillDownList\"" . $divstyle . "><div class=\"alert alert-info\"" . $divdataclass . ">" . $this->DrillDownList . "</div></div>";
			$this->Message_Showing($sMessage, "");
			echo $sMessage;
		}
	}

	// Get sort parameters based on sort links clicked
	function GetSort($options = array()) {
		if ($this->DrillDown)
			return "";
		$bResetSort = @$options["resetsort"] == "1" || @$_GET["cmd"] == "resetsort";
		$orderBy = (@$options["order"] <> "") ? @$options["order"] : @$_GET["order"];
		$orderType = (@$options["ordertype"] <> "") ? @$options["ordertype"] : @$_GET["ordertype"];

		// Check for a resetsort command
		if ($bResetSort) {
			$this->setOrderBy("");
			$this->setStartGroup(1);
			$this->deoartamento->setSort("");
			$this->unidadeducativa->setSort("");
			$this->nombres->setSort("");
			$this->apellidopaterno->setSort("");
			$this->apellidomaterno->setSort("");
			$this->nrodiscapacidad->setSort("");
			$this->ci->setSort("");
			$this->fechanacimiento->setSort("");
			$this->sexo->setSort("");
			$this->celular->setSort("");
			$this->materias->setSort("");
			$this->discapacidad->setSort("");
			$this->tipodiscapacidad->setSort("");
			$this->nombreinstitucion->setSort("");

		// Check for an Order parameter
		} elseif ($orderBy <> "") {
			$this->CurrentOrder = $orderBy;
			$this->CurrentOrderType = $orderType;
			$this->UpdateSort($this->deoartamento); // deoartamento
			$this->UpdateSort($this->unidadeducativa); // unidadeducativa
			$this->UpdateSort($this->nombres); // nombres
			$this->UpdateSort($this->apellidopaterno); // apellidopaterno
			$this->UpdateSort($this->apellidomaterno); // apellidomaterno
			$this->UpdateSort($this->nrodiscapacidad); // nrodiscapacidad
			$this->UpdateSort($this->ci); // ci
			$this->UpdateSort($this->fechanacimiento); // fechanacimiento
			$this->UpdateSort($this->sexo); // sexo
			$this->UpdateSort($this->celular); // celular
			$this->UpdateSort($this->materias); // materias
			$this->UpdateSort($this->discapacidad); // discapacidad
			$this->UpdateSort($this->tipodiscapacidad); // tipodiscapacidad
			$this->UpdateSort($this->nombreinstitucion); // nombreinstitucion
			$sSortSql = $this->SortSql();
			$this->setOrderBy($sSortSql);
			$this->setStartGroup(1);
		}
		return $this->getOrderBy();
	}

	// Export to HTML
	function ExportHtml($html, $options = array()) {

		//global $gsExportFile;
		//header('Content-Type: text/html' . (EWR_CHARSET <> '' ? ';charset=' . EWR_CHARSET : ''));
		//header('Content-Disposition: attachment; filename=' . $gsExportFile . '.html');

		$folder = @$this->GenOptions["folder"];
		$fileName = @$this->GenOptions["filename"];
		$responseType = @$options["responsetype"];
		$saveToFile = "";

		// Save generate file for print
		if ($folder <> "" && $fileName <> "" && ($responseType == "json" || $responseType == "file" && EWR_REPORT_SAVE_OUTPUT_ON_SERVER)) {
			$baseTag = "<base href=\"" . ewr_BaseUrl() . "\">";
			$html = preg_replace('/<head>/', '<head>' . $baseTag, $html);
			ewr_SaveFile($folder, $fileName, $html);
			$saveToFile = ewr_UploadPathEx(FALSE, $folder) . $fileName;
		}
		if ($saveToFile == "" || $responseType == "file")
			echo $html;
		return $saveToFile;
	}

	// Export to WORD
	function ExportWord($html, $options = array()) {
		global $gsExportFile;
		$folder = @$options["folder"];
		$fileName = @$options["filename"];
		$responseType = @$options["responsetype"];
		$saveToFile = "";
		if ($folder <> "" && $fileName <> "" && ($responseType == "json" || $responseType == "file" && EWR_REPORT_SAVE_OUTPUT_ON_SERVER)) {
		 	ewr_SaveFile(ewr_PathCombine(ewr_AppRoot(), $folder, TRUE), $fileName, $html);
			$saveToFile = ewr_UploadPathEx(FALSE, $folder) . $fileName;
		}
		if ($saveToFile == "" || $responseType == "file") {
			header('Set-Cookie: fileDownload=true; path=/');
			header('Content-Type: application/vnd.ms-word' . (EWR_CHARSET <> '' ? ';charset=' . EWR_CHARSET : ''));
			header('Content-Disposition: attachment; filename=' . $gsExportFile . '.doc');
			echo $html;
		}
		return $saveToFile;
	}

	// Export to EXCEL
	function ExportExcel($html, $options = array()) {
		global $gsExportFile;
		$folder = @$options["folder"];
		$fileName = @$options["filename"];
		$responseType = @$options["responsetype"];
		$saveToFile = "";
		if ($folder <> "" && $fileName <> "" && ($responseType == "json" || $responseType == "file" && EWR_REPORT_SAVE_OUTPUT_ON_SERVER)) {
		 	ewr_SaveFile(ewr_PathCombine(ewr_AppRoot(), $folder, TRUE), $fileName, $html);
			$saveToFile = ewr_UploadPathEx(FALSE, $folder) . $fileName;
		}
		if ($saveToFile == "" || $responseType == "file") {
			header('Set-Cookie: fileDownload=true; path=/');
			header('Content-Type: application/vnd.ms-excel' . (EWR_CHARSET <> '' ? ';charset=' . EWR_CHARSET : ''));
			header('Content-Disposition: attachment; filename=' . $gsExportFile . '.xls');
			echo $html;
		}
		return $saveToFile;
	}

	// Export PDF
	function ExportPdf($html, $options = array()) {
		global $gsExportFile;
		@ini_set("memory_limit", EWR_PDF_MEMORY_LIMIT);
		set_time_limit(EWR_PDF_TIME_LIMIT);
		if (EWR_DEBUG_ENABLED) // Add debug message
			$html = str_replace("</body>", ewr_DebugMsg() . "</body>", $html);
		$dompdf = new \Dompdf\Dompdf(array("pdf_backend" => "Cpdf"));
		$doc = new DOMDocument();
		@$doc->loadHTML('<?xml encoding="uft-8">' . ewr_ConvertToUtf8($html)); // Convert to utf-8
		$spans = $doc->getElementsByTagName("span");
		foreach ($spans as $span) {
			if ($span->getAttribute("class") == "ewFilterCaption")
				$span->parentNode->insertBefore($doc->createElement("span", ":&nbsp;"), $span->nextSibling);
		}
		$images = $doc->getElementsByTagName("img");
		$pageSize = "a4";
		$pageOrientation = "portrait";
		foreach ($images as $image) {
			$imagefn = $image->getAttribute("src");
			if (file_exists($imagefn)) {
				$imagefn = realpath($imagefn);
				$size = getimagesize($imagefn); // Get image size
				if ($size[0] <> 0) {
					if (ewr_SameText($pageSize, "letter")) { // Letter paper (8.5 in. by 11 in.)
						$w = ewr_SameText($pageOrientation, "portrait") ? 216 : 279;
					} elseif (ewr_SameText($pageSize, "legal")) { // Legal paper (8.5 in. by 14 in.)
						$w = ewr_SameText($pageOrientation, "portrait") ? 216 : 356;
					} else {
						$w = ewr_SameText($pageOrientation, "portrait") ? 210 : 297; // A4 paper (210 mm by 297 mm)
					}
					$w = min($size[0], ($w - 20 * 2) / 25.4 * 72); // Resize image, adjust the multiplying factor if necessary
					$h = $w / $size[0] * $size[1];
					$image->setAttribute("width", $w);
					$image->setAttribute("height", $h);
				}
			}
		}
		$html = $doc->saveHTML();
		$html = ewr_ConvertFromUtf8($html);
		$dompdf->load_html($html);
		$dompdf->set_paper($pageSize, $pageOrientation);
		$dompdf->render();
		$folder = @$options["folder"];
		$fileName = @$options["filename"];
		$responseType = @$options["responsetype"];
		$saveToFile = "";
		if ($folder <> "" && $fileName <> "" && ($responseType == "json" || $responseType == "file" && EWR_REPORT_SAVE_OUTPUT_ON_SERVER)) {
			ewr_SaveFile(ewr_PathCombine(ewr_AppRoot(), $folder, TRUE), $fileName, $dompdf->output());
			$saveToFile = ewr_UploadPathEx(FALSE, $folder) . $fileName;
		}
		if ($saveToFile == "" || $responseType == "file") {
			header('Set-Cookie: fileDownload=true; path=/');
			$sExportFile = strtolower(substr($gsExportFile, -4)) == ".pdf" ? $gsExportFile : $gsExportFile . ".pdf";
			$dompdf->stream($sExportFile, array("Attachment" => 1)); // 0 to open in browser, 1 to download
		}
		ewr_DeleteTmpImages($html);
		return $saveToFile;
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php

// Create page object
if (!isset($viewdocente_rpt)) $viewdocente_rpt = new crviewdocente_rpt();
if (isset($Page)) $OldPage = $Page;
$Page = &$viewdocente_rpt;

// Page init
$Page->Page_Init();

// Page main
$Page->Page_Main();
if (!$grDashboardReport)
	ewr_Header(FALSE);

// Global Page Rendering event (in ewrusrfn*.php)
Page_Rendering();

// Page Rendering event
$Page->Page_Render();
?>
<?php if (!$grDashboardReport) { ?>
<?php include_once "header.php" ?>
<?php include_once "phprptinc/header.php" ?>
<?php } ?>
<?php if ($Page->Export == "" || $Page->Export == "print" || $Page->Export == "email" && @$gsEmailContentType == "url") { ?>
<script type="text/javascript">

// Create page object
var viewdocente_rpt = new ewr_Page("viewdocente_rpt");

// Page properties
viewdocente_rpt.PageID = "rpt"; // Page ID
var EWR_PAGE_ID = viewdocente_rpt.PageID;
</script>
<?php } ?>
<?php if ($Page->Export == "" && !$Page->DrillDown && !$grDashboardReport) { ?>
<script type="text/javascript">

// Form object
var CurrentForm = fviewdocenterpt = new ewr_Form("fviewdocenterpt");

// Validate method
fviewdocenterpt.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);

	// Call Form Custom Validate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate method
fviewdocenterpt.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }
<?php if (EWR_CLIENT_VALIDATE) { ?>
fviewdocenterpt.ValidateRequired = true; // Uses JavaScript validation
<?php } else { ?>
fviewdocenterpt.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Use Ajax
fviewdocenterpt.Lists["sv_deoartamento"] = {"LinkField":"sv_deoartamento","Ajax":true,"DisplayFields":["sv_deoartamento","","",""],"ParentFields":[],"FilterFields":[],"Options":[],"Template":""};
fviewdocenterpt.Lists["sv_unidadeducativa"] = {"LinkField":"sv_unidadeducativa","Ajax":true,"DisplayFields":["sv_unidadeducativa","","",""],"ParentFields":[],"FilterFields":[],"Options":[],"Template":""};
fviewdocenterpt.Lists["sv_nombres"] = {"LinkField":"sv_nombres","Ajax":true,"DisplayFields":["sv_nombres","","",""],"ParentFields":[],"FilterFields":[],"Options":[],"Template":""};
fviewdocenterpt.Lists["sv_apellidopaterno"] = {"LinkField":"sv_apellidopaterno","Ajax":true,"DisplayFields":["sv_apellidopaterno","","",""],"ParentFields":[],"FilterFields":[],"Options":[],"Template":""};
fviewdocenterpt.Lists["sv_apellidomaterno"] = {"LinkField":"sv_apellidomaterno","Ajax":true,"DisplayFields":["sv_apellidomaterno","","",""],"ParentFields":[],"FilterFields":[],"Options":[],"Template":""};
fviewdocenterpt.Lists["sv_discapacidad"] = {"LinkField":"sv_discapacidad","Ajax":true,"DisplayFields":["sv_discapacidad","","",""],"ParentFields":[],"FilterFields":[],"Options":[],"Template":""};
fviewdocenterpt.Lists["sv_tipodiscapacidad"] = {"LinkField":"sv_tipodiscapacidad","Ajax":true,"DisplayFields":["sv_tipodiscapacidad","","",""],"ParentFields":[],"FilterFields":[],"Options":[],"Template":""};
fviewdocenterpt.Lists["sv_nombreinstitucion"] = {"LinkField":"sv_nombreinstitucion","Ajax":true,"DisplayFields":["sv_nombreinstitucion","","",""],"ParentFields":[],"FilterFields":[],"Options":[],"Template":""};
</script>
<?php } ?>
<?php if ($Page->Export == "" && !$Page->DrillDown && !$grDashboardReport) { ?>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<a id="top"></a>
<?php if ($Page->Export == "" && !$grDashboardReport) { ?>
<!-- Content Container -->
<div id="ewContainer" class="container-fluid ewContainer">
<?php } ?>
<?php if (@$Page->GenOptions["showfilter"] == "1") { ?>
<?php $Page->ShowFilterList(TRUE) ?>
<?php } ?>
<div class="ewToolbar">
<?php
if (!$Page->DrillDownInPanel) {
	$Page->ExportOptions->Render("body");
	$Page->SearchOptions->Render("body");
	$Page->FilterOptions->Render("body");
	$Page->GenerateOptions->Render("body");
}
?>
</div>
<?php $Page->ShowPageHeader(); ?>
<?php $Page->ShowMessage(); ?>
<?php if ($Page->Export == "" && !$grDashboardReport) { ?>
<div class="row">
<?php } ?>
<?php if ($Page->Export == "" && !$grDashboardReport) { ?>
<!-- Center Container - Report -->
<div id="ewCenter" class="col-sm-12 ewCenter">
<?php } ?>
<?php if ($Page->ShowDrillDownFilter) { ?>
<?php $Page->ShowDrillDownList() ?>
<?php } ?>
<!-- Summary Report begins -->
<?php if ($Page->Export <> "pdf") { ?>
<div id="report_summary">
<?php } ?>
<?php if ($Page->Export == "" && !$Page->DrillDown && !$grDashboardReport) { ?>
<!-- Search form (begin) -->
<form name="fviewdocenterpt" id="fviewdocenterpt" class="form-inline ewForm ewExtFilterForm" action="<?php echo ewr_CurrentPage() ?>">
<?php $SearchPanelClass = ($Page->Filter <> "") ? " in" : " in"; ?>
<div id="fviewdocenterpt_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<div id="r_1" class="ewRow">
<div id="c_deoartamento" class="ewCell form-group">
	<label for="sv_deoartamento" class="ewSearchCaption ewLabel"><?php echo $Page->deoartamento->FldCaption() ?></label>
	<span class="ewSearchField">
<?php ewr_PrependClass($Page->deoartamento->EditAttrs["class"], "form-control"); ?>
<select data-table="viewdocente" data-field="x_deoartamento" data-value-separator="<?php echo ewr_HtmlEncode(is_array($Page->deoartamento->DisplayValueSeparator) ? json_encode($Page->deoartamento->DisplayValueSeparator) : $Page->deoartamento->DisplayValueSeparator) ?>" id="sv_deoartamento" name="sv_deoartamento"<?php echo $Page->deoartamento->EditAttributes() ?>>
<option value=""><?php echo $ReportLanguage->Phrase("PleaseSelect") ?></option>
<?php
	$cntf = is_array($Page->deoartamento->AdvancedFilters) ? count($Page->deoartamento->AdvancedFilters) : 0;
	$cntd = is_array($Page->deoartamento->DropDownList) ? count($Page->deoartamento->DropDownList) : 0;
	$totcnt = $cntf + $cntd;
	$wrkcnt = 0;
	if ($cntf > 0) {
		foreach ($Page->deoartamento->AdvancedFilters as $filter) {
			if ($filter->Enabled) {
				$selwrk = ewr_MatchedFilterValue($Page->deoartamento->DropDownValue, $filter->ID) ? " selected" : "";
?>
<option value="<?php echo $filter->ID ?>"<?php echo $selwrk ?>><?php echo $filter->Name ?></option>
<?php
				$wrkcnt += 1;
			}
		}
	}
	for ($i = 0; $i < $cntd; $i++) {
		$selwrk = " selected";
?>
<option value="<?php echo $Page->deoartamento->DropDownList[$i] ?>"<?php echo $selwrk ?>><?php echo ewr_DropDownDisplayValue($Page->deoartamento->DropDownList[$i], "", 0) ?></option>
<?php
		$wrkcnt += 1;
	}
?>
</select>
<input type="hidden" name="s_sv_deoartamento" id="s_sv_deoartamento" value="<?php echo $Page->deoartamento->LookupFilterQuery() ?>">
<script type="text/javascript">
fviewdocenterpt.Lists["sv_deoartamento"].Options = <?php echo ewr_ArrayToJson($Page->deoartamento->LookupFilterOptions) ?>;
</script>
</span>
</div>
<div id="c_unidadeducativa" class="ewCell form-group">
	<label for="sv_unidadeducativa" class="ewSearchCaption ewLabel"><?php echo $Page->unidadeducativa->FldCaption() ?></label>
	<span class="ewSearchField">
<div class="ewDropdownList has-feedback">
	<span onclick="" class="form-control dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		<?php echo ewr_FilterDropDownValue($Page->unidadeducativa) ?>
	</span>
	<span class="glyphicon glyphicon-remove form-control-feedback ewDropdownListClear"></span>
	<span class="form-control-feedback"><span class="caret"></span></span>
	<div id="dsl_sv_unidadeducativa" data-repeatcolumn="1" class="dropdown-menu">
		<div class="ewItems" style="position: relative; overflow-x: hidden;">
<?php
	$cntf = is_array($Page->unidadeducativa->AdvancedFilters) ? count($Page->unidadeducativa->AdvancedFilters) : 0;
	$cntd = is_array($Page->unidadeducativa->DropDownList) ? count($Page->unidadeducativa->DropDownList) : 0;
	$totcnt = $cntf + $cntd;
	$wrkcnt = 0;
	if ($cntf > 0) {
		foreach ($Page->unidadeducativa->AdvancedFilters as $filter) {
			if ($filter->Enabled) {
				$selwrk = ewr_MatchedFilterValue($Page->unidadeducativa->DropDownValue, $filter->ID) ? " checked" : "";
?>
<input type="radio" data-table="viewdocente" data-field="x_unidadeducativa" data-value-separator="<?php echo ewr_HtmlEncode(is_array($Page->unidadeducativa->DisplayValueSeparator) ? json_encode($Page->unidadeducativa->DisplayValueSeparator) : $Page->unidadeducativa->DisplayValueSeparator) ?>" data-filter-name="<?php echo ewr_HtmlEncode($filter->Name) ?>" name="sv_unidadeducativa" value="<?php echo $filter->ID ?>"<?php echo $selwrk ?><?php echo $Page->unidadeducativa->EditAttributes() ?>><?php echo $filter->Name ?>
<?php
				$wrkcnt += 1;
			}
		}
	}
	for ($i = 0; $i < $cntd; $i++) {
		$selwrk = " checked";
?>
<input type="radio" data-table="viewdocente" data-field="x_unidadeducativa" data-value-separator="<?php echo ewr_HtmlEncode(is_array($Page->unidadeducativa->DisplayValueSeparator) ? json_encode($Page->unidadeducativa->DisplayValueSeparator) : $Page->unidadeducativa->DisplayValueSeparator) ?>" name="sv_unidadeducativa" value="<?php echo $Page->unidadeducativa->DropDownList[$i] ?>"<?php echo $selwrk ?><?php echo $Page->unidadeducativa->EditAttributes() ?>><?php echo ewr_DropDownDisplayValue($Page->unidadeducativa->DropDownList[$i], "", 0) ?>
<?php
		$wrkcnt += 1;
	}
?>
		</div>
	</div>
	<div id="tp_sv_unidadeducativa" class="ewTemplate"><input type="radio" data-table="viewdocente" data-field="x_unidadeducativa" data-value-separator="<?php echo ewr_HtmlEncode(is_array($Page->unidadeducativa->DisplayValueSeparator) ? json_encode($Page->unidadeducativa->DisplayValueSeparator) : $Page->unidadeducativa->DisplayValueSeparator) ?>" name="sv_unidadeducativa" id="sv_unidadeducativa" value="{value}"<?php echo $Page->unidadeducativa->EditAttributes() ?>></div>
</div>
<input type="hidden" name="s_sv_unidadeducativa" id="s_sv_unidadeducativa" value="<?php echo $Page->unidadeducativa->LookupFilterQuery() ?>">
<script type="text/javascript">
fviewdocenterpt.Lists["sv_unidadeducativa"].Options = <?php echo ewr_ArrayToJson($Page->unidadeducativa->LookupFilterOptions) ?>;
</script>
</span>
</div>
</div>
<div id="r_2" class="ewRow">
<div id="c_nombres" class="ewCell form-group">
	<label for="sv_nombres" class="ewSearchCaption ewLabel"><?php echo $Page->nombres->FldCaption() ?></label>
	<span class="ewSearchField">
<?php ewr_PrependClass($Page->nombres->EditAttrs["class"], "form-control"); ?>
<select data-table="viewdocente" data-field="x_nombres" data-value-separator="<?php echo ewr_HtmlEncode(is_array($Page->nombres->DisplayValueSeparator) ? json_encode($Page->nombres->DisplayValueSeparator) : $Page->nombres->DisplayValueSeparator) ?>" id="sv_nombres" name="sv_nombres"<?php echo $Page->nombres->EditAttributes() ?>>
<option value=""><?php echo $ReportLanguage->Phrase("PleaseSelect") ?></option>
<?php
	$cntf = is_array($Page->nombres->AdvancedFilters) ? count($Page->nombres->AdvancedFilters) : 0;
	$cntd = is_array($Page->nombres->DropDownList) ? count($Page->nombres->DropDownList) : 0;
	$totcnt = $cntf + $cntd;
	$wrkcnt = 0;
	if ($cntf > 0) {
		foreach ($Page->nombres->AdvancedFilters as $filter) {
			if ($filter->Enabled) {
				$selwrk = ewr_MatchedFilterValue($Page->nombres->DropDownValue, $filter->ID) ? " selected" : "";
?>
<option value="<?php echo $filter->ID ?>"<?php echo $selwrk ?>><?php echo $filter->Name ?></option>
<?php
				$wrkcnt += 1;
			}
		}
	}
	for ($i = 0; $i < $cntd; $i++) {
		$selwrk = " selected";
?>
<option value="<?php echo $Page->nombres->DropDownList[$i] ?>"<?php echo $selwrk ?>><?php echo ewr_DropDownDisplayValue($Page->nombres->DropDownList[$i], "", 0) ?></option>
<?php
		$wrkcnt += 1;
	}
?>
</select>
<input type="hidden" name="s_sv_nombres" id="s_sv_nombres" value="<?php echo $Page->nombres->LookupFilterQuery() ?>">
<script type="text/javascript">
fviewdocenterpt.Lists["sv_nombres"].Options = <?php echo ewr_ArrayToJson($Page->nombres->LookupFilterOptions) ?>;
</script>
</span>
</div>
<div id="c_apellidopaterno" class="ewCell form-group">
	<label for="sv_apellidopaterno" class="ewSearchCaption ewLabel"><?php echo $Page->apellidopaterno->FldCaption() ?></label>
	<span class="ewSearchField">
<?php ewr_PrependClass($Page->apellidopaterno->EditAttrs["class"], "form-control"); ?>
<select data-table="viewdocente" data-field="x_apellidopaterno" data-value-separator="<?php echo ewr_HtmlEncode(is_array($Page->apellidopaterno->DisplayValueSeparator) ? json_encode($Page->apellidopaterno->DisplayValueSeparator) : $Page->apellidopaterno->DisplayValueSeparator) ?>" id="sv_apellidopaterno" name="sv_apellidopaterno"<?php echo $Page->apellidopaterno->EditAttributes() ?>>
<option value=""><?php echo $ReportLanguage->Phrase("PleaseSelect") ?></option>
<?php
	$cntf = is_array($Page->apellidopaterno->AdvancedFilters) ? count($Page->apellidopaterno->AdvancedFilters) : 0;
	$cntd = is_array($Page->apellidopaterno->DropDownList) ? count($Page->apellidopaterno->DropDownList) : 0;
	$totcnt = $cntf + $cntd;
	$wrkcnt = 0;
	if ($cntf > 0) {
		foreach ($Page->apellidopaterno->AdvancedFilters as $filter) {
			if ($filter->Enabled) {
				$selwrk = ewr_MatchedFilterValue($Page->apellidopaterno->DropDownValue, $filter->ID) ? " selected" : "";
?>
<option value="<?php echo $filter->ID ?>"<?php echo $selwrk ?>><?php echo $filter->Name ?></option>
<?php
				$wrkcnt += 1;
			}
		}
	}
	for ($i = 0; $i < $cntd; $i++) {
		$selwrk = " selected";
?>
<option value="<?php echo $Page->apellidopaterno->DropDownList[$i] ?>"<?php echo $selwrk ?>><?php echo ewr_DropDownDisplayValue($Page->apellidopaterno->DropDownList[$i], "", 0) ?></option>
<?php
		$wrkcnt += 1;
	}
?>
</select>
<input type="hidden" name="s_sv_apellidopaterno" id="s_sv_apellidopaterno" value="<?php echo $Page->apellidopaterno->LookupFilterQuery() ?>">
<script type="text/javascript">
fviewdocenterpt.Lists["sv_apellidopaterno"].Options = <?php echo ewr_ArrayToJson($Page->apellidopaterno->LookupFilterOptions) ?>;
</script>
</span>
</div>
</div>
<div id="r_3" class="ewRow">
<div id="c_apellidomaterno" class="ewCell form-group">
	<label for="sv_apellidomaterno" class="ewSearchCaption ewLabel"><?php echo $Page->apellidomaterno->FldCaption() ?></label>
	<span class="ewSearchField">
<?php ewr_PrependClass($Page->apellidomaterno->EditAttrs["class"], "form-control"); ?>
<select data-table="viewdocente" data-field="x_apellidomaterno" data-value-separator="<?php echo ewr_HtmlEncode(is_array($Page->apellidomaterno->DisplayValueSeparator) ? json_encode($Page->apellidomaterno->DisplayValueSeparator) : $Page->apellidomaterno->DisplayValueSeparator) ?>" id="sv_apellidomaterno" name="sv_apellidomaterno"<?php echo $Page->apellidomaterno->EditAttributes() ?>>
<option value=""><?php echo $ReportLanguage->Phrase("PleaseSelect") ?></option>
<?php
	$cntf = is_array($Page->apellidomaterno->AdvancedFilters) ? count($Page->apellidomaterno->AdvancedFilters) : 0;
	$cntd = is_array($Page->apellidomaterno->DropDownList) ? count($Page->apellidomaterno->DropDownList) : 0;
	$totcnt = $cntf + $cntd;
	$wrkcnt = 0;
	if ($cntf > 0) {
		foreach ($Page->apellidomaterno->AdvancedFilters as $filter) {
			if ($filter->Enabled) {
				$selwrk = ewr_MatchedFilterValue($Page->apellidomaterno->DropDownValue, $filter->ID) ? " selected" : "";
?>
<option value="<?php echo $filter->ID ?>"<?php echo $selwrk ?>><?php echo $filter->Name ?></option>
<?php
				$wrkcnt += 1;
			}
		}
	}
	for ($i = 0; $i < $cntd; $i++) {
		$selwrk = " selected";
?>
<option value="<?php echo $Page->apellidomaterno->DropDownList[$i] ?>"<?php echo $selwrk ?>><?php echo ewr_DropDownDisplayValue($Page->apellidomaterno->DropDownList[$i], "", 0) ?></option>
<?php
		$wrkcnt += 1;
	}
?>
</select>
<input type="hidden" name="s_sv_apellidomaterno" id="s_sv_apellidomaterno" value="<?php echo $Page->apellidomaterno->LookupFilterQuery() ?>">
<script type="text/javascript">
fviewdocenterpt.Lists["sv_apellidomaterno"].Options = <?php echo ewr_ArrayToJson($Page->apellidomaterno->LookupFilterOptions) ?>;
</script>
</span>
</div>
<div id="c_nrodiscapacidad" class="ewCell form-group">
	<label for="sv_nrodiscapacidad" class="ewSearchCaption ewLabel"><?php echo $Page->nrodiscapacidad->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("LIKE"); ?><input type="hidden" name="so_nrodiscapacidad" id="so_nrodiscapacidad" value="LIKE"></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->nrodiscapacidad->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewdocente" data-field="x_nrodiscapacidad" id="sv_nrodiscapacidad" name="sv_nrodiscapacidad" size="30" maxlength="15" placeholder="<?php echo $Page->nrodiscapacidad->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->nrodiscapacidad->SearchValue) ?>"<?php echo $Page->nrodiscapacidad->EditAttributes() ?>>
</span>
</div>
</div>
<div id="r_4" class="ewRow">
<div id="c_ci" class="ewCell form-group">
	<label for="sv_ci" class="ewSearchCaption ewLabel"><?php echo $Page->ci->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("LIKE"); ?><input type="hidden" name="so_ci" id="so_ci" value="LIKE"></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->ci->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewdocente" data-field="x_ci" id="sv_ci" name="sv_ci" size="30" maxlength="15" placeholder="<?php echo $Page->ci->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->ci->SearchValue) ?>"<?php echo $Page->ci->EditAttributes() ?>>
</span>
</div>
<div id="c_materias" class="ewCell form-group">
	<label for="sv_materias" class="ewSearchCaption ewLabel"><?php echo $Page->materias->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("LIKE"); ?><input type="hidden" name="so_materias" id="so_materias" value="LIKE"></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->materias->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewdocente" data-field="x_materias" id="sv_materias" name="sv_materias" size="30" maxlength="100" placeholder="<?php echo $Page->materias->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->materias->SearchValue) ?>"<?php echo $Page->materias->EditAttributes() ?>>
</span>
</div>
</div>
<div id="r_5" class="ewRow">
<div id="c_discapacidad" class="ewCell form-group">
	<label class="ewSearchCaption ewLabel"><?php echo $Page->discapacidad->FldCaption() ?></label>
	<span class="ewSearchField">
<div id="tp_sv_discapacidad" class="<?php echo EWR_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" data-table="viewdocente" data-field="x_discapacidad" data-value-separator="<?php echo ewr_HtmlEncode(is_array($Page->discapacidad->DisplayValueSeparator) ? json_encode($Page->discapacidad->DisplayValueSeparator) : $Page->discapacidad->DisplayValueSeparator) ?>" name="sv_discapacidad" id="sv_discapacidad" value="{value}"<?php echo $Page->discapacidad->EditAttributes() ?>></div>
<div id="dsl_sv_discapacidad" data-repeatcolumn="5" class="ewItemList"><div>
<?php
	$cntf = is_array($Page->discapacidad->AdvancedFilters) ? count($Page->discapacidad->AdvancedFilters) : 0;
	$cntd = is_array($Page->discapacidad->DropDownList) ? count($Page->discapacidad->DropDownList) : 0;
	$totcnt = $cntf + $cntd;
	$wrkcnt = 0;
	if ($cntf > 0) {
		foreach ($Page->discapacidad->AdvancedFilters as $filter) {
			if ($filter->Enabled) {
				$selwrk = ewr_MatchedFilterValue($Page->discapacidad->DropDownValue, $filter->ID) ? " checked" : "";
?>
<?php echo ewr_RepeatColumnTable($totcnt, $wrkcnt, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-table="viewdocente" data-field="x_discapacidad" data-value-separator="<?php echo ewr_HtmlEncode(is_array($Page->discapacidad->DisplayValueSeparator) ? json_encode($Page->discapacidad->DisplayValueSeparator) : $Page->discapacidad->DisplayValueSeparator) ?>" name="sv_discapacidad" value="<?php echo $filter->ID ?>"<?php echo $selwrk ?><?php echo $Page->discapacidad->EditAttributes() ?>><?php echo $filter->Name ?></label>
<?php echo ewr_RepeatColumnTable($totcnt, $wrkcnt, 5, 2) ?>
<?php
				$wrkcnt += 1;
			}
		}
	}
	for ($i = 0; $i < $cntd; $i++) {
		$selwrk = " checked";
?>
<?php echo ewr_RepeatColumnTable($totcnt, $wrkcnt, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-table="viewdocente" data-field="x_discapacidad" data-value-separator="<?php echo ewr_HtmlEncode(is_array($Page->discapacidad->DisplayValueSeparator) ? json_encode($Page->discapacidad->DisplayValueSeparator) : $Page->discapacidad->DisplayValueSeparator) ?>" name="sv_discapacidad" value="<?php echo $Page->discapacidad->DropDownList[$i] ?>"<?php echo $selwrk ?><?php echo $Page->discapacidad->EditAttributes() ?>><?php echo ewr_DropDownDisplayValue($Page->discapacidad->DropDownList[$i], "", 0) ?></label>
<?php echo ewr_RepeatColumnTable($totcnt, $wrkcnt, 5, 2) ?>
<?php
		$wrkcnt += 1;
	}
?>
</div></div>
<input type="hidden" name="s_sv_discapacidad" id="s_sv_discapacidad" value="<?php echo $Page->discapacidad->LookupFilterQuery() ?>">
<script type="text/javascript">
fviewdocenterpt.Lists["sv_discapacidad"].Options = <?php echo ewr_ArrayToJson($Page->discapacidad->LookupFilterOptions) ?>;
</script>
</span>
</div>
<div id="c_tipodiscapacidad" class="ewCell form-group">
	<label for="sv_tipodiscapacidad" class="ewSearchCaption ewLabel"><?php echo $Page->tipodiscapacidad->FldCaption() ?></label>
	<span class="ewSearchField">
<?php ewr_PrependClass($Page->tipodiscapacidad->EditAttrs["class"], "form-control"); ?>
<select data-table="viewdocente" data-field="x_tipodiscapacidad" data-value-separator="<?php echo ewr_HtmlEncode(is_array($Page->tipodiscapacidad->DisplayValueSeparator) ? json_encode($Page->tipodiscapacidad->DisplayValueSeparator) : $Page->tipodiscapacidad->DisplayValueSeparator) ?>" id="sv_tipodiscapacidad" name="sv_tipodiscapacidad"<?php echo $Page->tipodiscapacidad->EditAttributes() ?>>
<option value=""><?php echo $ReportLanguage->Phrase("PleaseSelect") ?></option>
<?php
	$cntf = is_array($Page->tipodiscapacidad->AdvancedFilters) ? count($Page->tipodiscapacidad->AdvancedFilters) : 0;
	$cntd = is_array($Page->tipodiscapacidad->DropDownList) ? count($Page->tipodiscapacidad->DropDownList) : 0;
	$totcnt = $cntf + $cntd;
	$wrkcnt = 0;
	if ($cntf > 0) {
		foreach ($Page->tipodiscapacidad->AdvancedFilters as $filter) {
			if ($filter->Enabled) {
				$selwrk = ewr_MatchedFilterValue($Page->tipodiscapacidad->DropDownValue, $filter->ID) ? " selected" : "";
?>
<option value="<?php echo $filter->ID ?>"<?php echo $selwrk ?>><?php echo $filter->Name ?></option>
<?php
				$wrkcnt += 1;
			}
		}
	}
	for ($i = 0; $i < $cntd; $i++) {
		$selwrk = " selected";
?>
<option value="<?php echo $Page->tipodiscapacidad->DropDownList[$i] ?>"<?php echo $selwrk ?>><?php echo ewr_DropDownDisplayValue($Page->tipodiscapacidad->DropDownList[$i], "", 0) ?></option>
<?php
		$wrkcnt += 1;
	}
?>
</select>
<input type="hidden" name="s_sv_tipodiscapacidad" id="s_sv_tipodiscapacidad" value="<?php echo $Page->tipodiscapacidad->LookupFilterQuery() ?>">
<script type="text/javascript">
fviewdocenterpt.Lists["sv_tipodiscapacidad"].Options = <?php echo ewr_ArrayToJson($Page->tipodiscapacidad->LookupFilterOptions) ?>;
</script>
</span>
</div>
</div>
<div id="r_6" class="ewRow">
<div id="c_nombreinstitucion" class="ewCell form-group">
	<label for="sv_nombreinstitucion" class="ewSearchCaption ewLabel"><?php echo $Page->nombreinstitucion->FldCaption() ?></label>
	<span class="ewSearchField">
<?php ewr_PrependClass($Page->nombreinstitucion->EditAttrs["class"], "form-control"); ?>
<select data-table="viewdocente" data-field="x_nombreinstitucion" data-value-separator="<?php echo ewr_HtmlEncode(is_array($Page->nombreinstitucion->DisplayValueSeparator) ? json_encode($Page->nombreinstitucion->DisplayValueSeparator) : $Page->nombreinstitucion->DisplayValueSeparator) ?>" id="sv_nombreinstitucion" name="sv_nombreinstitucion"<?php echo $Page->nombreinstitucion->EditAttributes() ?>>
<option value=""><?php echo $ReportLanguage->Phrase("PleaseSelect") ?></option>
<?php
	$cntf = is_array($Page->nombreinstitucion->AdvancedFilters) ? count($Page->nombreinstitucion->AdvancedFilters) : 0;
	$cntd = is_array($Page->nombreinstitucion->DropDownList) ? count($Page->nombreinstitucion->DropDownList) : 0;
	$totcnt = $cntf + $cntd;
	$wrkcnt = 0;
	if ($cntf > 0) {
		foreach ($Page->nombreinstitucion->AdvancedFilters as $filter) {
			if ($filter->Enabled) {
				$selwrk = ewr_MatchedFilterValue($Page->nombreinstitucion->DropDownValue, $filter->ID) ? " selected" : "";
?>
<option value="<?php echo $filter->ID ?>"<?php echo $selwrk ?>><?php echo $filter->Name ?></option>
<?php
				$wrkcnt += 1;
			}
		}
	}
	for ($i = 0; $i < $cntd; $i++) {
		$selwrk = " selected";
?>
<option value="<?php echo $Page->nombreinstitucion->DropDownList[$i] ?>"<?php echo $selwrk ?>><?php echo ewr_DropDownDisplayValue($Page->nombreinstitucion->DropDownList[$i], "", 0) ?></option>
<?php
		$wrkcnt += 1;
	}
?>
</select>
<input type="hidden" name="s_sv_nombreinstitucion" id="s_sv_nombreinstitucion" value="<?php echo $Page->nombreinstitucion->LookupFilterQuery() ?>">
<script type="text/javascript">
fviewdocenterpt.Lists["sv_nombreinstitucion"].Options = <?php echo ewr_ArrayToJson($Page->nombreinstitucion->LookupFilterOptions) ?>;
</script>
</span>
</div>
</div>
<div class="ewRow"><input type="submit" name="btnsubmit" id="btnsubmit" class="btn btn-primary" value="<?php echo $ReportLanguage->Phrase("Search") ?>">
<input type="reset" name="btnreset" id="btnreset" class="btn hide" value="<?php echo $ReportLanguage->Phrase("Reset") ?>"></div>
</div>
</form>
<script type="text/javascript">
fviewdocenterpt.Init();
fviewdocenterpt.FilterList = <?php echo $Page->GetFilterList() ?>;
</script>
<!-- Search form (end) -->
<?php } ?>
<?php if ($Page->ShowCurrentFilter) { ?>
<?php $Page->ShowFilterList() ?>
<?php } ?>
<?php

// Set the last group to display if not export all
if ($Page->ExportAll && $Page->Export <> "") {
	$Page->StopGrp = $Page->TotalGrps;
} else {
	$Page->StopGrp = $Page->StartGrp + $Page->DisplayGrps - 1;
}

// Stop group <= total number of groups
if (intval($Page->StopGrp) > intval($Page->TotalGrps))
	$Page->StopGrp = $Page->TotalGrps;
$Page->RecCount = 0;
$Page->RecIndex = 0;

// Get first row
if ($Page->TotalGrps > 0) {
	$Page->GetRow(1);
	$Page->GrpCount = 1;
}
$Page->GrpIdx = ewr_InitArray(2, -1);
$Page->GrpIdx[0] = -1;
$Page->GrpIdx[1] = $Page->StopGrp - $Page->StartGrp + 1;
while ($rs && !$rs->EOF && $Page->GrpCount <= $Page->DisplayGrps || $Page->ShowHeader) {

	// Show dummy header for custom template
	// Show header

	if ($Page->ShowHeader) {
?>
<?php if ($Page->Export <> "pdf") { ?>
<?php if ($Page->Export == "word" || $Page->Export == "excel") { ?>
<div class="ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } else { ?>
<div class="box ewBox ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } ?>
<?php } ?>
<?php if ($Page->Export == "" && !($Page->DrillDown && $Page->TotalGrps > 0)) { ?>
<div class="box-header ewGridUpperPanel">
<?php include "viewdocenterptpager.php" ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<!-- Report grid (begin) -->
<?php if ($Page->Export <> "pdf") { ?>
<div id="gmp_viewdocente" class="<?php if (ewr_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php } ?>
<table class="<?php echo $Page->ReportTableClass ?>">
<thead>
	<!-- Table header -->
	<tr class="ewTableHeader">
<?php if ($Page->deoartamento->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="deoartamento"><div class="viewdocente_deoartamento"><span class="ewTableHeaderCaption"><?php echo $Page->deoartamento->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="deoartamento">
<?php if ($Page->SortUrl($Page->deoartamento) == "") { ?>
		<div class="ewTableHeaderBtn viewdocente_deoartamento">
			<span class="ewTableHeaderCaption"><?php echo $Page->deoartamento->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewdocente_deoartamento', range: false, from: '<?php echo $Page->deoartamento->RangeFrom; ?>', to: '<?php echo $Page->deoartamento->RangeTo; ?>', url: 'viewdocenterpt.php' });" id="x_deoartamento<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewdocente_deoartamento" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->deoartamento) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->deoartamento->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->deoartamento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->deoartamento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewdocente_deoartamento', range: false, from: '<?php echo $Page->deoartamento->RangeFrom; ?>', to: '<?php echo $Page->deoartamento->RangeTo; ?>', url: 'viewdocenterpt.php' });" id="x_deoartamento<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->unidadeducativa->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="unidadeducativa"><div class="viewdocente_unidadeducativa"><span class="ewTableHeaderCaption"><?php echo $Page->unidadeducativa->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="unidadeducativa">
<?php if ($Page->SortUrl($Page->unidadeducativa) == "") { ?>
		<div class="ewTableHeaderBtn viewdocente_unidadeducativa">
			<span class="ewTableHeaderCaption"><?php echo $Page->unidadeducativa->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewdocente_unidadeducativa', range: false, from: '<?php echo $Page->unidadeducativa->RangeFrom; ?>', to: '<?php echo $Page->unidadeducativa->RangeTo; ?>', url: 'viewdocenterpt.php' });" id="x_unidadeducativa<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewdocente_unidadeducativa" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->unidadeducativa) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->unidadeducativa->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->unidadeducativa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->unidadeducativa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewdocente_unidadeducativa', range: false, from: '<?php echo $Page->unidadeducativa->RangeFrom; ?>', to: '<?php echo $Page->unidadeducativa->RangeTo; ?>', url: 'viewdocenterpt.php' });" id="x_unidadeducativa<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->nombres->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="nombres"><div class="viewdocente_nombres"><span class="ewTableHeaderCaption"><?php echo $Page->nombres->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="nombres">
<?php if ($Page->SortUrl($Page->nombres) == "") { ?>
		<div class="ewTableHeaderBtn viewdocente_nombres">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombres->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewdocente_nombres', range: false, from: '<?php echo $Page->nombres->RangeFrom; ?>', to: '<?php echo $Page->nombres->RangeTo; ?>', url: 'viewdocenterpt.php' });" id="x_nombres<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewdocente_nombres" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->nombres) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombres->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->nombres->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->nombres->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewdocente_nombres', range: false, from: '<?php echo $Page->nombres->RangeFrom; ?>', to: '<?php echo $Page->nombres->RangeTo; ?>', url: 'viewdocenterpt.php' });" id="x_nombres<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->apellidopaterno->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="apellidopaterno"><div class="viewdocente_apellidopaterno"><span class="ewTableHeaderCaption"><?php echo $Page->apellidopaterno->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="apellidopaterno">
<?php if ($Page->SortUrl($Page->apellidopaterno) == "") { ?>
		<div class="ewTableHeaderBtn viewdocente_apellidopaterno">
			<span class="ewTableHeaderCaption"><?php echo $Page->apellidopaterno->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewdocente_apellidopaterno', range: false, from: '<?php echo $Page->apellidopaterno->RangeFrom; ?>', to: '<?php echo $Page->apellidopaterno->RangeTo; ?>', url: 'viewdocenterpt.php' });" id="x_apellidopaterno<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewdocente_apellidopaterno" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->apellidopaterno) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->apellidopaterno->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->apellidopaterno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->apellidopaterno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewdocente_apellidopaterno', range: false, from: '<?php echo $Page->apellidopaterno->RangeFrom; ?>', to: '<?php echo $Page->apellidopaterno->RangeTo; ?>', url: 'viewdocenterpt.php' });" id="x_apellidopaterno<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->apellidomaterno->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="apellidomaterno"><div class="viewdocente_apellidomaterno"><span class="ewTableHeaderCaption"><?php echo $Page->apellidomaterno->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="apellidomaterno">
<?php if ($Page->SortUrl($Page->apellidomaterno) == "") { ?>
		<div class="ewTableHeaderBtn viewdocente_apellidomaterno">
			<span class="ewTableHeaderCaption"><?php echo $Page->apellidomaterno->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewdocente_apellidomaterno', range: false, from: '<?php echo $Page->apellidomaterno->RangeFrom; ?>', to: '<?php echo $Page->apellidomaterno->RangeTo; ?>', url: 'viewdocenterpt.php' });" id="x_apellidomaterno<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewdocente_apellidomaterno" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->apellidomaterno) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->apellidomaterno->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->apellidomaterno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->apellidomaterno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewdocente_apellidomaterno', range: false, from: '<?php echo $Page->apellidomaterno->RangeFrom; ?>', to: '<?php echo $Page->apellidomaterno->RangeTo; ?>', url: 'viewdocenterpt.php' });" id="x_apellidomaterno<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->nrodiscapacidad->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="nrodiscapacidad"><div class="viewdocente_nrodiscapacidad"><span class="ewTableHeaderCaption"><?php echo $Page->nrodiscapacidad->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="nrodiscapacidad">
<?php if ($Page->SortUrl($Page->nrodiscapacidad) == "") { ?>
		<div class="ewTableHeaderBtn viewdocente_nrodiscapacidad">
			<span class="ewTableHeaderCaption"><?php echo $Page->nrodiscapacidad->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewdocente_nrodiscapacidad', range: false, from: '<?php echo $Page->nrodiscapacidad->RangeFrom; ?>', to: '<?php echo $Page->nrodiscapacidad->RangeTo; ?>', url: 'viewdocenterpt.php' });" id="x_nrodiscapacidad<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewdocente_nrodiscapacidad" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->nrodiscapacidad) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->nrodiscapacidad->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->nrodiscapacidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->nrodiscapacidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewdocente_nrodiscapacidad', range: false, from: '<?php echo $Page->nrodiscapacidad->RangeFrom; ?>', to: '<?php echo $Page->nrodiscapacidad->RangeTo; ?>', url: 'viewdocenterpt.php' });" id="x_nrodiscapacidad<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->ci->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="ci"><div class="viewdocente_ci"><span class="ewTableHeaderCaption"><?php echo $Page->ci->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="ci">
<?php if ($Page->SortUrl($Page->ci) == "") { ?>
		<div class="ewTableHeaderBtn viewdocente_ci">
			<span class="ewTableHeaderCaption"><?php echo $Page->ci->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewdocente_ci" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->ci) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->ci->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->ci->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->ci->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->fechanacimiento->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="fechanacimiento"><div class="viewdocente_fechanacimiento"><span class="ewTableHeaderCaption"><?php echo $Page->fechanacimiento->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="fechanacimiento">
<?php if ($Page->SortUrl($Page->fechanacimiento) == "") { ?>
		<div class="ewTableHeaderBtn viewdocente_fechanacimiento">
			<span class="ewTableHeaderCaption"><?php echo $Page->fechanacimiento->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewdocente_fechanacimiento', range: false, from: '<?php echo $Page->fechanacimiento->RangeFrom; ?>', to: '<?php echo $Page->fechanacimiento->RangeTo; ?>', url: 'viewdocenterpt.php' });" id="x_fechanacimiento<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewdocente_fechanacimiento" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->fechanacimiento) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->fechanacimiento->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->fechanacimiento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->fechanacimiento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewdocente_fechanacimiento', range: false, from: '<?php echo $Page->fechanacimiento->RangeFrom; ?>', to: '<?php echo $Page->fechanacimiento->RangeTo; ?>', url: 'viewdocenterpt.php' });" id="x_fechanacimiento<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->sexo->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="sexo"><div class="viewdocente_sexo"><span class="ewTableHeaderCaption"><?php echo $Page->sexo->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="sexo">
<?php if ($Page->SortUrl($Page->sexo) == "") { ?>
		<div class="ewTableHeaderBtn viewdocente_sexo">
			<span class="ewTableHeaderCaption"><?php echo $Page->sexo->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewdocente_sexo', range: false, from: '<?php echo $Page->sexo->RangeFrom; ?>', to: '<?php echo $Page->sexo->RangeTo; ?>', url: 'viewdocenterpt.php' });" id="x_sexo<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewdocente_sexo" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->sexo) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->sexo->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->sexo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->sexo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewdocente_sexo', range: false, from: '<?php echo $Page->sexo->RangeFrom; ?>', to: '<?php echo $Page->sexo->RangeTo; ?>', url: 'viewdocenterpt.php' });" id="x_sexo<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->celular->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="celular"><div class="viewdocente_celular"><span class="ewTableHeaderCaption"><?php echo $Page->celular->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="celular">
<?php if ($Page->SortUrl($Page->celular) == "") { ?>
		<div class="ewTableHeaderBtn viewdocente_celular">
			<span class="ewTableHeaderCaption"><?php echo $Page->celular->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewdocente_celular', range: false, from: '<?php echo $Page->celular->RangeFrom; ?>', to: '<?php echo $Page->celular->RangeTo; ?>', url: 'viewdocenterpt.php' });" id="x_celular<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewdocente_celular" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->celular) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->celular->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->celular->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->celular->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewdocente_celular', range: false, from: '<?php echo $Page->celular->RangeFrom; ?>', to: '<?php echo $Page->celular->RangeTo; ?>', url: 'viewdocenterpt.php' });" id="x_celular<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->materias->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="materias"><div class="viewdocente_materias"><span class="ewTableHeaderCaption"><?php echo $Page->materias->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="materias">
<?php if ($Page->SortUrl($Page->materias) == "") { ?>
		<div class="ewTableHeaderBtn viewdocente_materias">
			<span class="ewTableHeaderCaption"><?php echo $Page->materias->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewdocente_materias', range: false, from: '<?php echo $Page->materias->RangeFrom; ?>', to: '<?php echo $Page->materias->RangeTo; ?>', url: 'viewdocenterpt.php' });" id="x_materias<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewdocente_materias" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->materias) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->materias->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->materias->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->materias->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewdocente_materias', range: false, from: '<?php echo $Page->materias->RangeFrom; ?>', to: '<?php echo $Page->materias->RangeTo; ?>', url: 'viewdocenterpt.php' });" id="x_materias<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->discapacidad->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="discapacidad"><div class="viewdocente_discapacidad"><span class="ewTableHeaderCaption"><?php echo $Page->discapacidad->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="discapacidad">
<?php if ($Page->SortUrl($Page->discapacidad) == "") { ?>
		<div class="ewTableHeaderBtn viewdocente_discapacidad">
			<span class="ewTableHeaderCaption"><?php echo $Page->discapacidad->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewdocente_discapacidad', range: false, from: '<?php echo $Page->discapacidad->RangeFrom; ?>', to: '<?php echo $Page->discapacidad->RangeTo; ?>', url: 'viewdocenterpt.php' });" id="x_discapacidad<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewdocente_discapacidad" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->discapacidad) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->discapacidad->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->discapacidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->discapacidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewdocente_discapacidad', range: false, from: '<?php echo $Page->discapacidad->RangeFrom; ?>', to: '<?php echo $Page->discapacidad->RangeTo; ?>', url: 'viewdocenterpt.php' });" id="x_discapacidad<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->tipodiscapacidad->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="tipodiscapacidad"><div class="viewdocente_tipodiscapacidad"><span class="ewTableHeaderCaption"><?php echo $Page->tipodiscapacidad->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="tipodiscapacidad">
<?php if ($Page->SortUrl($Page->tipodiscapacidad) == "") { ?>
		<div class="ewTableHeaderBtn viewdocente_tipodiscapacidad">
			<span class="ewTableHeaderCaption"><?php echo $Page->tipodiscapacidad->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewdocente_tipodiscapacidad', range: false, from: '<?php echo $Page->tipodiscapacidad->RangeFrom; ?>', to: '<?php echo $Page->tipodiscapacidad->RangeTo; ?>', url: 'viewdocenterpt.php' });" id="x_tipodiscapacidad<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewdocente_tipodiscapacidad" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->tipodiscapacidad) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->tipodiscapacidad->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->tipodiscapacidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->tipodiscapacidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewdocente_tipodiscapacidad', range: false, from: '<?php echo $Page->tipodiscapacidad->RangeFrom; ?>', to: '<?php echo $Page->tipodiscapacidad->RangeTo; ?>', url: 'viewdocenterpt.php' });" id="x_tipodiscapacidad<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->nombreinstitucion->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="nombreinstitucion"><div class="viewdocente_nombreinstitucion"><span class="ewTableHeaderCaption"><?php echo $Page->nombreinstitucion->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="nombreinstitucion">
<?php if ($Page->SortUrl($Page->nombreinstitucion) == "") { ?>
		<div class="ewTableHeaderBtn viewdocente_nombreinstitucion">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombreinstitucion->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewdocente_nombreinstitucion', range: false, from: '<?php echo $Page->nombreinstitucion->RangeFrom; ?>', to: '<?php echo $Page->nombreinstitucion->RangeTo; ?>', url: 'viewdocenterpt.php' });" id="x_nombreinstitucion<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewdocente_nombreinstitucion" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->nombreinstitucion) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombreinstitucion->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->nombreinstitucion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->nombreinstitucion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewdocente_nombreinstitucion', range: false, from: '<?php echo $Page->nombreinstitucion->RangeFrom; ?>', to: '<?php echo $Page->nombreinstitucion->RangeTo; ?>', url: 'viewdocenterpt.php' });" id="x_nombreinstitucion<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
	</tr>
</thead>
<tbody>
<?php
		if ($Page->TotalGrps == 0) break; // Show header only
		$Page->ShowHeader = FALSE;
	}
	$Page->RecCount++;
	$Page->RecIndex++;
?>
<?php

		// Render detail row
		$Page->ResetAttrs();
		$Page->RowType = EWR_ROWTYPE_DETAIL;
		$Page->RenderRow();
?>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->deoartamento->Visible) { ?>
		<td data-field="deoartamento"<?php echo $Page->deoartamento->CellAttributes() ?>>
<span<?php echo $Page->deoartamento->ViewAttributes() ?>><?php echo $Page->deoartamento->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->unidadeducativa->Visible) { ?>
		<td data-field="unidadeducativa"<?php echo $Page->unidadeducativa->CellAttributes() ?>>
<span<?php echo $Page->unidadeducativa->ViewAttributes() ?>><?php echo $Page->unidadeducativa->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->nombres->Visible) { ?>
		<td data-field="nombres"<?php echo $Page->nombres->CellAttributes() ?>>
<span<?php echo $Page->nombres->ViewAttributes() ?>><?php echo $Page->nombres->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->apellidopaterno->Visible) { ?>
		<td data-field="apellidopaterno"<?php echo $Page->apellidopaterno->CellAttributes() ?>>
<span<?php echo $Page->apellidopaterno->ViewAttributes() ?>><?php echo $Page->apellidopaterno->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->apellidomaterno->Visible) { ?>
		<td data-field="apellidomaterno"<?php echo $Page->apellidomaterno->CellAttributes() ?>>
<span<?php echo $Page->apellidomaterno->ViewAttributes() ?>><?php echo $Page->apellidomaterno->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->nrodiscapacidad->Visible) { ?>
		<td data-field="nrodiscapacidad"<?php echo $Page->nrodiscapacidad->CellAttributes() ?>>
<span<?php echo $Page->nrodiscapacidad->ViewAttributes() ?>><?php echo $Page->nrodiscapacidad->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->ci->Visible) { ?>
		<td data-field="ci"<?php echo $Page->ci->CellAttributes() ?>>
<span<?php echo $Page->ci->ViewAttributes() ?>><?php echo $Page->ci->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->fechanacimiento->Visible) { ?>
		<td data-field="fechanacimiento"<?php echo $Page->fechanacimiento->CellAttributes() ?>>
<span<?php echo $Page->fechanacimiento->ViewAttributes() ?>><?php echo $Page->fechanacimiento->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->sexo->Visible) { ?>
		<td data-field="sexo"<?php echo $Page->sexo->CellAttributes() ?>>
<span<?php echo $Page->sexo->ViewAttributes() ?>><?php echo $Page->sexo->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->celular->Visible) { ?>
		<td data-field="celular"<?php echo $Page->celular->CellAttributes() ?>>
<span<?php echo $Page->celular->ViewAttributes() ?>><?php echo $Page->celular->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->materias->Visible) { ?>
		<td data-field="materias"<?php echo $Page->materias->CellAttributes() ?>>
<span<?php echo $Page->materias->ViewAttributes() ?>><?php echo $Page->materias->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->discapacidad->Visible) { ?>
		<td data-field="discapacidad"<?php echo $Page->discapacidad->CellAttributes() ?>>
<span<?php echo $Page->discapacidad->ViewAttributes() ?>><?php echo $Page->discapacidad->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->tipodiscapacidad->Visible) { ?>
		<td data-field="tipodiscapacidad"<?php echo $Page->tipodiscapacidad->CellAttributes() ?>>
<span<?php echo $Page->tipodiscapacidad->ViewAttributes() ?>><?php echo $Page->tipodiscapacidad->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->nombreinstitucion->Visible) { ?>
		<td data-field="nombreinstitucion"<?php echo $Page->nombreinstitucion->CellAttributes() ?>>
<span<?php echo $Page->nombreinstitucion->ViewAttributes() ?>><?php echo $Page->nombreinstitucion->ListViewValue() ?></span></td>
<?php } ?>
	</tr>
<?php

		// Accumulate page summary
		$Page->AccumulateSummary();

		// Get next record
		$Page->GetRow(2);
	$Page->GrpCount++;
} // End while
?>
<?php if ($Page->TotalGrps > 0) { ?>
</tbody>
<tfoot>
	</tfoot>
<?php } elseif (!$Page->ShowHeader && TRUE) { // No header displayed ?>
<?php if ($Page->Export <> "pdf") { ?>
<?php if ($Page->Export == "word" || $Page->Export == "excel") { ?>
<div class="ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } else { ?>
<div class="box ewBox ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } ?>
<?php } ?>
<?php if ($Page->Export == "" && !($Page->DrillDown && $Page->TotalGrps > 0)) { ?>
<div class="box-header ewGridUpperPanel">
<?php include "viewdocenterptpager.php" ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<!-- Report grid (begin) -->
<?php if ($Page->Export <> "pdf") { ?>
<div id="gmp_viewdocente" class="<?php if (ewr_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php } ?>
<table class="<?php echo $Page->ReportTableClass ?>">
<?php } ?>
<?php if ($Page->TotalGrps > 0 || TRUE) { // Show footer ?>
</table>
<?php if ($Page->Export <> "pdf") { ?>
</div>
<?php } ?>
<?php if ($Page->TotalGrps > 0) { ?>
<?php if ($Page->Export == "" && !($Page->DrillDown && $Page->TotalGrps > 0)) { ?>
<div class="box-footer ewGridLowerPanel">
<?php include "viewdocenterptpager.php" ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php } ?>
<?php if ($Page->Export <> "pdf") { ?>
</div>
<?php } ?>
<?php } ?>
<?php if ($Page->Export <> "pdf") { ?>
</div>
<?php } ?>
<!-- Summary Report Ends -->
<?php if ($Page->Export == "" && !$grDashboardReport) { ?>
</div>
<!-- /#ewCenter -->
<?php } ?>
<?php if ($Page->Export == "" && !$grDashboardReport) { ?>
</div>
<!-- /.row -->
<?php } ?>
<?php if ($Page->Export == "" && !$grDashboardReport) { ?>
</div>
<!-- /.ewContainer -->
<?php } ?>
<?php
$Page->ShowPageFooter();
if (EWR_DEBUG_ENABLED)
	echo ewr_DebugMsg();
?>
<?php

// Close recordsets
if ($rsgrp) $rsgrp->Close();
if ($rs) $rs->Close();
?>
<?php if ($Page->Export == "" && !$Page->DrillDown && !$grDashboardReport) { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// console.log("page loaded");

</script>
<?php } ?>
<?php if (!$grDashboardReport) { ?>
<?php include_once "phprptinc/footer.php" ?>
<?php include_once "footer.php" ?>
<?php } ?>
<?php
$Page->Page_Terminate();
if (isset($OldPage)) $Page = $OldPage;
?>

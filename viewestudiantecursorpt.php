<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start();
?>
<?php include_once "rcfg11.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "phprptinc/ewmysql.php") ?>
<?php include_once "rphpfn11.php" ?>
<?php include_once "rusrfn11.php" ?>
<?php include_once "viewestudiantecursorptinfo.php" ?>
<?php

//
// Page class
//

$viewestudiantecurso_rpt = NULL; // Initialize page object first

class crviewestudiantecurso_rpt extends crviewestudiantecurso {

	// Page ID
	var $PageID = 'rpt';

	// Project ID
	var $ProjectID = "{707530BA-BEB7-415A-B683-2C9753B31FA3}";

	// Page object name
	var $PageObjName = 'viewestudiantecurso_rpt';

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

		// Table object (viewestudiantecurso)
		if (!isset($GLOBALS["viewestudiantecurso"])) {
			$GLOBALS["viewestudiantecurso"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["viewestudiantecurso"];
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
			define("EWR_TABLE_NAME", 'viewestudiantecurso', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fviewestudiantecursorpt";

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
		$Security->LoadCurrentUserLevel($this->ProjectID . 'viewestudiantecurso');
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
		$this->codigorude->PlaceHolder = $this->codigorude->FldCaption();
		$this->codigorude_es->PlaceHolder = $this->codigorude_es->FldCaption();
		$this->municipio->PlaceHolder = $this->municipio->FldCaption();
		$this->provincia->PlaceHolder = $this->provincia->FldCaption();
		$this->unidadeducativa->PlaceHolder = $this->unidadeducativa->FldCaption();
		$this->fechanacimiento->PlaceHolder = $this->fechanacimiento->FldCaption();
		$this->edad->PlaceHolder = $this->edad->FldCaption();
		$this->curso->PlaceHolder = $this->curso->FldCaption();
		$this->discapacidad->PlaceHolder = $this->discapacidad->FldCaption();
		$this->nombreinstitucion->PlaceHolder = $this->nombreinstitucion->FldCaption();

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
		$item->Body = "<a class=\"ewrExportLink ewEmail\" title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToEmail", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToEmail", TRUE)) . "\" id=\"emf_viewestudiantecurso\" href=\"javascript:void(0);\" onclick=\"ewr_EmailDialogShow({lnk:'emf_viewestudiantecurso',hdr:ewLanguage.Phrase('ExportToEmail'),url:'$url',exportid:'$exportid',el:this});\">" . $ReportLanguage->Phrase("ExportToEmail") . "</a>";
		$item->Visible = TRUE;
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fviewestudiantecursorpt\" href=\"#\">" . $ReportLanguage->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fviewestudiantecursorpt\" href=\"#\">" . $ReportLanguage->Phrase("DeleteFilter") . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $ReportLanguage->Phrase("SearchBtn", TRUE) . "\" data-caption=\"" . $ReportLanguage->Phrase("SearchBtn", TRUE) . "\" data-toggle=\"button\" data-form=\"fviewestudiantecursorpt\">" . $ReportLanguage->Phrase("SearchBtn") . "</button>";
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
		$this->departamento->SetVisibility();
		$this->codigorude->SetVisibility();
		$this->codigorude_es->SetVisibility();
		$this->municipio->SetVisibility();
		$this->provincia->SetVisibility();
		$this->unidadeducativa->SetVisibility();
		$this->nombre->SetVisibility();
		$this->materno->SetVisibility();
		$this->paterno->SetVisibility();
		$this->nrodiscapacidad->SetVisibility();
		$this->ci->SetVisibility();
		$this->fechanacimiento->SetVisibility();
		$this->edad->SetVisibility();
		$this->sexo->SetVisibility();
		$this->curso->SetVisibility();
		$this->discapacidad->SetVisibility();
		$this->tipodiscapcidad->SetVisibility();
		$this->nombreinstitucion->SetVisibility();

		// Handle drill down
		$sDrillDownFilter = $this->GetDrillDownFilter();
		$grDrillDownInPanel = $this->DrillDownInPanel;
		if ($this->DrillDown)
			ewr_AddFilter($this->Filter, $sDrillDownFilter);

		// Aggregate variables
		// 1st dimension = no of groups (level 0 used for grand total)
		// 2nd dimension = no of fields

		$nDtls = 19;
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
		$this->Col = array(array(FALSE, FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(TRUE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE));

		// Set up groups per page dynamically
		$this->SetUpDisplayGrps();

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();
		$this->departamento->SelectionList = "";
		$this->departamento->DefaultSelectionList = "";
		$this->departamento->ValueList = "";
		$this->codigorude->SelectionList = "";
		$this->codigorude->DefaultSelectionList = "";
		$this->codigorude->ValueList = "";
		$this->codigorude_es->SelectionList = "";
		$this->codigorude_es->DefaultSelectionList = "";
		$this->codigorude_es->ValueList = "";
		$this->municipio->SelectionList = "";
		$this->municipio->DefaultSelectionList = "";
		$this->municipio->ValueList = "";
		$this->provincia->SelectionList = "";
		$this->provincia->DefaultSelectionList = "";
		$this->provincia->ValueList = "";
		$this->unidadeducativa->SelectionList = "";
		$this->unidadeducativa->DefaultSelectionList = "";
		$this->unidadeducativa->ValueList = "";
		$this->nombre->SelectionList = "";
		$this->nombre->DefaultSelectionList = "";
		$this->nombre->ValueList = "";
		$this->materno->SelectionList = "";
		$this->materno->DefaultSelectionList = "";
		$this->materno->ValueList = "";
		$this->paterno->SelectionList = "";
		$this->paterno->DefaultSelectionList = "";
		$this->paterno->ValueList = "";
		$this->nrodiscapacidad->SelectionList = "";
		$this->nrodiscapacidad->DefaultSelectionList = "";
		$this->nrodiscapacidad->ValueList = "";
		$this->ci->SelectionList = "";
		$this->ci->DefaultSelectionList = "";
		$this->ci->ValueList = "";
		$this->fechanacimiento->SelectionList = "";
		$this->fechanacimiento->DefaultSelectionList = "";
		$this->fechanacimiento->ValueList = "";
		$this->edad->SelectionList = "";
		$this->edad->DefaultSelectionList = "";
		$this->edad->ValueList = "";
		$this->sexo->SelectionList = "";
		$this->sexo->DefaultSelectionList = "";
		$this->sexo->ValueList = "";
		$this->curso->SelectionList = "";
		$this->curso->DefaultSelectionList = "";
		$this->curso->ValueList = "";
		$this->discapacidad->SelectionList = "";
		$this->discapacidad->DefaultSelectionList = "";
		$this->discapacidad->ValueList = "";
		$this->tipodiscapcidad->SelectionList = "";
		$this->tipodiscapcidad->DefaultSelectionList = "";
		$this->tipodiscapcidad->ValueList = "";
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
				$this->FirstRowData['departamento'] = ewr_Conv($rs->fields('departamento'), 200);
				$this->FirstRowData['codigorude'] = ewr_Conv($rs->fields('codigorude'), 200);
				$this->FirstRowData['codigorude_es'] = ewr_Conv($rs->fields('codigorude_es'), 200);
				$this->FirstRowData['municipio'] = ewr_Conv($rs->fields('municipio'), 200);
				$this->FirstRowData['provincia'] = ewr_Conv($rs->fields('provincia'), 200);
				$this->FirstRowData['unidadeducativa'] = ewr_Conv($rs->fields('unidadeducativa'), 200);
				$this->FirstRowData['nombre'] = ewr_Conv($rs->fields('nombre'), 200);
				$this->FirstRowData['materno'] = ewr_Conv($rs->fields('materno'), 200);
				$this->FirstRowData['paterno'] = ewr_Conv($rs->fields('paterno'), 200);
				$this->FirstRowData['nrodiscapacidad'] = ewr_Conv($rs->fields('nrodiscapacidad'), 200);
				$this->FirstRowData['ci'] = ewr_Conv($rs->fields('ci'), 200);
				$this->FirstRowData['fechanacimiento'] = ewr_Conv($rs->fields('fechanacimiento'), 133);
				$this->FirstRowData['edad'] = ewr_Conv($rs->fields('edad'), 20);
				$this->FirstRowData['sexo'] = ewr_Conv($rs->fields('sexo'), 200);
				$this->FirstRowData['curso'] = ewr_Conv($rs->fields('curso'), 200);
				$this->FirstRowData['discapacidad'] = ewr_Conv($rs->fields('discapacidad'), 200);
				$this->FirstRowData['tipodiscapcidad'] = ewr_Conv($rs->fields('tipodiscapcidad'), 200);
				$this->FirstRowData['nombreinstitucion'] = ewr_Conv($rs->fields('nombreinstitucion'), 200);
		} else { // Get next row
			$rs->MoveNext();
		}
		if (!$rs->EOF) {
			$this->departamento->setDbValue($rs->fields('departamento'));
			$this->codigorude->setDbValue($rs->fields('codigorude'));
			$this->codigorude_es->setDbValue($rs->fields('codigorude_es'));
			$this->municipio->setDbValue($rs->fields('municipio'));
			$this->provincia->setDbValue($rs->fields('provincia'));
			$this->unidadeducativa->setDbValue($rs->fields('unidadeducativa'));
			$this->nombre->setDbValue($rs->fields('nombre'));
			$this->materno->setDbValue($rs->fields('materno'));
			$this->paterno->setDbValue($rs->fields('paterno'));
			$this->nrodiscapacidad->setDbValue($rs->fields('nrodiscapacidad'));
			$this->ci->setDbValue($rs->fields('ci'));
			$this->fechanacimiento->setDbValue($rs->fields('fechanacimiento'));
			$this->edad->setDbValue($rs->fields('edad'));
			$this->sexo->setDbValue($rs->fields('sexo'));
			$this->curso->setDbValue($rs->fields('curso'));
			$this->discapacidad->setDbValue($rs->fields('discapacidad'));
			$this->tipodiscapcidad->setDbValue($rs->fields('tipodiscapcidad'));
			$this->nombreinstitucion->setDbValue($rs->fields('nombreinstitucion'));
			$this->Val[1] = $this->departamento->CurrentValue;
			$this->Val[2] = $this->codigorude->CurrentValue;
			$this->Val[3] = $this->codigorude_es->CurrentValue;
			$this->Val[4] = $this->municipio->CurrentValue;
			$this->Val[5] = $this->provincia->CurrentValue;
			$this->Val[6] = $this->unidadeducativa->CurrentValue;
			$this->Val[7] = $this->nombre->CurrentValue;
			$this->Val[8] = $this->materno->CurrentValue;
			$this->Val[9] = $this->paterno->CurrentValue;
			$this->Val[10] = $this->nrodiscapacidad->CurrentValue;
			$this->Val[11] = $this->ci->CurrentValue;
			$this->Val[12] = $this->fechanacimiento->CurrentValue;
			$this->Val[13] = $this->edad->CurrentValue;
			$this->Val[14] = $this->sexo->CurrentValue;
			$this->Val[15] = $this->curso->CurrentValue;
			$this->Val[16] = $this->discapacidad->CurrentValue;
			$this->Val[17] = $this->tipodiscapcidad->CurrentValue;
			$this->Val[18] = $this->nombreinstitucion->CurrentValue;
		} else {
			$this->departamento->setDbValue("");
			$this->codigorude->setDbValue("");
			$this->codigorude_es->setDbValue("");
			$this->municipio->setDbValue("");
			$this->provincia->setDbValue("");
			$this->unidadeducativa->setDbValue("");
			$this->nombre->setDbValue("");
			$this->materno->setDbValue("");
			$this->paterno->setDbValue("");
			$this->nrodiscapacidad->setDbValue("");
			$this->ci->setDbValue("");
			$this->fechanacimiento->setDbValue("");
			$this->edad->setDbValue("");
			$this->sexo->setDbValue("");
			$this->curso->setDbValue("");
			$this->discapacidad->setDbValue("");
			$this->tipodiscapcidad->setDbValue("");
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
			// Build distinct values for departamento

			if ($popupname == 'viewestudiantecurso_departamento') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->departamento, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->departamento->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->departamento->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->departamento->setDbValue($rswrk->fields[0]);
					$this->departamento->ViewValue = @$rswrk->fields[1];
					if (is_null($this->departamento->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->departamento->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->departamento->ValueList, $this->departamento->CurrentValue, $this->departamento->ViewValue, FALSE, $this->departamento->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->departamento->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->departamento->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->departamento;
			}

			// Build distinct values for codigorude
			if ($popupname == 'viewestudiantecurso_codigorude') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->codigorude, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->codigorude->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->codigorude->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->codigorude->setDbValue($rswrk->fields[0]);
					$this->codigorude->ViewValue = @$rswrk->fields[1];
					if (is_null($this->codigorude->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->codigorude->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->codigorude->ValueList, $this->codigorude->CurrentValue, $this->codigorude->ViewValue, FALSE, $this->codigorude->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->codigorude->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->codigorude->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->codigorude;
			}

			// Build distinct values for codigorude_es
			if ($popupname == 'viewestudiantecurso_codigorude_es') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->codigorude_es, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->codigorude_es->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->codigorude_es->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->codigorude_es->setDbValue($rswrk->fields[0]);
					$this->codigorude_es->ViewValue = @$rswrk->fields[1];
					if (is_null($this->codigorude_es->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->codigorude_es->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->codigorude_es->ValueList, $this->codigorude_es->CurrentValue, $this->codigorude_es->ViewValue, FALSE, $this->codigorude_es->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->codigorude_es->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->codigorude_es->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->codigorude_es;
			}

			// Build distinct values for municipio
			if ($popupname == 'viewestudiantecurso_municipio') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->municipio, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->municipio->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->municipio->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->municipio->setDbValue($rswrk->fields[0]);
					$this->municipio->ViewValue = @$rswrk->fields[1];
					if (is_null($this->municipio->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->municipio->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->municipio->ValueList, $this->municipio->CurrentValue, $this->municipio->ViewValue, FALSE, $this->municipio->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->municipio->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->municipio->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->municipio;
			}

			// Build distinct values for provincia
			if ($popupname == 'viewestudiantecurso_provincia') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->provincia, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->provincia->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->provincia->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->provincia->setDbValue($rswrk->fields[0]);
					$this->provincia->ViewValue = @$rswrk->fields[1];
					if (is_null($this->provincia->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->provincia->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->provincia->ValueList, $this->provincia->CurrentValue, $this->provincia->ViewValue, FALSE, $this->provincia->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->provincia->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->provincia->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->provincia;
			}

			// Build distinct values for unidadeducativa
			if ($popupname == 'viewestudiantecurso_unidadeducativa') {
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

			// Build distinct values for nombre
			if ($popupname == 'viewestudiantecurso_nombre') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->nombre, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->nombre->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->nombre->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->nombre->setDbValue($rswrk->fields[0]);
					$this->nombre->ViewValue = @$rswrk->fields[1];
					if (is_null($this->nombre->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->nombre->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->nombre->ValueList, $this->nombre->CurrentValue, $this->nombre->ViewValue, FALSE, $this->nombre->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->nombre->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->nombre->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->nombre;
			}

			// Build distinct values for materno
			if ($popupname == 'viewestudiantecurso_materno') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->materno, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->materno->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->materno->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->materno->setDbValue($rswrk->fields[0]);
					$this->materno->ViewValue = @$rswrk->fields[1];
					if (is_null($this->materno->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->materno->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->materno->ValueList, $this->materno->CurrentValue, $this->materno->ViewValue, FALSE, $this->materno->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->materno->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->materno->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->materno;
			}

			// Build distinct values for paterno
			if ($popupname == 'viewestudiantecurso_paterno') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->paterno, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->paterno->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->paterno->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->paterno->setDbValue($rswrk->fields[0]);
					$this->paterno->ViewValue = @$rswrk->fields[1];
					if (is_null($this->paterno->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->paterno->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->paterno->ValueList, $this->paterno->CurrentValue, $this->paterno->ViewValue, FALSE, $this->paterno->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->paterno->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->paterno->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->paterno;
			}

			// Build distinct values for nrodiscapacidad
			if ($popupname == 'viewestudiantecurso_nrodiscapacidad') {
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

			// Build distinct values for ci
			if ($popupname == 'viewestudiantecurso_ci') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->ci, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->ci->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->ci->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->ci->setDbValue($rswrk->fields[0]);
					$this->ci->ViewValue = @$rswrk->fields[1];
					if (is_null($this->ci->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->ci->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->ci->ValueList, $this->ci->CurrentValue, $this->ci->ViewValue, FALSE, $this->ci->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->ci->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->ci->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->ci;
			}

			// Build distinct values for fechanacimiento
			if ($popupname == 'viewestudiantecurso_fechanacimiento') {
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

			// Build distinct values for edad
			if ($popupname == 'viewestudiantecurso_edad') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->edad, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->edad->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->edad->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->edad->setDbValue($rswrk->fields[0]);
					$this->edad->ViewValue = @$rswrk->fields[1];
					if (is_null($this->edad->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->edad->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->edad->ValueList, $this->edad->CurrentValue, $this->edad->ViewValue, FALSE, $this->edad->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->edad->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->edad->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->edad;
			}

			// Build distinct values for sexo
			if ($popupname == 'viewestudiantecurso_sexo') {
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

			// Build distinct values for curso
			if ($popupname == 'viewestudiantecurso_curso') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->curso, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->curso->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->curso->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->curso->setDbValue($rswrk->fields[0]);
					$this->curso->ViewValue = @$rswrk->fields[1];
					if (is_null($this->curso->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->curso->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->curso->ValueList, $this->curso->CurrentValue, $this->curso->ViewValue, FALSE, $this->curso->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->curso->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->curso->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->curso;
			}

			// Build distinct values for discapacidad
			if ($popupname == 'viewestudiantecurso_discapacidad') {
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

			// Build distinct values for tipodiscapcidad
			if ($popupname == 'viewestudiantecurso_tipodiscapcidad') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->tipodiscapcidad, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->tipodiscapcidad->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->tipodiscapcidad->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->tipodiscapcidad->setDbValue($rswrk->fields[0]);
					$this->tipodiscapcidad->ViewValue = @$rswrk->fields[1];
					if (is_null($this->tipodiscapcidad->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->tipodiscapcidad->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						ewr_SetupDistinctValues($this->tipodiscapcidad->ValueList, $this->tipodiscapcidad->CurrentValue, $this->tipodiscapcidad->ViewValue, FALSE, $this->tipodiscapcidad->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->tipodiscapcidad->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->tipodiscapcidad->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->tipodiscapcidad;
			}

			// Build distinct values for nombreinstitucion
			if ($popupname == 'viewestudiantecurso_nombreinstitucion') {
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
				$this->ClearSessionSelection('departamento');
				$this->ClearSessionSelection('codigorude');
				$this->ClearSessionSelection('codigorude_es');
				$this->ClearSessionSelection('municipio');
				$this->ClearSessionSelection('provincia');
				$this->ClearSessionSelection('unidadeducativa');
				$this->ClearSessionSelection('nombre');
				$this->ClearSessionSelection('materno');
				$this->ClearSessionSelection('paterno');
				$this->ClearSessionSelection('nrodiscapacidad');
				$this->ClearSessionSelection('ci');
				$this->ClearSessionSelection('fechanacimiento');
				$this->ClearSessionSelection('edad');
				$this->ClearSessionSelection('sexo');
				$this->ClearSessionSelection('curso');
				$this->ClearSessionSelection('discapacidad');
				$this->ClearSessionSelection('tipodiscapcidad');
				$this->ClearSessionSelection('nombreinstitucion');
				$this->ResetPager();
			}
		}

		// Load selection criteria to array
		// Get departamento selected values

		if (is_array(@$_SESSION["sel_viewestudiantecurso_departamento"])) {
			$this->LoadSelectionFromSession('departamento');
		} elseif (@$_SESSION["sel_viewestudiantecurso_departamento"] == EWR_INIT_VALUE) { // Select all
			$this->departamento->SelectionList = "";
		}

		// Get codigorude selected values
		if (is_array(@$_SESSION["sel_viewestudiantecurso_codigorude"])) {
			$this->LoadSelectionFromSession('codigorude');
		} elseif (@$_SESSION["sel_viewestudiantecurso_codigorude"] == EWR_INIT_VALUE) { // Select all
			$this->codigorude->SelectionList = "";
		}

		// Get codigorude_es selected values
		if (is_array(@$_SESSION["sel_viewestudiantecurso_codigorude_es"])) {
			$this->LoadSelectionFromSession('codigorude_es');
		} elseif (@$_SESSION["sel_viewestudiantecurso_codigorude_es"] == EWR_INIT_VALUE) { // Select all
			$this->codigorude_es->SelectionList = "";
		}

		// Get municipio selected values
		if (is_array(@$_SESSION["sel_viewestudiantecurso_municipio"])) {
			$this->LoadSelectionFromSession('municipio');
		} elseif (@$_SESSION["sel_viewestudiantecurso_municipio"] == EWR_INIT_VALUE) { // Select all
			$this->municipio->SelectionList = "";
		}

		// Get provincia selected values
		if (is_array(@$_SESSION["sel_viewestudiantecurso_provincia"])) {
			$this->LoadSelectionFromSession('provincia');
		} elseif (@$_SESSION["sel_viewestudiantecurso_provincia"] == EWR_INIT_VALUE) { // Select all
			$this->provincia->SelectionList = "";
		}

		// Get unidadeducativa selected values
		if (is_array(@$_SESSION["sel_viewestudiantecurso_unidadeducativa"])) {
			$this->LoadSelectionFromSession('unidadeducativa');
		} elseif (@$_SESSION["sel_viewestudiantecurso_unidadeducativa"] == EWR_INIT_VALUE) { // Select all
			$this->unidadeducativa->SelectionList = "";
		}

		// Get nombre selected values
		if (is_array(@$_SESSION["sel_viewestudiantecurso_nombre"])) {
			$this->LoadSelectionFromSession('nombre');
		} elseif (@$_SESSION["sel_viewestudiantecurso_nombre"] == EWR_INIT_VALUE) { // Select all
			$this->nombre->SelectionList = "";
		}

		// Get materno selected values
		if (is_array(@$_SESSION["sel_viewestudiantecurso_materno"])) {
			$this->LoadSelectionFromSession('materno');
		} elseif (@$_SESSION["sel_viewestudiantecurso_materno"] == EWR_INIT_VALUE) { // Select all
			$this->materno->SelectionList = "";
		}

		// Get paterno selected values
		if (is_array(@$_SESSION["sel_viewestudiantecurso_paterno"])) {
			$this->LoadSelectionFromSession('paterno');
		} elseif (@$_SESSION["sel_viewestudiantecurso_paterno"] == EWR_INIT_VALUE) { // Select all
			$this->paterno->SelectionList = "";
		}

		// Get nrodiscapacidad selected values
		if (is_array(@$_SESSION["sel_viewestudiantecurso_nrodiscapacidad"])) {
			$this->LoadSelectionFromSession('nrodiscapacidad');
		} elseif (@$_SESSION["sel_viewestudiantecurso_nrodiscapacidad"] == EWR_INIT_VALUE) { // Select all
			$this->nrodiscapacidad->SelectionList = "";
		}

		// Get ci selected values
		if (is_array(@$_SESSION["sel_viewestudiantecurso_ci"])) {
			$this->LoadSelectionFromSession('ci');
		} elseif (@$_SESSION["sel_viewestudiantecurso_ci"] == EWR_INIT_VALUE) { // Select all
			$this->ci->SelectionList = "";
		}

		// Get fechanacimiento selected values
		if (is_array(@$_SESSION["sel_viewestudiantecurso_fechanacimiento"])) {
			$this->LoadSelectionFromSession('fechanacimiento');
		} elseif (@$_SESSION["sel_viewestudiantecurso_fechanacimiento"] == EWR_INIT_VALUE) { // Select all
			$this->fechanacimiento->SelectionList = "";
		}

		// Get edad selected values
		if (is_array(@$_SESSION["sel_viewestudiantecurso_edad"])) {
			$this->LoadSelectionFromSession('edad');
		} elseif (@$_SESSION["sel_viewestudiantecurso_edad"] == EWR_INIT_VALUE) { // Select all
			$this->edad->SelectionList = "";
		}

		// Get sexo selected values
		if (is_array(@$_SESSION["sel_viewestudiantecurso_sexo"])) {
			$this->LoadSelectionFromSession('sexo');
		} elseif (@$_SESSION["sel_viewestudiantecurso_sexo"] == EWR_INIT_VALUE) { // Select all
			$this->sexo->SelectionList = "";
		}

		// Get curso selected values
		if (is_array(@$_SESSION["sel_viewestudiantecurso_curso"])) {
			$this->LoadSelectionFromSession('curso');
		} elseif (@$_SESSION["sel_viewestudiantecurso_curso"] == EWR_INIT_VALUE) { // Select all
			$this->curso->SelectionList = "";
		}

		// Get discapacidad selected values
		if (is_array(@$_SESSION["sel_viewestudiantecurso_discapacidad"])) {
			$this->LoadSelectionFromSession('discapacidad');
		} elseif (@$_SESSION["sel_viewestudiantecurso_discapacidad"] == EWR_INIT_VALUE) { // Select all
			$this->discapacidad->SelectionList = "";
		}

		// Get tipodiscapcidad selected values
		if (is_array(@$_SESSION["sel_viewestudiantecurso_tipodiscapcidad"])) {
			$this->LoadSelectionFromSession('tipodiscapcidad');
		} elseif (@$_SESSION["sel_viewestudiantecurso_tipodiscapcidad"] == EWR_INIT_VALUE) { // Select all
			$this->tipodiscapcidad->SelectionList = "";
		}

		// Get nombreinstitucion selected values
		if (is_array(@$_SESSION["sel_viewestudiantecurso_nombreinstitucion"])) {
			$this->LoadSelectionFromSession('nombreinstitucion');
		} elseif (@$_SESSION["sel_viewestudiantecurso_nombreinstitucion"] == EWR_INIT_VALUE) { // Select all
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

			// Get total from sql directly
			$sSql = ewr_BuildReportSql($this->getSqlSelectAgg(), $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), "", $this->Filter, "");
			$sSql = $this->getSqlAggPfx() . $sSql . $this->getSqlAggSfx();
			$rsagg = $conn->Execute($sSql);
			if ($rsagg) {
				$this->GrandCnt[1] = $this->TotCount;
				$this->GrandCnt[2] = $this->TotCount;
				$this->GrandCnt[3] = $this->TotCount;
				$this->GrandCnt[4] = $this->TotCount;
				$this->GrandCnt[5] = $this->TotCount;
				$this->GrandCnt[6] = $this->TotCount;
				$this->GrandCnt[7] = $this->TotCount;
				$this->GrandCnt[8] = $this->TotCount;
				$this->GrandCnt[9] = $this->TotCount;
				$this->GrandCnt[10] = $this->TotCount;
				$this->GrandCnt[11] = $this->TotCount;
				$this->GrandCnt[12] = $this->TotCount;
				$this->GrandCnt[13] = $this->TotCount;
				$this->GrandCnt[14] = $this->TotCount;
				$this->GrandCnt[15] = $this->TotCount;
				$this->GrandCnt[16] = $this->TotCount;
				$this->GrandCnt[16] = $rsagg->fields("cnt_discapacidad");
				$this->GrandCnt[17] = $this->TotCount;
				$this->GrandCnt[18] = $this->TotCount;
				$rsagg->Close();
				$bGotSummary = TRUE;
			}

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

			// discapacidad
			$this->discapacidad->CntViewValue = $this->discapacidad->CntValue;
			$this->discapacidad->CntViewValue = ewr_FormatNumber($this->discapacidad->CntViewValue, 0, -2, -2, -2);
			$this->discapacidad->CellAttrs["class"] = ($this->RowTotalType == EWR_ROWTOTAL_PAGE || $this->RowTotalType == EWR_ROWTOTAL_GRAND) ? "ewRptGrpAggregate" : "ewRptGrpSummary" . $this->RowGroupLevel;

			// departamento
			$this->departamento->HrefValue = "";

			// codigorude
			$this->codigorude->HrefValue = "";

			// codigorude_es
			$this->codigorude_es->HrefValue = "";

			// municipio
			$this->municipio->HrefValue = "";

			// provincia
			$this->provincia->HrefValue = "";

			// unidadeducativa
			$this->unidadeducativa->HrefValue = "";

			// nombre
			$this->nombre->HrefValue = "";

			// materno
			$this->materno->HrefValue = "";

			// paterno
			$this->paterno->HrefValue = "";

			// nrodiscapacidad
			$this->nrodiscapacidad->HrefValue = "";

			// ci
			$this->ci->HrefValue = "";

			// fechanacimiento
			$this->fechanacimiento->HrefValue = "";

			// edad
			$this->edad->HrefValue = "";

			// sexo
			$this->sexo->HrefValue = "";

			// curso
			$this->curso->HrefValue = "";

			// discapacidad
			$this->discapacidad->HrefValue = "";

			// tipodiscapcidad
			$this->tipodiscapcidad->HrefValue = "";

			// nombreinstitucion
			$this->nombreinstitucion->HrefValue = "";
		} else {
			if ($this->RowTotalType == EWR_ROWTOTAL_GROUP && $this->RowTotalSubType == EWR_ROWTOTAL_HEADER) {
			} else {
			}

			// departamento
			$this->departamento->ViewValue = $this->departamento->CurrentValue;
			$this->departamento->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// codigorude
			$this->codigorude->ViewValue = $this->codigorude->CurrentValue;
			$this->codigorude->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// codigorude_es
			$this->codigorude_es->ViewValue = $this->codigorude_es->CurrentValue;
			$this->codigorude_es->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// municipio
			$this->municipio->ViewValue = $this->municipio->CurrentValue;
			$this->municipio->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// provincia
			$this->provincia->ViewValue = $this->provincia->CurrentValue;
			$this->provincia->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// unidadeducativa
			$this->unidadeducativa->ViewValue = $this->unidadeducativa->CurrentValue;
			$this->unidadeducativa->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// nombre
			$this->nombre->ViewValue = $this->nombre->CurrentValue;
			$this->nombre->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// materno
			$this->materno->ViewValue = $this->materno->CurrentValue;
			$this->materno->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// paterno
			$this->paterno->ViewValue = $this->paterno->CurrentValue;
			$this->paterno->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

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

			// edad
			$this->edad->ViewValue = $this->edad->CurrentValue;
			$this->edad->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// sexo
			$this->sexo->ViewValue = $this->sexo->CurrentValue;
			$this->sexo->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// curso
			$this->curso->ViewValue = $this->curso->CurrentValue;
			$this->curso->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// discapacidad
			$this->discapacidad->ViewValue = $this->discapacidad->CurrentValue;
			$this->discapacidad->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// tipodiscapcidad
			$this->tipodiscapcidad->ViewValue = $this->tipodiscapcidad->CurrentValue;
			$this->tipodiscapcidad->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// nombreinstitucion
			$this->nombreinstitucion->ViewValue = $this->nombreinstitucion->CurrentValue;
			$this->nombreinstitucion->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// departamento
			$this->departamento->HrefValue = "";

			// codigorude
			$this->codigorude->HrefValue = "";

			// codigorude_es
			$this->codigorude_es->HrefValue = "";

			// municipio
			$this->municipio->HrefValue = "";

			// provincia
			$this->provincia->HrefValue = "";

			// unidadeducativa
			$this->unidadeducativa->HrefValue = "";

			// nombre
			$this->nombre->HrefValue = "";

			// materno
			$this->materno->HrefValue = "";

			// paterno
			$this->paterno->HrefValue = "";

			// nrodiscapacidad
			$this->nrodiscapacidad->HrefValue = "";

			// ci
			$this->ci->HrefValue = "";

			// fechanacimiento
			$this->fechanacimiento->HrefValue = "";

			// edad
			$this->edad->HrefValue = "";

			// sexo
			$this->sexo->HrefValue = "";

			// curso
			$this->curso->HrefValue = "";

			// discapacidad
			$this->discapacidad->HrefValue = "";

			// tipodiscapcidad
			$this->tipodiscapcidad->HrefValue = "";

			// nombreinstitucion
			$this->nombreinstitucion->HrefValue = "";
		}

		// Call Cell_Rendered event
		if ($this->RowType == EWR_ROWTYPE_TOTAL) { // Summary row

			// discapacidad
			$CurrentValue = $this->discapacidad->CntValue;
			$ViewValue = &$this->discapacidad->CntViewValue;
			$ViewAttrs = &$this->discapacidad->ViewAttrs;
			$CellAttrs = &$this->discapacidad->CellAttrs;
			$HrefValue = &$this->discapacidad->HrefValue;
			$LinkAttrs = &$this->discapacidad->LinkAttrs;
			$this->Cell_Rendered($this->discapacidad, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);
		} else {

			// departamento
			$CurrentValue = $this->departamento->CurrentValue;
			$ViewValue = &$this->departamento->ViewValue;
			$ViewAttrs = &$this->departamento->ViewAttrs;
			$CellAttrs = &$this->departamento->CellAttrs;
			$HrefValue = &$this->departamento->HrefValue;
			$LinkAttrs = &$this->departamento->LinkAttrs;
			$this->Cell_Rendered($this->departamento, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// codigorude
			$CurrentValue = $this->codigorude->CurrentValue;
			$ViewValue = &$this->codigorude->ViewValue;
			$ViewAttrs = &$this->codigorude->ViewAttrs;
			$CellAttrs = &$this->codigorude->CellAttrs;
			$HrefValue = &$this->codigorude->HrefValue;
			$LinkAttrs = &$this->codigorude->LinkAttrs;
			$this->Cell_Rendered($this->codigorude, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// codigorude_es
			$CurrentValue = $this->codigorude_es->CurrentValue;
			$ViewValue = &$this->codigorude_es->ViewValue;
			$ViewAttrs = &$this->codigorude_es->ViewAttrs;
			$CellAttrs = &$this->codigorude_es->CellAttrs;
			$HrefValue = &$this->codigorude_es->HrefValue;
			$LinkAttrs = &$this->codigorude_es->LinkAttrs;
			$this->Cell_Rendered($this->codigorude_es, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// municipio
			$CurrentValue = $this->municipio->CurrentValue;
			$ViewValue = &$this->municipio->ViewValue;
			$ViewAttrs = &$this->municipio->ViewAttrs;
			$CellAttrs = &$this->municipio->CellAttrs;
			$HrefValue = &$this->municipio->HrefValue;
			$LinkAttrs = &$this->municipio->LinkAttrs;
			$this->Cell_Rendered($this->municipio, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// provincia
			$CurrentValue = $this->provincia->CurrentValue;
			$ViewValue = &$this->provincia->ViewValue;
			$ViewAttrs = &$this->provincia->ViewAttrs;
			$CellAttrs = &$this->provincia->CellAttrs;
			$HrefValue = &$this->provincia->HrefValue;
			$LinkAttrs = &$this->provincia->LinkAttrs;
			$this->Cell_Rendered($this->provincia, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// unidadeducativa
			$CurrentValue = $this->unidadeducativa->CurrentValue;
			$ViewValue = &$this->unidadeducativa->ViewValue;
			$ViewAttrs = &$this->unidadeducativa->ViewAttrs;
			$CellAttrs = &$this->unidadeducativa->CellAttrs;
			$HrefValue = &$this->unidadeducativa->HrefValue;
			$LinkAttrs = &$this->unidadeducativa->LinkAttrs;
			$this->Cell_Rendered($this->unidadeducativa, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// nombre
			$CurrentValue = $this->nombre->CurrentValue;
			$ViewValue = &$this->nombre->ViewValue;
			$ViewAttrs = &$this->nombre->ViewAttrs;
			$CellAttrs = &$this->nombre->CellAttrs;
			$HrefValue = &$this->nombre->HrefValue;
			$LinkAttrs = &$this->nombre->LinkAttrs;
			$this->Cell_Rendered($this->nombre, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// materno
			$CurrentValue = $this->materno->CurrentValue;
			$ViewValue = &$this->materno->ViewValue;
			$ViewAttrs = &$this->materno->ViewAttrs;
			$CellAttrs = &$this->materno->CellAttrs;
			$HrefValue = &$this->materno->HrefValue;
			$LinkAttrs = &$this->materno->LinkAttrs;
			$this->Cell_Rendered($this->materno, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// paterno
			$CurrentValue = $this->paterno->CurrentValue;
			$ViewValue = &$this->paterno->ViewValue;
			$ViewAttrs = &$this->paterno->ViewAttrs;
			$CellAttrs = &$this->paterno->CellAttrs;
			$HrefValue = &$this->paterno->HrefValue;
			$LinkAttrs = &$this->paterno->LinkAttrs;
			$this->Cell_Rendered($this->paterno, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

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

			// edad
			$CurrentValue = $this->edad->CurrentValue;
			$ViewValue = &$this->edad->ViewValue;
			$ViewAttrs = &$this->edad->ViewAttrs;
			$CellAttrs = &$this->edad->CellAttrs;
			$HrefValue = &$this->edad->HrefValue;
			$LinkAttrs = &$this->edad->LinkAttrs;
			$this->Cell_Rendered($this->edad, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// sexo
			$CurrentValue = $this->sexo->CurrentValue;
			$ViewValue = &$this->sexo->ViewValue;
			$ViewAttrs = &$this->sexo->ViewAttrs;
			$CellAttrs = &$this->sexo->CellAttrs;
			$HrefValue = &$this->sexo->HrefValue;
			$LinkAttrs = &$this->sexo->LinkAttrs;
			$this->Cell_Rendered($this->sexo, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// curso
			$CurrentValue = $this->curso->CurrentValue;
			$ViewValue = &$this->curso->ViewValue;
			$ViewAttrs = &$this->curso->ViewAttrs;
			$CellAttrs = &$this->curso->CellAttrs;
			$HrefValue = &$this->curso->HrefValue;
			$LinkAttrs = &$this->curso->LinkAttrs;
			$this->Cell_Rendered($this->curso, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// discapacidad
			$CurrentValue = $this->discapacidad->CurrentValue;
			$ViewValue = &$this->discapacidad->ViewValue;
			$ViewAttrs = &$this->discapacidad->ViewAttrs;
			$CellAttrs = &$this->discapacidad->CellAttrs;
			$HrefValue = &$this->discapacidad->HrefValue;
			$LinkAttrs = &$this->discapacidad->LinkAttrs;
			$this->Cell_Rendered($this->discapacidad, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// tipodiscapcidad
			$CurrentValue = $this->tipodiscapcidad->CurrentValue;
			$ViewValue = &$this->tipodiscapcidad->ViewValue;
			$ViewAttrs = &$this->tipodiscapcidad->ViewAttrs;
			$CellAttrs = &$this->tipodiscapcidad->CellAttrs;
			$HrefValue = &$this->tipodiscapcidad->HrefValue;
			$LinkAttrs = &$this->tipodiscapcidad->LinkAttrs;
			$this->Cell_Rendered($this->tipodiscapcidad, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

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
		if ($this->departamento->Visible) $this->DtlColumnCount += 1;
		if ($this->codigorude->Visible) $this->DtlColumnCount += 1;
		if ($this->codigorude_es->Visible) $this->DtlColumnCount += 1;
		if ($this->municipio->Visible) $this->DtlColumnCount += 1;
		if ($this->provincia->Visible) $this->DtlColumnCount += 1;
		if ($this->unidadeducativa->Visible) $this->DtlColumnCount += 1;
		if ($this->nombre->Visible) $this->DtlColumnCount += 1;
		if ($this->materno->Visible) $this->DtlColumnCount += 1;
		if ($this->paterno->Visible) $this->DtlColumnCount += 1;
		if ($this->nrodiscapacidad->Visible) $this->DtlColumnCount += 1;
		if ($this->ci->Visible) $this->DtlColumnCount += 1;
		if ($this->fechanacimiento->Visible) $this->DtlColumnCount += 1;
		if ($this->edad->Visible) $this->DtlColumnCount += 1;
		if ($this->sexo->Visible) $this->DtlColumnCount += 1;
		if ($this->curso->Visible) $this->DtlColumnCount += 1;
		if ($this->discapacidad->Visible) $this->DtlColumnCount += 1;
		if ($this->tipodiscapcidad->Visible) $this->DtlColumnCount += 1;
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

			// Set/clear dropdown for field departamento
			if ($this->PopupName == 'viewestudiantecurso_departamento' && $this->PopupValue <> "") {
				if ($this->PopupValue == EWR_INIT_VALUE)
					$this->departamento->DropDownValue = EWR_ALL_VALUE;
				else
					$this->departamento->DropDownValue = $this->PopupValue;
				$bRestoreSession = FALSE; // Do not restore
			} elseif ($this->ClearExtFilter == 'viewestudiantecurso_departamento') {
				$this->SetSessionDropDownValue(EWR_INIT_VALUE, '', 'departamento');
			}

			// Clear extended filter for field codigorude
			if ($this->ClearExtFilter == 'viewestudiantecurso_codigorude')
				$this->SetSessionFilterValues('', '=', 'AND', '', '=', 'codigorude');

			// Clear extended filter for field codigorude_es
			if ($this->ClearExtFilter == 'viewestudiantecurso_codigorude_es')
				$this->SetSessionFilterValues('', '=', 'AND', '', '=', 'codigorude_es');

			// Clear extended filter for field municipio
			if ($this->ClearExtFilter == 'viewestudiantecurso_municipio')
				$this->SetSessionFilterValues('', '=', 'AND', '', '=', 'municipio');

			// Clear extended filter for field provincia
			if ($this->ClearExtFilter == 'viewestudiantecurso_provincia')
				$this->SetSessionFilterValues('', '=', 'AND', '', '=', 'provincia');

			// Clear extended filter for field unidadeducativa
			if ($this->ClearExtFilter == 'viewestudiantecurso_unidadeducativa')
				$this->SetSessionFilterValues('', '=', 'AND', '', '=', 'unidadeducativa');

			// Clear extended filter for field fechanacimiento
			if ($this->ClearExtFilter == 'viewestudiantecurso_fechanacimiento')
				$this->SetSessionFilterValues('', '=', 'AND', '', '=', 'fechanacimiento');

			// Clear extended filter for field edad
			if ($this->ClearExtFilter == 'viewestudiantecurso_edad')
				$this->SetSessionFilterValues('', '=', 'AND', '', '=', 'edad');

			// Set/clear dropdown for field sexo
			if ($this->PopupName == 'viewestudiantecurso_sexo' && $this->PopupValue <> "") {
				if ($this->PopupValue == EWR_INIT_VALUE)
					$this->sexo->DropDownValue = EWR_ALL_VALUE;
				else
					$this->sexo->DropDownValue = $this->PopupValue;
				$bRestoreSession = FALSE; // Do not restore
			} elseif ($this->ClearExtFilter == 'viewestudiantecurso_sexo') {
				$this->SetSessionDropDownValue(EWR_INIT_VALUE, '', 'sexo');
			}

			// Clear extended filter for field curso
			if ($this->ClearExtFilter == 'viewestudiantecurso_curso')
				$this->SetSessionFilterValues('', '=', 'AND', '', '=', 'curso');

			// Clear extended filter for field discapacidad
			if ($this->ClearExtFilter == 'viewestudiantecurso_discapacidad')
				$this->SetSessionFilterValues('', '=', 'AND', '', '=', 'discapacidad');

			// Clear extended filter for field nombreinstitucion
			if ($this->ClearExtFilter == 'viewestudiantecurso_nombreinstitucion')
				$this->SetSessionFilterValues('', '=', 'AND', '', '=', 'nombreinstitucion');

		// Reset search command
		} elseif (@$_GET["cmd"] == "reset") {

			// Load default values
			$this->SetSessionDropDownValue($this->departamento->DropDownValue, $this->departamento->SearchOperator, 'departamento'); // Field departamento
			$this->SetSessionFilterValues($this->codigorude->SearchValue, $this->codigorude->SearchOperator, $this->codigorude->SearchCondition, $this->codigorude->SearchValue2, $this->codigorude->SearchOperator2, 'codigorude'); // Field codigorude
			$this->SetSessionFilterValues($this->codigorude_es->SearchValue, $this->codigorude_es->SearchOperator, $this->codigorude_es->SearchCondition, $this->codigorude_es->SearchValue2, $this->codigorude_es->SearchOperator2, 'codigorude_es'); // Field codigorude_es
			$this->SetSessionFilterValues($this->municipio->SearchValue, $this->municipio->SearchOperator, $this->municipio->SearchCondition, $this->municipio->SearchValue2, $this->municipio->SearchOperator2, 'municipio'); // Field municipio
			$this->SetSessionFilterValues($this->provincia->SearchValue, $this->provincia->SearchOperator, $this->provincia->SearchCondition, $this->provincia->SearchValue2, $this->provincia->SearchOperator2, 'provincia'); // Field provincia
			$this->SetSessionFilterValues($this->unidadeducativa->SearchValue, $this->unidadeducativa->SearchOperator, $this->unidadeducativa->SearchCondition, $this->unidadeducativa->SearchValue2, $this->unidadeducativa->SearchOperator2, 'unidadeducativa'); // Field unidadeducativa
			$this->SetSessionFilterValues($this->fechanacimiento->SearchValue, $this->fechanacimiento->SearchOperator, $this->fechanacimiento->SearchCondition, $this->fechanacimiento->SearchValue2, $this->fechanacimiento->SearchOperator2, 'fechanacimiento'); // Field fechanacimiento
			$this->SetSessionFilterValues($this->edad->SearchValue, $this->edad->SearchOperator, $this->edad->SearchCondition, $this->edad->SearchValue2, $this->edad->SearchOperator2, 'edad'); // Field edad
			$this->SetSessionDropDownValue($this->sexo->DropDownValue, $this->sexo->SearchOperator, 'sexo'); // Field sexo
			$this->SetSessionFilterValues($this->curso->SearchValue, $this->curso->SearchOperator, $this->curso->SearchCondition, $this->curso->SearchValue2, $this->curso->SearchOperator2, 'curso'); // Field curso
			$this->SetSessionFilterValues($this->discapacidad->SearchValue, $this->discapacidad->SearchOperator, $this->discapacidad->SearchCondition, $this->discapacidad->SearchValue2, $this->discapacidad->SearchOperator2, 'discapacidad'); // Field discapacidad
			$this->SetSessionFilterValues($this->nombreinstitucion->SearchValue, $this->nombreinstitucion->SearchOperator, $this->nombreinstitucion->SearchCondition, $this->nombreinstitucion->SearchValue2, $this->nombreinstitucion->SearchOperator2, 'nombreinstitucion'); // Field nombreinstitucion

			//$bSetupFilter = TRUE; // No need to set up, just use default
		} else {
			$bRestoreSession = !$this->SearchCommand;

			// Field departamento
			if ($this->GetDropDownValue($this->departamento)) {
				$bSetupFilter = TRUE;
			} elseif ($this->departamento->DropDownValue <> EWR_INIT_VALUE && !isset($_SESSION['sv_viewestudiantecurso_departamento'])) {
				$bSetupFilter = TRUE;
			}

			// Field codigorude
			if ($this->GetFilterValues($this->codigorude)) {
				$bSetupFilter = TRUE;
			}

			// Field codigorude_es
			if ($this->GetFilterValues($this->codigorude_es)) {
				$bSetupFilter = TRUE;
			}

			// Field municipio
			if ($this->GetFilterValues($this->municipio)) {
				$bSetupFilter = TRUE;
			}

			// Field provincia
			if ($this->GetFilterValues($this->provincia)) {
				$bSetupFilter = TRUE;
			}

			// Field unidadeducativa
			if ($this->GetFilterValues($this->unidadeducativa)) {
				$bSetupFilter = TRUE;
			}

			// Field fechanacimiento
			if ($this->GetFilterValues($this->fechanacimiento)) {
				$bSetupFilter = TRUE;
			}

			// Field edad
			if ($this->GetFilterValues($this->edad)) {
				$bSetupFilter = TRUE;
			}

			// Field sexo
			if ($this->GetDropDownValue($this->sexo)) {
				$bSetupFilter = TRUE;
			} elseif ($this->sexo->DropDownValue <> EWR_INIT_VALUE && !isset($_SESSION['sv_viewestudiantecurso_sexo'])) {
				$bSetupFilter = TRUE;
			}

			// Field curso
			if ($this->GetFilterValues($this->curso)) {
				$bSetupFilter = TRUE;
			}

			// Field discapacidad
			if ($this->GetFilterValues($this->discapacidad)) {
				$bSetupFilter = TRUE;
			}

			// Field nombreinstitucion
			if ($this->GetFilterValues($this->nombreinstitucion)) {
				$bSetupFilter = TRUE;
			}
			if (!$this->ValidateForm()) {
				$this->setFailureMessage($grFormError);
				return $sFilter;
			}
		}

		// Restore session
		if ($bRestoreSession) {
			$this->GetSessionDropDownValue($this->departamento); // Field departamento
			$this->GetSessionFilterValues($this->codigorude); // Field codigorude
			$this->GetSessionFilterValues($this->codigorude_es); // Field codigorude_es
			$this->GetSessionFilterValues($this->municipio); // Field municipio
			$this->GetSessionFilterValues($this->provincia); // Field provincia
			$this->GetSessionFilterValues($this->unidadeducativa); // Field unidadeducativa
			$this->GetSessionFilterValues($this->fechanacimiento); // Field fechanacimiento
			$this->GetSessionFilterValues($this->edad); // Field edad
			$this->GetSessionDropDownValue($this->sexo); // Field sexo
			$this->GetSessionFilterValues($this->curso); // Field curso
			$this->GetSessionFilterValues($this->discapacidad); // Field discapacidad
			$this->GetSessionFilterValues($this->nombreinstitucion); // Field nombreinstitucion
		}

		// Call page filter validated event
		$this->Page_FilterValidated();

		// Build SQL
		$this->BuildDropDownFilter($this->departamento, $sFilter, $this->departamento->SearchOperator, FALSE, TRUE); // Field departamento
		$this->BuildExtendedFilter($this->codigorude, $sFilter, FALSE, TRUE); // Field codigorude
		$this->BuildExtendedFilter($this->codigorude_es, $sFilter, FALSE, TRUE); // Field codigorude_es
		$this->BuildExtendedFilter($this->municipio, $sFilter, FALSE, TRUE); // Field municipio
		$this->BuildExtendedFilter($this->provincia, $sFilter, FALSE, TRUE); // Field provincia
		$this->BuildExtendedFilter($this->unidadeducativa, $sFilter, FALSE, TRUE); // Field unidadeducativa
		$this->BuildExtendedFilter($this->fechanacimiento, $sFilter, FALSE, TRUE); // Field fechanacimiento
		$this->BuildExtendedFilter($this->edad, $sFilter, FALSE, TRUE); // Field edad
		$this->BuildDropDownFilter($this->sexo, $sFilter, $this->sexo->SearchOperator, FALSE, TRUE); // Field sexo
		$this->BuildExtendedFilter($this->curso, $sFilter, FALSE, TRUE); // Field curso
		$this->BuildExtendedFilter($this->discapacidad, $sFilter, FALSE, TRUE); // Field discapacidad
		$this->BuildExtendedFilter($this->nombreinstitucion, $sFilter, FALSE, TRUE); // Field nombreinstitucion

		// Save parms to session
		$this->SetSessionDropDownValue($this->departamento->DropDownValue, $this->departamento->SearchOperator, 'departamento'); // Field departamento
		$this->SetSessionFilterValues($this->codigorude->SearchValue, $this->codigorude->SearchOperator, $this->codigorude->SearchCondition, $this->codigorude->SearchValue2, $this->codigorude->SearchOperator2, 'codigorude'); // Field codigorude
		$this->SetSessionFilterValues($this->codigorude_es->SearchValue, $this->codigorude_es->SearchOperator, $this->codigorude_es->SearchCondition, $this->codigorude_es->SearchValue2, $this->codigorude_es->SearchOperator2, 'codigorude_es'); // Field codigorude_es
		$this->SetSessionFilterValues($this->municipio->SearchValue, $this->municipio->SearchOperator, $this->municipio->SearchCondition, $this->municipio->SearchValue2, $this->municipio->SearchOperator2, 'municipio'); // Field municipio
		$this->SetSessionFilterValues($this->provincia->SearchValue, $this->provincia->SearchOperator, $this->provincia->SearchCondition, $this->provincia->SearchValue2, $this->provincia->SearchOperator2, 'provincia'); // Field provincia
		$this->SetSessionFilterValues($this->unidadeducativa->SearchValue, $this->unidadeducativa->SearchOperator, $this->unidadeducativa->SearchCondition, $this->unidadeducativa->SearchValue2, $this->unidadeducativa->SearchOperator2, 'unidadeducativa'); // Field unidadeducativa
		$this->SetSessionFilterValues($this->fechanacimiento->SearchValue, $this->fechanacimiento->SearchOperator, $this->fechanacimiento->SearchCondition, $this->fechanacimiento->SearchValue2, $this->fechanacimiento->SearchOperator2, 'fechanacimiento'); // Field fechanacimiento
		$this->SetSessionFilterValues($this->edad->SearchValue, $this->edad->SearchOperator, $this->edad->SearchCondition, $this->edad->SearchValue2, $this->edad->SearchOperator2, 'edad'); // Field edad
		$this->SetSessionDropDownValue($this->sexo->DropDownValue, $this->sexo->SearchOperator, 'sexo'); // Field sexo
		$this->SetSessionFilterValues($this->curso->SearchValue, $this->curso->SearchOperator, $this->curso->SearchCondition, $this->curso->SearchValue2, $this->curso->SearchOperator2, 'curso'); // Field curso
		$this->SetSessionFilterValues($this->discapacidad->SearchValue, $this->discapacidad->SearchOperator, $this->discapacidad->SearchCondition, $this->discapacidad->SearchValue2, $this->discapacidad->SearchOperator2, 'discapacidad'); // Field discapacidad
		$this->SetSessionFilterValues($this->nombreinstitucion->SearchValue, $this->nombreinstitucion->SearchOperator, $this->nombreinstitucion->SearchCondition, $this->nombreinstitucion->SearchValue2, $this->nombreinstitucion->SearchOperator2, 'nombreinstitucion'); // Field nombreinstitucion

		// Setup filter
		if ($bSetupFilter) {

			// Field departamento
			$sWrk = "";
			$this->BuildDropDownFilter($this->departamento, $sWrk, $this->departamento->SearchOperator);
			ewr_LoadSelectionFromFilter($this->departamento, $sWrk, $this->departamento->SelectionList, $this->departamento->DropDownValue);
			$_SESSION['sel_viewestudiantecurso_departamento'] = ($this->departamento->SelectionList == "") ? EWR_INIT_VALUE : $this->departamento->SelectionList;

			// Field codigorude
			$sWrk = "";
			$this->BuildExtendedFilter($this->codigorude, $sWrk);
			ewr_LoadSelectionFromFilter($this->codigorude, $sWrk, $this->codigorude->SelectionList);
			$_SESSION['sel_viewestudiantecurso_codigorude'] = ($this->codigorude->SelectionList == "") ? EWR_INIT_VALUE : $this->codigorude->SelectionList;

			// Field codigorude_es
			$sWrk = "";
			$this->BuildExtendedFilter($this->codigorude_es, $sWrk);
			ewr_LoadSelectionFromFilter($this->codigorude_es, $sWrk, $this->codigorude_es->SelectionList);
			$_SESSION['sel_viewestudiantecurso_codigorude_es'] = ($this->codigorude_es->SelectionList == "") ? EWR_INIT_VALUE : $this->codigorude_es->SelectionList;

			// Field municipio
			$sWrk = "";
			$this->BuildExtendedFilter($this->municipio, $sWrk);
			ewr_LoadSelectionFromFilter($this->municipio, $sWrk, $this->municipio->SelectionList);
			$_SESSION['sel_viewestudiantecurso_municipio'] = ($this->municipio->SelectionList == "") ? EWR_INIT_VALUE : $this->municipio->SelectionList;

			// Field provincia
			$sWrk = "";
			$this->BuildExtendedFilter($this->provincia, $sWrk);
			ewr_LoadSelectionFromFilter($this->provincia, $sWrk, $this->provincia->SelectionList);
			$_SESSION['sel_viewestudiantecurso_provincia'] = ($this->provincia->SelectionList == "") ? EWR_INIT_VALUE : $this->provincia->SelectionList;

			// Field unidadeducativa
			$sWrk = "";
			$this->BuildExtendedFilter($this->unidadeducativa, $sWrk);
			ewr_LoadSelectionFromFilter($this->unidadeducativa, $sWrk, $this->unidadeducativa->SelectionList);
			$_SESSION['sel_viewestudiantecurso_unidadeducativa'] = ($this->unidadeducativa->SelectionList == "") ? EWR_INIT_VALUE : $this->unidadeducativa->SelectionList;

			// Field fechanacimiento
			$sWrk = "";
			$this->BuildExtendedFilter($this->fechanacimiento, $sWrk);
			ewr_LoadSelectionFromFilter($this->fechanacimiento, $sWrk, $this->fechanacimiento->SelectionList);
			$_SESSION['sel_viewestudiantecurso_fechanacimiento'] = ($this->fechanacimiento->SelectionList == "") ? EWR_INIT_VALUE : $this->fechanacimiento->SelectionList;

			// Field edad
			$sWrk = "";
			$this->BuildExtendedFilter($this->edad, $sWrk);
			ewr_LoadSelectionFromFilter($this->edad, $sWrk, $this->edad->SelectionList);
			$_SESSION['sel_viewestudiantecurso_edad'] = ($this->edad->SelectionList == "") ? EWR_INIT_VALUE : $this->edad->SelectionList;

			// Field sexo
			$sWrk = "";
			$this->BuildDropDownFilter($this->sexo, $sWrk, $this->sexo->SearchOperator);
			ewr_LoadSelectionFromFilter($this->sexo, $sWrk, $this->sexo->SelectionList, $this->sexo->DropDownValue);
			$_SESSION['sel_viewestudiantecurso_sexo'] = ($this->sexo->SelectionList == "") ? EWR_INIT_VALUE : $this->sexo->SelectionList;

			// Field curso
			$sWrk = "";
			$this->BuildExtendedFilter($this->curso, $sWrk);
			ewr_LoadSelectionFromFilter($this->curso, $sWrk, $this->curso->SelectionList);
			$_SESSION['sel_viewestudiantecurso_curso'] = ($this->curso->SelectionList == "") ? EWR_INIT_VALUE : $this->curso->SelectionList;

			// Field discapacidad
			$sWrk = "";
			$this->BuildExtendedFilter($this->discapacidad, $sWrk);
			ewr_LoadSelectionFromFilter($this->discapacidad, $sWrk, $this->discapacidad->SelectionList);
			$_SESSION['sel_viewestudiantecurso_discapacidad'] = ($this->discapacidad->SelectionList == "") ? EWR_INIT_VALUE : $this->discapacidad->SelectionList;

			// Field nombreinstitucion
			$sWrk = "";
			$this->BuildExtendedFilter($this->nombreinstitucion, $sWrk);
			ewr_LoadSelectionFromFilter($this->nombreinstitucion, $sWrk, $this->nombreinstitucion->SelectionList);
			$_SESSION['sel_viewestudiantecurso_nombreinstitucion'] = ($this->nombreinstitucion->SelectionList == "") ? EWR_INIT_VALUE : $this->nombreinstitucion->SelectionList;
		}

		// Field departamento
		ewr_LoadDropDownList($this->departamento->DropDownList, $this->departamento->DropDownValue);

		// Field sexo
		ewr_LoadDropDownList($this->sexo->DropDownList, $this->sexo->DropDownValue);
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
		$this->GetSessionValue($fld->DropDownValue, 'sv_viewestudiantecurso_' . $parm);
		$this->GetSessionValue($fld->SearchOperator, 'so_viewestudiantecurso_' . $parm);
	}

	// Get filter values from session
	function GetSessionFilterValues(&$fld) {
		$parm = substr($fld->FldVar, 2);
		$this->GetSessionValue($fld->SearchValue, 'sv_viewestudiantecurso_' . $parm);
		$this->GetSessionValue($fld->SearchOperator, 'so_viewestudiantecurso_' . $parm);
		$this->GetSessionValue($fld->SearchCondition, 'sc_viewestudiantecurso_' . $parm);
		$this->GetSessionValue($fld->SearchValue2, 'sv2_viewestudiantecurso_' . $parm);
		$this->GetSessionValue($fld->SearchOperator2, 'so2_viewestudiantecurso_' . $parm);
	}

	// Get value from session
	function GetSessionValue(&$sv, $sn) {
		if (array_key_exists($sn, $_SESSION))
			$sv = $_SESSION[$sn];
	}

	// Set dropdown value to session
	function SetSessionDropDownValue($sv, $so, $parm) {
		$_SESSION['sv_viewestudiantecurso_' . $parm] = $sv;
		$_SESSION['so_viewestudiantecurso_' . $parm] = $so;
	}

	// Set filter values to session
	function SetSessionFilterValues($sv1, $so1, $sc, $sv2, $so2, $parm) {
		$_SESSION['sv_viewestudiantecurso_' . $parm] = $sv1;
		$_SESSION['so_viewestudiantecurso_' . $parm] = $so1;
		$_SESSION['sc_viewestudiantecurso_' . $parm] = $sc;
		$_SESSION['sv2_viewestudiantecurso_' . $parm] = $sv2;
		$_SESSION['so2_viewestudiantecurso_' . $parm] = $so2;
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
		if (!ewr_CheckDateDef($this->fechanacimiento->SearchValue)) {
			if ($grFormError <> "") $grFormError .= "<br>";
			$grFormError .= $this->fechanacimiento->FldErrMsg();
		}
		if (!ewr_CheckInteger($this->edad->SearchValue)) {
			if ($grFormError <> "") $grFormError .= "<br>";
			$grFormError .= $this->edad->FldErrMsg();
		}

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
		$_SESSION["sel_viewestudiantecurso_$parm"] = "";
		$_SESSION["rf_viewestudiantecurso_$parm"] = "";
		$_SESSION["rt_viewestudiantecurso_$parm"] = "";
	}

	// Load selection from session
	function LoadSelectionFromSession($parm) {
		$fld = &$this->FieldByParm($parm);
		$fld->SelectionList = @$_SESSION["sel_viewestudiantecurso_$parm"];
		$fld->RangeFrom = @$_SESSION["rf_viewestudiantecurso_$parm"];
		$fld->RangeTo = @$_SESSION["rt_viewestudiantecurso_$parm"];
	}

	// Load default value for filters
	function LoadDefaultFilters() {
		/**
		* Set up default values for non Text filters
		*/

		// Field departamento
		$this->departamento->DefaultDropDownValue = EWR_INIT_VALUE;
		if (!$this->SearchCommand) $this->departamento->DropDownValue = $this->departamento->DefaultDropDownValue;
		$sWrk = "";
		$this->BuildDropDownFilter($this->departamento, $sWrk, $this->departamento->SearchOperator, TRUE);
		ewr_LoadSelectionFromFilter($this->departamento, $sWrk, $this->departamento->DefaultSelectionList);
		if (!$this->SearchCommand) $this->departamento->SelectionList = $this->departamento->DefaultSelectionList;

		// Field sexo
		$this->sexo->DefaultDropDownValue = EWR_INIT_VALUE;
		if (!$this->SearchCommand) $this->sexo->DropDownValue = $this->sexo->DefaultDropDownValue;
		$sWrk = "";
		$this->BuildDropDownFilter($this->sexo, $sWrk, $this->sexo->SearchOperator, TRUE);
		ewr_LoadSelectionFromFilter($this->sexo, $sWrk, $this->sexo->DefaultSelectionList);
		if (!$this->SearchCommand) $this->sexo->SelectionList = $this->sexo->DefaultSelectionList;
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

		// Field codigorude
		$this->SetDefaultExtFilter($this->codigorude, "LIKE", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->codigorude);
		$sWrk = "";
		$this->BuildExtendedFilter($this->codigorude, $sWrk, TRUE);
		ewr_LoadSelectionFromFilter($this->codigorude, $sWrk, $this->codigorude->DefaultSelectionList);
		if (!$this->SearchCommand) $this->codigorude->SelectionList = $this->codigorude->DefaultSelectionList;

		// Field codigorude_es
		$this->SetDefaultExtFilter($this->codigorude_es, "LIKE", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->codigorude_es);
		$sWrk = "";
		$this->BuildExtendedFilter($this->codigorude_es, $sWrk, TRUE);
		ewr_LoadSelectionFromFilter($this->codigorude_es, $sWrk, $this->codigorude_es->DefaultSelectionList);
		if (!$this->SearchCommand) $this->codigorude_es->SelectionList = $this->codigorude_es->DefaultSelectionList;

		// Field municipio
		$this->SetDefaultExtFilter($this->municipio, "LIKE", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->municipio);
		$sWrk = "";
		$this->BuildExtendedFilter($this->municipio, $sWrk, TRUE);
		ewr_LoadSelectionFromFilter($this->municipio, $sWrk, $this->municipio->DefaultSelectionList);
		if (!$this->SearchCommand) $this->municipio->SelectionList = $this->municipio->DefaultSelectionList;

		// Field provincia
		$this->SetDefaultExtFilter($this->provincia, "LIKE", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->provincia);
		$sWrk = "";
		$this->BuildExtendedFilter($this->provincia, $sWrk, TRUE);
		ewr_LoadSelectionFromFilter($this->provincia, $sWrk, $this->provincia->DefaultSelectionList);
		if (!$this->SearchCommand) $this->provincia->SelectionList = $this->provincia->DefaultSelectionList;

		// Field unidadeducativa
		$this->SetDefaultExtFilter($this->unidadeducativa, "LIKE", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->unidadeducativa);
		$sWrk = "";
		$this->BuildExtendedFilter($this->unidadeducativa, $sWrk, TRUE);
		ewr_LoadSelectionFromFilter($this->unidadeducativa, $sWrk, $this->unidadeducativa->DefaultSelectionList);
		if (!$this->SearchCommand) $this->unidadeducativa->SelectionList = $this->unidadeducativa->DefaultSelectionList;

		// Field fechanacimiento
		$this->SetDefaultExtFilter($this->fechanacimiento, "=", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->fechanacimiento);
		$sWrk = "";
		$this->BuildExtendedFilter($this->fechanacimiento, $sWrk, TRUE);
		ewr_LoadSelectionFromFilter($this->fechanacimiento, $sWrk, $this->fechanacimiento->DefaultSelectionList);
		if (!$this->SearchCommand) $this->fechanacimiento->SelectionList = $this->fechanacimiento->DefaultSelectionList;

		// Field edad
		$this->SetDefaultExtFilter($this->edad, "=", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->edad);
		$sWrk = "";
		$this->BuildExtendedFilter($this->edad, $sWrk, TRUE);
		ewr_LoadSelectionFromFilter($this->edad, $sWrk, $this->edad->DefaultSelectionList);
		if (!$this->SearchCommand) $this->edad->SelectionList = $this->edad->DefaultSelectionList;

		// Field curso
		$this->SetDefaultExtFilter($this->curso, "LIKE", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->curso);
		$sWrk = "";
		$this->BuildExtendedFilter($this->curso, $sWrk, TRUE);
		ewr_LoadSelectionFromFilter($this->curso, $sWrk, $this->curso->DefaultSelectionList);
		if (!$this->SearchCommand) $this->curso->SelectionList = $this->curso->DefaultSelectionList;

		// Field discapacidad
		$this->SetDefaultExtFilter($this->discapacidad, "LIKE", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->discapacidad);
		$sWrk = "";
		$this->BuildExtendedFilter($this->discapacidad, $sWrk, TRUE);
		ewr_LoadSelectionFromFilter($this->discapacidad, $sWrk, $this->discapacidad->DefaultSelectionList);
		if (!$this->SearchCommand) $this->discapacidad->SelectionList = $this->discapacidad->DefaultSelectionList;

		// Field nombreinstitucion
		$this->SetDefaultExtFilter($this->nombreinstitucion, "LIKE", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->nombreinstitucion);
		$sWrk = "";
		$this->BuildExtendedFilter($this->nombreinstitucion, $sWrk, TRUE);
		ewr_LoadSelectionFromFilter($this->nombreinstitucion, $sWrk, $this->nombreinstitucion->DefaultSelectionList);
		if (!$this->SearchCommand) $this->nombreinstitucion->SelectionList = $this->nombreinstitucion->DefaultSelectionList;
		/**
		* Set up default values for popup filters
		*/

		// Field departamento
		// $this->departamento->DefaultSelectionList = array("val1", "val2");
		// Field codigorude
		// $this->codigorude->DefaultSelectionList = array("val1", "val2");
		// Field codigorude_es
		// $this->codigorude_es->DefaultSelectionList = array("val1", "val2");
		// Field municipio
		// $this->municipio->DefaultSelectionList = array("val1", "val2");
		// Field provincia
		// $this->provincia->DefaultSelectionList = array("val1", "val2");
		// Field unidadeducativa
		// $this->unidadeducativa->DefaultSelectionList = array("val1", "val2");
		// Field nombre
		// $this->nombre->DefaultSelectionList = array("val1", "val2");
		// Field materno
		// $this->materno->DefaultSelectionList = array("val1", "val2");
		// Field paterno
		// $this->paterno->DefaultSelectionList = array("val1", "val2");
		// Field nrodiscapacidad
		// $this->nrodiscapacidad->DefaultSelectionList = array("val1", "val2");
		// Field ci
		// $this->ci->DefaultSelectionList = array("val1", "val2");
		// Field fechanacimiento
		// $this->fechanacimiento->DefaultSelectionList = array("val1", "val2");
		// Field edad
		// $this->edad->DefaultSelectionList = array("val1", "val2");
		// Field sexo
		// $this->sexo->DefaultSelectionList = array("val1", "val2");
		// Field curso
		// $this->curso->DefaultSelectionList = array("val1", "val2");
		// Field discapacidad
		// $this->discapacidad->DefaultSelectionList = array("val1", "val2");
		// Field tipodiscapcidad
		// $this->tipodiscapcidad->DefaultSelectionList = array("val1", "val2");
		// Field nombreinstitucion
		// $this->nombreinstitucion->DefaultSelectionList = array("val1", "val2");

	}

	// Check if filter applied
	function CheckFilter() {

		// Check departamento extended filter
		if ($this->NonTextFilterApplied($this->departamento))
			return TRUE;

		// Check departamento popup filter
		if (!ewr_MatchedArray($this->departamento->DefaultSelectionList, $this->departamento->SelectionList))
			return TRUE;

		// Check codigorude text filter
		if ($this->TextFilterApplied($this->codigorude))
			return TRUE;

		// Check codigorude popup filter
		if (!ewr_MatchedArray($this->codigorude->DefaultSelectionList, $this->codigorude->SelectionList))
			return TRUE;

		// Check codigorude_es text filter
		if ($this->TextFilterApplied($this->codigorude_es))
			return TRUE;

		// Check codigorude_es popup filter
		if (!ewr_MatchedArray($this->codigorude_es->DefaultSelectionList, $this->codigorude_es->SelectionList))
			return TRUE;

		// Check municipio text filter
		if ($this->TextFilterApplied($this->municipio))
			return TRUE;

		// Check municipio popup filter
		if (!ewr_MatchedArray($this->municipio->DefaultSelectionList, $this->municipio->SelectionList))
			return TRUE;

		// Check provincia text filter
		if ($this->TextFilterApplied($this->provincia))
			return TRUE;

		// Check provincia popup filter
		if (!ewr_MatchedArray($this->provincia->DefaultSelectionList, $this->provincia->SelectionList))
			return TRUE;

		// Check unidadeducativa text filter
		if ($this->TextFilterApplied($this->unidadeducativa))
			return TRUE;

		// Check unidadeducativa popup filter
		if (!ewr_MatchedArray($this->unidadeducativa->DefaultSelectionList, $this->unidadeducativa->SelectionList))
			return TRUE;

		// Check nombre popup filter
		if (!ewr_MatchedArray($this->nombre->DefaultSelectionList, $this->nombre->SelectionList))
			return TRUE;

		// Check materno popup filter
		if (!ewr_MatchedArray($this->materno->DefaultSelectionList, $this->materno->SelectionList))
			return TRUE;

		// Check paterno popup filter
		if (!ewr_MatchedArray($this->paterno->DefaultSelectionList, $this->paterno->SelectionList))
			return TRUE;

		// Check nrodiscapacidad popup filter
		if (!ewr_MatchedArray($this->nrodiscapacidad->DefaultSelectionList, $this->nrodiscapacidad->SelectionList))
			return TRUE;

		// Check ci popup filter
		if (!ewr_MatchedArray($this->ci->DefaultSelectionList, $this->ci->SelectionList))
			return TRUE;

		// Check fechanacimiento text filter
		if ($this->TextFilterApplied($this->fechanacimiento))
			return TRUE;

		// Check fechanacimiento popup filter
		if (!ewr_MatchedArray($this->fechanacimiento->DefaultSelectionList, $this->fechanacimiento->SelectionList))
			return TRUE;

		// Check edad text filter
		if ($this->TextFilterApplied($this->edad))
			return TRUE;

		// Check edad popup filter
		if (!ewr_MatchedArray($this->edad->DefaultSelectionList, $this->edad->SelectionList))
			return TRUE;

		// Check sexo extended filter
		if ($this->NonTextFilterApplied($this->sexo))
			return TRUE;

		// Check sexo popup filter
		if (!ewr_MatchedArray($this->sexo->DefaultSelectionList, $this->sexo->SelectionList))
			return TRUE;

		// Check curso text filter
		if ($this->TextFilterApplied($this->curso))
			return TRUE;

		// Check curso popup filter
		if (!ewr_MatchedArray($this->curso->DefaultSelectionList, $this->curso->SelectionList))
			return TRUE;

		// Check discapacidad text filter
		if ($this->TextFilterApplied($this->discapacidad))
			return TRUE;

		// Check discapacidad popup filter
		if (!ewr_MatchedArray($this->discapacidad->DefaultSelectionList, $this->discapacidad->SelectionList))
			return TRUE;

		// Check tipodiscapcidad popup filter
		if (!ewr_MatchedArray($this->tipodiscapcidad->DefaultSelectionList, $this->tipodiscapcidad->SelectionList))
			return TRUE;

		// Check nombreinstitucion text filter
		if ($this->TextFilterApplied($this->nombreinstitucion))
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

		// Field departamento
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildDropDownFilter($this->departamento, $sExtWrk, $this->departamento->SearchOperator);
		if (is_array($this->departamento->SelectionList))
			$sWrk = ewr_JoinArray($this->departamento->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->departamento->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field codigorude
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->codigorude, $sExtWrk);
		if (is_array($this->codigorude->SelectionList))
			$sWrk = ewr_JoinArray($this->codigorude->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->codigorude->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field codigorude_es
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->codigorude_es, $sExtWrk);
		if (is_array($this->codigorude_es->SelectionList))
			$sWrk = ewr_JoinArray($this->codigorude_es->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->codigorude_es->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field municipio
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->municipio, $sExtWrk);
		if (is_array($this->municipio->SelectionList))
			$sWrk = ewr_JoinArray($this->municipio->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->municipio->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field provincia
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->provincia, $sExtWrk);
		if (is_array($this->provincia->SelectionList))
			$sWrk = ewr_JoinArray($this->provincia->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->provincia->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field unidadeducativa
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->unidadeducativa, $sExtWrk);
		if (is_array($this->unidadeducativa->SelectionList))
			$sWrk = ewr_JoinArray($this->unidadeducativa->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->unidadeducativa->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field nombre
		$sExtWrk = "";
		$sWrk = "";
		if (is_array($this->nombre->SelectionList))
			$sWrk = ewr_JoinArray($this->nombre->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->nombre->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field materno
		$sExtWrk = "";
		$sWrk = "";
		if (is_array($this->materno->SelectionList))
			$sWrk = ewr_JoinArray($this->materno->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->materno->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field paterno
		$sExtWrk = "";
		$sWrk = "";
		if (is_array($this->paterno->SelectionList))
			$sWrk = ewr_JoinArray($this->paterno->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->paterno->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field nrodiscapacidad
		$sExtWrk = "";
		$sWrk = "";
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
		if (is_array($this->ci->SelectionList))
			$sWrk = ewr_JoinArray($this->ci->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
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
		$this->BuildExtendedFilter($this->fechanacimiento, $sExtWrk);
		if (is_array($this->fechanacimiento->SelectionList))
			$sWrk = ewr_JoinArray($this->fechanacimiento->SelectionList, ", ", EWR_DATATYPE_DATE, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->fechanacimiento->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field edad
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->edad, $sExtWrk);
		if (is_array($this->edad->SelectionList))
			$sWrk = ewr_JoinArray($this->edad->SelectionList, ", ", EWR_DATATYPE_NUMBER, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->edad->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field sexo
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildDropDownFilter($this->sexo, $sExtWrk, $this->sexo->SearchOperator);
		if (is_array($this->sexo->SelectionList))
			$sWrk = ewr_JoinArray($this->sexo->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->sexo->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field curso
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->curso, $sExtWrk);
		if (is_array($this->curso->SelectionList))
			$sWrk = ewr_JoinArray($this->curso->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->curso->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field discapacidad
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->discapacidad, $sExtWrk);
		if (is_array($this->discapacidad->SelectionList))
			$sWrk = ewr_JoinArray($this->discapacidad->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->discapacidad->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field tipodiscapcidad
		$sExtWrk = "";
		$sWrk = "";
		if (is_array($this->tipodiscapcidad->SelectionList))
			$sWrk = ewr_JoinArray($this->tipodiscapcidad->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->tipodiscapcidad->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field nombreinstitucion
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->nombreinstitucion, $sExtWrk);
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

		// Field departamento
		$sWrk = "";
		$sWrk = ($this->departamento->DropDownValue <> EWR_INIT_VALUE) ? $this->departamento->DropDownValue : "";
		if (is_array($sWrk))
			$sWrk = implode("||", $sWrk);
		if ($sWrk <> "")
			$sWrk = "\"sv_departamento\":\"" . ewr_JsEncode2($sWrk) . "\"";
		if ($sWrk == "") {
			$sWrk = ($this->departamento->SelectionList <> EWR_INIT_VALUE) ? $this->departamento->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_departamento\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field codigorude
		$sWrk = "";
		if ($this->codigorude->SearchValue <> "" || $this->codigorude->SearchValue2 <> "") {
			$sWrk = "\"sv_codigorude\":\"" . ewr_JsEncode2($this->codigorude->SearchValue) . "\"," .
				"\"so_codigorude\":\"" . ewr_JsEncode2($this->codigorude->SearchOperator) . "\"," .
				"\"sc_codigorude\":\"" . ewr_JsEncode2($this->codigorude->SearchCondition) . "\"," .
				"\"sv2_codigorude\":\"" . ewr_JsEncode2($this->codigorude->SearchValue2) . "\"," .
				"\"so2_codigorude\":\"" . ewr_JsEncode2($this->codigorude->SearchOperator2) . "\"";
		}
		if ($sWrk == "") {
			$sWrk = ($this->codigorude->SelectionList <> EWR_INIT_VALUE) ? $this->codigorude->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_codigorude\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field codigorude_es
		$sWrk = "";
		if ($this->codigorude_es->SearchValue <> "" || $this->codigorude_es->SearchValue2 <> "") {
			$sWrk = "\"sv_codigorude_es\":\"" . ewr_JsEncode2($this->codigorude_es->SearchValue) . "\"," .
				"\"so_codigorude_es\":\"" . ewr_JsEncode2($this->codigorude_es->SearchOperator) . "\"," .
				"\"sc_codigorude_es\":\"" . ewr_JsEncode2($this->codigorude_es->SearchCondition) . "\"," .
				"\"sv2_codigorude_es\":\"" . ewr_JsEncode2($this->codigorude_es->SearchValue2) . "\"," .
				"\"so2_codigorude_es\":\"" . ewr_JsEncode2($this->codigorude_es->SearchOperator2) . "\"";
		}
		if ($sWrk == "") {
			$sWrk = ($this->codigorude_es->SelectionList <> EWR_INIT_VALUE) ? $this->codigorude_es->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_codigorude_es\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field municipio
		$sWrk = "";
		if ($this->municipio->SearchValue <> "" || $this->municipio->SearchValue2 <> "") {
			$sWrk = "\"sv_municipio\":\"" . ewr_JsEncode2($this->municipio->SearchValue) . "\"," .
				"\"so_municipio\":\"" . ewr_JsEncode2($this->municipio->SearchOperator) . "\"," .
				"\"sc_municipio\":\"" . ewr_JsEncode2($this->municipio->SearchCondition) . "\"," .
				"\"sv2_municipio\":\"" . ewr_JsEncode2($this->municipio->SearchValue2) . "\"," .
				"\"so2_municipio\":\"" . ewr_JsEncode2($this->municipio->SearchOperator2) . "\"";
		}
		if ($sWrk == "") {
			$sWrk = ($this->municipio->SelectionList <> EWR_INIT_VALUE) ? $this->municipio->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_municipio\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field provincia
		$sWrk = "";
		if ($this->provincia->SearchValue <> "" || $this->provincia->SearchValue2 <> "") {
			$sWrk = "\"sv_provincia\":\"" . ewr_JsEncode2($this->provincia->SearchValue) . "\"," .
				"\"so_provincia\":\"" . ewr_JsEncode2($this->provincia->SearchOperator) . "\"," .
				"\"sc_provincia\":\"" . ewr_JsEncode2($this->provincia->SearchCondition) . "\"," .
				"\"sv2_provincia\":\"" . ewr_JsEncode2($this->provincia->SearchValue2) . "\"," .
				"\"so2_provincia\":\"" . ewr_JsEncode2($this->provincia->SearchOperator2) . "\"";
		}
		if ($sWrk == "") {
			$sWrk = ($this->provincia->SelectionList <> EWR_INIT_VALUE) ? $this->provincia->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_provincia\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field unidadeducativa
		$sWrk = "";
		if ($this->unidadeducativa->SearchValue <> "" || $this->unidadeducativa->SearchValue2 <> "") {
			$sWrk = "\"sv_unidadeducativa\":\"" . ewr_JsEncode2($this->unidadeducativa->SearchValue) . "\"," .
				"\"so_unidadeducativa\":\"" . ewr_JsEncode2($this->unidadeducativa->SearchOperator) . "\"," .
				"\"sc_unidadeducativa\":\"" . ewr_JsEncode2($this->unidadeducativa->SearchCondition) . "\"," .
				"\"sv2_unidadeducativa\":\"" . ewr_JsEncode2($this->unidadeducativa->SearchValue2) . "\"," .
				"\"so2_unidadeducativa\":\"" . ewr_JsEncode2($this->unidadeducativa->SearchOperator2) . "\"";
		}
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

		// Field nombre
		$sWrk = "";
		if ($sWrk == "") {
			$sWrk = ($this->nombre->SelectionList <> EWR_INIT_VALUE) ? $this->nombre->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_nombre\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field materno
		$sWrk = "";
		if ($sWrk == "") {
			$sWrk = ($this->materno->SelectionList <> EWR_INIT_VALUE) ? $this->materno->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_materno\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field paterno
		$sWrk = "";
		if ($sWrk == "") {
			$sWrk = ($this->paterno->SelectionList <> EWR_INIT_VALUE) ? $this->paterno->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_paterno\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field nrodiscapacidad
		$sWrk = "";
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
		if ($sWrk == "") {
			$sWrk = ($this->ci->SelectionList <> EWR_INIT_VALUE) ? $this->ci->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_ci\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field fechanacimiento
		$sWrk = "";
		if ($this->fechanacimiento->SearchValue <> "" || $this->fechanacimiento->SearchValue2 <> "") {
			$sWrk = "\"sv_fechanacimiento\":\"" . ewr_JsEncode2($this->fechanacimiento->SearchValue) . "\"," .
				"\"so_fechanacimiento\":\"" . ewr_JsEncode2($this->fechanacimiento->SearchOperator) . "\"," .
				"\"sc_fechanacimiento\":\"" . ewr_JsEncode2($this->fechanacimiento->SearchCondition) . "\"," .
				"\"sv2_fechanacimiento\":\"" . ewr_JsEncode2($this->fechanacimiento->SearchValue2) . "\"," .
				"\"so2_fechanacimiento\":\"" . ewr_JsEncode2($this->fechanacimiento->SearchOperator2) . "\"";
		}
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

		// Field edad
		$sWrk = "";
		if ($this->edad->SearchValue <> "" || $this->edad->SearchValue2 <> "") {
			$sWrk = "\"sv_edad\":\"" . ewr_JsEncode2($this->edad->SearchValue) . "\"," .
				"\"so_edad\":\"" . ewr_JsEncode2($this->edad->SearchOperator) . "\"," .
				"\"sc_edad\":\"" . ewr_JsEncode2($this->edad->SearchCondition) . "\"," .
				"\"sv2_edad\":\"" . ewr_JsEncode2($this->edad->SearchValue2) . "\"," .
				"\"so2_edad\":\"" . ewr_JsEncode2($this->edad->SearchOperator2) . "\"";
		}
		if ($sWrk == "") {
			$sWrk = ($this->edad->SelectionList <> EWR_INIT_VALUE) ? $this->edad->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_edad\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field sexo
		$sWrk = "";
		$sWrk = ($this->sexo->DropDownValue <> EWR_INIT_VALUE) ? $this->sexo->DropDownValue : "";
		if (is_array($sWrk))
			$sWrk = implode("||", $sWrk);
		if ($sWrk <> "")
			$sWrk = "\"sv_sexo\":\"" . ewr_JsEncode2($sWrk) . "\"";
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

		// Field curso
		$sWrk = "";
		if ($this->curso->SearchValue <> "" || $this->curso->SearchValue2 <> "") {
			$sWrk = "\"sv_curso\":\"" . ewr_JsEncode2($this->curso->SearchValue) . "\"," .
				"\"so_curso\":\"" . ewr_JsEncode2($this->curso->SearchOperator) . "\"," .
				"\"sc_curso\":\"" . ewr_JsEncode2($this->curso->SearchCondition) . "\"," .
				"\"sv2_curso\":\"" . ewr_JsEncode2($this->curso->SearchValue2) . "\"," .
				"\"so2_curso\":\"" . ewr_JsEncode2($this->curso->SearchOperator2) . "\"";
		}
		if ($sWrk == "") {
			$sWrk = ($this->curso->SelectionList <> EWR_INIT_VALUE) ? $this->curso->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_curso\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field discapacidad
		$sWrk = "";
		if ($this->discapacidad->SearchValue <> "" || $this->discapacidad->SearchValue2 <> "") {
			$sWrk = "\"sv_discapacidad\":\"" . ewr_JsEncode2($this->discapacidad->SearchValue) . "\"," .
				"\"so_discapacidad\":\"" . ewr_JsEncode2($this->discapacidad->SearchOperator) . "\"," .
				"\"sc_discapacidad\":\"" . ewr_JsEncode2($this->discapacidad->SearchCondition) . "\"," .
				"\"sv2_discapacidad\":\"" . ewr_JsEncode2($this->discapacidad->SearchValue2) . "\"," .
				"\"so2_discapacidad\":\"" . ewr_JsEncode2($this->discapacidad->SearchOperator2) . "\"";
		}
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

		// Field tipodiscapcidad
		$sWrk = "";
		if ($sWrk == "") {
			$sWrk = ($this->tipodiscapcidad->SelectionList <> EWR_INIT_VALUE) ? $this->tipodiscapcidad->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_tipodiscapcidad\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field nombreinstitucion
		$sWrk = "";
		if ($this->nombreinstitucion->SearchValue <> "" || $this->nombreinstitucion->SearchValue2 <> "") {
			$sWrk = "\"sv_nombreinstitucion\":\"" . ewr_JsEncode2($this->nombreinstitucion->SearchValue) . "\"," .
				"\"so_nombreinstitucion\":\"" . ewr_JsEncode2($this->nombreinstitucion->SearchOperator) . "\"," .
				"\"sc_nombreinstitucion\":\"" . ewr_JsEncode2($this->nombreinstitucion->SearchCondition) . "\"," .
				"\"sv2_nombreinstitucion\":\"" . ewr_JsEncode2($this->nombreinstitucion->SearchValue2) . "\"," .
				"\"so2_nombreinstitucion\":\"" . ewr_JsEncode2($this->nombreinstitucion->SearchOperator2) . "\"";
		}
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

		// Field departamento
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_departamento", $filter)) {
			$sWrk = $filter["sv_departamento"];
			if (strpos($sWrk, "||") !== FALSE)
				$sWrk = explode("||", $sWrk);
			$this->SetSessionDropDownValue($sWrk, @$filter["so_departamento"], "departamento");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_departamento", $filter)) {
			$sWrk = $filter["sel_departamento"];
			$sWrk = explode("||", $sWrk);
			$this->departamento->SelectionList = $sWrk;
			$_SESSION["sel_viewestudiantecurso_departamento"] = $sWrk;
			$this->SetSessionDropDownValue(EWR_INIT_VALUE, "", "departamento"); // Clear drop down
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionDropDownValue(EWR_INIT_VALUE, "", "departamento");
			$this->departamento->SelectionList = "";
			$_SESSION["sel_viewestudiantecurso_departamento"] = "";
		}

		// Field codigorude
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_codigorude", $filter) || array_key_exists("so_codigorude", $filter) ||
			array_key_exists("sc_codigorude", $filter) ||
			array_key_exists("sv2_codigorude", $filter) || array_key_exists("so2_codigorude", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_codigorude"], @$filter["so_codigorude"], @$filter["sc_codigorude"], @$filter["sv2_codigorude"], @$filter["so2_codigorude"], "codigorude");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_codigorude", $filter)) {
			$sWrk = $filter["sel_codigorude"];
			$sWrk = explode("||", $sWrk);
			$this->codigorude->SelectionList = $sWrk;
			$_SESSION["sel_viewestudiantecurso_codigorude"] = $sWrk;
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "codigorude"); // Clear extended filter
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "codigorude");
			$this->codigorude->SelectionList = "";
			$_SESSION["sel_viewestudiantecurso_codigorude"] = "";
		}

		// Field codigorude_es
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_codigorude_es", $filter) || array_key_exists("so_codigorude_es", $filter) ||
			array_key_exists("sc_codigorude_es", $filter) ||
			array_key_exists("sv2_codigorude_es", $filter) || array_key_exists("so2_codigorude_es", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_codigorude_es"], @$filter["so_codigorude_es"], @$filter["sc_codigorude_es"], @$filter["sv2_codigorude_es"], @$filter["so2_codigorude_es"], "codigorude_es");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_codigorude_es", $filter)) {
			$sWrk = $filter["sel_codigorude_es"];
			$sWrk = explode("||", $sWrk);
			$this->codigorude_es->SelectionList = $sWrk;
			$_SESSION["sel_viewestudiantecurso_codigorude_es"] = $sWrk;
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "codigorude_es"); // Clear extended filter
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "codigorude_es");
			$this->codigorude_es->SelectionList = "";
			$_SESSION["sel_viewestudiantecurso_codigorude_es"] = "";
		}

		// Field municipio
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_municipio", $filter) || array_key_exists("so_municipio", $filter) ||
			array_key_exists("sc_municipio", $filter) ||
			array_key_exists("sv2_municipio", $filter) || array_key_exists("so2_municipio", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_municipio"], @$filter["so_municipio"], @$filter["sc_municipio"], @$filter["sv2_municipio"], @$filter["so2_municipio"], "municipio");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_municipio", $filter)) {
			$sWrk = $filter["sel_municipio"];
			$sWrk = explode("||", $sWrk);
			$this->municipio->SelectionList = $sWrk;
			$_SESSION["sel_viewestudiantecurso_municipio"] = $sWrk;
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "municipio"); // Clear extended filter
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "municipio");
			$this->municipio->SelectionList = "";
			$_SESSION["sel_viewestudiantecurso_municipio"] = "";
		}

		// Field provincia
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_provincia", $filter) || array_key_exists("so_provincia", $filter) ||
			array_key_exists("sc_provincia", $filter) ||
			array_key_exists("sv2_provincia", $filter) || array_key_exists("so2_provincia", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_provincia"], @$filter["so_provincia"], @$filter["sc_provincia"], @$filter["sv2_provincia"], @$filter["so2_provincia"], "provincia");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_provincia", $filter)) {
			$sWrk = $filter["sel_provincia"];
			$sWrk = explode("||", $sWrk);
			$this->provincia->SelectionList = $sWrk;
			$_SESSION["sel_viewestudiantecurso_provincia"] = $sWrk;
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "provincia"); // Clear extended filter
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "provincia");
			$this->provincia->SelectionList = "";
			$_SESSION["sel_viewestudiantecurso_provincia"] = "";
		}

		// Field unidadeducativa
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_unidadeducativa", $filter) || array_key_exists("so_unidadeducativa", $filter) ||
			array_key_exists("sc_unidadeducativa", $filter) ||
			array_key_exists("sv2_unidadeducativa", $filter) || array_key_exists("so2_unidadeducativa", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_unidadeducativa"], @$filter["so_unidadeducativa"], @$filter["sc_unidadeducativa"], @$filter["sv2_unidadeducativa"], @$filter["so2_unidadeducativa"], "unidadeducativa");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_unidadeducativa", $filter)) {
			$sWrk = $filter["sel_unidadeducativa"];
			$sWrk = explode("||", $sWrk);
			$this->unidadeducativa->SelectionList = $sWrk;
			$_SESSION["sel_viewestudiantecurso_unidadeducativa"] = $sWrk;
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "unidadeducativa"); // Clear extended filter
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "unidadeducativa");
			$this->unidadeducativa->SelectionList = "";
			$_SESSION["sel_viewestudiantecurso_unidadeducativa"] = "";
		}

		// Field nombre
		$bRestoreFilter = FALSE;
		if (array_key_exists("sel_nombre", $filter)) {
			$sWrk = $filter["sel_nombre"];
			$sWrk = explode("||", $sWrk);
			$this->nombre->SelectionList = $sWrk;
			$_SESSION["sel_viewestudiantecurso_nombre"] = $sWrk;
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
		}

		// Field materno
		$bRestoreFilter = FALSE;
		if (array_key_exists("sel_materno", $filter)) {
			$sWrk = $filter["sel_materno"];
			$sWrk = explode("||", $sWrk);
			$this->materno->SelectionList = $sWrk;
			$_SESSION["sel_viewestudiantecurso_materno"] = $sWrk;
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
		}

		// Field paterno
		$bRestoreFilter = FALSE;
		if (array_key_exists("sel_paterno", $filter)) {
			$sWrk = $filter["sel_paterno"];
			$sWrk = explode("||", $sWrk);
			$this->paterno->SelectionList = $sWrk;
			$_SESSION["sel_viewestudiantecurso_paterno"] = $sWrk;
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
		}

		// Field nrodiscapacidad
		$bRestoreFilter = FALSE;
		if (array_key_exists("sel_nrodiscapacidad", $filter)) {
			$sWrk = $filter["sel_nrodiscapacidad"];
			$sWrk = explode("||", $sWrk);
			$this->nrodiscapacidad->SelectionList = $sWrk;
			$_SESSION["sel_viewestudiantecurso_nrodiscapacidad"] = $sWrk;
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
		}

		// Field ci
		$bRestoreFilter = FALSE;
		if (array_key_exists("sel_ci", $filter)) {
			$sWrk = $filter["sel_ci"];
			$sWrk = explode("||", $sWrk);
			$this->ci->SelectionList = $sWrk;
			$_SESSION["sel_viewestudiantecurso_ci"] = $sWrk;
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
		}

		// Field fechanacimiento
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_fechanacimiento", $filter) || array_key_exists("so_fechanacimiento", $filter) ||
			array_key_exists("sc_fechanacimiento", $filter) ||
			array_key_exists("sv2_fechanacimiento", $filter) || array_key_exists("so2_fechanacimiento", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_fechanacimiento"], @$filter["so_fechanacimiento"], @$filter["sc_fechanacimiento"], @$filter["sv2_fechanacimiento"], @$filter["so2_fechanacimiento"], "fechanacimiento");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_fechanacimiento", $filter)) {
			$sWrk = $filter["sel_fechanacimiento"];
			$sWrk = explode("||", $sWrk);
			$this->fechanacimiento->SelectionList = $sWrk;
			$_SESSION["sel_viewestudiantecurso_fechanacimiento"] = $sWrk;
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "fechanacimiento"); // Clear extended filter
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "fechanacimiento");
			$this->fechanacimiento->SelectionList = "";
			$_SESSION["sel_viewestudiantecurso_fechanacimiento"] = "";
		}

		// Field edad
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_edad", $filter) || array_key_exists("so_edad", $filter) ||
			array_key_exists("sc_edad", $filter) ||
			array_key_exists("sv2_edad", $filter) || array_key_exists("so2_edad", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_edad"], @$filter["so_edad"], @$filter["sc_edad"], @$filter["sv2_edad"], @$filter["so2_edad"], "edad");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_edad", $filter)) {
			$sWrk = $filter["sel_edad"];
			$sWrk = explode("||", $sWrk);
			$this->edad->SelectionList = $sWrk;
			$_SESSION["sel_viewestudiantecurso_edad"] = $sWrk;
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "edad"); // Clear extended filter
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "edad");
			$this->edad->SelectionList = "";
			$_SESSION["sel_viewestudiantecurso_edad"] = "";
		}

		// Field sexo
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_sexo", $filter)) {
			$sWrk = $filter["sv_sexo"];
			if (strpos($sWrk, "||") !== FALSE)
				$sWrk = explode("||", $sWrk);
			$this->SetSessionDropDownValue($sWrk, @$filter["so_sexo"], "sexo");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_sexo", $filter)) {
			$sWrk = $filter["sel_sexo"];
			$sWrk = explode("||", $sWrk);
			$this->sexo->SelectionList = $sWrk;
			$_SESSION["sel_viewestudiantecurso_sexo"] = $sWrk;
			$this->SetSessionDropDownValue(EWR_INIT_VALUE, "", "sexo"); // Clear drop down
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionDropDownValue(EWR_INIT_VALUE, "", "sexo");
			$this->sexo->SelectionList = "";
			$_SESSION["sel_viewestudiantecurso_sexo"] = "";
		}

		// Field curso
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_curso", $filter) || array_key_exists("so_curso", $filter) ||
			array_key_exists("sc_curso", $filter) ||
			array_key_exists("sv2_curso", $filter) || array_key_exists("so2_curso", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_curso"], @$filter["so_curso"], @$filter["sc_curso"], @$filter["sv2_curso"], @$filter["so2_curso"], "curso");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_curso", $filter)) {
			$sWrk = $filter["sel_curso"];
			$sWrk = explode("||", $sWrk);
			$this->curso->SelectionList = $sWrk;
			$_SESSION["sel_viewestudiantecurso_curso"] = $sWrk;
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "curso"); // Clear extended filter
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "curso");
			$this->curso->SelectionList = "";
			$_SESSION["sel_viewestudiantecurso_curso"] = "";
		}

		// Field discapacidad
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_discapacidad", $filter) || array_key_exists("so_discapacidad", $filter) ||
			array_key_exists("sc_discapacidad", $filter) ||
			array_key_exists("sv2_discapacidad", $filter) || array_key_exists("so2_discapacidad", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_discapacidad"], @$filter["so_discapacidad"], @$filter["sc_discapacidad"], @$filter["sv2_discapacidad"], @$filter["so2_discapacidad"], "discapacidad");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_discapacidad", $filter)) {
			$sWrk = $filter["sel_discapacidad"];
			$sWrk = explode("||", $sWrk);
			$this->discapacidad->SelectionList = $sWrk;
			$_SESSION["sel_viewestudiantecurso_discapacidad"] = $sWrk;
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "discapacidad"); // Clear extended filter
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "discapacidad");
			$this->discapacidad->SelectionList = "";
			$_SESSION["sel_viewestudiantecurso_discapacidad"] = "";
		}

		// Field tipodiscapcidad
		$bRestoreFilter = FALSE;
		if (array_key_exists("sel_tipodiscapcidad", $filter)) {
			$sWrk = $filter["sel_tipodiscapcidad"];
			$sWrk = explode("||", $sWrk);
			$this->tipodiscapcidad->SelectionList = $sWrk;
			$_SESSION["sel_viewestudiantecurso_tipodiscapcidad"] = $sWrk;
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
		}

		// Field nombreinstitucion
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_nombreinstitucion", $filter) || array_key_exists("so_nombreinstitucion", $filter) ||
			array_key_exists("sc_nombreinstitucion", $filter) ||
			array_key_exists("sv2_nombreinstitucion", $filter) || array_key_exists("so2_nombreinstitucion", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_nombreinstitucion"], @$filter["so_nombreinstitucion"], @$filter["sc_nombreinstitucion"], @$filter["sv2_nombreinstitucion"], @$filter["so2_nombreinstitucion"], "nombreinstitucion");
			$bRestoreFilter = TRUE;
		}
		if (array_key_exists("sel_nombreinstitucion", $filter)) {
			$sWrk = $filter["sel_nombreinstitucion"];
			$sWrk = explode("||", $sWrk);
			$this->nombreinstitucion->SelectionList = $sWrk;
			$_SESSION["sel_viewestudiantecurso_nombreinstitucion"] = $sWrk;
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "nombreinstitucion"); // Clear extended filter
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "nombreinstitucion");
			$this->nombreinstitucion->SelectionList = "";
			$_SESSION["sel_viewestudiantecurso_nombreinstitucion"] = "";
		}
		return TRUE;
	}

	// Return popup filter
	function GetPopupFilter() {
		$sWrk = "";
		if ($this->DrillDown)
			return "";
		if (!$this->DropDownFilterExist($this->departamento, $this->departamento->SearchOperator)) {
			if (is_array($this->departamento->SelectionList)) {
				$sFilter = ewr_FilterSql($this->departamento, "`departamento`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->departamento, $sFilter, "popup");
				$this->departamento->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
		if (!$this->ExtendedFilterExist($this->codigorude)) {
			if (is_array($this->codigorude->SelectionList)) {
				$sFilter = ewr_FilterSql($this->codigorude, "`codigorude`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->codigorude, $sFilter, "popup");
				$this->codigorude->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
		if (!$this->ExtendedFilterExist($this->codigorude_es)) {
			if (is_array($this->codigorude_es->SelectionList)) {
				$sFilter = ewr_FilterSql($this->codigorude_es, "`codigorude_es`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->codigorude_es, $sFilter, "popup");
				$this->codigorude_es->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
		if (!$this->ExtendedFilterExist($this->municipio)) {
			if (is_array($this->municipio->SelectionList)) {
				$sFilter = ewr_FilterSql($this->municipio, "`municipio`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->municipio, $sFilter, "popup");
				$this->municipio->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
		if (!$this->ExtendedFilterExist($this->provincia)) {
			if (is_array($this->provincia->SelectionList)) {
				$sFilter = ewr_FilterSql($this->provincia, "`provincia`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->provincia, $sFilter, "popup");
				$this->provincia->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
		if (!$this->ExtendedFilterExist($this->unidadeducativa)) {
			if (is_array($this->unidadeducativa->SelectionList)) {
				$sFilter = ewr_FilterSql($this->unidadeducativa, "`unidadeducativa`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->unidadeducativa, $sFilter, "popup");
				$this->unidadeducativa->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
			if (is_array($this->nombre->SelectionList)) {
				$sFilter = ewr_FilterSql($this->nombre, "`nombre`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->nombre, $sFilter, "popup");
				$this->nombre->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
			if (is_array($this->materno->SelectionList)) {
				$sFilter = ewr_FilterSql($this->materno, "`materno`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->materno, $sFilter, "popup");
				$this->materno->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
			if (is_array($this->paterno->SelectionList)) {
				$sFilter = ewr_FilterSql($this->paterno, "`paterno`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->paterno, $sFilter, "popup");
				$this->paterno->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
			if (is_array($this->nrodiscapacidad->SelectionList)) {
				$sFilter = ewr_FilterSql($this->nrodiscapacidad, "`nrodiscapacidad`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->nrodiscapacidad, $sFilter, "popup");
				$this->nrodiscapacidad->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
			if (is_array($this->ci->SelectionList)) {
				$sFilter = ewr_FilterSql($this->ci, "`ci`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->ci, $sFilter, "popup");
				$this->ci->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		if (!$this->ExtendedFilterExist($this->fechanacimiento)) {
			if (is_array($this->fechanacimiento->SelectionList)) {
				$sFilter = ewr_FilterSql($this->fechanacimiento, "`fechanacimiento`", EWR_DATATYPE_DATE, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->fechanacimiento, $sFilter, "popup");
				$this->fechanacimiento->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
		if (!$this->ExtendedFilterExist($this->edad)) {
			if (is_array($this->edad->SelectionList)) {
				$sFilter = ewr_FilterSql($this->edad, "`edad`", EWR_DATATYPE_NUMBER, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->edad, $sFilter, "popup");
				$this->edad->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
		if (!$this->DropDownFilterExist($this->sexo, $this->sexo->SearchOperator)) {
			if (is_array($this->sexo->SelectionList)) {
				$sFilter = ewr_FilterSql($this->sexo, "`sexo`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->sexo, $sFilter, "popup");
				$this->sexo->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
		if (!$this->ExtendedFilterExist($this->curso)) {
			if (is_array($this->curso->SelectionList)) {
				$sFilter = ewr_FilterSql($this->curso, "`curso`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->curso, $sFilter, "popup");
				$this->curso->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
		if (!$this->ExtendedFilterExist($this->discapacidad)) {
			if (is_array($this->discapacidad->SelectionList)) {
				$sFilter = ewr_FilterSql($this->discapacidad, "`discapacidad`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->discapacidad, $sFilter, "popup");
				$this->discapacidad->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		}
			if (is_array($this->tipodiscapcidad->SelectionList)) {
				$sFilter = ewr_FilterSql($this->tipodiscapcidad, "`tipodiscapcidad`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->tipodiscapcidad, $sFilter, "popup");
				$this->tipodiscapcidad->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		if (!$this->ExtendedFilterExist($this->nombreinstitucion)) {
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
			$sql = @$post["departamento"];

			// Fusioncharts do not support "-" in drill down link. The encrypted data uses "$" instead of "-". Change back to "-" for decrypt.
			// https://www.fusioncharts.com/dev/advanced-chart-configurations/drill-down/using-javascript-functions-as-links.html
			// - Special characters like (, ), -, % and , cannot be passed as a parameter while function call.

			$sql = str_replace("$", "-", $sql);
			$sql = ewr_Decrypt($sql);
			$sql = str_replace("@departamento", "`departamento`", $sql);
			if ($sql <> "") {
				if ($filter <> "") $filter .= " AND ";
				$filter .= $sql;
				if ($sql <> "1=1")
					$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->departamento->FldCaption() . "</span><span class=\"ewFilterValue\">$sql</span></div>";
			}
			$sql = @$post["codigorude"];

			// Fusioncharts do not support "-" in drill down link. The encrypted data uses "$" instead of "-". Change back to "-" for decrypt.
			// https://www.fusioncharts.com/dev/advanced-chart-configurations/drill-down/using-javascript-functions-as-links.html
			// - Special characters like (, ), -, % and , cannot be passed as a parameter while function call.

			$sql = str_replace("$", "-", $sql);
			$sql = ewr_Decrypt($sql);
			$sql = str_replace("@codigorude", "`codigorude`", $sql);
			if ($sql <> "") {
				if ($filter <> "") $filter .= " AND ";
				$filter .= $sql;
				if ($sql <> "1=1")
					$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->codigorude->FldCaption() . "</span><span class=\"ewFilterValue\">$sql</span></div>";
			}
			$sql = @$post["codigorude_es"];

			// Fusioncharts do not support "-" in drill down link. The encrypted data uses "$" instead of "-". Change back to "-" for decrypt.
			// https://www.fusioncharts.com/dev/advanced-chart-configurations/drill-down/using-javascript-functions-as-links.html
			// - Special characters like (, ), -, % and , cannot be passed as a parameter while function call.

			$sql = str_replace("$", "-", $sql);
			$sql = ewr_Decrypt($sql);
			$sql = str_replace("@codigorude_es", "`codigorude_es`", $sql);
			if ($sql <> "") {
				if ($filter <> "") $filter .= " AND ";
				$filter .= $sql;
				if ($sql <> "1=1")
					$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->codigorude_es->FldCaption() . "</span><span class=\"ewFilterValue\">$sql</span></div>";
			}
			$sql = @$post["municipio"];

			// Fusioncharts do not support "-" in drill down link. The encrypted data uses "$" instead of "-". Change back to "-" for decrypt.
			// https://www.fusioncharts.com/dev/advanced-chart-configurations/drill-down/using-javascript-functions-as-links.html
			// - Special characters like (, ), -, % and , cannot be passed as a parameter while function call.

			$sql = str_replace("$", "-", $sql);
			$sql = ewr_Decrypt($sql);
			$sql = str_replace("@municipio", "`municipio`", $sql);
			if ($sql <> "") {
				if ($filter <> "") $filter .= " AND ";
				$filter .= $sql;
				if ($sql <> "1=1")
					$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->municipio->FldCaption() . "</span><span class=\"ewFilterValue\">$sql</span></div>";
			}
			$sql = @$post["provincia"];

			// Fusioncharts do not support "-" in drill down link. The encrypted data uses "$" instead of "-". Change back to "-" for decrypt.
			// https://www.fusioncharts.com/dev/advanced-chart-configurations/drill-down/using-javascript-functions-as-links.html
			// - Special characters like (, ), -, % and , cannot be passed as a parameter while function call.

			$sql = str_replace("$", "-", $sql);
			$sql = ewr_Decrypt($sql);
			$sql = str_replace("@provincia", "`provincia`", $sql);
			if ($sql <> "") {
				if ($filter <> "") $filter .= " AND ";
				$filter .= $sql;
				if ($sql <> "1=1")
					$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->provincia->FldCaption() . "</span><span class=\"ewFilterValue\">$sql</span></div>";
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
			$sql = @$post["nombre"];

			// Fusioncharts do not support "-" in drill down link. The encrypted data uses "$" instead of "-". Change back to "-" for decrypt.
			// https://www.fusioncharts.com/dev/advanced-chart-configurations/drill-down/using-javascript-functions-as-links.html
			// - Special characters like (, ), -, % and , cannot be passed as a parameter while function call.

			$sql = str_replace("$", "-", $sql);
			$sql = ewr_Decrypt($sql);
			$sql = str_replace("@nombre", "`nombre`", $sql);
			if ($sql <> "") {
				if ($filter <> "") $filter .= " AND ";
				$filter .= $sql;
				if ($sql <> "1=1")
					$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->nombre->FldCaption() . "</span><span class=\"ewFilterValue\">$sql</span></div>";
			}
			$sql = @$post["materno"];

			// Fusioncharts do not support "-" in drill down link. The encrypted data uses "$" instead of "-". Change back to "-" for decrypt.
			// https://www.fusioncharts.com/dev/advanced-chart-configurations/drill-down/using-javascript-functions-as-links.html
			// - Special characters like (, ), -, % and , cannot be passed as a parameter while function call.

			$sql = str_replace("$", "-", $sql);
			$sql = ewr_Decrypt($sql);
			$sql = str_replace("@materno", "`materno`", $sql);
			if ($sql <> "") {
				if ($filter <> "") $filter .= " AND ";
				$filter .= $sql;
				if ($sql <> "1=1")
					$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->materno->FldCaption() . "</span><span class=\"ewFilterValue\">$sql</span></div>";
			}
			$sql = @$post["paterno"];

			// Fusioncharts do not support "-" in drill down link. The encrypted data uses "$" instead of "-". Change back to "-" for decrypt.
			// https://www.fusioncharts.com/dev/advanced-chart-configurations/drill-down/using-javascript-functions-as-links.html
			// - Special characters like (, ), -, % and , cannot be passed as a parameter while function call.

			$sql = str_replace("$", "-", $sql);
			$sql = ewr_Decrypt($sql);
			$sql = str_replace("@paterno", "`paterno`", $sql);
			if ($sql <> "") {
				if ($filter <> "") $filter .= " AND ";
				$filter .= $sql;
				if ($sql <> "1=1")
					$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->paterno->FldCaption() . "</span><span class=\"ewFilterValue\">$sql</span></div>";
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
			$sql = @$post["fechanacimiento"];

			// Fusioncharts do not support "-" in drill down link. The encrypted data uses "$" instead of "-". Change back to "-" for decrypt.
			// https://www.fusioncharts.com/dev/advanced-chart-configurations/drill-down/using-javascript-functions-as-links.html
			// - Special characters like (, ), -, % and , cannot be passed as a parameter while function call.

			$sql = str_replace("$", "-", $sql);
			$sql = ewr_Decrypt($sql);
			$sql = str_replace("@fechanacimiento", "`fechanacimiento`", $sql);
			if ($sql <> "") {
				if ($filter <> "") $filter .= " AND ";
				$filter .= $sql;
				if ($sql <> "1=1")
					$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->fechanacimiento->FldCaption() . "</span><span class=\"ewFilterValue\">$sql</span></div>";
			}
			$sql = @$post["edad"];

			// Fusioncharts do not support "-" in drill down link. The encrypted data uses "$" instead of "-". Change back to "-" for decrypt.
			// https://www.fusioncharts.com/dev/advanced-chart-configurations/drill-down/using-javascript-functions-as-links.html
			// - Special characters like (, ), -, % and , cannot be passed as a parameter while function call.

			$sql = str_replace("$", "-", $sql);
			$sql = ewr_Decrypt($sql);
			$sql = str_replace("@edad", "`edad`", $sql);
			if ($sql <> "") {
				if ($filter <> "") $filter .= " AND ";
				$filter .= $sql;
				if ($sql <> "1=1")
					$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->edad->FldCaption() . "</span><span class=\"ewFilterValue\">$sql</span></div>";
			}
			$sql = @$post["sexo"];

			// Fusioncharts do not support "-" in drill down link. The encrypted data uses "$" instead of "-". Change back to "-" for decrypt.
			// https://www.fusioncharts.com/dev/advanced-chart-configurations/drill-down/using-javascript-functions-as-links.html
			// - Special characters like (, ), -, % and , cannot be passed as a parameter while function call.

			$sql = str_replace("$", "-", $sql);
			$sql = ewr_Decrypt($sql);
			$sql = str_replace("@sexo", "`sexo`", $sql);
			if ($sql <> "") {
				if ($filter <> "") $filter .= " AND ";
				$filter .= $sql;
				if ($sql <> "1=1")
					$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->sexo->FldCaption() . "</span><span class=\"ewFilterValue\">$sql</span></div>";
			}
			$sql = @$post["curso"];

			// Fusioncharts do not support "-" in drill down link. The encrypted data uses "$" instead of "-". Change back to "-" for decrypt.
			// https://www.fusioncharts.com/dev/advanced-chart-configurations/drill-down/using-javascript-functions-as-links.html
			// - Special characters like (, ), -, % and , cannot be passed as a parameter while function call.

			$sql = str_replace("$", "-", $sql);
			$sql = ewr_Decrypt($sql);
			$sql = str_replace("@curso", "`curso`", $sql);
			if ($sql <> "") {
				if ($filter <> "") $filter .= " AND ";
				$filter .= $sql;
				if ($sql <> "1=1")
					$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->curso->FldCaption() . "</span><span class=\"ewFilterValue\">$sql</span></div>";
			}
			$sql = @$post["discapacidad"];

			// Fusioncharts do not support "-" in drill down link. The encrypted data uses "$" instead of "-". Change back to "-" for decrypt.
			// https://www.fusioncharts.com/dev/advanced-chart-configurations/drill-down/using-javascript-functions-as-links.html
			// - Special characters like (, ), -, % and , cannot be passed as a parameter while function call.

			$sql = str_replace("$", "-", $sql);
			$sql = ewr_Decrypt($sql);
			$sql = str_replace("@discapacidad", "`discapacidad`", $sql);
			if ($sql <> "") {
				if ($filter <> "") $filter .= " AND ";
				$filter .= $sql;
				if ($sql <> "1=1")
					$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->discapacidad->FldCaption() . "</span><span class=\"ewFilterValue\">$sql</span></div>";
			}
			$sql = @$post["tipodiscapcidad"];

			// Fusioncharts do not support "-" in drill down link. The encrypted data uses "$" instead of "-". Change back to "-" for decrypt.
			// https://www.fusioncharts.com/dev/advanced-chart-configurations/drill-down/using-javascript-functions-as-links.html
			// - Special characters like (, ), -, % and , cannot be passed as a parameter while function call.

			$sql = str_replace("$", "-", $sql);
			$sql = ewr_Decrypt($sql);
			$sql = str_replace("@tipodiscapcidad", "`tipodiscapcidad`", $sql);
			if ($sql <> "") {
				if ($filter <> "") $filter .= " AND ";
				$filter .= $sql;
				if ($sql <> "1=1")
					$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->tipodiscapcidad->FldCaption() . "</span><span class=\"ewFilterValue\">$sql</span></div>";
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
			$_SESSION['do_viewestudiantecurso'] = $opt;
			$_SESSION['df_viewestudiantecurso'] = $filter;
			$_SESSION['dl_viewestudiantecurso'] = $sFilterList;
		} elseif (@$_GET["cmd"] == "resetdrilldown") { // Clear drill down
			$_SESSION[EWR_PROJECT_NAME . "_" . $this->TableVar . "_" . EWR_TABLE_MASTER_TABLE] = "";
			$_SESSION['do_viewestudiantecurso'] = "";
			$_SESSION['df_viewestudiantecurso'] = "";
			$_SESSION['dl_viewestudiantecurso'] = "";
		} else { // Restore from Session
			$opt = @$_SESSION['do_viewestudiantecurso'];
			$filter = @$_SESSION['df_viewestudiantecurso'];
			$sFilterList = @$_SESSION['dl_viewestudiantecurso'];
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
			$this->departamento->setSort("");
			$this->codigorude->setSort("");
			$this->codigorude_es->setSort("");
			$this->municipio->setSort("");
			$this->provincia->setSort("");
			$this->unidadeducativa->setSort("");
			$this->nombre->setSort("");
			$this->materno->setSort("");
			$this->paterno->setSort("");
			$this->nrodiscapacidad->setSort("");
			$this->ci->setSort("");
			$this->fechanacimiento->setSort("");
			$this->edad->setSort("");
			$this->sexo->setSort("");
			$this->curso->setSort("");
			$this->discapacidad->setSort("");
			$this->tipodiscapcidad->setSort("");
			$this->nombreinstitucion->setSort("");

		// Check for an Order parameter
		} elseif ($orderBy <> "") {
			$this->CurrentOrder = $orderBy;
			$this->CurrentOrderType = $orderType;
			$this->UpdateSort($this->departamento); // departamento
			$this->UpdateSort($this->codigorude); // codigorude
			$this->UpdateSort($this->codigorude_es); // codigorude_es
			$this->UpdateSort($this->municipio); // municipio
			$this->UpdateSort($this->provincia); // provincia
			$this->UpdateSort($this->unidadeducativa); // unidadeducativa
			$this->UpdateSort($this->nombre); // nombre
			$this->UpdateSort($this->materno); // materno
			$this->UpdateSort($this->paterno); // paterno
			$this->UpdateSort($this->nrodiscapacidad); // nrodiscapacidad
			$this->UpdateSort($this->ci); // ci
			$this->UpdateSort($this->fechanacimiento); // fechanacimiento
			$this->UpdateSort($this->edad); // edad
			$this->UpdateSort($this->sexo); // sexo
			$this->UpdateSort($this->curso); // curso
			$this->UpdateSort($this->discapacidad); // discapacidad
			$this->UpdateSort($this->tipodiscapcidad); // tipodiscapcidad
			$this->UpdateSort($this->nombreinstitucion); // nombreinstitucion
			$sSortSql = $this->SortSql();
			$this->setOrderBy($sSortSql);
			$this->setStartGroup(1);
		}
		return $this->getOrderBy();
	}

	// Export email
	function ExportEmail($EmailContent, $options = array()) {
		global $grTmpImages, $ReportLanguage;
		$bGenRequest = @$options["reporttype"] == "email";
		$sFailRespPfx = $bGenRequest ? "" : "<p class=\"text-error\">";
		$sSuccessRespPfx = $bGenRequest ? "" : "<p class=\"text-success\">";
		$sRespPfx = $bGenRequest ? "" : "</p>";
		$sContentType = (@$options["contenttype"] <> "") ? $options["contenttype"] : @$_POST["contenttype"];
		$sSender = (@$options["sender"] <> "") ? $options["sender"] : @$_POST["sender"];
		$sRecipient = (@$options["recipient"] <> "") ? $options["recipient"] : @$_POST["recipient"];
		$sCc = (@$options["cc"] <> "") ? $options["cc"] : @$_POST["cc"];
		$sBcc = (@$options["bcc"] <> "") ? $options["bcc"] : @$_POST["bcc"];

		// Subject
		$sEmailSubject = (@$options["subject"] <> "") ? $options["subject"] : @$_POST["subject"];

		// Message
		$sEmailMessage = (@$options["message"] <> "") ? $options["message"] : @$_POST["message"];

		// Check sender
		if ($sSender == "")
			return $sFailRespPfx . $ReportLanguage->Phrase("EnterSenderEmail") . $sRespPfx;
		if (!ewr_CheckEmail($sSender))
			return $sFailRespPfx . $ReportLanguage->Phrase("EnterProperSenderEmail") . $sRespPfx;

		// Check recipient
		if (!ewr_CheckEmailList($sRecipient, EWR_MAX_EMAIL_RECIPIENT))
			return $sFailRespPfx . $ReportLanguage->Phrase("EnterProperRecipientEmail") . $sRespPfx;

		// Check cc
		if (!ewr_CheckEmailList($sCc, EWR_MAX_EMAIL_RECIPIENT))
			return $sFailRespPfx . $ReportLanguage->Phrase("EnterProperCcEmail") . $sRespPfx;

		// Check bcc
		if (!ewr_CheckEmailList($sBcc, EWR_MAX_EMAIL_RECIPIENT))
			return $sFailRespPfx . $ReportLanguage->Phrase("EnterProperBccEmail") . $sRespPfx;

		// Check email sent count
		$emailcount = $bGenRequest ? 0 : ewr_LoadEmailCount();
		if (intval($emailcount) >= EWR_MAX_EMAIL_SENT_COUNT)
			return $sFailRespPfx . $ReportLanguage->Phrase("ExceedMaxEmailExport") . $sRespPfx;
		if ($sEmailMessage <> "") {
			if (EWR_REMOVE_XSS) $sEmailMessage = ewr_RemoveXSS($sEmailMessage);
			$sEmailMessage .= ($sContentType == "url") ? "\r\n\r\n" : "<br><br>";
		}
		$sAttachmentContent = ewr_AdjustEmailContent($EmailContent);
		$sAppPath = ewr_FullUrl();
		$sAppPath = substr($sAppPath, 0, strrpos($sAppPath, "/")+1);
		if (strpos($sAttachmentContent, "<head>") !== FALSE)
			$sAttachmentContent = str_replace("<head>", "<head><base href=\"" . $sAppPath . "\">", $sAttachmentContent); // Add <base href> statement inside the header
		else
			$sAttachmentContent = "<base href=\"" . $sAppPath . "\">" . $sAttachmentContent; // Add <base href> statement as the first statement

		//$sAttachmentFile = $this->TableVar . "_" . Date("YmdHis") . ".html";
		$sAttachmentFile = $this->TableVar . "_" . Date("YmdHis") . "_" . ewr_Random() . ".html";
		if ($sContentType == "url") {
			ewr_SaveFile(EWR_UPLOAD_DEST_PATH, $sAttachmentFile, $sAttachmentContent);
			$sAttachmentFile = EWR_UPLOAD_DEST_PATH . $sAttachmentFile;
			$sUrl = $sAppPath . $sAttachmentFile;
			$sEmailMessage .= $sUrl; // Send URL only
			$sAttachmentFile = "";
			$sAttachmentContent = "";
		} else {
			$sEmailMessage .= $sAttachmentContent;
			$sAttachmentFile = "";
			$sAttachmentContent = "";
		}

		// Send email
		$Email = new crEmail();
		$Email->Sender = $sSender; // Sender
		$Email->Recipient = $sRecipient; // Recipient
		$Email->Cc = $sCc; // Cc
		$Email->Bcc = $sBcc; // Bcc
		$Email->Subject = $sEmailSubject; // Subject
		$Email->Content = $sEmailMessage; // Content
		if ($sAttachmentFile <> "")
			$Email->AddAttachment($sAttachmentFile, $sAttachmentContent);
		if ($sContentType <> "url") {
			foreach ($grTmpImages as $tmpimage)
				$Email->AddEmbeddedImage($tmpimage);
		}
		$Email->Format = ($sContentType == "url") ? "text" : "html";
		$Email->Charset = EWR_EMAIL_CHARSET;
		$EventArgs = array();
		$bEmailSent = FALSE;
		if ($this->Email_Sending($Email, $EventArgs))
			$bEmailSent = $Email->Send();
		ewr_DeleteTmpImages($EmailContent);

		// Check email sent status
		if ($bEmailSent) {

			// Update email sent count and write log
			ewr_AddEmailLog($sSender, $sRecipient, $sEmailSubject, $sEmailMessage);

			// Sent email success
			return $sSuccessRespPfx . $ReportLanguage->Phrase("SendEmailSuccess") . $sRespPfx; // Set up success message
		} else {

			// Sent email failure
			return $sFailRespPfx . $Email->SendErrDescription . $sRespPfx;
		}
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
if (!isset($viewestudiantecurso_rpt)) $viewestudiantecurso_rpt = new crviewestudiantecurso_rpt();
if (isset($Page)) $OldPage = $Page;
$Page = &$viewestudiantecurso_rpt;

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
var viewestudiantecurso_rpt = new ewr_Page("viewestudiantecurso_rpt");

// Page properties
viewestudiantecurso_rpt.PageID = "rpt"; // Page ID
var EWR_PAGE_ID = viewestudiantecurso_rpt.PageID;
</script>
<?php } ?>
<?php if ($Page->Export == "" && !$Page->DrillDown && !$grDashboardReport) { ?>
<script type="text/javascript">

// Form object
var CurrentForm = fviewestudiantecursorpt = new ewr_Form("fviewestudiantecursorpt");

// Validate method
fviewestudiantecursorpt.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	var elm = fobj.sv_fechanacimiento;
	if (elm && !ewr_CheckDateDef(elm.value)) {
		if (!this.OnError(elm, "<?php echo ewr_JsEncode2($Page->fechanacimiento->FldErrMsg()) ?>"))
			return false;
	}
	var elm = fobj.sv_edad;
	if (elm && !ewr_CheckInteger(elm.value)) {
		if (!this.OnError(elm, "<?php echo ewr_JsEncode2($Page->edad->FldErrMsg()) ?>"))
			return false;
	}

	// Call Form Custom Validate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate method
fviewestudiantecursorpt.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }
<?php if (EWR_CLIENT_VALIDATE) { ?>
fviewestudiantecursorpt.ValidateRequired = true; // Uses JavaScript validation
<?php } else { ?>
fviewestudiantecursorpt.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Use Ajax
fviewestudiantecursorpt.Lists["sv_departamento"] = {"LinkField":"sv_departamento","Ajax":true,"DisplayFields":["sv_departamento","","",""],"ParentFields":[],"FilterFields":[],"Options":[],"Template":""};
fviewestudiantecursorpt.Lists["sv_ci"] = {"LinkField":"sv_ci","Ajax":true,"DisplayFields":["sv_ci","","",""],"ParentFields":[],"FilterFields":[],"Options":[],"Template":""};
fviewestudiantecursorpt.AutoSuggests["sv_ci"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $viewestudiantecurso_rpt->ci->LookupFilterQuery(TRUE))) ?>;
fviewestudiantecursorpt.Lists["sv_sexo"] = {"LinkField":"sv_sexo","Ajax":true,"DisplayFields":["sv_sexo","","",""],"ParentFields":[],"FilterFields":[],"Options":[],"Template":""};
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
<form name="fviewestudiantecursorpt" id="fviewestudiantecursorpt" class="form-inline ewForm ewExtFilterForm" action="<?php echo ewr_CurrentPage() ?>">
<?php $SearchPanelClass = ($Page->Filter <> "") ? " in" : " in"; ?>
<div id="fviewestudiantecursorpt_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<div id="r_1" class="ewRow">
<div id="c_departamento" class="ewCell form-group">
	<label for="sv_departamento" class="ewSearchCaption ewLabel"><?php echo $Page->departamento->FldCaption() ?></label>
	<span class="ewSearchField">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_sv_departamento"><?php echo $ReportLanguage->Phrase("PleaseSelect") ?></span>
</span>
<button type="button" title="<?php echo ewr_HtmlEncode(str_replace("%s", ewr_RemoveHtml($Page->departamento->FldCaption()), $ReportLanguage->Phrase("LookupLink", TRUE))) ?>" onclick="ewr_ModalLookupShow({lnk:this,el:'sv_departamento',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="viewestudiantecurso" data-field="x_departamento" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $Page->departamento->DisplayValueSeparatorAttribute() ?>" name="sv_departamento" id="sv_departamento" value="<?php echo ewr_FilterCurrentValue($Page->departamento, ",") ?>"<?php echo $Page->departamento->EditAttributes() ?>>
<input type="hidden" name="s_sv_departamento" id="s_sv_departamento" value="<?php echo $Page->departamento->LookupFilterQuery() ?>">
<script type="text/javascript">
fviewestudiantecursorpt.Lists["sv_departamento"].Options = <?php echo ewr_ArrayToJson($Page->departamento->LookupFilterOptions) ?>;
</script>
</span>
</div>
<div id="c_codigorude" class="ewCell form-group">
	<label for="sv_codigorude" class="ewSearchCaption ewLabel"><?php echo $Page->codigorude->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("LIKE"); ?><input type="hidden" name="so_codigorude" id="so_codigorude" value="LIKE"></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->codigorude->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewestudiantecurso" data-field="x_codigorude" id="sv_codigorude" name="sv_codigorude" size="30" maxlength="100" placeholder="<?php echo $Page->codigorude->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->codigorude->SearchValue) ?>"<?php echo $Page->codigorude->EditAttributes() ?>>
</span>
</div>
<div id="c_codigorude_es" class="ewCell form-group">
	<label for="sv_codigorude_es" class="ewSearchCaption ewLabel"><?php echo $Page->codigorude_es->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("LIKE"); ?><input type="hidden" name="so_codigorude_es" id="so_codigorude_es" value="LIKE"></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->codigorude_es->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewestudiantecurso" data-field="x_codigorude_es" id="sv_codigorude_es" name="sv_codigorude_es" size="30" maxlength="100" placeholder="<?php echo $Page->codigorude_es->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->codigorude_es->SearchValue) ?>"<?php echo $Page->codigorude_es->EditAttributes() ?>>
</span>
</div>
<div id="c_municipio" class="ewCell form-group">
	<label for="sv_municipio" class="ewSearchCaption ewLabel"><?php echo $Page->municipio->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("LIKE"); ?><input type="hidden" name="so_municipio" id="so_municipio" value="LIKE"></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->municipio->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewestudiantecurso" data-field="x_municipio" id="sv_municipio" name="sv_municipio" size="30" maxlength="100" placeholder="<?php echo $Page->municipio->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->municipio->SearchValue) ?>"<?php echo $Page->municipio->EditAttributes() ?>>
</span>
</div>
<div id="c_provincia" class="ewCell form-group">
	<label for="sv_provincia" class="ewSearchCaption ewLabel"><?php echo $Page->provincia->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("LIKE"); ?><input type="hidden" name="so_provincia" id="so_provincia" value="LIKE"></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->provincia->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewestudiantecurso" data-field="x_provincia" id="sv_provincia" name="sv_provincia" size="30" maxlength="100" placeholder="<?php echo $Page->provincia->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->provincia->SearchValue) ?>"<?php echo $Page->provincia->EditAttributes() ?>>
</span>
</div>
<div id="c_unidadeducativa" class="ewCell form-group">
	<label for="sv_unidadeducativa" class="ewSearchCaption ewLabel"><?php echo $Page->unidadeducativa->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("LIKE"); ?><input type="hidden" name="so_unidadeducativa" id="so_unidadeducativa" value="LIKE"></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->unidadeducativa->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewestudiantecurso" data-field="x_unidadeducativa" id="sv_unidadeducativa" name="sv_unidadeducativa" size="30" maxlength="100" placeholder="<?php echo $Page->unidadeducativa->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->unidadeducativa->SearchValue) ?>"<?php echo $Page->unidadeducativa->EditAttributes() ?>>
</span>
</div>
<div id="c_fechanacimiento" class="ewCell form-group">
	<label for="sv_fechanacimiento" class="ewSearchCaption ewLabel"><?php echo $Page->fechanacimiento->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("="); ?><input type="hidden" name="so_fechanacimiento" id="so_fechanacimiento" value="="></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->fechanacimiento->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewestudiantecurso" data-field="x_fechanacimiento" id="sv_fechanacimiento" name="sv_fechanacimiento" placeholder="<?php echo $Page->fechanacimiento->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->fechanacimiento->SearchValue) ?>"<?php echo $Page->fechanacimiento->EditAttributes() ?>>
</span>
</div>
<div id="c_edad" class="ewCell form-group">
	<label for="sv_edad" class="ewSearchCaption ewLabel"><?php echo $Page->edad->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("="); ?><input type="hidden" name="so_edad" id="so_edad" value="="></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->edad->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewestudiantecurso" data-field="x_edad" id="sv_edad" name="sv_edad" size="30" placeholder="<?php echo $Page->edad->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->edad->SearchValue) ?>"<?php echo $Page->edad->EditAttributes() ?>>
</span>
</div>
<div id="c_sexo" class="ewCell form-group">
	<label for="sv_sexo" class="ewSearchCaption ewLabel"><?php echo $Page->sexo->FldCaption() ?></label>
	<span class="ewSearchField">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_sv_sexo"><?php echo $ReportLanguage->Phrase("PleaseSelect") ?></span>
</span>
<button type="button" title="<?php echo ewr_HtmlEncode(str_replace("%s", ewr_RemoveHtml($Page->sexo->FldCaption()), $ReportLanguage->Phrase("LookupLink", TRUE))) ?>" onclick="ewr_ModalLookupShow({lnk:this,el:'sv_sexo',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="viewestudiantecurso" data-field="x_sexo" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $Page->sexo->DisplayValueSeparatorAttribute() ?>" name="sv_sexo" id="sv_sexo" value="<?php echo ewr_FilterCurrentValue($Page->sexo, ",") ?>"<?php echo $Page->sexo->EditAttributes() ?>>
<input type="hidden" name="s_sv_sexo" id="s_sv_sexo" value="<?php echo $Page->sexo->LookupFilterQuery() ?>">
<script type="text/javascript">
fviewestudiantecursorpt.Lists["sv_sexo"].Options = <?php echo ewr_ArrayToJson($Page->sexo->LookupFilterOptions) ?>;
</script>
</span>
</div>
<div id="c_curso" class="ewCell form-group">
	<label for="sv_curso" class="ewSearchCaption ewLabel"><?php echo $Page->curso->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("LIKE"); ?><input type="hidden" name="so_curso" id="so_curso" value="LIKE"></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->curso->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewestudiantecurso" data-field="x_curso" id="sv_curso" name="sv_curso" size="30" maxlength="100" placeholder="<?php echo $Page->curso->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->curso->SearchValue) ?>"<?php echo $Page->curso->EditAttributes() ?>>
</span>
</div>
</div>
<div id="r_2" class="ewRow">
<div id="c_discapacidad" class="ewCell form-group">
	<label for="sv_discapacidad" class="ewSearchCaption ewLabel"><?php echo $Page->discapacidad->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("LIKE"); ?><input type="hidden" name="so_discapacidad" id="so_discapacidad" value="LIKE"></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->discapacidad->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewestudiantecurso" data-field="x_discapacidad" id="sv_discapacidad" name="sv_discapacidad" size="30" maxlength="100" placeholder="<?php echo $Page->discapacidad->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->discapacidad->SearchValue) ?>"<?php echo $Page->discapacidad->EditAttributes() ?>>
</span>
</div>
<div id="c_nombreinstitucion" class="ewCell form-group">
	<label for="sv_nombreinstitucion" class="ewSearchCaption ewLabel"><?php echo $Page->nombreinstitucion->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("LIKE"); ?><input type="hidden" name="so_nombreinstitucion" id="so_nombreinstitucion" value="LIKE"></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->nombreinstitucion->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="viewestudiantecurso" data-field="x_nombreinstitucion" id="sv_nombreinstitucion" name="sv_nombreinstitucion" size="30" maxlength="100" placeholder="<?php echo $Page->nombreinstitucion->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->nombreinstitucion->SearchValue) ?>"<?php echo $Page->nombreinstitucion->EditAttributes() ?>>
</span>
</div>
</div>
<div class="ewRow"><input type="submit" name="btnsubmit" id="btnsubmit" class="btn btn-primary" value="<?php echo $ReportLanguage->Phrase("Search") ?>">
<input type="reset" name="btnreset" id="btnreset" class="btn hide" value="<?php echo $ReportLanguage->Phrase("Reset") ?>"></div>
</div>
</form>
<script type="text/javascript">
fviewestudiantecursorpt.Init();
fviewestudiantecursorpt.FilterList = <?php echo $Page->GetFilterList() ?>;
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
<!-- Report grid (begin) -->
<?php if ($Page->Export <> "pdf") { ?>
<div id="gmp_viewestudiantecurso" class="<?php if (ewr_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php } ?>
<table class="<?php echo $Page->ReportTableClass ?>">
<thead>
	<!-- Table header -->
	<tr class="ewTableHeader">
<?php if ($Page->departamento->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="departamento"><div class="viewestudiantecurso_departamento"><span class="ewTableHeaderCaption"><?php echo $Page->departamento->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="departamento">
<?php if ($Page->SortUrl($Page->departamento) == "") { ?>
		<div class="ewTableHeaderBtn viewestudiantecurso_departamento">
			<span class="ewTableHeaderCaption"><?php echo $Page->departamento->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewestudiantecurso_departamento', range: false, from: '<?php echo $Page->departamento->RangeFrom; ?>', to: '<?php echo $Page->departamento->RangeTo; ?>', url: 'viewestudiantecursorpt.php' });" id="x_departamento<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewestudiantecurso_departamento" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->departamento) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->departamento->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->departamento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->departamento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewestudiantecurso_departamento', range: false, from: '<?php echo $Page->departamento->RangeFrom; ?>', to: '<?php echo $Page->departamento->RangeTo; ?>', url: 'viewestudiantecursorpt.php' });" id="x_departamento<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->codigorude->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="codigorude"><div class="viewestudiantecurso_codigorude"><span class="ewTableHeaderCaption"><?php echo $Page->codigorude->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="codigorude">
<?php if ($Page->SortUrl($Page->codigorude) == "") { ?>
		<div class="ewTableHeaderBtn viewestudiantecurso_codigorude">
			<span class="ewTableHeaderCaption"><?php echo $Page->codigorude->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewestudiantecurso_codigorude', range: false, from: '<?php echo $Page->codigorude->RangeFrom; ?>', to: '<?php echo $Page->codigorude->RangeTo; ?>', url: 'viewestudiantecursorpt.php' });" id="x_codigorude<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewestudiantecurso_codigorude" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->codigorude) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->codigorude->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->codigorude->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->codigorude->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewestudiantecurso_codigorude', range: false, from: '<?php echo $Page->codigorude->RangeFrom; ?>', to: '<?php echo $Page->codigorude->RangeTo; ?>', url: 'viewestudiantecursorpt.php' });" id="x_codigorude<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->codigorude_es->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="codigorude_es"><div class="viewestudiantecurso_codigorude_es"><span class="ewTableHeaderCaption"><?php echo $Page->codigorude_es->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="codigorude_es">
<?php if ($Page->SortUrl($Page->codigorude_es) == "") { ?>
		<div class="ewTableHeaderBtn viewestudiantecurso_codigorude_es">
			<span class="ewTableHeaderCaption"><?php echo $Page->codigorude_es->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewestudiantecurso_codigorude_es', range: false, from: '<?php echo $Page->codigorude_es->RangeFrom; ?>', to: '<?php echo $Page->codigorude_es->RangeTo; ?>', url: 'viewestudiantecursorpt.php' });" id="x_codigorude_es<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewestudiantecurso_codigorude_es" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->codigorude_es) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->codigorude_es->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->codigorude_es->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->codigorude_es->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewestudiantecurso_codigorude_es', range: false, from: '<?php echo $Page->codigorude_es->RangeFrom; ?>', to: '<?php echo $Page->codigorude_es->RangeTo; ?>', url: 'viewestudiantecursorpt.php' });" id="x_codigorude_es<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->municipio->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="municipio"><div class="viewestudiantecurso_municipio"><span class="ewTableHeaderCaption"><?php echo $Page->municipio->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="municipio">
<?php if ($Page->SortUrl($Page->municipio) == "") { ?>
		<div class="ewTableHeaderBtn viewestudiantecurso_municipio">
			<span class="ewTableHeaderCaption"><?php echo $Page->municipio->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewestudiantecurso_municipio', range: false, from: '<?php echo $Page->municipio->RangeFrom; ?>', to: '<?php echo $Page->municipio->RangeTo; ?>', url: 'viewestudiantecursorpt.php' });" id="x_municipio<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewestudiantecurso_municipio" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->municipio) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->municipio->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->municipio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->municipio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewestudiantecurso_municipio', range: false, from: '<?php echo $Page->municipio->RangeFrom; ?>', to: '<?php echo $Page->municipio->RangeTo; ?>', url: 'viewestudiantecursorpt.php' });" id="x_municipio<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->provincia->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="provincia"><div class="viewestudiantecurso_provincia"><span class="ewTableHeaderCaption"><?php echo $Page->provincia->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="provincia">
<?php if ($Page->SortUrl($Page->provincia) == "") { ?>
		<div class="ewTableHeaderBtn viewestudiantecurso_provincia">
			<span class="ewTableHeaderCaption"><?php echo $Page->provincia->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewestudiantecurso_provincia', range: false, from: '<?php echo $Page->provincia->RangeFrom; ?>', to: '<?php echo $Page->provincia->RangeTo; ?>', url: 'viewestudiantecursorpt.php' });" id="x_provincia<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewestudiantecurso_provincia" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->provincia) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->provincia->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->provincia->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->provincia->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewestudiantecurso_provincia', range: false, from: '<?php echo $Page->provincia->RangeFrom; ?>', to: '<?php echo $Page->provincia->RangeTo; ?>', url: 'viewestudiantecursorpt.php' });" id="x_provincia<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->unidadeducativa->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="unidadeducativa"><div class="viewestudiantecurso_unidadeducativa"><span class="ewTableHeaderCaption"><?php echo $Page->unidadeducativa->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="unidadeducativa">
<?php if ($Page->SortUrl($Page->unidadeducativa) == "") { ?>
		<div class="ewTableHeaderBtn viewestudiantecurso_unidadeducativa">
			<span class="ewTableHeaderCaption"><?php echo $Page->unidadeducativa->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewestudiantecurso_unidadeducativa', range: false, from: '<?php echo $Page->unidadeducativa->RangeFrom; ?>', to: '<?php echo $Page->unidadeducativa->RangeTo; ?>', url: 'viewestudiantecursorpt.php' });" id="x_unidadeducativa<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewestudiantecurso_unidadeducativa" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->unidadeducativa) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->unidadeducativa->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->unidadeducativa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->unidadeducativa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewestudiantecurso_unidadeducativa', range: false, from: '<?php echo $Page->unidadeducativa->RangeFrom; ?>', to: '<?php echo $Page->unidadeducativa->RangeTo; ?>', url: 'viewestudiantecursorpt.php' });" id="x_unidadeducativa<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->nombre->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="nombre"><div class="viewestudiantecurso_nombre"><span class="ewTableHeaderCaption"><?php echo $Page->nombre->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="nombre">
<?php if ($Page->SortUrl($Page->nombre) == "") { ?>
		<div class="ewTableHeaderBtn viewestudiantecurso_nombre">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombre->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewestudiantecurso_nombre', range: false, from: '<?php echo $Page->nombre->RangeFrom; ?>', to: '<?php echo $Page->nombre->RangeTo; ?>', url: 'viewestudiantecursorpt.php' });" id="x_nombre<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewestudiantecurso_nombre" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->nombre) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombre->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->nombre->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->nombre->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewestudiantecurso_nombre', range: false, from: '<?php echo $Page->nombre->RangeFrom; ?>', to: '<?php echo $Page->nombre->RangeTo; ?>', url: 'viewestudiantecursorpt.php' });" id="x_nombre<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->materno->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="materno"><div class="viewestudiantecurso_materno"><span class="ewTableHeaderCaption"><?php echo $Page->materno->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="materno">
<?php if ($Page->SortUrl($Page->materno) == "") { ?>
		<div class="ewTableHeaderBtn viewestudiantecurso_materno">
			<span class="ewTableHeaderCaption"><?php echo $Page->materno->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewestudiantecurso_materno', range: false, from: '<?php echo $Page->materno->RangeFrom; ?>', to: '<?php echo $Page->materno->RangeTo; ?>', url: 'viewestudiantecursorpt.php' });" id="x_materno<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewestudiantecurso_materno" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->materno) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->materno->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->materno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->materno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewestudiantecurso_materno', range: false, from: '<?php echo $Page->materno->RangeFrom; ?>', to: '<?php echo $Page->materno->RangeTo; ?>', url: 'viewestudiantecursorpt.php' });" id="x_materno<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->paterno->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="paterno"><div class="viewestudiantecurso_paterno"><span class="ewTableHeaderCaption"><?php echo $Page->paterno->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="paterno">
<?php if ($Page->SortUrl($Page->paterno) == "") { ?>
		<div class="ewTableHeaderBtn viewestudiantecurso_paterno">
			<span class="ewTableHeaderCaption"><?php echo $Page->paterno->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewestudiantecurso_paterno', range: false, from: '<?php echo $Page->paterno->RangeFrom; ?>', to: '<?php echo $Page->paterno->RangeTo; ?>', url: 'viewestudiantecursorpt.php' });" id="x_paterno<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewestudiantecurso_paterno" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->paterno) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->paterno->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->paterno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->paterno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewestudiantecurso_paterno', range: false, from: '<?php echo $Page->paterno->RangeFrom; ?>', to: '<?php echo $Page->paterno->RangeTo; ?>', url: 'viewestudiantecursorpt.php' });" id="x_paterno<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->nrodiscapacidad->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="nrodiscapacidad"><div class="viewestudiantecurso_nrodiscapacidad"><span class="ewTableHeaderCaption"><?php echo $Page->nrodiscapacidad->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="nrodiscapacidad">
<?php if ($Page->SortUrl($Page->nrodiscapacidad) == "") { ?>
		<div class="ewTableHeaderBtn viewestudiantecurso_nrodiscapacidad">
			<span class="ewTableHeaderCaption"><?php echo $Page->nrodiscapacidad->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewestudiantecurso_nrodiscapacidad', range: false, from: '<?php echo $Page->nrodiscapacidad->RangeFrom; ?>', to: '<?php echo $Page->nrodiscapacidad->RangeTo; ?>', url: 'viewestudiantecursorpt.php' });" id="x_nrodiscapacidad<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewestudiantecurso_nrodiscapacidad" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->nrodiscapacidad) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->nrodiscapacidad->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->nrodiscapacidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->nrodiscapacidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewestudiantecurso_nrodiscapacidad', range: false, from: '<?php echo $Page->nrodiscapacidad->RangeFrom; ?>', to: '<?php echo $Page->nrodiscapacidad->RangeTo; ?>', url: 'viewestudiantecursorpt.php' });" id="x_nrodiscapacidad<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->ci->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="ci"><div class="viewestudiantecurso_ci"><span class="ewTableHeaderCaption"><?php echo $Page->ci->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="ci">
<?php if ($Page->SortUrl($Page->ci) == "") { ?>
		<div class="ewTableHeaderBtn viewestudiantecurso_ci">
			<span class="ewTableHeaderCaption"><?php echo $Page->ci->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewestudiantecurso_ci', range: false, from: '<?php echo $Page->ci->RangeFrom; ?>', to: '<?php echo $Page->ci->RangeTo; ?>', url: 'viewestudiantecursorpt.php' });" id="x_ci<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewestudiantecurso_ci" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->ci) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->ci->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->ci->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->ci->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewestudiantecurso_ci', range: false, from: '<?php echo $Page->ci->RangeFrom; ?>', to: '<?php echo $Page->ci->RangeTo; ?>', url: 'viewestudiantecursorpt.php' });" id="x_ci<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->fechanacimiento->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="fechanacimiento"><div class="viewestudiantecurso_fechanacimiento"><span class="ewTableHeaderCaption"><?php echo $Page->fechanacimiento->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="fechanacimiento">
<?php if ($Page->SortUrl($Page->fechanacimiento) == "") { ?>
		<div class="ewTableHeaderBtn viewestudiantecurso_fechanacimiento">
			<span class="ewTableHeaderCaption"><?php echo $Page->fechanacimiento->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewestudiantecurso_fechanacimiento', range: false, from: '<?php echo $Page->fechanacimiento->RangeFrom; ?>', to: '<?php echo $Page->fechanacimiento->RangeTo; ?>', url: 'viewestudiantecursorpt.php' });" id="x_fechanacimiento<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewestudiantecurso_fechanacimiento" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->fechanacimiento) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->fechanacimiento->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->fechanacimiento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->fechanacimiento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewestudiantecurso_fechanacimiento', range: false, from: '<?php echo $Page->fechanacimiento->RangeFrom; ?>', to: '<?php echo $Page->fechanacimiento->RangeTo; ?>', url: 'viewestudiantecursorpt.php' });" id="x_fechanacimiento<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->edad->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="edad"><div class="viewestudiantecurso_edad"><span class="ewTableHeaderCaption"><?php echo $Page->edad->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="edad">
<?php if ($Page->SortUrl($Page->edad) == "") { ?>
		<div class="ewTableHeaderBtn viewestudiantecurso_edad">
			<span class="ewTableHeaderCaption"><?php echo $Page->edad->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewestudiantecurso_edad', range: false, from: '<?php echo $Page->edad->RangeFrom; ?>', to: '<?php echo $Page->edad->RangeTo; ?>', url: 'viewestudiantecursorpt.php' });" id="x_edad<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewestudiantecurso_edad" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->edad) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->edad->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->edad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->edad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewestudiantecurso_edad', range: false, from: '<?php echo $Page->edad->RangeFrom; ?>', to: '<?php echo $Page->edad->RangeTo; ?>', url: 'viewestudiantecursorpt.php' });" id="x_edad<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->sexo->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="sexo"><div class="viewestudiantecurso_sexo"><span class="ewTableHeaderCaption"><?php echo $Page->sexo->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="sexo">
<?php if ($Page->SortUrl($Page->sexo) == "") { ?>
		<div class="ewTableHeaderBtn viewestudiantecurso_sexo">
			<span class="ewTableHeaderCaption"><?php echo $Page->sexo->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewestudiantecurso_sexo', range: false, from: '<?php echo $Page->sexo->RangeFrom; ?>', to: '<?php echo $Page->sexo->RangeTo; ?>', url: 'viewestudiantecursorpt.php' });" id="x_sexo<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewestudiantecurso_sexo" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->sexo) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->sexo->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->sexo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->sexo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewestudiantecurso_sexo', range: false, from: '<?php echo $Page->sexo->RangeFrom; ?>', to: '<?php echo $Page->sexo->RangeTo; ?>', url: 'viewestudiantecursorpt.php' });" id="x_sexo<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->curso->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="curso"><div class="viewestudiantecurso_curso"><span class="ewTableHeaderCaption"><?php echo $Page->curso->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="curso">
<?php if ($Page->SortUrl($Page->curso) == "") { ?>
		<div class="ewTableHeaderBtn viewestudiantecurso_curso">
			<span class="ewTableHeaderCaption"><?php echo $Page->curso->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewestudiantecurso_curso', range: false, from: '<?php echo $Page->curso->RangeFrom; ?>', to: '<?php echo $Page->curso->RangeTo; ?>', url: 'viewestudiantecursorpt.php' });" id="x_curso<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewestudiantecurso_curso" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->curso) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->curso->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->curso->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->curso->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewestudiantecurso_curso', range: false, from: '<?php echo $Page->curso->RangeFrom; ?>', to: '<?php echo $Page->curso->RangeTo; ?>', url: 'viewestudiantecursorpt.php' });" id="x_curso<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->discapacidad->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="discapacidad"><div class="viewestudiantecurso_discapacidad"><span class="ewTableHeaderCaption"><?php echo $Page->discapacidad->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="discapacidad">
<?php if ($Page->SortUrl($Page->discapacidad) == "") { ?>
		<div class="ewTableHeaderBtn viewestudiantecurso_discapacidad">
			<span class="ewTableHeaderCaption"><?php echo $Page->discapacidad->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewestudiantecurso_discapacidad', range: false, from: '<?php echo $Page->discapacidad->RangeFrom; ?>', to: '<?php echo $Page->discapacidad->RangeTo; ?>', url: 'viewestudiantecursorpt.php' });" id="x_discapacidad<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewestudiantecurso_discapacidad" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->discapacidad) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->discapacidad->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->discapacidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->discapacidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewestudiantecurso_discapacidad', range: false, from: '<?php echo $Page->discapacidad->RangeFrom; ?>', to: '<?php echo $Page->discapacidad->RangeTo; ?>', url: 'viewestudiantecursorpt.php' });" id="x_discapacidad<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->tipodiscapcidad->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="tipodiscapcidad"><div class="viewestudiantecurso_tipodiscapcidad"><span class="ewTableHeaderCaption"><?php echo $Page->tipodiscapcidad->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="tipodiscapcidad">
<?php if ($Page->SortUrl($Page->tipodiscapcidad) == "") { ?>
		<div class="ewTableHeaderBtn viewestudiantecurso_tipodiscapcidad">
			<span class="ewTableHeaderCaption"><?php echo $Page->tipodiscapcidad->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewestudiantecurso_tipodiscapcidad', range: false, from: '<?php echo $Page->tipodiscapcidad->RangeFrom; ?>', to: '<?php echo $Page->tipodiscapcidad->RangeTo; ?>', url: 'viewestudiantecursorpt.php' });" id="x_tipodiscapcidad<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewestudiantecurso_tipodiscapcidad" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->tipodiscapcidad) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->tipodiscapcidad->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->tipodiscapcidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->tipodiscapcidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewestudiantecurso_tipodiscapcidad', range: false, from: '<?php echo $Page->tipodiscapcidad->RangeFrom; ?>', to: '<?php echo $Page->tipodiscapcidad->RangeTo; ?>', url: 'viewestudiantecursorpt.php' });" id="x_tipodiscapcidad<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->nombreinstitucion->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="nombreinstitucion"><div class="viewestudiantecurso_nombreinstitucion"><span class="ewTableHeaderCaption"><?php echo $Page->nombreinstitucion->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="nombreinstitucion">
<?php if ($Page->SortUrl($Page->nombreinstitucion) == "") { ?>
		<div class="ewTableHeaderBtn viewestudiantecurso_nombreinstitucion">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombreinstitucion->FldCaption() ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewestudiantecurso_nombreinstitucion', range: false, from: '<?php echo $Page->nombreinstitucion->RangeFrom; ?>', to: '<?php echo $Page->nombreinstitucion->RangeTo; ?>', url: 'viewestudiantecursorpt.php' });" id="x_nombreinstitucion<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
	<?php } ?>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer viewestudiantecurso_nombreinstitucion" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->nombreinstitucion) ?>',1);">
			<span class="ewTableHeaderCaption"><?php echo $Page->nombreinstitucion->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->nombreinstitucion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->nombreinstitucion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
	<?php if (!$grDashboardReport) { ?>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, { name: 'viewestudiantecurso_nombreinstitucion', range: false, from: '<?php echo $Page->nombreinstitucion->RangeFrom; ?>', to: '<?php echo $Page->nombreinstitucion->RangeTo; ?>', url: 'viewestudiantecursorpt.php' });" id="x_nombreinstitucion<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
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
<?php if ($Page->departamento->Visible) { ?>
		<td data-field="departamento"<?php echo $Page->departamento->CellAttributes() ?>>
<span<?php echo $Page->departamento->ViewAttributes() ?>><?php echo $Page->departamento->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->codigorude->Visible) { ?>
		<td data-field="codigorude"<?php echo $Page->codigorude->CellAttributes() ?>>
<span<?php echo $Page->codigorude->ViewAttributes() ?>><?php echo $Page->codigorude->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->codigorude_es->Visible) { ?>
		<td data-field="codigorude_es"<?php echo $Page->codigorude_es->CellAttributes() ?>>
<span<?php echo $Page->codigorude_es->ViewAttributes() ?>><?php echo $Page->codigorude_es->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->municipio->Visible) { ?>
		<td data-field="municipio"<?php echo $Page->municipio->CellAttributes() ?>>
<span<?php echo $Page->municipio->ViewAttributes() ?>><?php echo $Page->municipio->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->provincia->Visible) { ?>
		<td data-field="provincia"<?php echo $Page->provincia->CellAttributes() ?>>
<span<?php echo $Page->provincia->ViewAttributes() ?>><?php echo $Page->provincia->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->unidadeducativa->Visible) { ?>
		<td data-field="unidadeducativa"<?php echo $Page->unidadeducativa->CellAttributes() ?>>
<span<?php echo $Page->unidadeducativa->ViewAttributes() ?>><?php echo $Page->unidadeducativa->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->nombre->Visible) { ?>
		<td data-field="nombre"<?php echo $Page->nombre->CellAttributes() ?>>
<span<?php echo $Page->nombre->ViewAttributes() ?>><?php echo $Page->nombre->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->materno->Visible) { ?>
		<td data-field="materno"<?php echo $Page->materno->CellAttributes() ?>>
<span<?php echo $Page->materno->ViewAttributes() ?>><?php echo $Page->materno->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->paterno->Visible) { ?>
		<td data-field="paterno"<?php echo $Page->paterno->CellAttributes() ?>>
<span<?php echo $Page->paterno->ViewAttributes() ?>><?php echo $Page->paterno->ListViewValue() ?></span></td>
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
<?php if ($Page->edad->Visible) { ?>
		<td data-field="edad"<?php echo $Page->edad->CellAttributes() ?>>
<span<?php echo $Page->edad->ViewAttributes() ?>><?php echo $Page->edad->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->sexo->Visible) { ?>
		<td data-field="sexo"<?php echo $Page->sexo->CellAttributes() ?>>
<span<?php echo $Page->sexo->ViewAttributes() ?>><?php echo $Page->sexo->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->curso->Visible) { ?>
		<td data-field="curso"<?php echo $Page->curso->CellAttributes() ?>>
<span<?php echo $Page->curso->ViewAttributes() ?>><?php echo $Page->curso->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->discapacidad->Visible) { ?>
		<td data-field="discapacidad"<?php echo $Page->discapacidad->CellAttributes() ?>>
<span<?php echo $Page->discapacidad->ViewAttributes() ?>><?php echo $Page->discapacidad->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->tipodiscapcidad->Visible) { ?>
		<td data-field="tipodiscapcidad"<?php echo $Page->tipodiscapcidad->CellAttributes() ?>>
<span<?php echo $Page->tipodiscapcidad->ViewAttributes() ?>><?php echo $Page->tipodiscapcidad->ListViewValue() ?></span></td>
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
<!-- Report grid (begin) -->
<?php if ($Page->Export <> "pdf") { ?>
<div id="gmp_viewestudiantecurso" class="<?php if (ewr_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php } ?>
<table class="<?php echo $Page->ReportTableClass ?>">
<?php } ?>
<?php if ($Page->TotalGrps > 0 || TRUE) { // Show footer ?>
</table>
<?php if ($Page->Export <> "pdf") { ?>
</div>
<?php } ?>
<?php if ($Page->Export == "" && !($Page->DrillDown && $Page->TotalGrps > 0)) { ?>
<div class="box-footer ewGridLowerPanel">
<?php include "viewestudiantecursorptpager.php" ?>
<div class="clearfix"></div>
</div>
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
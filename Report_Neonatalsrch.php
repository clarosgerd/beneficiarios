<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "Report_Neonatalinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$Report_Neonatal_search = NULL; // Initialize page object first

class cReport_Neonatal_search extends cReport_Neonatal {

	// Page ID
	var $PageID = 'search';

	// Project ID
	var $ProjectID = '{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}';

	// Table name
	var $TableName = 'Report Neonatal';

	// Page object name
	var $PageObjName = 'Report_Neonatal_search';

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
		if ($this->TableName)
			return $Language->Phrase($this->PageID);
		return "";
	}

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
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
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
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

		// Table object (Report_Neonatal)
		if (!isset($GLOBALS["Report_Neonatal"]) || get_class($GLOBALS["Report_Neonatal"]) == "cReport_Neonatal") {
			$GLOBALS["Report_Neonatal"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["Report_Neonatal"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'search', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'Report Neonatal', TRUE);

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

		// Is modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");

		// User profile
		$UserProfile = new cUserProfile();

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanSearch()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("Report_Neonatallist.php"));
			else
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
		// Create form object

		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->id_neonato->SetVisibility();
		$this->apellidopaterno->SetVisibility();
		if ($this->IsAddOrEdit())
			$this->apellidopaterno->Visible = FALSE;
		$this->apellidomaterno->SetVisibility();
		if ($this->IsAddOrEdit())
			$this->apellidomaterno->Visible = FALSE;
		$this->nombre->SetVisibility();
		if ($this->IsAddOrEdit())
			$this->nombre->Visible = FALSE;
		$this->ci->SetVisibility();
		if ($this->IsAddOrEdit())
			$this->ci->Visible = FALSE;
		$this->fecha_nacimiento->SetVisibility();
		if ($this->IsAddOrEdit())
			$this->fecha_nacimiento->Visible = FALSE;
		$this->dias->SetVisibility();
		if ($this->IsAddOrEdit())
			$this->dias->Visible = FALSE;
		$this->semanas->SetVisibility();
		if ($this->IsAddOrEdit())
			$this->semanas->Visible = FALSE;
		$this->meses->SetVisibility();
		if ($this->IsAddOrEdit())
			$this->meses->Visible = FALSE;
		$this->discapacidad->SetVisibility();
		if ($this->IsAddOrEdit())
			$this->discapacidad->Visible = FALSE;
		$this->resultado->SetVisibility();
		if ($this->IsAddOrEdit())
			$this->resultado->Visible = FALSE;
		$this->observaciones->SetVisibility();
		if ($this->IsAddOrEdit())
			$this->observaciones->Visible = FALSE;
		$this->tipoprueba->SetVisibility();
		if ($this->IsAddOrEdit())
			$this->tipoprueba->Visible = FALSE;
		$this->resultadprueba->SetVisibility();
		if ($this->IsAddOrEdit())
			$this->resultadprueba->Visible = FALSE;
		$this->recomendacion->SetVisibility();
		if ($this->IsAddOrEdit())
			$this->recomendacion->Visible = FALSE;
		$this->id_tipodiagnosticoaudiologia->SetVisibility();
		if ($this->IsAddOrEdit())
			$this->id_tipodiagnosticoaudiologia->Visible = FALSE;
		$this->nombrediagnotico->SetVisibility();
		if ($this->IsAddOrEdit())
			$this->nombrediagnotico->Visible = FALSE;
		$this->resultadodiagnostico->SetVisibility();
		if ($this->IsAddOrEdit())
			$this->resultadodiagnostico->Visible = FALSE;
		$this->tipotratamiento->SetVisibility();
		if ($this->IsAddOrEdit())
			$this->tipotratamiento->Visible = FALSE;
		$this->tipoderivacion->SetVisibility();
		if ($this->IsAddOrEdit())
			$this->tipoderivacion->Visible = FALSE;
		$this->nombreespcialidad->SetVisibility();
		if ($this->IsAddOrEdit())
			$this->nombreespcialidad->Visible = FALSE;
		$this->observaciones1->SetVisibility();
		$this->fecha->SetVisibility();

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
		global $EW_EXPORT, $Report_Neonatal;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($Report_Neonatal);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		// Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();

			// Handle modal response
			if ($this->IsModal) { // Show as modal
				$row = array("url" => $url, "modal" => "1");
				$pageName = ew_GetPageName($url);
				if ($pageName != $this->GetListUrl()) { // Not List page
					$row["caption"] = $this->GetModalCaption($pageName);
					if ($pageName == "Report_Neonatalview.php")
						$row["view"] = "1";
				} else { // List page should not be shown as modal => error
					$row["error"] = $this->getFailureMessage();
					$this->clearFailureMessage();
				}
				header("Content-Type: application/json; charset=utf-8");
				echo ew_ConvertToUtf8(ew_ArrayToJson(array($row)));
			} else {
				ew_SaveDebugMsg();
				header("Location: " . $url);
			}
		}
		exit();
	}
	var $FormClassName = "form-horizontal ewForm ewSearchForm";
	var $IsModal = FALSE;
	var $IsMobileOrModal = FALSE;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsSearchError;
		global $gbSkipHeaderFooter;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Check modal
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;
		$this->IsMobileOrModal = ew_IsMobile() || $this->IsModal;
		if ($this->IsPageRequest()) { // Validate request

			// Get action
			$this->CurrentAction = $objForm->GetValue("a_search");
			switch ($this->CurrentAction) {
				case "S": // Get search criteria

					// Build search string for advanced search, remove blank field
					$this->LoadSearchValues(); // Get search values
					if ($this->ValidateSearch()) {
						$sSrchStr = $this->BuildAdvancedSearch();
					} else {
						$sSrchStr = "";
						$this->setFailureMessage($gsSearchError);
					}
					if ($sSrchStr <> "") {
						$sSrchStr = $this->UrlParm($sSrchStr);
						$sSrchStr = "Report_Neonatallist.php" . "?" . $sSrchStr;
						$this->Page_Terminate($sSrchStr); // Go to list page
					}
			}
		}

		// Restore search settings from Session
		if ($gsSearchError == "")
			$this->LoadAdvancedSearch();

		// Render row for search
		$this->RowType = EW_ROWTYPE_SEARCH;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Build advanced search
	function BuildAdvancedSearch() {
		$sSrchUrl = "";
		$this->BuildSearchUrl($sSrchUrl, $this->id_neonato); // id_neonato
		$this->BuildSearchUrl($sSrchUrl, $this->apellidopaterno); // apellidopaterno
		$this->BuildSearchUrl($sSrchUrl, $this->apellidomaterno); // apellidomaterno
		$this->BuildSearchUrl($sSrchUrl, $this->nombre); // nombre
		$this->BuildSearchUrl($sSrchUrl, $this->ci); // ci
		$this->BuildSearchUrl($sSrchUrl, $this->fecha_nacimiento); // fecha_nacimiento
		$this->BuildSearchUrl($sSrchUrl, $this->dias); // dias
		$this->BuildSearchUrl($sSrchUrl, $this->semanas); // semanas
		$this->BuildSearchUrl($sSrchUrl, $this->meses); // meses
		$this->BuildSearchUrl($sSrchUrl, $this->discapacidad); // discapacidad
		$this->BuildSearchUrl($sSrchUrl, $this->resultado); // resultado
		$this->BuildSearchUrl($sSrchUrl, $this->observaciones); // observaciones
		$this->BuildSearchUrl($sSrchUrl, $this->tipoprueba); // tipoprueba
		$this->BuildSearchUrl($sSrchUrl, $this->resultadprueba); // resultadprueba
		$this->BuildSearchUrl($sSrchUrl, $this->recomendacion); // recomendacion
		$this->BuildSearchUrl($sSrchUrl, $this->id_tipodiagnosticoaudiologia); // id_tipodiagnosticoaudiologia
		$this->BuildSearchUrl($sSrchUrl, $this->nombrediagnotico); // nombrediagnotico
		$this->BuildSearchUrl($sSrchUrl, $this->resultadodiagnostico); // resultadodiagnostico
		$this->BuildSearchUrl($sSrchUrl, $this->tipotratamiento); // tipotratamiento
		$this->BuildSearchUrl($sSrchUrl, $this->tipoderivacion); // tipoderivacion
		$this->BuildSearchUrl($sSrchUrl, $this->nombreespcialidad); // nombreespcialidad
		$this->BuildSearchUrl($sSrchUrl, $this->observaciones1); // observaciones1
		$this->BuildSearchUrl($sSrchUrl, $this->fecha); // fecha
		if ($sSrchUrl <> "") $sSrchUrl .= "&";
		$sSrchUrl .= "cmd=search";
		return $sSrchUrl;
	}

	// Build search URL
	function BuildSearchUrl(&$Url, &$Fld, $OprOnly=FALSE) {
		global $objForm;
		$sWrk = "";
		$FldParm = $Fld->FldParm();
		$FldVal = $objForm->GetValue("x_$FldParm");
		$FldOpr = $objForm->GetValue("z_$FldParm");
		$FldCond = $objForm->GetValue("v_$FldParm");
		$FldVal2 = $objForm->GetValue("y_$FldParm");
		$FldOpr2 = $objForm->GetValue("w_$FldParm");
		$FldVal = $FldVal;
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);
		$FldVal2 = $FldVal2;
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		$lFldDataType = ($Fld->FldIsVirtual) ? EW_DATATYPE_STRING : $Fld->FldDataType;
		if ($FldOpr == "BETWEEN") {
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal) && $this->SearchValueIsNumeric($Fld, $FldVal2));
			if ($FldVal <> "" && $FldVal2 <> "" && $IsValidValue) {
				$sWrk = "x_" . $FldParm . "=" . urlencode($FldVal) .
					"&y_" . $FldParm . "=" . urlencode($FldVal2) .
					"&z_" . $FldParm . "=" . urlencode($FldOpr);
			}
		} else {
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal));
			if ($FldVal <> "" && $IsValidValue && ew_IsValidOpr($FldOpr, $lFldDataType)) {
				$sWrk = "x_" . $FldParm . "=" . urlencode($FldVal) .
					"&z_" . $FldParm . "=" . urlencode($FldOpr);
			} elseif ($FldOpr == "IS NULL" || $FldOpr == "IS NOT NULL" || ($FldOpr <> "" && $OprOnly && ew_IsValidOpr($FldOpr, $lFldDataType))) {
				$sWrk = "z_" . $FldParm . "=" . urlencode($FldOpr);
			}
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal2));
			if ($FldVal2 <> "" && $IsValidValue && ew_IsValidOpr($FldOpr2, $lFldDataType)) {
				if ($sWrk <> "") $sWrk .= "&v_" . $FldParm . "=" . urlencode($FldCond) . "&";
				$sWrk .= "y_" . $FldParm . "=" . urlencode($FldVal2) .
					"&w_" . $FldParm . "=" . urlencode($FldOpr2);
			} elseif ($FldOpr2 == "IS NULL" || $FldOpr2 == "IS NOT NULL" || ($FldOpr2 <> "" && $OprOnly && ew_IsValidOpr($FldOpr2, $lFldDataType))) {
				if ($sWrk <> "") $sWrk .= "&v_" . $FldParm . "=" . urlencode($FldCond) . "&";
				$sWrk .= "w_" . $FldParm . "=" . urlencode($FldOpr2);
			}
		}
		if ($sWrk <> "") {
			if ($Url <> "") $Url .= "&";
			$Url .= $sWrk;
		}
	}

	function SearchValueIsNumeric($Fld, $Value) {
		if (ew_IsFloatFormat($Fld->FldType)) $Value = ew_StrToFloat($Value);
		return is_numeric($Value);
	}

	// Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// id_neonato

		$this->id_neonato->AdvancedSearch->SearchValue = $objForm->GetValue("x_id_neonato");
		$this->id_neonato->AdvancedSearch->SearchOperator = $objForm->GetValue("z_id_neonato");

		// apellidopaterno
		$this->apellidopaterno->AdvancedSearch->SearchValue = $objForm->GetValue("x_apellidopaterno");
		$this->apellidopaterno->AdvancedSearch->SearchOperator = $objForm->GetValue("z_apellidopaterno");

		// apellidomaterno
		$this->apellidomaterno->AdvancedSearch->SearchValue = $objForm->GetValue("x_apellidomaterno");
		$this->apellidomaterno->AdvancedSearch->SearchOperator = $objForm->GetValue("z_apellidomaterno");

		// nombre
		$this->nombre->AdvancedSearch->SearchValue = $objForm->GetValue("x_nombre");
		$this->nombre->AdvancedSearch->SearchOperator = $objForm->GetValue("z_nombre");

		// ci
		$this->ci->AdvancedSearch->SearchValue = $objForm->GetValue("x_ci");
		$this->ci->AdvancedSearch->SearchOperator = $objForm->GetValue("z_ci");

		// fecha_nacimiento
		$this->fecha_nacimiento->AdvancedSearch->SearchValue = $objForm->GetValue("x_fecha_nacimiento");
		$this->fecha_nacimiento->AdvancedSearch->SearchOperator = $objForm->GetValue("z_fecha_nacimiento");

		// dias
		$this->dias->AdvancedSearch->SearchValue = $objForm->GetValue("x_dias");
		$this->dias->AdvancedSearch->SearchOperator = $objForm->GetValue("z_dias");

		// semanas
		$this->semanas->AdvancedSearch->SearchValue = $objForm->GetValue("x_semanas");
		$this->semanas->AdvancedSearch->SearchOperator = $objForm->GetValue("z_semanas");

		// meses
		$this->meses->AdvancedSearch->SearchValue = $objForm->GetValue("x_meses");
		$this->meses->AdvancedSearch->SearchOperator = $objForm->GetValue("z_meses");

		// discapacidad
		$this->discapacidad->AdvancedSearch->SearchValue = $objForm->GetValue("x_discapacidad");
		$this->discapacidad->AdvancedSearch->SearchOperator = $objForm->GetValue("z_discapacidad");

		// resultado
		$this->resultado->AdvancedSearch->SearchValue = $objForm->GetValue("x_resultado");
		$this->resultado->AdvancedSearch->SearchOperator = $objForm->GetValue("z_resultado");

		// observaciones
		$this->observaciones->AdvancedSearch->SearchValue = $objForm->GetValue("x_observaciones");
		$this->observaciones->AdvancedSearch->SearchOperator = $objForm->GetValue("z_observaciones");

		// tipoprueba
		$this->tipoprueba->AdvancedSearch->SearchValue = $objForm->GetValue("x_tipoprueba");
		$this->tipoprueba->AdvancedSearch->SearchOperator = $objForm->GetValue("z_tipoprueba");

		// resultadprueba
		$this->resultadprueba->AdvancedSearch->SearchValue = $objForm->GetValue("x_resultadprueba");
		$this->resultadprueba->AdvancedSearch->SearchOperator = $objForm->GetValue("z_resultadprueba");

		// recomendacion
		$this->recomendacion->AdvancedSearch->SearchValue = $objForm->GetValue("x_recomendacion");
		$this->recomendacion->AdvancedSearch->SearchOperator = $objForm->GetValue("z_recomendacion");

		// id_tipodiagnosticoaudiologia
		$this->id_tipodiagnosticoaudiologia->AdvancedSearch->SearchValue = $objForm->GetValue("x_id_tipodiagnosticoaudiologia");
		$this->id_tipodiagnosticoaudiologia->AdvancedSearch->SearchOperator = $objForm->GetValue("z_id_tipodiagnosticoaudiologia");

		// nombrediagnotico
		$this->nombrediagnotico->AdvancedSearch->SearchValue = $objForm->GetValue("x_nombrediagnotico");
		$this->nombrediagnotico->AdvancedSearch->SearchOperator = $objForm->GetValue("z_nombrediagnotico");

		// resultadodiagnostico
		$this->resultadodiagnostico->AdvancedSearch->SearchValue = $objForm->GetValue("x_resultadodiagnostico");
		$this->resultadodiagnostico->AdvancedSearch->SearchOperator = $objForm->GetValue("z_resultadodiagnostico");

		// tipotratamiento
		$this->tipotratamiento->AdvancedSearch->SearchValue = $objForm->GetValue("x_tipotratamiento");
		$this->tipotratamiento->AdvancedSearch->SearchOperator = $objForm->GetValue("z_tipotratamiento");

		// tipoderivacion
		$this->tipoderivacion->AdvancedSearch->SearchValue = $objForm->GetValue("x_tipoderivacion");
		$this->tipoderivacion->AdvancedSearch->SearchOperator = $objForm->GetValue("z_tipoderivacion");

		// nombreespcialidad
		$this->nombreespcialidad->AdvancedSearch->SearchValue = $objForm->GetValue("x_nombreespcialidad");
		$this->nombreespcialidad->AdvancedSearch->SearchOperator = $objForm->GetValue("z_nombreespcialidad");

		// observaciones1
		$this->observaciones1->AdvancedSearch->SearchValue = $objForm->GetValue("x_observaciones1");
		$this->observaciones1->AdvancedSearch->SearchOperator = $objForm->GetValue("z_observaciones1");

		// fecha
		$this->fecha->AdvancedSearch->SearchValue = $objForm->GetValue("x_fecha");
		$this->fecha->AdvancedSearch->SearchOperator = $objForm->GetValue("z_fecha");
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id_neonato
		// apellidopaterno
		// apellidomaterno
		// nombre
		// ci
		// fecha_nacimiento
		// dias
		// semanas
		// meses
		// discapacidad
		// resultado
		// observaciones
		// tipoprueba
		// resultadprueba
		// recomendacion
		// id_tipodiagnosticoaudiologia
		// nombrediagnotico
		// resultadodiagnostico
		// tipotratamiento
		// tipoderivacion
		// nombreespcialidad
		// observaciones1
		// fecha

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id_neonato
		$this->id_neonato->ViewValue = $this->id_neonato->CurrentValue;
		if (strval($this->id_neonato->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_neonato->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `neonatal`";
		$sWhereWrk = "";
		$this->id_neonato->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_neonato, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->id_neonato->ViewValue = $this->id_neonato->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_neonato->ViewValue = $this->id_neonato->CurrentValue;
			}
		} else {
			$this->id_neonato->ViewValue = NULL;
		}
		$this->id_neonato->ViewCustomAttributes = "";

		// apellidopaterno
		$this->apellidopaterno->ViewValue = $this->apellidopaterno->CurrentValue;
		$this->apellidopaterno->ViewCustomAttributes = "";

		// apellidomaterno
		$this->apellidomaterno->ViewValue = $this->apellidomaterno->CurrentValue;
		$this->apellidomaterno->ViewCustomAttributes = "";

		// nombre
		$this->nombre->ViewValue = $this->nombre->CurrentValue;
		$this->nombre->ViewCustomAttributes = "";

		// ci
		$this->ci->ViewValue = $this->ci->CurrentValue;
		$this->ci->ViewCustomAttributes = "";

		// fecha_nacimiento
		$this->fecha_nacimiento->ViewValue = $this->fecha_nacimiento->CurrentValue;
		$this->fecha_nacimiento->ViewValue = ew_FormatDateTime($this->fecha_nacimiento->ViewValue, 0);
		$this->fecha_nacimiento->ViewCustomAttributes = "";

		// dias
		$this->dias->ViewValue = $this->dias->CurrentValue;
		$this->dias->ViewCustomAttributes = "";

		// semanas
		$this->semanas->ViewValue = $this->semanas->CurrentValue;
		$this->semanas->ViewCustomAttributes = "";

		// meses
		$this->meses->ViewValue = $this->meses->CurrentValue;
		$this->meses->ViewCustomAttributes = "";

		// discapacidad
		$this->discapacidad->ViewValue = $this->discapacidad->CurrentValue;
		$this->discapacidad->ViewCustomAttributes = "";

		// resultado
		$this->resultado->ViewValue = $this->resultado->CurrentValue;
		$this->resultado->ViewCustomAttributes = "";

		// observaciones
		$this->observaciones->ViewValue = $this->observaciones->CurrentValue;
		$this->observaciones->ViewCustomAttributes = "";

		// tipoprueba
		$this->tipoprueba->ViewValue = $this->tipoprueba->CurrentValue;
		$this->tipoprueba->ViewCustomAttributes = "";

		// resultadprueba
		$this->resultadprueba->ViewValue = $this->resultadprueba->CurrentValue;
		$this->resultadprueba->ViewCustomAttributes = "";

		// recomendacion
		$this->recomendacion->ViewValue = $this->recomendacion->CurrentValue;
		$this->recomendacion->ViewCustomAttributes = "";

		// id_tipodiagnosticoaudiologia
		if (strval($this->id_tipodiagnosticoaudiologia->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_tipodiagnosticoaudiologia->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipodiagnosticoaudiologia`";
		$sWhereWrk = "";
		$this->id_tipodiagnosticoaudiologia->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_tipodiagnosticoaudiologia, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_tipodiagnosticoaudiologia->ViewValue = $this->id_tipodiagnosticoaudiologia->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_tipodiagnosticoaudiologia->ViewValue = $this->id_tipodiagnosticoaudiologia->CurrentValue;
			}
		} else {
			$this->id_tipodiagnosticoaudiologia->ViewValue = NULL;
		}
		$this->id_tipodiagnosticoaudiologia->ViewCustomAttributes = "";

		// nombrediagnotico
		$this->nombrediagnotico->ViewValue = $this->nombrediagnotico->CurrentValue;
		$this->nombrediagnotico->ViewCustomAttributes = "";

		// resultadodiagnostico
		$this->resultadodiagnostico->ViewValue = $this->resultadodiagnostico->CurrentValue;
		$this->resultadodiagnostico->ViewCustomAttributes = "";

		// tipotratamiento
		$this->tipotratamiento->ViewValue = $this->tipotratamiento->CurrentValue;
		$this->tipotratamiento->ViewCustomAttributes = "";

		// tipoderivacion
		if (strval($this->tipoderivacion->CurrentValue) <> "") {
			$this->tipoderivacion->ViewValue = $this->tipoderivacion->OptionCaption($this->tipoderivacion->CurrentValue);
		} else {
			$this->tipoderivacion->ViewValue = NULL;
		}
		$this->tipoderivacion->ViewCustomAttributes = "";

		// nombreespcialidad
		$this->nombreespcialidad->ViewValue = $this->nombreespcialidad->CurrentValue;
		$this->nombreespcialidad->ViewCustomAttributes = "";

		// observaciones1
		$this->observaciones1->ViewValue = $this->observaciones1->CurrentValue;
		$this->observaciones1->ViewCustomAttributes = "";

		// fecha
		$this->fecha->ViewValue = $this->fecha->CurrentValue;
		$this->fecha->ViewValue = ew_FormatDateTime($this->fecha->ViewValue, 0);
		$this->fecha->ViewCustomAttributes = "";

			// id_neonato
			$this->id_neonato->LinkCustomAttributes = "";
			$this->id_neonato->HrefValue = "";
			$this->id_neonato->TooltipValue = "";

			// apellidopaterno
			$this->apellidopaterno->LinkCustomAttributes = "";
			$this->apellidopaterno->HrefValue = "";
			$this->apellidopaterno->TooltipValue = "";

			// apellidomaterno
			$this->apellidomaterno->LinkCustomAttributes = "";
			$this->apellidomaterno->HrefValue = "";
			$this->apellidomaterno->TooltipValue = "";

			// nombre
			$this->nombre->LinkCustomAttributes = "";
			$this->nombre->HrefValue = "";
			$this->nombre->TooltipValue = "";

			// ci
			$this->ci->LinkCustomAttributes = "";
			$this->ci->HrefValue = "";
			$this->ci->TooltipValue = "";

			// fecha_nacimiento
			$this->fecha_nacimiento->LinkCustomAttributes = "";
			$this->fecha_nacimiento->HrefValue = "";
			$this->fecha_nacimiento->TooltipValue = "";

			// dias
			$this->dias->LinkCustomAttributes = "";
			$this->dias->HrefValue = "";
			$this->dias->TooltipValue = "";

			// semanas
			$this->semanas->LinkCustomAttributes = "";
			$this->semanas->HrefValue = "";
			$this->semanas->TooltipValue = "";

			// meses
			$this->meses->LinkCustomAttributes = "";
			$this->meses->HrefValue = "";
			$this->meses->TooltipValue = "";

			// discapacidad
			$this->discapacidad->LinkCustomAttributes = "";
			$this->discapacidad->HrefValue = "";
			$this->discapacidad->TooltipValue = "";

			// resultado
			$this->resultado->LinkCustomAttributes = "";
			$this->resultado->HrefValue = "";
			$this->resultado->TooltipValue = "";

			// observaciones
			$this->observaciones->LinkCustomAttributes = "";
			$this->observaciones->HrefValue = "";
			$this->observaciones->TooltipValue = "";

			// tipoprueba
			$this->tipoprueba->LinkCustomAttributes = "";
			$this->tipoprueba->HrefValue = "";
			$this->tipoprueba->TooltipValue = "";

			// resultadprueba
			$this->resultadprueba->LinkCustomAttributes = "";
			$this->resultadprueba->HrefValue = "";
			$this->resultadprueba->TooltipValue = "";

			// recomendacion
			$this->recomendacion->LinkCustomAttributes = "";
			$this->recomendacion->HrefValue = "";
			$this->recomendacion->TooltipValue = "";

			// id_tipodiagnosticoaudiologia
			$this->id_tipodiagnosticoaudiologia->LinkCustomAttributes = "";
			$this->id_tipodiagnosticoaudiologia->HrefValue = "";
			$this->id_tipodiagnosticoaudiologia->TooltipValue = "";

			// nombrediagnotico
			$this->nombrediagnotico->LinkCustomAttributes = "";
			$this->nombrediagnotico->HrefValue = "";
			$this->nombrediagnotico->TooltipValue = "";

			// resultadodiagnostico
			$this->resultadodiagnostico->LinkCustomAttributes = "";
			$this->resultadodiagnostico->HrefValue = "";
			$this->resultadodiagnostico->TooltipValue = "";

			// tipotratamiento
			$this->tipotratamiento->LinkCustomAttributes = "";
			$this->tipotratamiento->HrefValue = "";
			$this->tipotratamiento->TooltipValue = "";

			// tipoderivacion
			$this->tipoderivacion->LinkCustomAttributes = "";
			$this->tipoderivacion->HrefValue = "";
			$this->tipoderivacion->TooltipValue = "";

			// nombreespcialidad
			$this->nombreespcialidad->LinkCustomAttributes = "";
			$this->nombreespcialidad->HrefValue = "";
			$this->nombreespcialidad->TooltipValue = "";

			// observaciones1
			$this->observaciones1->LinkCustomAttributes = "";
			$this->observaciones1->HrefValue = "";
			$this->observaciones1->TooltipValue = "";

			// fecha
			$this->fecha->LinkCustomAttributes = "";
			$this->fecha->HrefValue = "";
			$this->fecha->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// id_neonato
			$this->id_neonato->EditAttrs["class"] = "form-control";
			$this->id_neonato->EditCustomAttributes = "";
			$this->id_neonato->EditValue = ew_HtmlEncode($this->id_neonato->AdvancedSearch->SearchValue);
			if (strval($this->id_neonato->AdvancedSearch->SearchValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_neonato->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `neonatal`";
			$sWhereWrk = "";
			$this->id_neonato->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_neonato, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$arwrk[3] = ew_HtmlEncode($rswrk->fields('Disp3Fld'));
					$this->id_neonato->EditValue = $this->id_neonato->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->id_neonato->EditValue = ew_HtmlEncode($this->id_neonato->AdvancedSearch->SearchValue);
				}
			} else {
				$this->id_neonato->EditValue = NULL;
			}
			$this->id_neonato->PlaceHolder = ew_RemoveHtml($this->id_neonato->FldCaption());

			// apellidopaterno
			$this->apellidopaterno->EditAttrs["class"] = "form-control";
			$this->apellidopaterno->EditCustomAttributes = "";
			$this->apellidopaterno->EditValue = ew_HtmlEncode($this->apellidopaterno->AdvancedSearch->SearchValue);
			$this->apellidopaterno->PlaceHolder = ew_RemoveHtml($this->apellidopaterno->FldCaption());

			// apellidomaterno
			$this->apellidomaterno->EditAttrs["class"] = "form-control";
			$this->apellidomaterno->EditCustomAttributes = "";
			$this->apellidomaterno->EditValue = ew_HtmlEncode($this->apellidomaterno->AdvancedSearch->SearchValue);
			$this->apellidomaterno->PlaceHolder = ew_RemoveHtml($this->apellidomaterno->FldCaption());

			// nombre
			$this->nombre->EditAttrs["class"] = "form-control";
			$this->nombre->EditCustomAttributes = "";
			$this->nombre->EditValue = ew_HtmlEncode($this->nombre->AdvancedSearch->SearchValue);
			$this->nombre->PlaceHolder = ew_RemoveHtml($this->nombre->FldCaption());

			// ci
			$this->ci->EditAttrs["class"] = "form-control";
			$this->ci->EditCustomAttributes = "";
			$this->ci->EditValue = ew_HtmlEncode($this->ci->AdvancedSearch->SearchValue);
			$this->ci->PlaceHolder = ew_RemoveHtml($this->ci->FldCaption());

			// fecha_nacimiento
			$this->fecha_nacimiento->EditAttrs["class"] = "form-control";
			$this->fecha_nacimiento->EditCustomAttributes = "";
			$this->fecha_nacimiento->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->fecha_nacimiento->AdvancedSearch->SearchValue, 0), 8));
			$this->fecha_nacimiento->PlaceHolder = ew_RemoveHtml($this->fecha_nacimiento->FldCaption());

			// dias
			$this->dias->EditAttrs["class"] = "form-control";
			$this->dias->EditCustomAttributes = "";
			$this->dias->EditValue = ew_HtmlEncode($this->dias->AdvancedSearch->SearchValue);
			$this->dias->PlaceHolder = ew_RemoveHtml($this->dias->FldCaption());

			// semanas
			$this->semanas->EditAttrs["class"] = "form-control";
			$this->semanas->EditCustomAttributes = "";
			$this->semanas->EditValue = ew_HtmlEncode($this->semanas->AdvancedSearch->SearchValue);
			$this->semanas->PlaceHolder = ew_RemoveHtml($this->semanas->FldCaption());

			// meses
			$this->meses->EditAttrs["class"] = "form-control";
			$this->meses->EditCustomAttributes = "";
			$this->meses->EditValue = ew_HtmlEncode($this->meses->AdvancedSearch->SearchValue);
			$this->meses->PlaceHolder = ew_RemoveHtml($this->meses->FldCaption());

			// discapacidad
			$this->discapacidad->EditAttrs["class"] = "form-control";
			$this->discapacidad->EditCustomAttributes = "";
			$this->discapacidad->EditValue = ew_HtmlEncode($this->discapacidad->AdvancedSearch->SearchValue);
			$this->discapacidad->PlaceHolder = ew_RemoveHtml($this->discapacidad->FldCaption());

			// resultado
			$this->resultado->EditAttrs["class"] = "form-control";
			$this->resultado->EditCustomAttributes = "";
			$this->resultado->EditValue = ew_HtmlEncode($this->resultado->AdvancedSearch->SearchValue);
			$this->resultado->PlaceHolder = ew_RemoveHtml($this->resultado->FldCaption());

			// observaciones
			$this->observaciones->EditAttrs["class"] = "form-control";
			$this->observaciones->EditCustomAttributes = "";
			$this->observaciones->EditValue = ew_HtmlEncode($this->observaciones->AdvancedSearch->SearchValue);
			$this->observaciones->PlaceHolder = ew_RemoveHtml($this->observaciones->FldCaption());

			// tipoprueba
			$this->tipoprueba->EditAttrs["class"] = "form-control";
			$this->tipoprueba->EditCustomAttributes = "";
			$this->tipoprueba->EditValue = ew_HtmlEncode($this->tipoprueba->AdvancedSearch->SearchValue);
			$this->tipoprueba->PlaceHolder = ew_RemoveHtml($this->tipoprueba->FldCaption());

			// resultadprueba
			$this->resultadprueba->EditAttrs["class"] = "form-control";
			$this->resultadprueba->EditCustomAttributes = "";
			$this->resultadprueba->EditValue = ew_HtmlEncode($this->resultadprueba->AdvancedSearch->SearchValue);
			$this->resultadprueba->PlaceHolder = ew_RemoveHtml($this->resultadprueba->FldCaption());

			// recomendacion
			$this->recomendacion->EditAttrs["class"] = "form-control";
			$this->recomendacion->EditCustomAttributes = "";
			$this->recomendacion->EditValue = ew_HtmlEncode($this->recomendacion->AdvancedSearch->SearchValue);
			$this->recomendacion->PlaceHolder = ew_RemoveHtml($this->recomendacion->FldCaption());

			// id_tipodiagnosticoaudiologia
			$this->id_tipodiagnosticoaudiologia->EditAttrs["class"] = "form-control";
			$this->id_tipodiagnosticoaudiologia->EditCustomAttributes = "";
			if (trim(strval($this->id_tipodiagnosticoaudiologia->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_tipodiagnosticoaudiologia->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `tipodiagnosticoaudiologia`";
			$sWhereWrk = "";
			$this->id_tipodiagnosticoaudiologia->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_tipodiagnosticoaudiologia, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_tipodiagnosticoaudiologia->EditValue = $arwrk;

			// nombrediagnotico
			$this->nombrediagnotico->EditAttrs["class"] = "form-control";
			$this->nombrediagnotico->EditCustomAttributes = "";
			$this->nombrediagnotico->EditValue = ew_HtmlEncode($this->nombrediagnotico->AdvancedSearch->SearchValue);
			$this->nombrediagnotico->PlaceHolder = ew_RemoveHtml($this->nombrediagnotico->FldCaption());

			// resultadodiagnostico
			$this->resultadodiagnostico->EditAttrs["class"] = "form-control";
			$this->resultadodiagnostico->EditCustomAttributes = "";
			$this->resultadodiagnostico->EditValue = ew_HtmlEncode($this->resultadodiagnostico->AdvancedSearch->SearchValue);
			$this->resultadodiagnostico->PlaceHolder = ew_RemoveHtml($this->resultadodiagnostico->FldCaption());

			// tipotratamiento
			$this->tipotratamiento->EditAttrs["class"] = "form-control";
			$this->tipotratamiento->EditCustomAttributes = "";
			$this->tipotratamiento->EditValue = ew_HtmlEncode($this->tipotratamiento->AdvancedSearch->SearchValue);
			$this->tipotratamiento->PlaceHolder = ew_RemoveHtml($this->tipotratamiento->FldCaption());

			// tipoderivacion
			$this->tipoderivacion->EditAttrs["class"] = "form-control";
			$this->tipoderivacion->EditCustomAttributes = "";
			$this->tipoderivacion->EditValue = $this->tipoderivacion->Options(TRUE);

			// nombreespcialidad
			$this->nombreespcialidad->EditAttrs["class"] = "form-control";
			$this->nombreespcialidad->EditCustomAttributes = "";
			$this->nombreespcialidad->EditValue = ew_HtmlEncode($this->nombreespcialidad->AdvancedSearch->SearchValue);
			$this->nombreespcialidad->PlaceHolder = ew_RemoveHtml($this->nombreespcialidad->FldCaption());

			// observaciones1
			$this->observaciones1->EditAttrs["class"] = "form-control";
			$this->observaciones1->EditCustomAttributes = "";
			$this->observaciones1->EditValue = ew_HtmlEncode($this->observaciones1->AdvancedSearch->SearchValue);
			$this->observaciones1->PlaceHolder = ew_RemoveHtml($this->observaciones1->FldCaption());

			// fecha
			$this->fecha->EditAttrs["class"] = "form-control";
			$this->fecha->EditCustomAttributes = "";
			$this->fecha->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->fecha->AdvancedSearch->SearchValue, 0), 8));
			$this->fecha->PlaceHolder = ew_RemoveHtml($this->fecha->FldCaption());
		}
		if ($this->RowType == EW_ROWTYPE_ADD || $this->RowType == EW_ROWTYPE_EDIT || $this->RowType == EW_ROWTYPE_SEARCH) // Add/Edit/Search row
			$this->SetupFieldTitles();

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate search
	function ValidateSearch() {
		global $gsSearchError;

		// Initialize
		$gsSearchError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return TRUE;
		if (!ew_CheckInteger($this->id_neonato->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->id_neonato->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->fecha_nacimiento->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->fecha_nacimiento->FldErrMsg());
		}
		if (!ew_CheckInteger($this->observaciones1->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->observaciones1->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->fecha->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->fecha->FldErrMsg());
		}

		// Return validate result
		$ValidateSearch = ($gsSearchError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateSearch = $ValidateSearch && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsSearchError, $sFormCustomError);
		}
		return $ValidateSearch;
	}

	// Load advanced search
	function LoadAdvancedSearch() {
		$this->id_neonato->AdvancedSearch->Load();
		$this->apellidopaterno->AdvancedSearch->Load();
		$this->apellidomaterno->AdvancedSearch->Load();
		$this->nombre->AdvancedSearch->Load();
		$this->ci->AdvancedSearch->Load();
		$this->fecha_nacimiento->AdvancedSearch->Load();
		$this->dias->AdvancedSearch->Load();
		$this->semanas->AdvancedSearch->Load();
		$this->meses->AdvancedSearch->Load();
		$this->discapacidad->AdvancedSearch->Load();
		$this->resultado->AdvancedSearch->Load();
		$this->observaciones->AdvancedSearch->Load();
		$this->tipoprueba->AdvancedSearch->Load();
		$this->resultadprueba->AdvancedSearch->Load();
		$this->recomendacion->AdvancedSearch->Load();
		$this->id_tipodiagnosticoaudiologia->AdvancedSearch->Load();
		$this->nombrediagnotico->AdvancedSearch->Load();
		$this->resultadodiagnostico->AdvancedSearch->Load();
		$this->tipotratamiento->AdvancedSearch->Load();
		$this->tipoderivacion->AdvancedSearch->Load();
		$this->nombreespcialidad->AdvancedSearch->Load();
		$this->observaciones1->AdvancedSearch->Load();
		$this->fecha->AdvancedSearch->Load();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("Report_Neonatallist.php"), "", $this->TableVar, TRUE);
		$PageId = "search";
		$Breadcrumb->Add("search", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_id_neonato":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `nombre` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `neonatal`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_neonato, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_id_tipodiagnosticoaudiologia":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipodiagnosticoaudiologia`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_tipodiagnosticoaudiologia, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_id_neonato":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `nombre` AS `DispFld`, `apellidopaterno` AS `Disp2Fld`, `apellidomaterno` AS `Disp3Fld` FROM `neonatal`";
			$sWhereWrk = "`nombre` LIKE '{query_value}%' OR CONCAT(COALESCE(`nombre`, ''),'" . ew_ValueSeparator(1, $this->id_neonato) . "',COALESCE(`apellidopaterno`,''),'" . ew_ValueSeparator(2, $this->id_neonato) . "',COALESCE(`apellidomaterno`,'')) LIKE '{query_value}%'";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_neonato, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		}
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
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($Report_Neonatal_search)) $Report_Neonatal_search = new cReport_Neonatal_search();

// Page init
$Report_Neonatal_search->Page_Init();

// Page main
$Report_Neonatal_search->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$Report_Neonatal_search->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "search";
<?php if ($Report_Neonatal_search->IsModal) { ?>
var CurrentAdvancedSearchForm = fReport_Neonatalsearch = new ew_Form("fReport_Neonatalsearch", "search");
<?php } else { ?>
var CurrentForm = fReport_Neonatalsearch = new ew_Form("fReport_Neonatalsearch", "search");
<?php } ?>

// Form_CustomValidate event
fReport_Neonatalsearch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fReport_Neonatalsearch.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fReport_Neonatalsearch.Lists["x_id_neonato"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","x_apellidopaterno","x_apellidomaterno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"neonatal"};
fReport_Neonatalsearch.Lists["x_id_neonato"].Data = "<?php echo $Report_Neonatal_search->id_neonato->LookupFilterQuery(FALSE, "search") ?>";
fReport_Neonatalsearch.AutoSuggests["x_id_neonato"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $Report_Neonatal_search->id_neonato->LookupFilterQuery(TRUE, "search"))) ?>;
fReport_Neonatalsearch.Lists["x_id_tipodiagnosticoaudiologia"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tipodiagnosticoaudiologia"};
fReport_Neonatalsearch.Lists["x_id_tipodiagnosticoaudiologia"].Data = "<?php echo $Report_Neonatal_search->id_tipodiagnosticoaudiologia->LookupFilterQuery(FALSE, "search") ?>";
fReport_Neonatalsearch.Lists["x_tipoderivacion"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fReport_Neonatalsearch.Lists["x_tipoderivacion"].Options = <?php echo json_encode($Report_Neonatal_search->tipoderivacion->Options()) ?>;

// Form object for search
// Validate function for search

fReport_Neonatalsearch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";
	elm = this.GetElements("x" + infix + "_id_neonato");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($Report_Neonatal->id_neonato->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_fecha_nacimiento");
	if (elm && !ew_CheckDateDef(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($Report_Neonatal->fecha_nacimiento->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_observaciones1");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($Report_Neonatal->observaciones1->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_fecha");
	if (elm && !ew_CheckDateDef(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($Report_Neonatal->fecha->FldErrMsg()) ?>");

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Report_Neonatal_search->ShowPageHeader(); ?>
<?php
$Report_Neonatal_search->ShowMessage();
?>
<form name="fReport_Neonatalsearch" id="fReport_Neonatalsearch" class="<?php echo $Report_Neonatal_search->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($Report_Neonatal_search->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $Report_Neonatal_search->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="Report_Neonatal">
<input type="hidden" name="a_search" id="a_search" value="S">
<input type="hidden" name="modal" value="<?php echo intval($Report_Neonatal_search->IsModal) ?>">
<div class="ewSearchDiv"><!-- page* -->
<?php if ($Report_Neonatal->id_neonato->Visible) { // id_neonato ?>
	<div id="r_id_neonato" class="form-group">
		<label class="<?php echo $Report_Neonatal_search->LeftColumnClass ?>"><span id="elh_Report_Neonatal_id_neonato"><?php echo $Report_Neonatal->id_neonato->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_id_neonato" id="z_id_neonato" value="="></p>
		</label>
		<div class="<?php echo $Report_Neonatal_search->RightColumnClass ?>"><div<?php echo $Report_Neonatal->id_neonato->CellAttributes() ?>>
			<span id="el_Report_Neonatal_id_neonato">
<?php
$wrkonchange = trim(" " . @$Report_Neonatal->id_neonato->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$Report_Neonatal->id_neonato->EditAttrs["onchange"] = "";
?>
<span id="as_x_id_neonato" style="white-space: nowrap; z-index: 8990">
	<input type="text" name="sv_x_id_neonato" id="sv_x_id_neonato" value="<?php echo $Report_Neonatal->id_neonato->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($Report_Neonatal->id_neonato->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($Report_Neonatal->id_neonato->getPlaceHolder()) ?>"<?php echo $Report_Neonatal->id_neonato->EditAttributes() ?>>
</span>
<input type="hidden" data-table="Report_Neonatal" data-field="x_id_neonato" data-value-separator="<?php echo $Report_Neonatal->id_neonato->DisplayValueSeparatorAttribute() ?>" name="x_id_neonato" id="x_id_neonato" value="<?php echo ew_HtmlEncode($Report_Neonatal->id_neonato->AdvancedSearch->SearchValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
fReport_Neonatalsearch.CreateAutoSuggest({"id":"x_id_neonato","forceSelect":false});
</script>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($Report_Neonatal->apellidopaterno->Visible) { // apellidopaterno ?>
	<div id="r_apellidopaterno" class="form-group">
		<label for="x_apellidopaterno" class="<?php echo $Report_Neonatal_search->LeftColumnClass ?>"><span id="elh_Report_Neonatal_apellidopaterno"><?php echo $Report_Neonatal->apellidopaterno->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_apellidopaterno" id="z_apellidopaterno" value="LIKE"></p>
		</label>
		<div class="<?php echo $Report_Neonatal_search->RightColumnClass ?>"><div<?php echo $Report_Neonatal->apellidopaterno->CellAttributes() ?>>
			<span id="el_Report_Neonatal_apellidopaterno">
<input type="text" data-table="Report_Neonatal" data-field="x_apellidopaterno" name="x_apellidopaterno" id="x_apellidopaterno" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($Report_Neonatal->apellidopaterno->getPlaceHolder()) ?>" value="<?php echo $Report_Neonatal->apellidopaterno->EditValue ?>"<?php echo $Report_Neonatal->apellidopaterno->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($Report_Neonatal->apellidomaterno->Visible) { // apellidomaterno ?>
	<div id="r_apellidomaterno" class="form-group">
		<label for="x_apellidomaterno" class="<?php echo $Report_Neonatal_search->LeftColumnClass ?>"><span id="elh_Report_Neonatal_apellidomaterno"><?php echo $Report_Neonatal->apellidomaterno->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_apellidomaterno" id="z_apellidomaterno" value="LIKE"></p>
		</label>
		<div class="<?php echo $Report_Neonatal_search->RightColumnClass ?>"><div<?php echo $Report_Neonatal->apellidomaterno->CellAttributes() ?>>
			<span id="el_Report_Neonatal_apellidomaterno">
<input type="text" data-table="Report_Neonatal" data-field="x_apellidomaterno" name="x_apellidomaterno" id="x_apellidomaterno" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($Report_Neonatal->apellidomaterno->getPlaceHolder()) ?>" value="<?php echo $Report_Neonatal->apellidomaterno->EditValue ?>"<?php echo $Report_Neonatal->apellidomaterno->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($Report_Neonatal->nombre->Visible) { // nombre ?>
	<div id="r_nombre" class="form-group">
		<label for="x_nombre" class="<?php echo $Report_Neonatal_search->LeftColumnClass ?>"><span id="elh_Report_Neonatal_nombre"><?php echo $Report_Neonatal->nombre->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_nombre" id="z_nombre" value="LIKE"></p>
		</label>
		<div class="<?php echo $Report_Neonatal_search->RightColumnClass ?>"><div<?php echo $Report_Neonatal->nombre->CellAttributes() ?>>
			<span id="el_Report_Neonatal_nombre">
<input type="text" data-table="Report_Neonatal" data-field="x_nombre" name="x_nombre" id="x_nombre" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($Report_Neonatal->nombre->getPlaceHolder()) ?>" value="<?php echo $Report_Neonatal->nombre->EditValue ?>"<?php echo $Report_Neonatal->nombre->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($Report_Neonatal->ci->Visible) { // ci ?>
	<div id="r_ci" class="form-group">
		<label for="x_ci" class="<?php echo $Report_Neonatal_search->LeftColumnClass ?>"><span id="elh_Report_Neonatal_ci"><?php echo $Report_Neonatal->ci->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_ci" id="z_ci" value="LIKE"></p>
		</label>
		<div class="<?php echo $Report_Neonatal_search->RightColumnClass ?>"><div<?php echo $Report_Neonatal->ci->CellAttributes() ?>>
			<span id="el_Report_Neonatal_ci">
<input type="text" data-table="Report_Neonatal" data-field="x_ci" name="x_ci" id="x_ci" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($Report_Neonatal->ci->getPlaceHolder()) ?>" value="<?php echo $Report_Neonatal->ci->EditValue ?>"<?php echo $Report_Neonatal->ci->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($Report_Neonatal->fecha_nacimiento->Visible) { // fecha_nacimiento ?>
	<div id="r_fecha_nacimiento" class="form-group">
		<label for="x_fecha_nacimiento" class="<?php echo $Report_Neonatal_search->LeftColumnClass ?>"><span id="elh_Report_Neonatal_fecha_nacimiento"><?php echo $Report_Neonatal->fecha_nacimiento->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_fecha_nacimiento" id="z_fecha_nacimiento" value="="></p>
		</label>
		<div class="<?php echo $Report_Neonatal_search->RightColumnClass ?>"><div<?php echo $Report_Neonatal->fecha_nacimiento->CellAttributes() ?>>
			<span id="el_Report_Neonatal_fecha_nacimiento">
<input type="text" data-table="Report_Neonatal" data-field="x_fecha_nacimiento" name="x_fecha_nacimiento" id="x_fecha_nacimiento" placeholder="<?php echo ew_HtmlEncode($Report_Neonatal->fecha_nacimiento->getPlaceHolder()) ?>" value="<?php echo $Report_Neonatal->fecha_nacimiento->EditValue ?>"<?php echo $Report_Neonatal->fecha_nacimiento->EditAttributes() ?>>
<?php if (!$Report_Neonatal->fecha_nacimiento->ReadOnly && !$Report_Neonatal->fecha_nacimiento->Disabled && !isset($Report_Neonatal->fecha_nacimiento->EditAttrs["readonly"]) && !isset($Report_Neonatal->fecha_nacimiento->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fReport_Neonatalsearch", "x_fecha_nacimiento", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($Report_Neonatal->dias->Visible) { // dias ?>
	<div id="r_dias" class="form-group">
		<label for="x_dias" class="<?php echo $Report_Neonatal_search->LeftColumnClass ?>"><span id="elh_Report_Neonatal_dias"><?php echo $Report_Neonatal->dias->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_dias" id="z_dias" value="LIKE"></p>
		</label>
		<div class="<?php echo $Report_Neonatal_search->RightColumnClass ?>"><div<?php echo $Report_Neonatal->dias->CellAttributes() ?>>
			<span id="el_Report_Neonatal_dias">
<input type="text" data-table="Report_Neonatal" data-field="x_dias" name="x_dias" id="x_dias" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($Report_Neonatal->dias->getPlaceHolder()) ?>" value="<?php echo $Report_Neonatal->dias->EditValue ?>"<?php echo $Report_Neonatal->dias->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($Report_Neonatal->semanas->Visible) { // semanas ?>
	<div id="r_semanas" class="form-group">
		<label for="x_semanas" class="<?php echo $Report_Neonatal_search->LeftColumnClass ?>"><span id="elh_Report_Neonatal_semanas"><?php echo $Report_Neonatal->semanas->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_semanas" id="z_semanas" value="LIKE"></p>
		</label>
		<div class="<?php echo $Report_Neonatal_search->RightColumnClass ?>"><div<?php echo $Report_Neonatal->semanas->CellAttributes() ?>>
			<span id="el_Report_Neonatal_semanas">
<input type="text" data-table="Report_Neonatal" data-field="x_semanas" name="x_semanas" id="x_semanas" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($Report_Neonatal->semanas->getPlaceHolder()) ?>" value="<?php echo $Report_Neonatal->semanas->EditValue ?>"<?php echo $Report_Neonatal->semanas->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($Report_Neonatal->meses->Visible) { // meses ?>
	<div id="r_meses" class="form-group">
		<label for="x_meses" class="<?php echo $Report_Neonatal_search->LeftColumnClass ?>"><span id="elh_Report_Neonatal_meses"><?php echo $Report_Neonatal->meses->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_meses" id="z_meses" value="LIKE"></p>
		</label>
		<div class="<?php echo $Report_Neonatal_search->RightColumnClass ?>"><div<?php echo $Report_Neonatal->meses->CellAttributes() ?>>
			<span id="el_Report_Neonatal_meses">
<input type="text" data-table="Report_Neonatal" data-field="x_meses" name="x_meses" id="x_meses" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($Report_Neonatal->meses->getPlaceHolder()) ?>" value="<?php echo $Report_Neonatal->meses->EditValue ?>"<?php echo $Report_Neonatal->meses->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($Report_Neonatal->discapacidad->Visible) { // discapacidad ?>
	<div id="r_discapacidad" class="form-group">
		<label for="x_discapacidad" class="<?php echo $Report_Neonatal_search->LeftColumnClass ?>"><span id="elh_Report_Neonatal_discapacidad"><?php echo $Report_Neonatal->discapacidad->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_discapacidad" id="z_discapacidad" value="LIKE"></p>
		</label>
		<div class="<?php echo $Report_Neonatal_search->RightColumnClass ?>"><div<?php echo $Report_Neonatal->discapacidad->CellAttributes() ?>>
			<span id="el_Report_Neonatal_discapacidad">
<input type="text" data-table="Report_Neonatal" data-field="x_discapacidad" name="x_discapacidad" id="x_discapacidad" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($Report_Neonatal->discapacidad->getPlaceHolder()) ?>" value="<?php echo $Report_Neonatal->discapacidad->EditValue ?>"<?php echo $Report_Neonatal->discapacidad->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($Report_Neonatal->resultado->Visible) { // resultado ?>
	<div id="r_resultado" class="form-group">
		<label for="x_resultado" class="<?php echo $Report_Neonatal_search->LeftColumnClass ?>"><span id="elh_Report_Neonatal_resultado"><?php echo $Report_Neonatal->resultado->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_resultado" id="z_resultado" value="LIKE"></p>
		</label>
		<div class="<?php echo $Report_Neonatal_search->RightColumnClass ?>"><div<?php echo $Report_Neonatal->resultado->CellAttributes() ?>>
			<span id="el_Report_Neonatal_resultado">
<input type="text" data-table="Report_Neonatal" data-field="x_resultado" name="x_resultado" id="x_resultado" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($Report_Neonatal->resultado->getPlaceHolder()) ?>" value="<?php echo $Report_Neonatal->resultado->EditValue ?>"<?php echo $Report_Neonatal->resultado->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($Report_Neonatal->observaciones->Visible) { // observaciones ?>
	<div id="r_observaciones" class="form-group">
		<label for="x_observaciones" class="<?php echo $Report_Neonatal_search->LeftColumnClass ?>"><span id="elh_Report_Neonatal_observaciones"><?php echo $Report_Neonatal->observaciones->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_observaciones" id="z_observaciones" value="LIKE"></p>
		</label>
		<div class="<?php echo $Report_Neonatal_search->RightColumnClass ?>"><div<?php echo $Report_Neonatal->observaciones->CellAttributes() ?>>
			<span id="el_Report_Neonatal_observaciones">
<input type="text" data-table="Report_Neonatal" data-field="x_observaciones" name="x_observaciones" id="x_observaciones" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($Report_Neonatal->observaciones->getPlaceHolder()) ?>" value="<?php echo $Report_Neonatal->observaciones->EditValue ?>"<?php echo $Report_Neonatal->observaciones->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($Report_Neonatal->tipoprueba->Visible) { // tipoprueba ?>
	<div id="r_tipoprueba" class="form-group">
		<label for="x_tipoprueba" class="<?php echo $Report_Neonatal_search->LeftColumnClass ?>"><span id="elh_Report_Neonatal_tipoprueba"><?php echo $Report_Neonatal->tipoprueba->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_tipoprueba" id="z_tipoprueba" value="LIKE"></p>
		</label>
		<div class="<?php echo $Report_Neonatal_search->RightColumnClass ?>"><div<?php echo $Report_Neonatal->tipoprueba->CellAttributes() ?>>
			<span id="el_Report_Neonatal_tipoprueba">
<input type="text" data-table="Report_Neonatal" data-field="x_tipoprueba" name="x_tipoprueba" id="x_tipoprueba" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($Report_Neonatal->tipoprueba->getPlaceHolder()) ?>" value="<?php echo $Report_Neonatal->tipoprueba->EditValue ?>"<?php echo $Report_Neonatal->tipoprueba->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($Report_Neonatal->resultadprueba->Visible) { // resultadprueba ?>
	<div id="r_resultadprueba" class="form-group">
		<label for="x_resultadprueba" class="<?php echo $Report_Neonatal_search->LeftColumnClass ?>"><span id="elh_Report_Neonatal_resultadprueba"><?php echo $Report_Neonatal->resultadprueba->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_resultadprueba" id="z_resultadprueba" value="LIKE"></p>
		</label>
		<div class="<?php echo $Report_Neonatal_search->RightColumnClass ?>"><div<?php echo $Report_Neonatal->resultadprueba->CellAttributes() ?>>
			<span id="el_Report_Neonatal_resultadprueba">
<input type="text" data-table="Report_Neonatal" data-field="x_resultadprueba" name="x_resultadprueba" id="x_resultadprueba" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($Report_Neonatal->resultadprueba->getPlaceHolder()) ?>" value="<?php echo $Report_Neonatal->resultadprueba->EditValue ?>"<?php echo $Report_Neonatal->resultadprueba->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($Report_Neonatal->recomendacion->Visible) { // recomendacion ?>
	<div id="r_recomendacion" class="form-group">
		<label for="x_recomendacion" class="<?php echo $Report_Neonatal_search->LeftColumnClass ?>"><span id="elh_Report_Neonatal_recomendacion"><?php echo $Report_Neonatal->recomendacion->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_recomendacion" id="z_recomendacion" value="LIKE"></p>
		</label>
		<div class="<?php echo $Report_Neonatal_search->RightColumnClass ?>"><div<?php echo $Report_Neonatal->recomendacion->CellAttributes() ?>>
			<span id="el_Report_Neonatal_recomendacion">
<input type="text" data-table="Report_Neonatal" data-field="x_recomendacion" name="x_recomendacion" id="x_recomendacion" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($Report_Neonatal->recomendacion->getPlaceHolder()) ?>" value="<?php echo $Report_Neonatal->recomendacion->EditValue ?>"<?php echo $Report_Neonatal->recomendacion->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($Report_Neonatal->id_tipodiagnosticoaudiologia->Visible) { // id_tipodiagnosticoaudiologia ?>
	<div id="r_id_tipodiagnosticoaudiologia" class="form-group">
		<label for="x_id_tipodiagnosticoaudiologia" class="<?php echo $Report_Neonatal_search->LeftColumnClass ?>"><span id="elh_Report_Neonatal_id_tipodiagnosticoaudiologia"><?php echo $Report_Neonatal->id_tipodiagnosticoaudiologia->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_id_tipodiagnosticoaudiologia" id="z_id_tipodiagnosticoaudiologia" value="="></p>
		</label>
		<div class="<?php echo $Report_Neonatal_search->RightColumnClass ?>"><div<?php echo $Report_Neonatal->id_tipodiagnosticoaudiologia->CellAttributes() ?>>
			<span id="el_Report_Neonatal_id_tipodiagnosticoaudiologia">
<select data-table="Report_Neonatal" data-field="x_id_tipodiagnosticoaudiologia" data-value-separator="<?php echo $Report_Neonatal->id_tipodiagnosticoaudiologia->DisplayValueSeparatorAttribute() ?>" id="x_id_tipodiagnosticoaudiologia" name="x_id_tipodiagnosticoaudiologia"<?php echo $Report_Neonatal->id_tipodiagnosticoaudiologia->EditAttributes() ?>>
<?php echo $Report_Neonatal->id_tipodiagnosticoaudiologia->SelectOptionListHtml("x_id_tipodiagnosticoaudiologia") ?>
</select>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($Report_Neonatal->nombrediagnotico->Visible) { // nombrediagnotico ?>
	<div id="r_nombrediagnotico" class="form-group">
		<label for="x_nombrediagnotico" class="<?php echo $Report_Neonatal_search->LeftColumnClass ?>"><span id="elh_Report_Neonatal_nombrediagnotico"><?php echo $Report_Neonatal->nombrediagnotico->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nombrediagnotico" id="z_nombrediagnotico" value="="></p>
		</label>
		<div class="<?php echo $Report_Neonatal_search->RightColumnClass ?>"><div<?php echo $Report_Neonatal->nombrediagnotico->CellAttributes() ?>>
			<span id="el_Report_Neonatal_nombrediagnotico">
<input type="text" data-table="Report_Neonatal" data-field="x_nombrediagnotico" name="x_nombrediagnotico" id="x_nombrediagnotico" size="30" placeholder="<?php echo ew_HtmlEncode($Report_Neonatal->nombrediagnotico->getPlaceHolder()) ?>" value="<?php echo $Report_Neonatal->nombrediagnotico->EditValue ?>"<?php echo $Report_Neonatal->nombrediagnotico->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($Report_Neonatal->resultadodiagnostico->Visible) { // resultadodiagnostico ?>
	<div id="r_resultadodiagnostico" class="form-group">
		<label for="x_resultadodiagnostico" class="<?php echo $Report_Neonatal_search->LeftColumnClass ?>"><span id="elh_Report_Neonatal_resultadodiagnostico"><?php echo $Report_Neonatal->resultadodiagnostico->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_resultadodiagnostico" id="z_resultadodiagnostico" value="LIKE"></p>
		</label>
		<div class="<?php echo $Report_Neonatal_search->RightColumnClass ?>"><div<?php echo $Report_Neonatal->resultadodiagnostico->CellAttributes() ?>>
			<span id="el_Report_Neonatal_resultadodiagnostico">
<input type="text" data-table="Report_Neonatal" data-field="x_resultadodiagnostico" name="x_resultadodiagnostico" id="x_resultadodiagnostico" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($Report_Neonatal->resultadodiagnostico->getPlaceHolder()) ?>" value="<?php echo $Report_Neonatal->resultadodiagnostico->EditValue ?>"<?php echo $Report_Neonatal->resultadodiagnostico->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($Report_Neonatal->tipotratamiento->Visible) { // tipotratamiento ?>
	<div id="r_tipotratamiento" class="form-group">
		<label for="x_tipotratamiento" class="<?php echo $Report_Neonatal_search->LeftColumnClass ?>"><span id="elh_Report_Neonatal_tipotratamiento"><?php echo $Report_Neonatal->tipotratamiento->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_tipotratamiento" id="z_tipotratamiento" value="LIKE"></p>
		</label>
		<div class="<?php echo $Report_Neonatal_search->RightColumnClass ?>"><div<?php echo $Report_Neonatal->tipotratamiento->CellAttributes() ?>>
			<span id="el_Report_Neonatal_tipotratamiento">
<input type="text" data-table="Report_Neonatal" data-field="x_tipotratamiento" name="x_tipotratamiento" id="x_tipotratamiento" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($Report_Neonatal->tipotratamiento->getPlaceHolder()) ?>" value="<?php echo $Report_Neonatal->tipotratamiento->EditValue ?>"<?php echo $Report_Neonatal->tipotratamiento->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($Report_Neonatal->tipoderivacion->Visible) { // tipoderivacion ?>
	<div id="r_tipoderivacion" class="form-group">
		<label for="x_tipoderivacion" class="<?php echo $Report_Neonatal_search->LeftColumnClass ?>"><span id="elh_Report_Neonatal_tipoderivacion"><?php echo $Report_Neonatal->tipoderivacion->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_tipoderivacion" id="z_tipoderivacion" value="LIKE"></p>
		</label>
		<div class="<?php echo $Report_Neonatal_search->RightColumnClass ?>"><div<?php echo $Report_Neonatal->tipoderivacion->CellAttributes() ?>>
			<span id="el_Report_Neonatal_tipoderivacion">
<select data-table="Report_Neonatal" data-field="x_tipoderivacion" data-value-separator="<?php echo $Report_Neonatal->tipoderivacion->DisplayValueSeparatorAttribute() ?>" id="x_tipoderivacion" name="x_tipoderivacion"<?php echo $Report_Neonatal->tipoderivacion->EditAttributes() ?>>
<?php echo $Report_Neonatal->tipoderivacion->SelectOptionListHtml("x_tipoderivacion") ?>
</select>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($Report_Neonatal->nombreespcialidad->Visible) { // nombreespcialidad ?>
	<div id="r_nombreespcialidad" class="form-group">
		<label for="x_nombreespcialidad" class="<?php echo $Report_Neonatal_search->LeftColumnClass ?>"><span id="elh_Report_Neonatal_nombreespcialidad"><?php echo $Report_Neonatal->nombreespcialidad->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_nombreespcialidad" id="z_nombreespcialidad" value="LIKE"></p>
		</label>
		<div class="<?php echo $Report_Neonatal_search->RightColumnClass ?>"><div<?php echo $Report_Neonatal->nombreespcialidad->CellAttributes() ?>>
			<span id="el_Report_Neonatal_nombreespcialidad">
<input type="text" data-table="Report_Neonatal" data-field="x_nombreespcialidad" name="x_nombreespcialidad" id="x_nombreespcialidad" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($Report_Neonatal->nombreespcialidad->getPlaceHolder()) ?>" value="<?php echo $Report_Neonatal->nombreespcialidad->EditValue ?>"<?php echo $Report_Neonatal->nombreespcialidad->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($Report_Neonatal->observaciones1->Visible) { // observaciones1 ?>
	<div id="r_observaciones1" class="form-group">
		<label for="x_observaciones1" class="<?php echo $Report_Neonatal_search->LeftColumnClass ?>"><span id="elh_Report_Neonatal_observaciones1"><?php echo $Report_Neonatal->observaciones1->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_observaciones1" id="z_observaciones1" value="="></p>
		</label>
		<div class="<?php echo $Report_Neonatal_search->RightColumnClass ?>"><div<?php echo $Report_Neonatal->observaciones1->CellAttributes() ?>>
			<span id="el_Report_Neonatal_observaciones1">
<input type="text" data-table="Report_Neonatal" data-field="x_observaciones1" name="x_observaciones1" id="x_observaciones1" size="30" placeholder="<?php echo ew_HtmlEncode($Report_Neonatal->observaciones1->getPlaceHolder()) ?>" value="<?php echo $Report_Neonatal->observaciones1->EditValue ?>"<?php echo $Report_Neonatal->observaciones1->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($Report_Neonatal->fecha->Visible) { // fecha ?>
	<div id="r_fecha" class="form-group">
		<label for="x_fecha" class="<?php echo $Report_Neonatal_search->LeftColumnClass ?>"><span id="elh_Report_Neonatal_fecha"><?php echo $Report_Neonatal->fecha->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_fecha" id="z_fecha" value="="></p>
		</label>
		<div class="<?php echo $Report_Neonatal_search->RightColumnClass ?>"><div<?php echo $Report_Neonatal->fecha->CellAttributes() ?>>
			<span id="el_Report_Neonatal_fecha">
<input type="text" data-table="Report_Neonatal" data-field="x_fecha" name="x_fecha" id="x_fecha" placeholder="<?php echo ew_HtmlEncode($Report_Neonatal->fecha->getPlaceHolder()) ?>" value="<?php echo $Report_Neonatal->fecha->EditValue ?>"<?php echo $Report_Neonatal->fecha->EditAttributes() ?>>
<?php if (!$Report_Neonatal->fecha->ReadOnly && !$Report_Neonatal->fecha->Disabled && !isset($Report_Neonatal->fecha->EditAttrs["readonly"]) && !isset($Report_Neonatal->fecha->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fReport_Neonatalsearch", "x_fecha", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
		</div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$Report_Neonatal_search->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $Report_Neonatal_search->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("Search") ?></button>
<button class="btn btn-default ewButton" name="btnReset" id="btnReset" type="button" onclick="ew_ClearForm(this.form);"><?php echo $Language->Phrase("Reset") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fReport_Neonatalsearch.Init();
</script>
<?php
$Report_Neonatal_search->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$Report_Neonatal_search->Page_Terminate();
?>

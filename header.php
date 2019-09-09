<?php

// Compatibility with PHP Report Maker
if (!isset($Language)) {
	include_once "ewcfg14.php";
	include_once "ewshared14.php";
	$Language = new cLanguage();
}
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $Language->ProjectPhrase("BodyTitle") ?></title>
<meta charset="utf-8">
<?php if (@$gsExport == "" || @$gsExport == "print") { ?>
<link rel="stylesheet" type="text/css" href="<?php echo $EW_RELATIVE_PATH ?>bootstrap3/css/<?php echo ew_CssFile("bootstrap.css") ?>">
<link rel="stylesheet" type="text/css" href="<?php echo $EW_RELATIVE_PATH ?>adminlte/css/<?php echo ew_CssFile("AdminLTE.css") ?>">
<link rel="stylesheet" type="text/css" href="<?php echo $EW_RELATIVE_PATH ?>adminlte/css/font-awesome.min.css"><!-- Optional font -->
<?php } ?>
<?php if (@$gsExport == "" || @$gsExport == "print") { ?>
<link rel="stylesheet" type="text/css" href="<?php echo $EW_RELATIVE_PATH ?>phpcss/jquery.fileupload.css">
<link rel="stylesheet" type="text/css" href="<?php echo $EW_RELATIVE_PATH ?>phpcss/jquery.fileupload-ui.css">
<link rel="stylesheet" type="text/css" href="<?php echo $EW_RELATIVE_PATH ?>colorbox/colorbox.css">
<?php
	foreach ($EW_STYLESHEET_FILES as $cssfile) { // External Stylesheets
		if (preg_match('/^(https?:)?\/\//i', $cssfile) == 0)
			$cssfile = $EW_RELATIVE_PATH . $cssfile;
?>
<link rel="stylesheet" type="text/css" href="<?php echo $cssfile ?>">
<?php } ?>
<?php } ?>
<?php if (@$gsExport == "" || @$gsExport == "print") { ?>
<?php if (ew_IsResponsiveLayout()) { ?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php } ?>
<link rel="stylesheet" type="text/css" href="<?php echo $EW_RELATIVE_PATH ?><?php echo ew_CssFile(EW_PROJECT_STYLESHEET_FILENAME) ?>">
<?php if (@$gsCustomExport == "pdf" && EW_PDF_STYLESHEET_FILENAME <> "") { ?>
<link rel="stylesheet" type="text/css" href="<?php echo $EW_RELATIVE_PATH ?><?php echo EW_PDF_STYLESHEET_FILENAME ?>">
<?php } ?>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>jquery/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>jquery/jquery.ui.widget.js"></script>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>jquery/jquery.storageapi.min.js"></script>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>jquery/pGenerator.jquery.js"></script>
<?php } ?>
<?php if (@$gsExport == "" || @$gsExport == "print") { ?>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>bootstrap3/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>adminlte/js/adminlte.js"></script>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>jquery/jquery.fileDownload.min.js"></script>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>jquery/load-image.all.min.js"></script>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>jquery/jqueryfileupload.min.js"></script>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>phpjs/typeahead.jquery.js"></script>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>colorbox/jquery.colorbox-min.js"></script>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>phpjs/mobile-detect.min.js"></script>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>moment/moment.min.js"></script>
<?php
	foreach ($EW_JAVASCRIPT_FILES as $jsfile) { // External JavaScripts
		if (preg_match('/^(https?:)?\/\//i', $jsfile) == 0)
			$jsfile = $EW_RELATIVE_PATH . $jsfile;
?>
<script type="text/javascript" src="<?php echo $jsfile ?>"></script>
<?php } ?>
<link rel="stylesheet" type="text/css" href="<?php echo $EW_RELATIVE_PATH ?>phpcss/bootstrap-datetimepicker.css">
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>phpjs/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>phpjs/ewdatetimepicker.js"></script>
<script type="text/javascript">
var EW_LANGUAGE_ID = "<?php echo $gsLanguage ?>";
var EW_DATE_SEPARATOR = "<?php echo $EW_DATE_SEPARATOR ?>"; // Date separator
var EW_TIME_SEPARATOR = "<?php echo $EW_TIME_SEPARATOR ?>"; // Time separator
var EW_DATE_FORMAT = "<?php echo $EW_DATE_FORMAT ?>"; // Default date format
var EW_DATE_FORMAT_ID = <?php echo $EW_DATE_FORMAT_ID ?>; // Default date format ID
var EW_DECIMAL_POINT = "<?php echo $EW_DECIMAL_POINT ?>";
var EW_THOUSANDS_SEP = "<?php echo $EW_THOUSANDS_SEP ?>";
var EW_GENERATE_PASSWORD_LENGTH = 16;
var EW_GENERATE_PASSWORD_UPPERCASE = true;
var EW_GENERATE_PASSWORD_LOWERCASE = true;
var EW_GENERATE_PASSWORD_NUMBER = true;
var EW_GENERATE_PASSWORD_SPECIALCHARS = false;
var EW_SESSION_TIMEOUT = <?php echo (EW_SESSION_TIMEOUT > 0) ? ew_SessionTimeoutTime() : 0 ?>; // Session timeout time (seconds)
var EW_SESSION_TIMEOUT_COUNTDOWN = <?php echo EW_SESSION_TIMEOUT_COUNTDOWN ?>; // Count down time to session timeout (seconds)
var EW_SESSION_KEEP_ALIVE_INTERVAL = <?php echo EW_SESSION_KEEP_ALIVE_INTERVAL ?>; // Keep alive interval (seconds)
var EW_RELATIVE_PATH = "<?php echo $EW_RELATIVE_PATH ?>"; // Relative path
var EW_SESSION_URL = EW_RELATIVE_PATH + "ewsession14.php"; // Session URL
var EW_IS_LOGGEDIN = <?php echo IsLoggedIn() ? "true" : "false" ?>; // Is logged in
var EW_IS_SYS_ADMIN = <?php echo IsSysAdmin() ? "true" : "false" ?>; // Is sys admin
var EW_CURRENT_USER_NAME = "<?php echo ew_JsEncode2(CurrentUserName()) ?>"; // Current user name
var EW_IS_AUTOLOGIN = <?php echo IsAutoLogin() ? "true" : "false" ?>; // Is logged in with option "Auto login until I logout explicitly"
var EW_TIMEOUT_URL = EW_RELATIVE_PATH + "logout.php"; // Timeout URL
var EW_LOOKUP_FILE_NAME = "ewlookup14.php"; // Lookup file name
var EW_LOOKUP_FILTER_VALUE_SEPARATOR = "<?php echo EW_LOOKUP_FILTER_VALUE_SEPARATOR ?>"; // Lookup filter value separator
var EW_MODAL_LOOKUP_FILE_NAME = "ewmodallookup14.php"; // Modal lookup file name
var EW_AUTO_SUGGEST_MAX_ENTRIES = <?php echo EW_AUTO_SUGGEST_MAX_ENTRIES ?>; // Auto-Suggest max entries
var EW_DISABLE_BUTTON_ON_SUBMIT = true;
var EW_IMAGE_FOLDER = "phpimages/"; // Image folder
var EW_UPLOAD_URL = "<?php echo EW_UPLOAD_URL ?>"; // Upload URL
var EW_UPLOAD_TYPE = "<?php echo $EW_UPLOAD_TYPE ?>"; // Upload type
var EW_UPLOAD_THUMBNAIL_WIDTH = <?php echo EW_UPLOAD_THUMBNAIL_WIDTH ?>; // Upload thumbnail width
var EW_UPLOAD_THUMBNAIL_HEIGHT = <?php echo EW_UPLOAD_THUMBNAIL_HEIGHT ?>; // Upload thumbnail height
var EW_MULTIPLE_UPLOAD_SEPARATOR = "<?php echo EW_MULTIPLE_UPLOAD_SEPARATOR ?>"; // Upload multiple separator
var EW_USE_COLORBOX = <?php echo (EW_USE_COLORBOX) ? "true" : "false" ?>;
var EW_USE_JAVASCRIPT_MESSAGE = false;
var EW_MOBILE_DETECT = new MobileDetect(window.navigator.userAgent);
var EW_IS_MOBILE = !!EW_MOBILE_DETECT.mobile();
var EW_PROJECT_STYLESHEET_FILENAME = "<?php echo EW_PROJECT_STYLESHEET_FILENAME ?>"; // Project style sheet
var EW_PDF_STYLESHEET_FILENAME = "<?php echo EW_PDF_STYLESHEET_FILENAME ?>"; // PDF style sheet
var EW_TOKEN = "<?php echo @$gsToken ?>";
var EW_CSS_FLIP = <?php echo ($EW_CSS_FLIP) ? "true" : "false" ?>;
var EW_LAZY_LOAD = <?php echo ($EW_LAZY_LOAD) ? "true" : "false" ?>;
var EW_RESET_HEIGHT = <?php echo ($EW_RESET_HEIGHT) ? "true" : "false" ?>;
var EW_DEBUG_ENABLED = <?php echo (EW_DEBUG_ENABLED) ? "true" : "false" ?>;
var EW_CONFIRM_CANCEL = true;
var EW_SEARCH_FILTER_OPTION = "<?php echo EW_SEARCH_FILTER_OPTION ?>";
</script>
<?php } ?>
<?php if (@$gsExport == "" || @$gsExport == "print") { ?>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>phpjs/jsrender.min.js"></script>
<script type="text/javascript">
$.views.settings.debugMode(EW_DEBUG_ENABLED);
</script>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>phpjs/ewp14.js"></script>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>jquery/jquery.ewjtable.min.js"></script>
<?php } ?>
<?php if (@$gsExport == "" || @$gsExport == "print") { ?>
<script type="text/javascript">
<?php
	echo $Language->ToJson();
	SetupLoginStatus(); // Setup login status
	ew_SetClientVar("login", LoginStatus());
?>
var ewVar = <?php echo json_encode($EW_CLIENT_VAR); ?>;
</script>
<script type="text/javascript" src="<?php echo $EW_RELATIVE_PATH ?>phpjs/userfn14.js"></script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<meta name="generator" content="PHPMaker v2018.0.7">
</head>
<body class="<?php echo $EW_BODY_CLASS ?>" dir="<?php echo ($EW_CSS_FLIP) ? "rtl" : "ltr" ?>">
<?php if (@!$gbSkipHeaderFooter) { ?>
<?php if (@$gsExport == "") { ?>
<div class="wrapper ewLayout">
	<!-- Main Header -->
	<header class="main-header">
		<div class="logo">
			<!-- Logo //** Note: Only licensed users are allowed to change the logo ** -->
			<!-- mini logo for sidebar mini 50x50 pixels -->
			<span class="logo-mini"></span>
			<!-- logo for regular state and mobile devices -->
			<span class="logo-lg"><img src="<?php echo $EW_RELATIVE_PATH ?>phpimages/phpmkrlogo2017.png" alt=""></span>
		</div>
		<!-- Header Navbar -->
		<nav class="navbar navbar-static-top" role="navigation">
			<!-- Sidebar toggle button-->
			<a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
				<span class="sr-only">Toggle navigation</span>
			</a>
			<!-- Navbar custom menu (on right) -->
			<div class="navbar-custom-menu">
				<ul class="nav navbar-nav"></ul>
			</div>
		</nav>
	</header>
	<!-- Left side column, contains the logo and sidebar -->
	<aside class="main-sidebar">
		<!-- Sidebar -->
		<section class="sidebar">
		<!-- Sidebar menu -->
<?php include_once $EW_RELATIVE_PATH . "ewmenu.php" ?>
		<!-- /.sidebar-menu -->
		</section>
		<!-- /.sidebar -->
	</aside>
	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
<?php if (EW_PAGE_TITLE_STYLE <> "None") { ?>
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1><?php echo CurrentPageHeading() ?> <small><?php echo CurrentPageSubheading() ?></small></h1>
			<?php Breadcrumb()->Render() ?>
		</section>
<?php } ?>
		<!-- Main content -->
		<section class="content">
<?php } ?>
<?php } ?>

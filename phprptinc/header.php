<?php if (@$gsExport == "email" || @$gsExport == "pdf") ob_clean(); ?>
<?php if (@$gsExport == "" || @$gsExport == "print" || @$gsExport == "email" && @$gsEmailContentType == "url") { ?>
<script type="text/javascript">
var EWR_RELATIVE_PATH = "<?php echo $EWR_RELATIVE_PATH ?>";

function ewr_GetScript(url) { document.write("<" + "script type=\"text/javascript\" src=\"" + EWR_RELATIVE_PATH + url + "\"><" + "/script>"); }

function ewr_GetCss(url) { document.write("<link rel=\"stylesheet\" type=\"text/css\" href=\"" + EWR_RELATIVE_PATH + url + "\">"); }
var EWR_LANGUAGE_ID = "<?php echo $grLanguage ?>";
var EWR_DATE_SEPARATOR = "<?php echo $EWR_DATE_SEPARATOR ?>"; // Date separator
var EWR_TIME_SEPARATOR = "<?php echo $EWR_TIME_SEPARATOR ?>"; // Time separator
var EWR_DATE_FORMAT = "<?php echo $EWR_DATE_FORMAT ?>"; // Default date format
var EWR_DATE_FORMAT_ID = <?php echo $EWR_DATE_FORMAT_ID ?>; // Default date format ID
var EWR_DECIMAL_POINT = "<?php echo $EWR_DECIMAL_POINT ?>";
var EWR_THOUSANDS_SEP = "<?php echo $EWR_THOUSANDS_SEP ?>";
var EWR_SESSION_TIMEOUT = <?php echo (EWR_SESSION_TIMEOUT > 0) ? ewr_SessionTimeoutTime() : 0 ?>; // Session timeout time (seconds)
var EWR_SESSION_TIMEOUT_COUNTDOWN = <?php echo EWR_SESSION_TIMEOUT_COUNTDOWN ?>; // Count down time to session timeout (seconds)
var EWR_SESSION_KEEP_ALIVE_INTERVAL = <?php echo EWR_SESSION_KEEP_ALIVE_INTERVAL ?>; // Keep alive interval (seconds)
var EWR_SESSION_URL = EWR_RELATIVE_PATH + "rsession11.php"; // Session URL
var EWR_IS_LOGGEDIN = <?php echo IsLoggedIn() ? "true" : "false" ?>; // Is logged in
var EWR_IS_AUTOLOGIN = <?php echo ewr_IsAutoLogin() ? "true" : "false" ?>; // Is logged in with option "Auto login until I logout explicitly"
var EWR_TIMEOUT_URL = EWR_RELATIVE_PATH + "logout.php"; // Timeout URL
var EWR_MAX_EMAIL_RECIPIENT = <?php echo EWR_MAX_EMAIL_RECIPIENT ?>;
var EWR_DISABLE_BUTTON_ON_SUBMIT = true;
var EWR_IMAGE_FOLDER = "phprptimages/"; // Image folder
var EWR_LOOKUP_FILE_NAME = "rajax11.php"; // Lookup file name
var EWR_LOOKUP_FILTER_VALUE_SEPARATOR = "<?php echo EWR_LOOKUP_FILTER_VALUE_SEPARATOR ?>"; // Lookup filter value separator
var EWR_MODAL_LOOKUP_FILE_NAME = "rmodallookup11.php"; // Modal lookup file name
var EWR_AUTO_SUGGEST_MAX_ENTRIES = <?php echo EWR_AUTO_SUGGEST_MAX_ENTRIES ?>; // Auto-Suggest max entries
var EWR_USE_JAVASCRIPT_MESSAGE = false;
var EWR_PROJECT_STYLESHEET_FILENAME = "<?php echo EWR_PROJECT_STYLESHEET_FILENAME ?>"; // Project style sheet
var EWR_PDF_STYLESHEET_FILENAME = "<?php echo (EWR_PDF_STYLESHEET_FILENAME == "" ? EWR_PROJECT_STYLESHEET_FILENAME : EWR_PDF_STYLESHEET_FILENAME) ?>"; // Export PDF style sheet
var EWR_TOKEN = "<?php echo @$grToken ?>";
var EWR_CSS_FLIP = <?php echo ($EWR_CSS_FLIP) ? "true" : "false" ?>;
var EWR_RESET_HEIGHT = <?php echo ($EWR_RESET_HEIGHT) ? "true" : "false" ?>;
</script>
<?php } ?>
<?php if (@$gsExport == "" || @$gsExport == "print") { ?>
<script type="text/javascript">
if (!window.jQuery || !jQuery.fn.alert) {
	ewr_GetCss("bootstrap3/css/<?php echo ewr_CssFile("bootstrap.css") ?>");
	ewr_GetCss("adminlte/css/<?php echo ewr_CssFile("AdminLTE.css") ?>");
	ewr_GetCss("adminlte/css/font-awesome.min.css"); // Optional font
}
ewr_GetCss("colorbox/colorbox.css");
<?php foreach ($EWR_STYLESHEET_FILES as $cssfile) { // External Stylesheets ?>
ewr_GetCss("<?php echo $cssfile ?>");
<?php } ?>
<?php if (!@$grDrillDownInPanel) { ?>
ewr_GetCss("<?php echo ewr_CssFile(EWR_PROJECT_STYLESHEET_FILENAME) ?>");
<?php } ?>
</script>
<?php if (ewr_IsMobile()) { ?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php } ?>
<?php } else { ?>
<style type="text/css">
<?php $cssfile = (@$gsExport == "pdf") ? (EWR_PDF_STYLESHEET_FILENAME == "" ? EWR_PROJECT_STYLESHEET_FILENAME : EWR_PDF_STYLESHEET_FILENAME) : EWR_PROJECT_STYLESHEET_FILENAME ?>
<?php echo file_get_contents($cssfile) ?>
</style>
<?php } ?>
<?php if (@$gsExport == "" || @$gsExport == "print" || @$gsExport == "email" && @$gsEmailContentType == "url") { ?>
<script type="text/javascript">if (!window.jQuery) ewr_GetScript("jquery/jquery-3.2.1.min.js");</script>
<script type="text/javascript">if (window.jQuery && !window.jQuery.widget) ewr_GetScript("jquery/jquery.ui.widget.js");</script>
<script type="text/javascript">if (window.jQuery && !window.jQuery.localStorage) ewr_GetScript("jquery/jquery.storageapi.min.js");</script>
<script type="text/javascript">if (!window.moment) ewr_GetScript("moment/moment.min.js");</script>
<?php foreach ($EWR_JAVASCRIPT_FILES as $jsfile) { // External JavaScripts ?>
<script type="text/javascript">ewr_GetScript("<?php echo $jsfile ?>");</script>
<?php } ?>
<?php } ?>
<?php if (@$gsExport == "" || @$gsExport == "print" || @$gsExport == "email" && @$gsEmailContentType == "url") { ?>
<?php if (@$gsCustomExport == "") { ?>
<script type="text/javascript" src="<?php echo $EWR_RELATIVE_PATH . $EWR_FUSIONCHARTS_PATH ?>fusioncharts.js"></script>
<script type="text/javascript" src="<?php echo $EWR_RELATIVE_PATH . $EWR_FUSIONCHARTS_PATH ?>fusioncharts.ssgrid.js"></script>
<script type="text/javascript" src="<?php echo $EWR_RELATIVE_PATH . $EWR_FUSIONCHARTS_PATH ?>themes/fusioncharts.theme.ocean.js"></script>
<script type="text/javascript" src="<?php echo $EWR_RELATIVE_PATH . $EWR_FUSIONCHARTS_PATH ?>themes/fusioncharts.theme.carbon.js"></script>
<script type="text/javascript" src="<?php echo $EWR_RELATIVE_PATH . $EWR_FUSIONCHARTS_PATH ?>themes/fusioncharts.theme.zune.js"></script>
<?php if ($EWR_NON_FUSIONCHARTS) { ?>
<script type="text/javascript" src="<?php echo $EWR_RELATIVE_PATH . $EWR_NON_FUSIONCHARTS_PATH ?>fusioncharts.powercharts.js"></script>
<script type="text/javascript" src="<?php echo $EWR_RELATIVE_PATH . $EWR_NON_FUSIONCHARTS_PATH ?>fusioncharts.gantt.js"></script>
<?php } ?>
<?php if (!$EWR_NON_FUSIONCHARTS || $EWR_USE_GOOGLE_CHARTS) { ?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<?php } ?>
<script type="text/javascript">
var EWR_CHART_EXPORT_HANDLER = "<?php echo ewr_FullUrl("chartexporter11.php", "chartexport") ?>";
</script>
<?php } ?>
<?php if (@$gsExport == "") { ?>
<script type="text/javascript">if (window.jQuery && !jQuery.colorbox) ewr_GetScript("colorbox/jquery.colorbox-min.js");</script>
<?php } ?>
<script type="text/javascript">if (window.jQuery && typeof MobileDetect === 'undefined') ewr_GetScript("phprptjs/mobile-detect.min.js");</script>
<script type="text/javascript">ewr_GetScript("phprptjs/clipboard.min.js");</script>
<script type="text/javascript">ewr_GetScript("phprptjs/ewr11.js");</script>
<script type="text/javascript">if (window.jQuery && !window.jQuery.views) ewr_GetScript("phprptjs/jsrender.min.js");</script>
<script type="text/javascript">
if (window.jQuery && !jQuery.fn.alert) ewr_GetScript("bootstrap3/js/bootstrap.min.js");
if (window.jQuery && !window.jQuery.fileDownload) ewr_GetScript("jquery/jquery.fileDownload.min.js");
if (window.jQuery && !jQuery.typeahead) ewr_GetScript("phprptjs/typeahead.jquery.js");
</script>
<?php } ?>
<?php if (@$gsExport == "" || @$gsExport == "print") { ?>
<script type="text/javascript">
var EWR_MOBILE_DETECT = new MobileDetect(window.navigator.userAgent);
var EWR_IS_MOBILE = !!EWR_MOBILE_DETECT.mobile();
<?php
	echo $ReportLanguage->ToJson();
?>
var ewrVar = <?php echo json_encode($EWR_CLIENT_VAR); ?>;
</script>
<script type="text/javascript">ewr_GetScript("phprptjs/rusrfn11.js");</script>
<script type="text/javascript">if (window.jQuery && !jQuery.fn.layout) ewr_GetScript("adminlte/js/adminlte.js");</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>

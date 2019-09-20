<?php if (@$gsExport == "") { ?>
<!-- Create report URL dialog -->
<div id="ewrReportUrlDialog" class="modal"><div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header"><h4 class="modal-title"></h4></div>
<div class="modal-body">
<?php include_once "rurl11.php" ?>
</div><div class="modal-footer"><button type="button" class="btn btn-primary ewButton"><?php echo $ReportLanguage->Phrase("Generate") ?></button><button class="btn btn-default ewButton ewCopyToClipboard" data-clipboard-target="#ewrUrl"><?php echo $ReportLanguage->Phrase("CopyToClipboard") ?></button><button type="button" class="btn btn-default ewButton" data-dismiss="modal" aria-hidden="true"><?php echo $ReportLanguage->Phrase("CloseBtn") ?></button></div></div></div></div>
<!-- modal dialog -->
<div id="ewrModalDialog" class="modal" role="dialog" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h4 class="modal-title"></h4></div><div class="modal-body"></div><div class="modal-footer"></div></div></div></div>
<!-- email dialog -->
<div id="ewrEmailDialog" class="modal"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h4 class="modal-title"></h4></div>
<div class="modal-body">
<?php include_once "remail11.php" ?>
</div><div class="modal-footer"><button type="button" class="btn btn-primary ewButton"><?php echo $ReportLanguage->Phrase("SendEmailBtn") ?></button><button type="button" class="btn btn-default ewButton" data-dismiss="modal" aria-hidden="true"><?php echo $ReportLanguage->Phrase("Cancel") ?></button></div></div></div></div>
<!-- message box -->
<div id="ewrMsgBox" class="modal"><div class="modal-dialog"><div class="modal-content"><div class="modal-body"></div><div class="modal-footer"><button type="button" class="btn btn-primary ewButton" data-dismiss="modal" aria-hidden="true"><?php echo $ReportLanguage->Phrase("MessageOK") ?></button></div></div></div></div>
<!-- prompt -->
<div id="ewrPrompt" class="modal"><div class="modal-dialog"><div class="modal-content"><div class="modal-body"></div><div class="modal-footer"><button type="button" class="btn btn-primary ewButton"><?php echo $ReportLanguage->Phrase("MessageOK") ?></button><button type="button" class="btn btn-default ewButton" data-dismiss="modal"><?php echo $ReportLanguage->Phrase("Cancel") ?></button></div></div></div></div>
<!-- session timer -->
<div id="ewrTimer" class="modal" role="dialog" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-body"></div><div class="modal-footer"><button type="button" class="btn btn-primary ewButton" data-dismiss="modal"><?php echo $ReportLanguage->Phrase("MessageOK") ?></button></div></div></div></div>
<!-- popup filter -->
<div id="ewrPopupFilterDialog"></div>
<!-- export chart -->
<div id="ewrExportDialog"></div>
<!-- drill down -->
<?php if (@!$grDrillDownInPanel) { ?>
<div id="ewrDrillDownPanel"></div>
<?php } ?>
<script type="text/javascript">ewr_GetScript("phprptjs/rusrevt11.js");</script>
<?php } ?>

<form id="ewrReportUrlForm" class="form-horizontal ewForm ewReportUrlForm" action="<?php echo ewr_CurrentPage() ?>">
<input type="hidden" name="generateurl" value="1">
<?php if ($Page->CheckToken) { ?>
<input type="hidden" name="<?php echo EWR_TOKEN_NAME ?>" value="<?php echo $Page->Token ?>">
<?php } ?>
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel" for="ewrReportType"><?php echo $ReportLanguage->Phrase("ReportFormType") ?></label>
		<div class="col-sm-9">
			<select class="form-control ewControl" name="reporttype" id="ewrReportType">
<?php foreach ($ReportOptions["ReportTypes"] as $val => $name) { ?>
	<?php if ($val <> "" && $name <> "") { ?>
				<option value="<?php echo $val ?>"><?php echo $name ?></option>
	<?php } ?>
<?php } ?>
			</select>
		</div>
	</div>
<?php if (count($ReportOptions["UserNameList"]) == 1) { ?>
<input type="hidden" name="username" value="<?php echo key($ReportOptions["UserNameList"]) ?>">
<?php } elseif (count($ReportOptions["UserNameList"]) > 1) { ?>
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel" for="ewrUserName"><?php echo $ReportLanguage->Phrase("ReportFormUserName") ?></label>
		<div class="col-sm-9">
			<select class="form-control ewControl" name="username" id="ewrUserName">
	<?php foreach ($ReportOptions["UserNameList"] as $usr => $name) { ?>
				<option value="<?php echo $usr ?>"><?php echo $name ?></option>
	<?php } ?>
			</select>
		</div>
	</div>
<?php } ?>
<?php if (@$ReportOptions["ShowFilter"] === TRUE) { ?>
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel" for="ewrFilterName"><?php echo $ReportLanguage->Phrase("ReportFormFilterName") ?></label>
		<div class="col-sm-9">
			<select class="form-control ewControl" name="filtername" id="ewrFilterName">
				<option value=""><?php echo $ReportLanguage->Phrase("ReportFormFilterNone") ?></option>
				<option value="@@current" selected><?php echo $ReportLanguage->Phrase("ReportFormFilterCurrent") ?></option>
			</select>
		</div>
	</div>
<?php } ?>
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel" for="ewrPageOption"><?php echo $ReportLanguage->Phrase("ReportFormPageOption") ?></label>
		<div class="col-sm-9">
		<select class="form-control ewControl" name="pageoption" id="ewrPageOption">
			<option value="first"><?php echo $ReportLanguage->Phrase("ReportFormFirstPage") ?></option>
			<option value="all"><?php echo $ReportLanguage->Phrase("ReportFormAllPages") ?></option>
		</select>
		</div>
	</div>
	<div class="form-group ewReportEmail hidden">
		<label class="col-sm-3 control-label ewLabel" for="ewrReportSender"><?php echo $ReportLanguage->Phrase("ReportFormSender") ?></label>
		<div class="col-sm-9"><input type="text" class="form-control ewControl" name="sender" id="ewrReportSender"></div>
	</div>
	<div class="form-group ewReportEmail hidden">
		<label class="col-sm-3 control-label ewLabel" for="ewrReportRecipient"><?php echo $ReportLanguage->Phrase("ReportFormRecipient") ?></label>
		<div class="col-sm-9"><input type="text" class="form-control ewControl" name="recipient" id="ewrReportRecipient"></div>
	</div>
	<div class="form-group ewReportEmail hidden">
		<label class="col-sm-3 control-label ewLabel" for="ewrReportCc"><?php echo $ReportLanguage->Phrase("ReportFormCc") ?></label>
		<div class="col-sm-9"><input type="text" class="form-control ewControl" name="cc" id="ewrReportCc"></div>
	</div>
	<div class="form-group ewReportEmail hidden">
		<label class="col-sm-3 control-label ewLabel" for="ewrReportBcc"><?php echo $ReportLanguage->Phrase("ReportFormBcc") ?></label>
		<div class="col-sm-9"><input type="text" class="form-control ewControl" name="bcc" id="ewrReportBcc"></div>
	</div>
	<div class="form-group ewReportEmail hidden">
		<label class="col-sm-3 control-label ewLabel" for="ewrReportSubject"><?php echo $ReportLanguage->Phrase("ReportFormSubject") ?></label>
		<div class="col-sm-9"><input type="text" class="form-control ewControl" name="subject" id="ewrReportSubject"></div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel"><?php echo $ReportLanguage->Phrase("ReportFormResponseType") ?></label>
		<div class="col-sm-9">
			<label class="radio-inline ewRadio"><input type="radio" name="responsetype" value="json" checked><?php echo $ReportLanguage->Phrase("ReportFormResponseTypeJson") ?></label>
			<label class="radio-inline ewRadio"><input type="radio" name="responsetype" value="file"><?php echo $ReportLanguage->Phrase("ReportFormResponseTypeFile") ?></label>
		</div>
	</div>
<?php if (@$ReportOptions["ShowFilter"] === TRUE) { ?>
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel"><?php echo $ReportLanguage->Phrase("ReportFormShowCurrentFilter") ?></label>
		<div class="col-sm-9">
			<label class="ewCheckbox"><input type="checkbox" name="showcurrentfilter" value="1" checked></label>
		</div>
	</div>
<?php } ?>
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel" for="ewrUrl"><?php echo $ReportLanguage->Phrase("ReportFormUrl") ?></label>
		<div class="col-sm-9"><textarea readonly class="form-control ewControl" rows="6" name="url" id="ewrUrl"></textarea></div>
	</div>
</form>
<p></p>

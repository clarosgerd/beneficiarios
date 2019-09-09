<?php

// id_especialista
// especialidad
// fecha
// id_escolar
// id_neonato
// id_otros
// observaciones
// id_atencion

?>
<?php if ($audiologia->Visible) { ?>
<div class="ewMasterDiv">
<table id="tbl_audiologiamaster" class="table ewViewTable ewMasterTable ewVertical">
	<tbody>
<?php if ($audiologia->id_especialista->Visible) { // id_especialista ?>
		<tr id="r_id_especialista">
			<td class="col-sm-2"><?php echo $audiologia->id_especialista->FldCaption() ?></td>
			<td<?php echo $audiologia->id_especialista->CellAttributes() ?>>
<span id="el_audiologia_id_especialista">
<span<?php echo $audiologia->id_especialista->ViewAttributes() ?>>
<?php echo $audiologia->id_especialista->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($audiologia->especialidad->Visible) { // especialidad ?>
		<tr id="r_especialidad">
			<td class="col-sm-2"><?php echo $audiologia->especialidad->FldCaption() ?></td>
			<td<?php echo $audiologia->especialidad->CellAttributes() ?>>
<span id="el_audiologia_especialidad">
<span<?php echo $audiologia->especialidad->ViewAttributes() ?>>
<?php echo $audiologia->especialidad->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($audiologia->fecha->Visible) { // fecha ?>
		<tr id="r_fecha">
			<td class="col-sm-2"><?php echo $audiologia->fecha->FldCaption() ?></td>
			<td<?php echo $audiologia->fecha->CellAttributes() ?>>
<span id="el_audiologia_fecha">
<span<?php echo $audiologia->fecha->ViewAttributes() ?>>
<?php echo $audiologia->fecha->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($audiologia->id_escolar->Visible) { // id_escolar ?>
		<tr id="r_id_escolar">
			<td class="col-sm-2"><?php echo $audiologia->id_escolar->FldCaption() ?></td>
			<td<?php echo $audiologia->id_escolar->CellAttributes() ?>>
<span id="el_audiologia_id_escolar">
<span<?php echo $audiologia->id_escolar->ViewAttributes() ?>>
<?php echo $audiologia->id_escolar->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($audiologia->id_neonato->Visible) { // id_neonato ?>
		<tr id="r_id_neonato">
			<td class="col-sm-2"><?php echo $audiologia->id_neonato->FldCaption() ?></td>
			<td<?php echo $audiologia->id_neonato->CellAttributes() ?>>
<span id="el_audiologia_id_neonato">
<span<?php echo $audiologia->id_neonato->ViewAttributes() ?>>
<?php echo $audiologia->id_neonato->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($audiologia->id_otros->Visible) { // id_otros ?>
		<tr id="r_id_otros">
			<td class="col-sm-2"><?php echo $audiologia->id_otros->FldCaption() ?></td>
			<td<?php echo $audiologia->id_otros->CellAttributes() ?>>
<span id="el_audiologia_id_otros">
<span<?php echo $audiologia->id_otros->ViewAttributes() ?>>
<?php echo $audiologia->id_otros->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($audiologia->observaciones->Visible) { // observaciones ?>
		<tr id="r_observaciones">
			<td class="col-sm-2"><?php echo $audiologia->observaciones->FldCaption() ?></td>
			<td<?php echo $audiologia->observaciones->CellAttributes() ?>>
<span id="el_audiologia_observaciones">
<span<?php echo $audiologia->observaciones->ViewAttributes() ?>>
<?php echo $audiologia->observaciones->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($audiologia->id_atencion->Visible) { // id_atencion ?>
		<tr id="r_id_atencion">
			<td class="col-sm-2"><?php echo $audiologia->id_atencion->FldCaption() ?></td>
			<td<?php echo $audiologia->id_atencion->CellAttributes() ?>>
<span id="el_audiologia_id_atencion">
<span<?php echo $audiologia->id_atencion->ViewAttributes() ?>>
<?php echo $audiologia->id_atencion->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</div>
<?php } ?>

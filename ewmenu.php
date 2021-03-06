<?php

// Menu
$RootMenu = new cMenu("RootMenu", TRUE);
$RootMenu->AddMenuItem(14, "mci_Registros", $Language->MenuPhrase("14", "MenuText"), "", -1, "", IsLoggedIn(), FALSE, TRUE, "");
$RootMenu->AddMenuItem(2, "mi_centros", $Language->MenuPhrase("2", "MenuText"), "centroslist.php", 14, "", AllowListMenu('{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}centros'), FALSE, FALSE, "fa fa-circle-o");
$RootMenu->AddMenuItem(8, "mi_unidadeducativa", $Language->MenuPhrase("8", "MenuText"), "unidadeducativalist.php", 14, "", AllowListMenu('{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}unidadeducativa'), FALSE, FALSE, "fa fa-circle-o");
$RootMenu->AddMenuItem(6, "mi_institucionesdesalud", $Language->MenuPhrase("6", "MenuText"), "institucionesdesaludlist.php", 14, "", AllowListMenu('{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}institucionesdesalud'), FALSE, FALSE, "fa fa-circle-o");
$RootMenu->AddMenuItem(11, "mi_estudiante", $Language->MenuPhrase("11", "MenuText"), "estudiantelist.php", 14, "", AllowListMenu('{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}estudiante'), FALSE, FALSE, "fa fa-circle-o");
$RootMenu->AddMenuItem(12, "mi_actividad", $Language->MenuPhrase("12", "MenuText"), "actividadlist.php", 14, "", AllowListMenu('{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}actividad'), FALSE, FALSE, "fa fa-circle-o");
$RootMenu->AddMenuItem(13, "mi_participante", $Language->MenuPhrase("13", "MenuText"), "participantelist.php", 14, "", AllowListMenu('{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}participante'), FALSE, FALSE, "fa fa-circle-o");
$RootMenu->AddMenuItem(9, "mi_docente", $Language->MenuPhrase("9", "MenuText"), "docentelist.php", 14, "", AllowListMenu('{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}docente'), FALSE, FALSE, "fa fa-circle-o");
$RootMenu->AddMenuItem(10, "mi_especialista", $Language->MenuPhrase("10", "MenuText"), "especialistalist.php", 14, "", AllowListMenu('{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}especialista'), FALSE, FALSE, "fa fa-circle-o");
$RootMenu->AddMenuItem(28, "mci_Tamizaje", $Language->MenuPhrase("28", "MenuText"), "", -1, "", IsLoggedIn(), FALSE, TRUE, "");
$RootMenu->AddMenuItem(3, "mi_escolar", $Language->MenuPhrase("3", "MenuText"), "escolarlist.php", 28, "", AllowListMenu('{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}escolar'), FALSE, FALSE, "fa fa-circle-o");
$RootMenu->AddMenuItem(33, "mi_otros", $Language->MenuPhrase("33", "MenuText"), "otroslist.php", 28, "", AllowListMenu('{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}otros'), FALSE, FALSE, "fa fa-circle-o");
$RootMenu->AddMenuItem(32, "mi_neonatal", $Language->MenuPhrase("32", "MenuText"), "neonatallist.php", 28, "", AllowListMenu('{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}neonatal'), FALSE, FALSE, "fa fa-circle-o");
$RootMenu->AddMenuItem(1, "mi_apoderado", $Language->MenuPhrase("1", "MenuText"), "apoderadolist.php", 28, "", AllowListMenu('{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}apoderado'), FALSE, FALSE, "fa fa-circle-o");
$RootMenu->AddMenuItem(4, "mi_referencia", $Language->MenuPhrase("4", "MenuText"), "referencialist.php", 28, "", AllowListMenu('{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}referencia'), FALSE, FALSE, "fa fa-circle-o");
$RootMenu->AddMenuItem(29, "mci_Administracion", $Language->MenuPhrase("29", "MenuText"), "", -1, "", IsLoggedIn(), FALSE, TRUE, "");
$RootMenu->AddMenuItem(30, "mi_persona", $Language->MenuPhrase("30", "MenuText"), "personalist.php", 29, "", AllowListMenu('{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}persona'), FALSE, FALSE, "fa fa-circle-o");
$RootMenu->AddMenuItem(5, "mi_tipocentro", $Language->MenuPhrase("5", "MenuText"), "tipocentrolist.php", 29, "", AllowListMenu('{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}tipocentro'), FALSE, FALSE, "fa fa-circle-o");
$RootMenu->AddMenuItem(31, "mi_sector", $Language->MenuPhrase("31", "MenuText"), "sectorlist.php", 29, "", AllowListMenu('{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}sector'), FALSE, FALSE, "fa fa-circle-o");
$RootMenu->AddMenuItem(34, "mi_categoria", $Language->MenuPhrase("34", "MenuText"), "categorialist.php", 29, "", AllowListMenu('{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}categoria'), FALSE, FALSE, "fa fa-circle-o");
$RootMenu->AddMenuItem(35, "mi_usuario", $Language->MenuPhrase("35", "MenuText"), "usuariolist.php", 29, "", AllowListMenu('{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}usuario'), FALSE, FALSE, "fa fa-circle-o");
$RootMenu->AddMenuItem(36, "mi_userlevels", $Language->MenuPhrase("36", "MenuText"), "userlevelslist.php", 29, "", IsAdmin(), FALSE, FALSE, "fa fa-circle-o");
$RootMenu->AddMenuItem(37, "mi_userlevelpermissions", $Language->MenuPhrase("37", "MenuText"), "userlevelpermissionslist.php", 29, "", IsAdmin(), FALSE, FALSE, "fa fa-circle-o");
$RootMenu->AddMenuItem(38, "mi_tipoactividad", $Language->MenuPhrase("38", "MenuText"), "tipoactividadlist.php", 29, "", AllowListMenu('{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}tipoactividad'), FALSE, FALSE, "fa fa-circle-o");
$RootMenu->AddMenuItem(39, "mi_ciudad", $Language->MenuPhrase("39", "MenuText"), "ciudadlist.php", 29, "", AllowListMenu('{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}ciudad'), FALSE, FALSE, "fa fa-circle-o");
$RootMenu->AddMenuItem(40, "mi_departamento", $Language->MenuPhrase("40", "MenuText"), "departamentolist.php", 29, "", AllowListMenu('{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}departamento'), FALSE, FALSE, "fa fa-circle-o");
$RootMenu->AddMenuItem(41, "mi_discapacidad", $Language->MenuPhrase("41", "MenuText"), "discapacidadlist.php", 29, "", AllowListMenu('{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}discapacidad'), FALSE, FALSE, "fa fa-circle-o");
$RootMenu->AddMenuItem(42, "mi_tipodiscapacidad", $Language->MenuPhrase("42", "MenuText"), "tipodiscapacidadlist.php", 29, "", AllowListMenu('{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}tipodiscapacidad'), FALSE, FALSE, "fa fa-circle-o");
$RootMenu->AddMenuItem(43, "mi_medio", $Language->MenuPhrase("43", "MenuText"), "mediolist.php", 29, "", AllowListMenu('{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}medio'), FALSE, FALSE, "fa fa-circle-o");
$RootMenu->AddMenuItem(44, "mi_tapon", $Language->MenuPhrase("44", "MenuText"), "taponlist.php", 29, "", AllowListMenu('{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}tapon'), FALSE, FALSE, "fa fa-circle-o");
$RootMenu->AddMenuItem(57, "mi_municipio", $Language->MenuPhrase("57", "MenuText"), "municipiolist.php", 29, "", AllowListMenu('{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}municipio'), FALSE, FALSE, "fa fa-circle-o");
$RootMenu->AddMenuItem(58, "mi_provincia", $Language->MenuPhrase("58", "MenuText"), "provincialist.php", 29, "", AllowListMenu('{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}provincia'), FALSE, FALSE, "fa fa-circle-o");
$RootMenu->AddMenuItem(65, "mi_tipodiagnosticoaudiologia", $Language->MenuPhrase("65", "MenuText"), "tipodiagnosticoaudiologialist.php", 29, "", AllowListMenu('{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}tipodiagnosticoaudiologia'), FALSE, FALSE, "fa fa-circle-o");
$RootMenu->AddMenuItem(63, "mi_tipopruebasaudiologia", $Language->MenuPhrase("63", "MenuText"), "tipopruebasaudiologialist.php", 29, "", AllowListMenu('{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}tipopruebasaudiologia'), FALSE, FALSE, "fa fa-circle-o");
$RootMenu->AddMenuItem(66, "mi_tipotratamientoaudiologia", $Language->MenuPhrase("66", "MenuText"), "tipotratamientoaudiologialist.php", 29, "", AllowListMenu('{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}tipotratamientoaudiologia'), FALSE, FALSE, "fa fa-circle-o");
$RootMenu->AddMenuItem(69, "mi_tipoespecialidad", $Language->MenuPhrase("69", "MenuText"), "tipoespecialidadlist.php", 29, "", AllowListMenu('{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}tipoespecialidad'), FALSE, FALSE, "fa fa-circle-o");
$RootMenu->AddMenuItem(53, "mci_Servicios_de_Atencion", $Language->MenuPhrase("53", "MenuText"), "", -1, "", IsLoggedIn(), FALSE, TRUE, "");
$RootMenu->AddMenuItem(54, "mi_ticket_php", $Language->MenuPhrase("54", "MenuText"), "ticket.php", 53, "", AllowListMenu('{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}ticket.php'), FALSE, TRUE, "fa fa-circle-o");
$RootMenu->AddMenuItem(55, "mi_audiologia", $Language->MenuPhrase("55", "MenuText"), "audiologialist.php", 53, "", AllowListMenu('{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}audiologia'), FALSE, FALSE, "fa fa-circle-o");
$RootMenu->AddMenuItem(121, "mci_Reportes", $Language->MenuPhrase("121", "MenuText"), "", -1, "", TRUE, FALSE, TRUE, "");
$RootMenu->AddMenuItem(122, "mi_Report_Neonatal", $Language->MenuPhrase("122", "MenuText"), "Report_Neonatallist.php", 121, "", AllowListMenu('{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}Report Neonatal'), FALSE, FALSE, "fa fa-circle-o");
$RootMenu->AddMenuItem(71, "mi_Reporte_Neontal", $Language->MenuPhrase("71", "MenuText"), "Reporte_Neontalreport.php", 121, "", AllowListMenu('{2CC8AC78-3FBF-476E-B72B-9E6EDDABE9B2}Reporte Neontal'), FALSE, FALSE, "fa fa-circle-o");
echo $RootMenu->ToScript();
?>
<div class="ewVertical" id="ewMenu"></div>

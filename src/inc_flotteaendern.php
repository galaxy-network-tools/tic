<?php
// Ticks

//	$Ticks['angriffsflug'] = 30;
//	$Ticks['angriff'] = 5;
//	$Ticks['verteidigungsflug'] = 22;
//	$Ticks['verteidigen'] = 25;
//	$Ticks['lange'] = 15

	$tickm = $Ticks['lange'];

/*
	Änderungen für TIC mit hh:mm
	alle Vorkommen von
	($n * $tickm - $tick_abzug)
	ersetzen durch
	(($n * $tickm - $tick_abzug)<0?"00:00":sprintf("%02d:%02d",(($n * $tickm - $tick_abzug)/60),(($n * $tickm - $tick_abzug)%60)))
	und alle Vorkommen von
	($n * $tickm)
	ersetzen durch
	sprintf("%02d:%02d",(int)(($n * $tickm)/60),(int)(($n * $tickm)%60))
*/
    if (isset($_GET['id'])) $_POST['id']=$_GET['id'];
    if (isset($_GET['flottenid'])) $_POST['flottenid']=$_GET['flottenid'];
	if (!isset($_POST['flottenid'])) $_POST['flottenid'] = '';
	if (!isset($_POST['id'])) $_POST['id'] = '';
	if ($_POST['flottenid'] == '' || $_POST['id'] == '') $error_code = 8;
	$SQL_Result = tic_mysql_query('SELECT * FROM `gn4flottenbewegungen` WHERE id="'.$_POST['flottenid'].'";', $SQL_DBConn) or $error_code = 4;
	if (mysqli_num_rows($SQL_Result) != 1) $error_code = 8;
	if ($error_code != 0)
		include('./inc_errors.php');
	else {
?>
<CENTER>
	<TABLE>
		<TR>
			<TD BGCOLOR=#333333><FONT COLOR=#FFFFFF><B>Flottenbewegung ändern</B></FONT></TD>
		</TR>
		<TR>
			<TD>
				<P CLASS="hell">
					<FORM ACTION="./main.php" METHOD="POST" NAME="frmAendern">
						<INPUT TYPE="hidden" NAME="modul" VALUE="anzeigen">
						<INPUT TYPE="hidden" NAME="id" VALUE="<?=$_POST['id']?>">
						<INPUT TYPE="hidden" NAME="action" VALUE="flotteaendern">
						<INPUT TYPE="hidden" NAME="flottenid" VALUE="<?=$_POST['flottenid']?>">
						<TABLE>
							<TR>
								<TD>
									<?php
										$tmp_selected = 0;
										if (tic_mysql_result($SQL_Result, 0, 'modus') == 1) {
											echo '<INPUT TYPE="radio" NAME="optModus" VALUE="1" CHECKED><B>'.tic_mysql_result($SQL_Result, 0, 'angreifer_galaxie').':'.tic_mysql_result($SQL_Result, 0, 'angreifer_planet').' </B> greift <B>'.tic_mysql_result($SQL_Result, 0, 'verteidiger_galaxie').':'.tic_mysql_result($SQL_Result, 0, 'verteidiger_planet').' </B> an. ETA: <SELECT NAME="lst_ETA" SIZE=1>';
											for ($n = $Ticks['angriffsflug']; $n >= 0; $n--) {
												if ($n == eta(tic_mysql_result($SQL_Result, 0, 'ankunft')))
													$tmp = ' SELECTED';
												else
													$tmp = '';
												echo '<OPTION VALUE="'.$n.'"'.$tmp.'>'.getime4display($n * $Ticks['lange'] - $tick_abzug).'</OPTION>';
											}
											echo '</SELECT> Angriffszeit: <SELECT NAME="lst_Flugzeit" SIZE=1>';
											for ($n = $Ticks['angriffsflug'] - 1; $n >= 0; $n--) {
												if ($n == tic_mysql_result($SQL_Result, 0, 'flugzeit'))
													$tmp = ' SELECTED';
												else
													$tmp = '';
												echo '<OPTION VALUE="'.$n.'"'.$tmp.'>'.getime4display($n * $Ticks['lange']).'</OPTION>';
											}
											echo '</SELECT><BR>';
											$tmp_selected = 1;
										} elseif (tic_mysql_result($SQL_Result, 0, 'modus') == 2) {
											echo '<INPUT TYPE="radio" NAME="optModus" VALUE="2" CHECKED><B>'.tic_mysql_result($SQL_Result, 0, 'angreifer_galaxie').':'.tic_mysql_result($SQL_Result, 0, 'angreifer_planet').'</B> verteidigt <B>'.tic_mysql_result($SQL_Result, 0, 'verteidiger_galaxie').':'.tic_mysql_result($SQL_Result, 0, 'verteidiger_planet').'</B>. ETA: <SELECT NAME="lst_ETA" SIZE=1>';
											for ($n = $Ticks['verteidigungsflug']; $n >= 0; $n--) {
												if ($n == tic_mysql_result($SQL_Result, 0, 'eta'))
													$tmp = ' SELECTED';
												else
													$tmp = '';
												echo '<OPTION VALUE="'.$n.'"'.$tmp.'>'.getime4display($n * $Ticks['lange'] - $tick_abzug).'</OPTION>';
											}
											echo '</SELECT> Verteidigungszeit: <SELECT NAME="lst_Flugzeit" SIZE=1>';
											for ($n = $Ticks['angriffsflug'] - 1; $n >= 0; $n--) {
												if ($n == tic_mysql_result($SQL_Result, 0, 'flugzeit'))
													$tmp = ' SELECTED';
												else
													$tmp = '';
												echo '<OPTION VALUE="'.$n.'"'.$tmp.'>'.getime4display($n * $Ticks['lange']).'</OPTION>';
											}
											echo '</SELECT><BR>';
											$tmp_selected = 1;
										}
										if ($tmp_selected == 0)
											echo '<INPUT TYPE="radio" NAME="optModus" VALUE="0" CHECKED>';
										else
											echo '<INPUT TYPE="radio" NAME="optModus" VALUE="0">';
										echo 'Flotte befindet sich auf dem Rückflug von <B>'.tic_mysql_result($SQL_Result, 0, 'verteidiger_galaxie').':'.tic_mysql_result($SQL_Result, 0, 'verteidiger_planet').'</B>.  ETA: <SELECT NAME="lst_ETA0" SIZE=1>';
										for ($n = $Ticks['angriffsflug']; $n > 0; $n--) {
											if ($n == ($Ticks['angriffsflug'] - tic_mysql_result($SQL_Result, 0, 'eta') + 1))
													$tmp = ' SELECTED';
												else
													$tmp = '';
											echo '<OPTION VALUE="'.$n.'"'.$tmp.'>'.getime4display($n * $Ticks['lange'] - $tick_abzug).'</OPTION>';
										}
										echo '</SELECT>';
									?>
								</TD>
								<TD>
									Flotte:
									<SELECT NAME="lst_Flotte" SIZE=1>
										<?php
										$_name['0']='Unbekannt';
                    $_name['1']='1. Flotte';
                    $_name['2']='2. Flotte';
                    for($x=0;$x<3;$x++){
											$tmp ='';
                      if($x== tic_mysql_result($SQL_Result, 0, 'flottennr')){
											$tmp = ' SELECTED';
                      }
											echo '<OPTION VALUE="'.$x.'"'.$tmp.'>'.$_name[$x].'</OPTION>';
										}
                    ?>
									</SELECT>
									<A HREF="javascript:document.frmAendern.submit()">Flottenbewegung ändern</A>
								</TD>
							</TR>
						</TABLE>
					</FORM>
				</P>
			</TD>
		</TR>
	</TABLE>
<CENTER>
<?php
	}
?>

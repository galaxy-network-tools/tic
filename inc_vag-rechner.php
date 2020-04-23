<?php

function vag_errechnen() {
	global $kosten, $alg_format, $vag, $vag_faktor;

	$vag['m']['summe'] = 0;
	$vag['k']['summe'] = 0;
	$len0 = count($alg_format['schiffe']) + count($alg_format['deff']);
	for ($i0 = 0; $i0 < $len0; $i0++) {
		if ($i0 < 9) {
			$name = $alg_format['schiffe'][$i0];
		} else {
			$name = $alg_format['deff'][$i0 - 9];
		}
		$vag['m'][$name] = $_GET["vag_".$name] * $kosten['m'][$name];
		$vag['k'][$name] = $_GET["vag_".$name] * $kosten['k'][$name];
		$vag['m']['summe'] = $vag['m']['summe'] + $vag['m'][$name];
		$vag['k']['summe'] = $vag['k']['summe'] + $vag['k'][$name];
		$vag['m'][$name] = number_format($_GET["vag_".$name] * $kosten['m'][$name], 0, ',', ' ');
		$vag['k'][$name] = number_format($_GET["vag_".$name] * $kosten['k'][$name], 0, ',', ' ');
	}
	$vag['m']['v_summe'] = number_format($vag['m']['summe'], 0, ',', ' ');
	$vag['k']['v_summe'] = number_format($vag['k']['summe'], 0, ',', ' ');
	$vag['m']['t_summe'] = number_format($vag['m']['summe'] / $vag_faktor, 0, ',', ' ');
	$vag['k']['t_summe'] = number_format($vag['k']['summe'] / $vag_faktor, 0, ',', ' ');
}

function vag_rechner() {
	global $html_format, $alg_format, $vag, $kosten;

	echo '<center>';
	echo '<font size="+2">';
		echo '<u>V</u>erlust<u>a</u>us<u>g</u>leich-Rechner';
		echo '<br /><br />';
	echo '</font>';
	echo '<table border="0" align="center"><form action="main.php" method="get">';
	echo '<input type="hidden" name="modul" value="vag-rechner">';
		echo '<tr align="center">';
			echo '<td class="datatablehead">Schiffe/Deff</td>';
			echo '<td class="datatablehead">Verloren</td>';
			echo '<td class="datatablehead">Metall</td>';
			echo '<td class="datatablehead">Kristall</td>';
		echo '</tr>';
		$len0 = count($html_format['schiffe']) + count($html_format['deff']);
		for ($i0 = 0; $i0 < $len0; $i0++) {
			if ($i0 % 2 == 0) { $css_format = 'class="fieldnormallight"'; } else { $css_format = 'class="fieldnormaldark"'; }
			echo '<tr '.$css_format.'>';
				if ($i0 < 9) {
					$name = "vag_".$alg_format['schiffe'][$i0];
					$alg_name = $alg_format['schiffe'][$i0];
					$ausgabe = $html_format['schiffe'][$i0];
				} else {
					$z0 = $i0 - 9;
					$name = "vag_".$alg_format['deff'][$z0];
					$alg_name = $alg_format['deff'][$z0];
					$ausgabe = $html_format['deff'][$z0];
				}
				$anzeige = "Metall:".number_format($kosten['m'][$alg_name], 0, ',', ' ');
				$anzeige .= " <br />Kristall:".number_format($kosten['k'][$alg_name], 0, ',', ' ');
				echo '<td align="left">';
					echo '<font onmouseover="return overlib(\''.$anzeige.'\');" onmouseout="return nd();">'.$ausgabe.'</font>';
				echo '</td>';
				if (!isset($_GET[$name])) { $_GET[$name] = 0; }
				echo '<td align="center"><input type"text" size="8" name="'.$name.'" value="'.$_GET[$name].'"></td>';
				echo '<td align="center">'.$vag['m'][$alg_name].'</td>';
				echo '<td align="center">'.$vag['k'][$alg_name].'</td>';
			echo '</tr>';
		}
		echo '<tr height="20"><td colspan="4"></td></tr>';
		echo '<tr>';
			echo '<td colspan="2" align="right">Gesammt Verluste:</td>';
			echo '<td align="center">'.$vag['m']['v_summe'].'</td>';
			echo '<td align="center">'.$vag['k']['v_summe'].'</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td colspan="2" align="right">Verlustausgleich:</td>';
			echo '<td align="center"><font color="#32cd32">'.$vag['m']['t_summe'].'</font></td>';
			echo '<td align="center"><font color="#32cd32">'.$vag['k']['t_summe'].'</font></td>';
		echo '</tr>';
		echo '<tr align="center">';
			echo '<td colspan="4"><br /><input type="submit" name="vag-errechnen" value="Senden"></td>';
		echo '</tr>';
	echo '</form></table>';
	if (isset($_GET['vag-errechnen']) && ($vag['m']['v_summe'] + $vag['k']['v_summe']) != 0) {
		$url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		echo '<br /><a href="'.$url.'" target="_blank"><font size="+1"><u>Link<u/></font></a>';
	}
	echo '</center>';
}

vag_errechnen();
vag_rechner();

?>
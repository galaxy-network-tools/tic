<table align="center">
<?/*
  <tr>
    <td class="datatablehead">Attplaner I</td>
  </tr>
  <tr>
    <td class="fieldnormallight"><a href="?modul=massinc">Koordination</a></td>
  </tr>
  <tr>
    <td class="fieldnormallight"><a href="?modul=massincadmin">Administration</a></td>
  </tr>
  */?>
  <tr>
    <td class="datatablehead">Attplaner II</td>
  </tr>
<?
  // Erweierung von Bytehoppers 20.07.05 fr Attplaner2
   if ($Benutzer['rang'] >= $Rang_VizeAdmiral) {

   echo "  <tr>
       <td class=\"fieldnormallight\"><a href=\"?modul=attplaneradmin\">Attplaner Config</a></td>
          </tr>";
   }
?>
  <tr>
    <td class="fieldnormallight"><a href="?modul=attplanerlist">Attplaner Liste</a></td>
  </tr>
  <tr>
    <td class="fieldnormallight"><a href="?modul=atteinplanen">Neues Att-Ziel erfassen</a></td>
  </tr>
</table>
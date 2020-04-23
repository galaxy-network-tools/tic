<center>
    <form action="./main.php?modul=nachrichten" method="post">
        <input type="hidden" name="action" value="nachrichtschreiben" />
    <table>
      <tr>
        <td class="datatablehead">Nachricht schreiben</td>
      </tr>
      <tr class="fieldnormallight">
        <td>
            Titel:
            <input type="text" name="txtTitel" maxlength="50" size="50" />
        </td>
      </tr>
      <tr class="fieldnormallight">
        <td>
            <textarea name="txtText" style="width:100%;height:150px;" cols="50" rows="5"></textarea>
        </td>
      </tr>
      <tr class="fieldnormallight">
        <td>
<?
            if ($Benutzer['rang'] >= $Rang_VizeAdmiral) {
               echo '<select name="txtHC">';
                  echo '<option selected="selected" value="META">MetaInfo</option>';
                  echo '<option value="HC">HC-META-Info</option>';
                  echo '<option value="SHC">SUPER HC INFO</option>';
                  echo '<option value="alle">ALLE</option>';
              echo '</select>';
            } else {
               echo '<input type="hidden" name="txtHC" value="META">';
            }
?>
            <input type="submit" value="Abschicken" />
        </td>
      </tr>
    </table>
    </form>
<?
            echo '<a href="./main.php?modul=nachrichten" style="font-weight:bold">Alle Nachrichten anzeigen</a>';
?>
</center>

<?php

if ($_GET['action'] == 'displayaendern') {
        switch($_POST['disp_type']){
            case 'disp_stdmin':
                $SQL_command = 'UPDATE `gn4accounts` SET displaymode="1" WHERE id="'.$Benutzer['id'].'";';
                break;
            case 'disp_ticks':
                $SQL_command = 'UPDATE `gn4accounts` SET displaymode="2" WHERE id="'.$Benutzer['id'].'";';
                break;
            case 'disp_min':
            default:
                $SQL_command = 'UPDATE `gn4accounts` SET displaymode="0" WHERE id="'.$Benutzer['id'].'";';
                break;
        }
        $SQL_Result = tic_mysql_query( $SQL_command, $SQL_DBConn) or $error_code = 7;
    }

?>

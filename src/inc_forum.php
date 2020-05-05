<?php
    if (isset($_POST['faction'])) $faction = $_POST['faction'];
    if (isset($_POST['ftopic'])) $ftopic = $_POST['ftopic'];
    if (isset($_POST['falli'])) $falli = $_POST['falli'];
    if (isset($_POST['p_belongsto'])) $p_belongsto = $_POST['p_belongsto'];
    if (isset($_POST['p_alli'])) $p_alli = $_POST['p_alli'];
    if (isset($_POST['p_topic'])) $p_topic = $_POST['p_topic'];
    if (isset($_POST['p_text'])) $p_text = $_POST['p_text'];
    if (isset($_POST['p_topicid'])) $p_topicid = $_POST['p_topicid'];
    if (!isset($faction)) $faction = 'show';
    if (!isset($ftopic)) $ftopic = 0;
    if (!isset($falli)) $falli = 0;

    // if ($Benutzer['blind'] == 1) $falli = $Benutzer['allianz'];

    echo '<CENTER>';

    if ($faction == 'show') {
        if ($falli != 0 && $falli != $Benutzer['allianz']) {
            echo '<FONT COLOR=#FF0000><B>Sie sind berechtigt in diesem Forum zu agieren.</B></FONT>';
        } else {
            if ($ftopic == 0) {
// Themenübersicht
                if ($falli == 0)
                    echo '<FONT SIZE=4><B>Allgemeines Forum</B></FONT><BR><FONT SIZE="-1">(Hier können sich alle Allianzen gemeinsam besprechen)</FONT><BR><BR>';
                else
                    echo '<FONT SIZE=4><B>Internes Forum</B></FONT><BR><FONT SIZE="-1">(Hier hat nur ihre Allianz Zutritt)</FONT><BR><BR>';
                echo '<TABLE WIDTH=100%>';
                echo '  <TR><td COLSPAN=4><font size="-1"><B><A HREF="./main.php?modul=forum&faction=post&falli='.$falli.'&ftopic=0">Neues Thema erstellen</A></B></td></font></TR>';
                if ($Benutzer['rang'] > $Rang_GC)
                    echo '  <TR><td BGCOLOR=#333333 WIDTH=45%><font size="-1"><FONT COLOR=#FFFFFF SIZE="-1"><B>Thema</B></FONT></td></font><td BGCOLOR=#333333 WIDTH=5%><font size="-1"><FONT COLOR=#FFFFFF SIZE="-1"><B><CENTER>Antworten</CENTER></B></FONT></td></font><td BGCOLOR=#333333 WIDTH=5%><font size="-1"><FONT COLOR=#FFFFFF SIZE="-1"><B><CENTER>Klicks</CENTER></B></FONT></td></font><td BGCOLOR=#333333 WIDTH=10%><font size="-1"><FONT COLOR=#FFFFFF SIZE="-1"><B><CENTER>Autor</CENTER></B></FONT></td></font><td BGCOLOR=#333333 WIDTH=15%><font size="-1"><FONT COLOR=#FFFFFF SIZE="-1"><B><CENTER>Letzte Antwort</CENTER></B></FONT></td></font><td BGCOLOR=#333333 WIDTH=10%><font size="-1"><FONT COLOR=#FFFFFF SIZE="-1"><B><CENTER>Letzter Autor</CENTER></B></FONT></td></font><td BGCOLOR=#333333 WIDTH=10%><font size="-1"><FONT COLOR=#FFFFFF SIZE="-1"><B><CENTER>Moderieren</CENTER></B></FONT></td></font></TR>';
                else
                    echo '  <TR><td BGCOLOR=#333333 WIDTH=55%><font size="-1"><FONT COLOR=#FFFFFF SIZE="-1"><B>Thema</B></FONT></td></font><td BGCOLOR=#333333 WIDTH=5%><font size="-1"><FONT COLOR=#FFFFFF SIZE="-1"><B><CENTER>Antworten</CENTER></B></FONT></td></font><td BGCOLOR=#333333 WIDTH=5%><font size="-1"><FONT COLOR=#FFFFFF SIZE="-1"><B><CENTER>Klicks</CENTER></B></FONT></td></font><td BGCOLOR=#333333 WIDTH=10%><font size="-1"><FONT COLOR=#FFFFFF SIZE="-1"><B><CENTER>Autor</CENTER></B></FONT></td></font><td BGCOLOR=#333333 WIDTH=15%><font size="-1"><FONT COLOR=#FFFFFF SIZE="-1"><B><CENTER>Letzte Antwort</CENTER></B></FONT></td></font><td BGCOLOR=#333333 WIDTH=10%><font size="-1"><FONT COLOR=#FFFFFF SIZE="-1"><B><CENTER>Letzter Autor</CENTER></B></FONT></td></font></TR>';
                $SQL_Result = tic_mysql_query('SELECT id, autorid, zeit, belongsto, allianz, topic, wichtig, views  FROM `gn4forum` WHERE allianz="'.$falli.'" AND belongsto="0" ORDER BY wichtig DESC, priority DESC, id DESC;', $SQL_DBConn) or $error_code = 4;
                $SQL_Num = mysql_num_rows($SQL_Result);
                if ($SQL_Num == 0) {
                    echo '  <TR><td BGCOLOR=#'.$htmlstyle['hell'].' COLSPAN=7><font size="-1">Es wurden keine Themen in diesem Forum gefunden.</td></font></TR>';
                } else {
                    for ($n = 0; $n < $SQL_Num; $n++) {
                        $SQL_Result2 = tic_mysql_query('SELECT name FROM `gn4accounts` WHERE  id="'.mysql_result($SQL_Result, $n, 'autorid').'";', $SQL_DBConn) or $error_code = 4;
                        if (mysql_num_rows($SQL_Result2) != 1)
                            $forum_autor = '<I>Unbekannt</I>';
                        else
                            $forum_autor = '<A HREF="./main.php?modul=anzeigen&id='.mysql_result($SQL_Result, $n, 'autorid').'">'.mysql_result($SQL_Result2, 0, 'name').'</A>';
                        $SQL_Result2 = tic_mysql_query('SELECT autorid, zeit FROM `gn4forum` WHERE belongsto="'.mysql_result($SQL_Result, $n, 'id').'" ORDER BY priority DESC LIMIT 1;', $SQL_DBConn) or $error_code = 4;
                        if (mysql_num_rows($SQL_Result2) == 1) {
                            $forum_zeit = mysql_result($SQL_Result2, 0, 'zeit');
                            $last_id = mysql_result($SQL_Result2, 0, 'autorid');
                        } else {
                            $forum_zeit = mysql_result($SQL_Result, $n, 'zeit');
                            $last_id = mysql_result($SQL_Result, $n, 'autorid');
                        }
                        $SQL_Result2 = tic_mysql_query('SELECT id, name FROM `gn4accounts` WHERE  id="'.$last_id.'";', $SQL_DBConn) or $error_code = 4;
                        if (mysql_num_rows($SQL_Result2) != 1)
                            $last_autor = '<I>Unbekannt</I>';
                        else
                            $last_autor = '<A HREF="./main.php?modul=anzeigen&id='.mysql_result($SQL_Result2, 0, 'id').'">'.mysql_result($SQL_Result2, 0, 'name').'</A>';
                        $SQL_Result2 = tic_mysql_query('SELECT COUNT(*) FROM `gn4forum` WHERE belongsto="'.mysql_result($SQL_Result, $n, 'id').'";', $SQL_DBConn) or $error_code = 4;
                        $SQL_Row = mysql_fetch_row($SQL_Result2);
                        $wichtig = '';
                        if (mysql_result($SQL_Result, $n, 'wichtig') != 0) $wichtig = '<B>(!)</B> ';
                        if ($Benutzer['rang'] > $Rang_GC)
                            echo '  <TR><td BGCOLOR=#'.$htmlstyle['hell'].'><font size="-1">'.$wichtig.'<A HREF="./main.php?modul=forum&faction=show&falli='.$falli.'&ftopic='.mysql_result($SQL_Result, $n, 'id').'">'.mysql_result($SQL_Result, $n, 'topic').'</td></font><td BGCOLOR=#'.$htmlstyle['dunkel'].'><font size="-1"><CENTER>'.ZahlZuText($SQL_Row[0]).'</CENTER></td></font><td BGCOLOR=#'.$htmlstyle['hell'].'><font size="-1"><CENTER>'.ZahlZuText(mysql_result($SQL_Result, $n, 'views')).'</CENTER></td></font><td BGCOLOR=#'.$htmlstyle['dunkel'].'><font size="-1"><CENTER>'.$forum_autor.'</CENTER></td></font><td BGCOLOR=#'.$htmlstyle['hell'].'><font size="-1"><CENTER>'.$forum_zeit.'</CENTER></td></font><td BGCOLOR=#'.$htmlstyle['dunkel'].'><font size="-1"><CENTER>'.$last_autor.'</CENTER></td></font><td BGCOLOR=#'.$htmlstyle['hell'].'><font size="-1"><A HREF="./main.php?modul=forum&faction=delpost&falli='.$falli.'&ftopic='.mysql_result($SQL_Result, $n, 'id').'">Löschen</A></td></font></TR>';
                        else
                            echo '  <TR><td BGCOLOR=#'.$htmlstyle['hell'].'><font size="-1">'.$wichtig.'<A HREF="./main.php?modul=forum&faction=show&falli='.$falli.'&ftopic='.mysql_result($SQL_Result, $n, 'id').'">'.mysql_result($SQL_Result, $n, 'topic').'</td></font><td BGCOLOR=#'.$htmlstyle['dunkel'].'><font size="-1"><CENTER>'.ZahlZuText($SQL_Row[0]).'</CENTER></td></font><td BGCOLOR=#'.$htmlstyle['hell'].'><font size="-1"><CENTER>'.ZahlZuText(mysql_result($SQL_Result, $n, 'views')).'</CENTER></td></font><td BGCOLOR=#'.$htmlstyle['dunkel'].'><font size="-1"><CENTER>'.$forum_autor.'</CENTER></td></font><td BGCOLOR=#'.$htmlstyle['hell'].'><font size="-1"><CENTER>'.$forum_zeit.'</CENTER></td></font><td BGCOLOR=#'.$htmlstyle['dunkel'].'><font size="-1"><CENTER>'.$last_autor.'</CENTER></td></font></TR>';
                    }
                }
                echo '  <TR><td COLSPAN=4><font size="-1"><B><A HREF="./main.php?modul=forum&faction=post&falli='.$falli.'&ftopic=0">Neues Thema erstellen</A></B></td></font></TR>';
                echo '</TABLE>';
            } else {
// Topic anzeigen
                $SQL_Result = tic_mysql_query('SELECT *  FROM `gn4forum` WHERE  id="'.$ftopic.'" AND allianz="'.$falli.'";', $SQL_DBConn) or $error_code = 4;
                $SQL_Num=mysql_num_rows($SQL_Result);
                if ($SQL_Num == 0) {
                    echo '<B>Thema nicht gefunden!</B>';
                } else {
                    $SQL_Result2 = tic_mysql_query('UPDATE `gn4forum` SET views="'.(mysql_result($SQL_Result, 0, 'views') + 1).'" WHERE  id="'.$ftopic.'";', $SQL_DBConn) or $error_code = 4;
                    $SQL_Result2 = tic_mysql_query('SELECT id, ticid, galaxie, planet, name, rang, allianz, umod FROM `gn4accounts` WHERE  id="'.mysql_result($SQL_Result, 0, 'autorid').'";', $SQL_DBConn) or $error_code = 4;
                    if (mysql_num_rows($SQL_Result2) != 1) {
                        $forum_user['id'] = 0;
                        $forum_user['name'] = '<I>Unbekannt</I>';
                        $forum_user['rang'] = 0;
                        $forum_user['allianz'] = 0;
                        $forum_user['koords'] = '';
                        $forum_user['posts'] = 0;
                    } else {
                        $forum_user['id'] = mysql_result($SQL_Result2, 0, 'id');
                        $forum_user['name'] = mysql_result($SQL_Result2, 0, 'name');
                        $forum_user['rang'] = mysql_result($SQL_Result2, 0, 'rang');
                        $forum_user['allianz'] = mysql_result($SQL_Result2, 0, 'allianz');
                        $forum_user['koords'] = mysql_result($SQL_Result2, 0, 'galaxie').':'.mysql_result($SQL_Result2, 0, 'planet');
                        $forum_user['umod'] = mysql_result($SQL_Result2, 0, 'umod');
                        $forum_user['ticid'] = mysql_result($SQL_Result2, 0, 'ticid');
                        $SQL_Result2 = tic_mysql_query('SELECT COUNT(*) FROM `gn4forum` WHERE autorid="'.$forum_user['id'].'"', $SQL_DBConn);
                        $SQL_Row2 = mysql_fetch_row($SQL_Result2);
                        $forum_user['posts'] = $SQL_Row2[0];
                        $SQL_Result2 = tic_mysql_query('SELECT tag, name FROM `gn4allianzen` WHERE  id="'.$forum_user['allianz'].'";', $SQL_DBConn) or $error_code = 4;
                        $forum_user['tag'] = mysql_result($SQL_Result2, 0, 'tag');
                        $forum_user['aliname'] = mysql_result($SQL_Result2, 0, 'name');
                        $SQL_Result2 = tic_mysql_query('SELECT name FROM `gn4meta` WHERE  id="'.$forum_user['ticid'].'";', $SQL_DBConn) or $error_code = 4;
                        $forum_user['meta'] = mysql_result($SQL_Result2, 0, 'name');
                    }
                    if ($falli == 0)
                        $forum_titel = '<A HREF="./main.php?modul=forum&faction=show&falli=0&ftopic=0">Zum allgemeinem Forum</A>';
                    else
                        $forum_titel = '<A HREF="./main.php?modul=forum&faction=show&falli='.$Benutzer['allianz'].'&ftopic=0">Zum internen Forum</A>';
                    echo '<TABLE WIDTH=80%>';
                    echo '  <TR><td COLSPAN=3><font size="-1"><B>'.$forum_titel.'</B></td></font></TR>';
                    echo '  <TR><td BGCOLOR=#333333 WIDTH=80% COLSPAN=2><font size="-1"><FONT COLOR=#FFFFFF SIZE=4><B>'.mysql_result($SQL_Result, 0, 'topic').'</B></FONT></td></font><td BGCOLOR=#333333 WIDTH=20%><font size="-1"><FONT COLOR=#FFFFFF>'.mysql_result($SQL_Result, 0, 'zeit').'</FONT></td></font></TR>';
                    echo '  <TR>';
                    echo '      <td BGCOLOR=#'.$htmlstyle['dunkel'].' WIDTH=20% VALIGN="top" ROWSPAN=2><font size="-1">';
                    echo '          <FONT SIZE=4><B>'.$forum_user['name'].'</B></FONT><BR>';
                    echo '          <FONT SIZE="-1">['.$forum_user['tag'].'] '.$forum_user['aliname'].'<BR>';
                    echo '          '.$forum_user['meta'].'<BR>';
                    echo '          Koords: '.$forum_user['koords'].'<BR>';
                    echo '          Posts: '.ZahlZuText($forum_user['posts']).'<BR>';
                    echo '          Rang: '.$RangName[$forum_user['rang']].'</FONT><BR>';
                    if ($forum_user['umod'] != '') echo '<FONT SIZE="-1" COLOR=#'.$htmlstyle['dunkel_blau'].'><B><I>U-Mod aktiviert</I></B></FONT><BR>';
                    echo '      </td></font>';
                    echo '      <td COLSPAN=2 BGCOLOR=#'.$htmlstyle['dunkel'].'><font size="-1">';
                    echo '          <A HREF="./main.php?modul=anzeigen&id='.$forum_user['id'].'">Profil anschauen</A>';
                    if ($Benutzer['rang'] > $Rang_GC || $Benutzer['id'] == $forum_user['id']) echo ', <A HREF="./main.php?modul=forum&faction=update&ftopic='.mysql_result($SQL_Result, 0, 'id').'">Post editieren</A>';
                    if ($Benutzer['rang'] > $Rang_GC) {
                        echo ', <A HREF="./main.php?modul=forum&faction=delpost&falli='.$falli.'&ftopic='.mysql_result($SQL_Result, 0, 'id').'">Post löschen</A>';
                        if (mysql_result($SQL_Result, 0, 'wichtig') != 1)
                            echo ', <A HREF="./main.php?modul=forum&faction=setwichtig&falli='.$falli.'&ftopic='.mysql_result($SQL_Result, 0, 'id').'">Wichtig einstellen</A>';
                        else
                            echo ', <A HREF="./main.php?modul=forum&faction=setunwichtig&falli='.$falli.'&ftopic='.mysql_result($SQL_Result, 0, 'id').'">Wichtig ausstellen</A>';
                    }
                    echo '      </td></font>';
                    echo '  </TR>';
                    echo '  <TR>';
                    echo '      <td BGCOLOR=#'.$htmlstyle['hell'].' WIDTH=80% COLSPAN=2 VALIGN="top"><font size="-1">';
                    echo mysql_result($SQL_Result, 0, 'text');
                    echo '      </td></font>';
                    echo '  </TR>';
                    // Alle Antworten posten
                    $SQL_Result = tic_mysql_query('SELECT *  FROM `gn4forum` WHERE belongsto="'.$ftopic.'" ORDER BY id ASC;', $SQL_DBConn) or $error_code = 4;
                    $SQL_Num = mysql_num_rows($SQL_Result);
                    if ($SQL_Num > 0) {
                        for ($n = 0; $n < $SQL_Num; $n++) {
                            $SQL_Result2 = tic_mysql_query('SELECT id, ticid, galaxie, planet, name, rang, allianz, umod FROM `gn4accounts` WHERE  id="'.mysql_result($SQL_Result, $n, 'autorid').'";', $SQL_DBConn) or $error_code = 4;
                            if (mysql_num_rows($SQL_Result2) != 1) {
                                $forum_user['id'] = 0;
                                $forum_user['name'] = '<I>Unbekannt</I>';
                                $forum_user['rang'] = 0;
                                $forum_user['allianz'] = 0;
                                $forum_user['koords'] = '';
                                $forum_user['posts'] = 0;
                            } else {
                                $forum_user['id'] = mysql_result($SQL_Result2, 0, 'id');
                                $forum_user['name'] = mysql_result($SQL_Result2, 0, 'name');
                                $forum_user['rang'] = mysql_result($SQL_Result2, 0, 'rang');
                                $forum_user['ticid'] = mysql_result($SQL_Result2, 0, 'ticid');
                                $forum_user['allianz'] = mysql_result($SQL_Result2, 0, 'allianz');
                                $forum_user['koords'] = mysql_result($SQL_Result2, 0, 'galaxie').':'.mysql_result($SQL_Result2, 0, 'planet');
                                $forum_user['umod'] = mysql_result($SQL_Result2, 0, 'umod');
                                $SQL_Result2 = tic_mysql_query('SELECT COUNT(*) FROM `gn4forum` WHERE autorid="'.$forum_user['id'].'"', $SQL_DBConn);
                                $SQL_Row2 = mysql_fetch_row($SQL_Result2);
                                $forum_user['posts'] = $SQL_Row2[0];
                                $SQL_Result2 = tic_mysql_query('SELECT tag, name FROM `gn4allianzen` WHERE  id="'.$forum_user['allianz'].'";', $SQL_DBConn) or $error_code = 4;
                                $forum_user['tag'] = mysql_result($SQL_Result2, 0, 'tag');
                                $forum_user['aliname'] = mysql_result($SQL_Result2, 0, 'name');
                                $SQL_Result2 = tic_mysql_query('SELECT name FROM `gn4meta` WHERE id="'.$forum_user['ticid'].'";', $SQL_DBConn) or $error_code = 4;
                                $forum_user['meta'] = mysql_result($SQL_Result2, 0, 'name');
                            }
                            // echo '   <TR><td COLSPAN=3><font size="-1"><BR></td></font></TR>';
                            echo '  <TR>';
                            echo '      <td BGCOLOR=#'.$htmlstyle['dunkel'].' WIDTH=20% VALIGN="top" ROWSPAN=3><font size="-1">';
                            echo '          <FONT SIZE=4><B>'.$forum_user['name'].'</B></FONT><BR>';
                            echo '          <FONT SIZE="-1">['.$forum_user['tag'].'] '.$forum_user['aliname'].'<BR>';
                            echo '          '.$forum_user['meta'].'<BR>';
                            echo '          Koords: '.$forum_user['koords'].'<BR>';
                            echo '          Posts: '.ZahlZuText($forum_user['posts']).'<BR>';
                            echo '          Rang: '.$RangName[$forum_user['rang']].'</FONT><BR>';
                            if ($forum_user['umod'] != '') echo '<FONT SIZE="-1" COLOR=#'.$htmlstyle['dunkel_blau'].'><B><I>U-Mod aktiviert</I></B></FONT><BR>';
                            echo '      </td></font>';
                            $forum_topic = mysql_result($SQL_Result, $n, 'topic');
                            if ($forum_topic == '') $forum_topic = '<BR>';
                            echo '      <td BGCOLOR=#'.$htmlstyle['dunkel'].' WIDTH=60%><font size="-1">'.$forum_topic.'</td></font>';
                            echo '      <td BGCOLOR=#'.$htmlstyle['dunkel'].' WIDTH=20%><font size="-1">'.mysql_result($SQL_Result, $n, 'zeit').'</td></font>';
                            echo '  </TR>';
                            echo '  <TR>';
                            echo '      <td COLSPAN=2 BGCOLOR=#'.$htmlstyle['dunkel'].'><font size="-1">';
                            echo '          <A HREF="./main.php?modul=anzeigen&id='.$forum_user['id'].'">Profil anschauen</A>';
                            if ($Benutzer['rang'] > $Rang_GC || $Benutzer['id'] == $forum_user['id']) echo ', <A HREF="./main.php?modul=forum&faction=update&ftopic='.mysql_result($SQL_Result, $n, 'id').'">Post editieren</A>';
                            if ($Benutzer['rang'] > $Rang_GC) echo ', <A HREF="./main.php?modul=forum&faction=delpost&falli='.$falli.'&ftopic='.mysql_result($SQL_Result, $n, 'id').'">Post löschen</A>';
                            echo '      </td></font>';
                            echo '  </TR>';
                            echo '  <TR>';
                            echo '      <td BGCOLOR=#'.$htmlstyle['hell'].' WIDTH=80% COLSPAN=2 VALIGN="top"><font size="-1">';
                            echo mysql_result($SQL_Result, $n, 'text');
                            echo '      </td></font>';
                            echo '  </TR>';
                        }
                    }
                    echo '  <TR><td COLSPAN=3><font size="-1"><B><A HREF="./main.php?modul=forum&faction=post&falli='.$falli.'&ftopic='.$ftopic.'">Antwort schreiben</A></B></td></font></TR>';
                    echo '</TABLE>';
                }
            }
        }
    } elseif ($faction == 'post') {
// Post Form anzeigen
        if ($ftopic == 0) {
            $post_title = 'Neues Thema erstellen';
            $new_title = '';
        } else {
            $SQL_Result = tic_mysql_query('SELECT topic FROM `gn4forum` WHERE  id="'.$ftopic.'" AND belongsto="0";', $SQL_DBConn) or $error_code = 4;
            if (mysql_num_rows($SQL_Result) != 1) {
                $post_title = '';
                echo '<FONT COLOR=#FF0000><B>Thema nicht gefunden</B></FONT>';
            } else {
                $post_title = mysql_result($SQL_Result, 0, 'topic');
                $new_title = 'Re: '.$post_title;
            }
        }
        if ($post_title != '') {
            echo '<TABLE WIDTH=50%>';
            echo '  <TR><td BGCOLOR=#333333><font size="-1"><FONT COLOR=#FFFFFF><B>'.$post_title.'</B></FONT></td></font></TR>';
            echo '  <FORM ACTION="./main.php" METHOD="POST">';
            echo '      <INPUT TYPE="hidden" NAME="modul" VALUE="forum">';
            echo '      <INPUT TYPE="hidden" NAME="faction" VALUE="dopost">';
            echo '      <INPUT TYPE="hidden" NAME="p_belongsto" VALUE="'.$ftopic.'">';
            echo '      <INPUT TYPE="hidden" NAME="p_alli" VALUE="'.$falli.'">';
            echo '      <TR><td BGCOLOR=#'.$htmlstyle['hell'].'><font size="-1">Titel: <INPUT TYPE="text" NAME="p_topic" VALUE="'.$new_title.'" MAXLENGTH=50 SIZE=70></td></font></TR>';
            echo '      <TR><td BGCOLOR=#'.$htmlstyle['hell'].'><font size="-1"><TEXTAREA NAME="p_text" ROWS=20 COLS=60></TEXTAREA></td></font></TR>';
            echo '      <TR><td BGCOLOR=#'.$htmlstyle['hell'].'><font size="-1"><INPUT TYPE="submit" VALUE="Antworten"></td></font></TR>';
            echo '  </FORM>';
            echo '</TABLE>';
        }
    } elseif ($faction == 'update') {
// Update Form anzeigen
        if ($ftopic == 0) {
            $post_title = '';
        } else {
            $SQL_Result = tic_mysql_query('SELECT * FROM `gn4forum` WHERE  id="'.$ftopic.'";', $SQL_DBConn) or $error_code = 4;
            if (mysql_num_rows($SQL_Result) != 1) {
                $post_title = '';
                echo '<FONT COLOR=#FF0000><B>Thema nicht gefunden</B></FONT>';
            } else {
                $post_title = mysql_result($SQL_Result, 0, 'topic');
                if ($post_title == '') $post_title = '<BR>';
            }
        }
        if ($post_title != '') {
            echo '<TABLE WIDTH=50%>';
            echo '  <TR><td BGCOLOR=#333333><font size="-1"><FONT COLOR=#FFFFFF><B>'.$post_title.'</B></FONT></td></font></TR>';
            if ($post_title == '<BR>') $post_title = '';
            echo '  <FORM ACTION="./main.php" METHOD="POST">';
            echo '      <INPUT TYPE="hidden" NAME="modul" VALUE="forum">';
            echo '      <INPUT TYPE="hidden" NAME="faction" VALUE="doupdate">';
            echo '      <INPUT TYPE="hidden" NAME="p_topicid" VALUE="'.$ftopic.'">';
            echo '      <INPUT TYPE="hidden" NAME="p_alli" VALUE="'.mysql_result($SQL_Result, 0, 'allianz').'">';
            echo '      <INPUT TYPE="hidden" NAME="p_belongsto" VALUE="'.mysql_result($SQL_Result, 0, 'belongsto').'">';
            echo '      <TR><td BGCOLOR=#'.$htmlstyle['hell'].'><font size="-1">Titel: <INPUT TYPE="text" NAME="p_topic" VALUE="'.$post_title.'" MAXLENGTH=50 SIZE=70></td></font></TR>';
            echo '      <TR><td BGCOLOR=#'.$htmlstyle['hell'].'><font size="-1"><TEXTAREA NAME="p_text" ROWS=20 COLS=60>'.str_replace('<BR>', "\n", mysql_result($SQL_Result, 0, 'text')).'</TEXTAREA></td></font></TR>';
            echo '      <TR><td BGCOLOR=#'.$htmlstyle['hell'].'><font size="-1"><INPUT TYPE="submit" VALUE="Ändern"></td></font></TR>';
            echo '  </FORM>';
            echo '</TABLE>';
        }
    } elseif ($faction == 'dopost') {
// Forum Post machen
        if (!isset($p_belongsto)) $p_belongsto = 0;
        if (!isset($p_alli)) $p_alli = 0;
        if (!isset($p_topic)) $p_topic = '';
        if (!isset($p_text)) $p_text = '';
        if ($p_alli == 0)
            $access = 1;
        elseif ($p_alli == $Benutzer['allianz'])
            $access = 1;
        else
            $access = 0;
        if ($p_belongsto != 0) {
            $SQL_Result = tic_mysql_query('SELECT allianz FROM `gn4forum` WHERE  id="'.$p_belongsto.'";', $SQL_DBConn) or $error_code = 4;
            if (mysql_num_rows($SQL_Result) != 1) {
                echo '<FONT COLOR=#FF0000><B>Sie sind nicht berechtigt zu diesem Thema eine Antwort zu schreiben</B></FONT>';
                $access = 0;
            } else {
                $access = 1;
            }
        }
        if ($access == 1) {
            if (trim($p_topic) == '' || trim($p_text) == '') {
                echo '<FONT COLOR=#FF0000><B>Sie müssen alle Felder ausfüllen</B></FONT>';
            } else {
                $p_text = str_replace("\n", '<BR>', $p_text);
                $SQL_Result = tic_mysql_query('INSERT INTO `gn4forum` (ticid, autorid, zeit, belongsto, topic, text, allianz, priority) VALUES ("'.$Benutzer['ticid'].'", "'.$Benutzer['id'].'", "'.date("H").':'.date("i").' '.date("d").'.'.date("m").'.'.date("Y").'", "'.$p_belongsto.'", "'.$p_topic.'", "'.$p_text.'", "'.$p_alli.'", "'.$forumpriority.'")', $SQL_DBConn) or $error_code = 7;
                if ($p_belongsto != 0) $SQL_Result = tic_mysql_query('UPDATE `gn4forum` SET priority="'.$forumpriority.'" WHERE  id="'.$p_belongsto.'";', $SQL_DBConn) or $error_code = 4;
                $forumpriority++;
                $SQL_Result = tic_mysql_query('UPDATE `gn4vars` SET value="'.$forumpriority.'" WHERE name="forumpriority";', $SQL_DBConn) or $error_code = 4;
                echo '<B>Post hinzugefügt</B><BR><A HREF="./main.php?modul=forum&faction=show&falli='.$p_alli.'&ftopic='.$p_belongsto.'">Zum Thema</A>';
            }
        }
    } elseif ($faction == 'doupdate') {
// Forum Post editieren
        if (!isset($p_topicid)) $p_topicid = 0;
        if (!isset($p_topic)) $p_topic = '';
        if (!isset($p_text)) $p_text = '';
        if (!isset($p_alli)) $p_alli = 0;
        if (!isset($p_belongsto)) $p_belongsto = 0;
        $SQL_Result = tic_mysql_query('SELECT allianz FROM `gn4forum` WHERE  id="'.$p_topicid.'" AND (allianz="0" OR allianz="'.$Benutzer['allianz'].'");', $SQL_DBConn) or $error_code = 4;
        if (mysql_num_rows($SQL_Result) != 1) {
            echo '<FONT COLOR=#FF0000><B>Dieses Topic wurde nicht gefunden</B></FONT>';
        } else {
            if (trim($p_topic) == '' || trim($p_text) == '') {
                echo '<FONT COLOR=#FF0000><B>Sie müssen alle Felder ausfüllen</B></FONT>';
            } else {
                $p_text = str_replace("\n", '<BR>', $p_text);
                $p_text = $p_text.'<BR><BR><I>Dieses Post wurde von '.$Benutzer['name'].' am '.date("d").'.'.date("m").'.'.date("Y").' um '.date("H").':'.date("i").' editiert</I>';
                $SQL_Result = tic_mysql_query('UPDATE `gn4forum` SET topic="'.$p_topic.'", text="'.$p_text.'" WHERE  id="'.$p_topicid.'"', $SQL_DBConn) or $error_code = 7;
                echo '<B>Post editiert</B><BR><A HREF="./main.php?modul=forum&faction=show&falli='.$p_alli.'&ftopic='.$p_belongsto.'">Zum Thema</A>';
            }
        }
    } elseif ($faction == 'delpost') {
// Forumpost löschen
        if (!isset($ftopic)) $ftopic = 0;
        if (!isset($falli)) $falli = 0;
        if ($falli > 0 && $falli != $Benutzer['allianz']) {
            echo '<FONT COLOR=#FF0000><B>Sie sind nicht berechtigt diesen Post zu löschen</B></FONT><BR><A HREF="./main.php?modul=forum&faction=show&falli='.$p_alli.'&ftopic=0">Zur Themenübersicht</A>';
        } else {
            if ($ftopic != 0) {
                $SQL_Result = tic_mysql_query('DELETE FROM `gn4forum` WHERE  id="'.$ftopic.'" AND allianz="'.$falli.'"', $SQL_DBConn);
                $SQL_Result = tic_mysql_query('DELETE FROM `gn4forum` WHERE belongsto="'.$ftopic.'" AND allianz="'.$falli.'"', $SQL_DBConn);
                echo '<B>Post gelöscht</B><BR><A HREF="./main.php?modul=forum&faction=show&falli='.$falli.'&ftopic=0">Zur Themenübersicht</A>';
            } else {
                echo '<FONT COLOR=#FF0000><B>Ungültiger Post</B></FONT><BR><A HREF="./main.php?modul=forum&faction=show&falli='.$p_alli.'&ftopic=0">Zur Themenübersicht</A>';
            }
        }

// Forumpost als wichtig makieren
    } elseif ($faction == 'setwichtig') {
        if (!isset($ftopic)) $ftopic = 0;
        if (!isset($falli)) $falli = 0;
        if ($falli > 0 && $falli != $Benutzer['allianz']) {
            echo '<FONT COLOR=#FF0000><B>Sie sind nicht berechtigt diesen Post wichtig zu makieren</B></FONT><BR><A HREF="./main.php?modul=forum&faction=show&falli='.$p_alli.'&ftopic=0">Zur Themenübersicht</A>';
        } else {
            if ($ftopic != 0) {
                $SQL_Result = tic_mysql_query('UPDATE `gn4forum` SET wichtig="1" WHERE  id="'.$ftopic.'" AND allianz="'.$falli.'"', $SQL_DBConn);
                echo '<B>Post wichtig makiert</B><BR><A HREF="./main.php?modul=forum&faction=show&falli='.$p_alli.'&ftopic=0">Zur Themenübersicht</A>';
            } else {
                echo '<FONT COLOR=#FF0000><B>Ungültiger Post</B></FONT><BR><A HREF="./main.php?modul=forum&faction=show&falli='.$p_alli.'&ftopic=0">Zur Themenübersicht</A>';
            }
        }
// Forumpost als unwichtig makieren
    } elseif ($faction == 'setunwichtig') {
        if (!isset($ftopic)) $ftopic = 0;
        if (!isset($falli)) $falli = 0;
        if ($falli > 0 && $falli != $Benutzer['allianz']) {
            echo '<FONT COLOR=#FF0000><B>Sie sind nicht berechtigt diesen Post als unwichtig zu makieren</B></FONT><BR><A HREF="./main.php?modul=forum&faction=show&falli='.$p_alli.'&ftopic=0">Zur Themenübersicht</A>';
        } else {
            if ($ftopic != 0) {
                $SQL_Result = tic_mysql_query('UPDATE `gn4forum` SET wichtig="0" WHERE  id="'.$ftopic.'" AND allianz="'.$falli.'"', $SQL_DBConn);
                echo '<B>Post unwichtig makiert</B><BR><A HREF="./main.php?modul=forum&faction=show&falli='.$p_alli.'&ftopic=0">Zur Themenübersicht</A>';
            } else {
                echo '<FONT COLOR=#FF0000><B>Ungültiger Post</B></FONT><BR><A HREF="./main.php?modul=forum&faction=show&falli='.$p_alli.'&ftopic=0">Zur Themenübersicht</A>';
            }
        }
    }

    echo '</CENTER>';
?>

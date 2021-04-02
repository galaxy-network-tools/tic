<?php
//////////////////////////////////////////////////////
//
// Version 2.3.1
//
// Copyright (C) 2005  Lars-Peter 'laprican' Clausen
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
/////////////////////////////////////////////////////

    class mysql
    {
        var $link;
        var $errortext;
        var $querys;
        var $connected;
        var $result;
        var $use;

        function _construct($args)
        {
            $this->querys = 0;
            $this->link = null;
            $this->connected = false;
            $this->result = array();
            $this->use = "0";

            if(count($args) == 4)
                return $this->connect($args['host'],$args['user'],$args['password'],$args['dbname']);
            else
            {
                if(count($args))
                    die("Fatalerror: mysql::mysql invalid argument count");

                return true;
            }
        }

        function connect($host,$user,$password,$dbname = "")
        {
            $this->connected = 0;
            if (!$this->link = mysqli_connect($host,$user,$password)) {
                $this->errortext = "Konnte keine Verbindung zu $host herstellen: ".mysqli_error($this->link);
                return false;
            }
            if($dbname != "")
            {
                 if(!$this->select_db($dbname))
                     return false;
            }
            $this->connected = 1;
            return true;
        }

        function select_db($dbname)
        {
            $this->querys++;
            if (!mysqli_select_db($this->link, $dbname))
            {
                $this->errortext = "Datenbank $dbname konnte nicht ausgeählt werden: ".mysqli_error($this->link);
                 return false;
            }
            return true;
        }

        function disconnect()
        {
            mysqli_close($this->link);
            $this->connected = 0;
        }

        function select($from, $what,$where=1,$extra="",$saveresult=1) {
             return $this->query("SELECT $what FROM $from WHERE $where $extra;", $saveresult);
        }

        function insert($into, $vars,$values,$saveresult=1) {
            if(is_array($values))
               $values_ = implode("), (",$values);
            else
               $values_ = $values;
            return $this->query("INSERT INTO $into ($vars) VALUES($values_);", $saveresult);
        }

        function update($what, $vars,$where=1,$extra="",$saveresult=1) {
            return $this->query("UPDATE $what SET $vars WHERE $where $extra;", $saveresult);
        }
        function delete($from, $where=1,$extra="",$saveresult=1) {
             return $this->query("DELETE FROM $from WHERE $where $extra;", $saveresult);
        }

        function useresult($name)
        {
            $temp = $this->use;
            $this->use = $name;
            return $temp;
        }

        function query($cmd, $saveresult=1)
        {
            $this->querys++;
            $result = mysqli_query($this->link, $cmd);
            if($saveresult)
            {
                if($this->use)
                    $this->result[$this->use] = $result;
                else
                    $this->result[0] = $result;
            }
            if (!$result)
            $this->errortext = "Query (\"<i>$cmd</i>\") fehlegeschlagen, Fehlermeldung: \"<i>".mysqli_error($this->link)."\"</i>";
            return $result;
        }

        function error($file = "",$line= "")
        {
            if($this->errortext != "" )
                return "<b>Mysql Fehler:</b> ".$this->errortext.($file != "" && $line != "" ? " in ".$file."(".$line.")" : "");
            else
            return "<b>Mysql Fehler:</b> ".mysqli_error($this->link)." in ".$file."(".$line.")";  ;
        }

        function numQuerys()
        {
            return $this->querys;
        }

        function is_connected()
        {
            return $this->connected;
        }

        function getlastresult()
        {
            if($this->use)
                return $this->result[$this->use];
            else
                return $this->result[0];
        }

        function getresult($index)
        {
            return $this->result[$index];
        }

        function rows()
        {
            if($this->use)
                return mysqli_num_rows($this->result[$this->use]);
            else
                return mysqli_num_rows($this->result[0]);
        }

        function fetch($result=0)
        {
            if($result)
                return mysqli_fetch_array($result);

            if($this->use)
                return mysqli_fetch_array($this->result[$this->use]);
            else
                return mysqli_fetch_array($this->result[0]);
        }

        function insert_id()
        {
            return mysql_insert_id();
        }

        function multiquery($string)
        {
			/*TODO
			    - Semikolon innerhalb von Strings nicht beachten
			*/
            $querys = explode(";", $string);
            foreach($querys as $query)
            {
				if($query != "")
				    if(!$this->query($query,0))
                        return false;
            }
            return true;
        }

        function fromfile($filename)
        {
            $file = fopen($filename, "r");
            if(!$file)
                return false;
            $query = fread($file, filesize($filename));
            fclose($file);
            return $this->multiquery($query);
        }
    }
?>

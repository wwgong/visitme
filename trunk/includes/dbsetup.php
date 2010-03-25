<?php
/*
Copyright 2010 GoPandas
This file is part of VisitME.

VisitME is free software: you can redistribute it and/or modify it
under the terms of the GNU General Public License as published by the
Free Software Foundation, either version 3 of the License, or (at your
option) any later version.

VisitME is distributed in the hope that it will be useful, but WITHOUT
ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License
for more details.

You should have received a copy of the GNU General Public License
along with VisitME. If not, see http://www.gnu.org/licenses/.
*/
    require_once("includes/sqlfunctions.php");

    class DB_SETUP
    {
        public function  __construct($db_name, $db_user, $db_pass)
        {
            $this->create_db($db_name, $db_user, $db_pass);
            $this->populate_db();
        }
      
        private function create_db($db_name, $db_user, $db_pass)
        {
            $sql = null;
            $result  = null;

            $sql = "CREATE DATABASE IF NOT EXISTS ".$db_name;
            $result = sql_result($sql);

           // $sql = "CREATE USER $db_user IDENTIFIED BY PASSWORD $db_pass";
           // $result = sql_result($sql);

            //$sql = "GRANT ALL ON $db_name.\".*\" TO $db_user.\"@LOCALHOST\" IDENTIFIED BY $db_user";
            //$result = sql_result($sql);

            $sql = "USE ".$db_name;
            $result = sql_result($sql);
        }

        private function populate_db()
        {
            $sql = null;
            $result  = null;

            // Clean up existing tables with same names if they exist...
            $sql = "DROP TABLE IF EXISTS states";
            $result = sql_result($sql);
            $sql = "DROP TABLE IF EXISTS country";
            $result = sql_result($sql);
            $sql = "DROP TABLE IF EXISTS airports";
            $result = sql_result($sql);

            // Create states table
            $sql = "CREATE TABLE states (STATE varchar(36), CODE varchar(2))";
            $result = sql_result($sql);

            // Populate states table
            $sql = "LOAD DATA
                    LOCAL INFILE 'database/states.csv'
                    INTO TABLE states
                    FIELDS TERMINATED BY \",\" OPTIONALLY ENCLOSED BY '\"'
                    LINES TERMINATED BY '\r\n'
                    IGNORE 1 LINES";
            $result = sql_result($sql);

            // Create country table
            $sql = "CREATE TABLE country (CODE varchar(3), NAME varchar(25))";
            $result = sql_result($sql);

            // Populate country table
            $sql = "LOAD DATA
                    LOCAL INFILE 'database/country.csv'
                    INTO TABLE country
                    FIELDS TERMINATED BY \",\" OPTIONALLY ENCLOSED BY '\"'
                    LINES TERMINATED BY '\r\n'
                    IGNORE 1 LINES";
            $result = sql_result($sql);

            // Create airports table
            $sql = "CREATE TABLE airports (CODE varchar(3), NAME varchar(32), CITY varchar(32), STATE varchar(2), COUNTRY varchar(2), X float, Y float)";
            $result = sql_result($sql);

            // Populate airports table
            $sql = "LOAD DATA
                    LOCAL INFILE 'database/airports.csv'
                    INTO TABLE airports
                    FIELDS TERMINATED BY \",\" OPTIONALLY ENCLOSED BY '\"'
                    LINES TERMINATED BY '\r\n'
                    IGNORE 1 LINES";
            $result = sql_result($sql);
        }
    }
    
?>

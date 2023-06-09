<?php
    namespace DB;

    class DBAccess{
        private const HOST_DB = "127.0.0.1";
        private const DB_NAME = "rcontin";
        private const USERNAME = "rcontin";
        private const PASSWD = "caiquoogh3tha3Ce";

        private $conn;

        public function openDBConnection(){
            $this->conn = mysqli_connect(DBAccess::HOST_DB,
                                         DBAccess::USERNAME,
                                         DBAccess::PASSWD,
                                         DBAccess::DB_NAME);

            if (mysqli_connect_errno($this->conn)) {
                return false;
            } else {
                return true;
            }
        }

        public function closeConnection(){
            mysqli_close($this->conn);
        }

        public function getPisteList(){
            $query = "SELECT * FROM Piste ORDER BY numero ASC";
            $queryResult = mysqli_query($this->conn,$query) or die("Errore in getPisteList: " . mysqli_error($this->conn));
    
            if (mysqli_num_rows($queryResult) == 0) {
                return null;
            } else {
                $result = array();
                while ($row = mysqli_fetch_assoc($queryResult)) {
                    array_push($result,$row);
                }
                $queryResult->free();
                return $result;
            }
        }

        public function getImpiantiList(){
            $query = "SELECT * FROM Impianti ORDER BY numero ASC";
            $queryResult = mysqli_query($this->conn,$query) or die("Errore in getImpiantiList: " . mysqli_error($this->conn));
    
            if (mysqli_num_rows($queryResult) == 0) {
                return null;
            } else {
                $result = array();
                while ($row = mysqli_fetch_assoc($queryResult)) {
                    array_push($result,$row);
                }
                $queryResult->free();
                return $result;
            }
        }

        public function isUsernameTaken($username){
            $query = "SELECT * FROM Utenti WHERE Username='$username'";
            $queryResult = mysqli_query($this->conn,$query) or die("Errore in isUsernameTaken: " . mysqli_error($this->conn));
            if (mysqli_num_rows($queryResult)==0){
                return false;
            }
            return true;
        }

        public function execQuery($query){
            return mysqli_query($this->conn,$query);
        }
        public function execMultiQuery($query){
            return mysqli_multi_query($this->conn,$query);
        }

        public function getErrors(){
            return $this->conn->error;
        }
        public function getErrorsNumber(){
            return $this->conn->errno;
        }
        public function getAffectedRows(){
            return $this->conn->affected_rows;
        }

        public function updateState($tipo,$numero,$stato){
            $stringaQuery = "UPDATE $tipo SET stato = $stato WHERE numero = \"$numero\"";

            $risultato = mysqli_query($this->conn,$stringaQuery) or die (mysqli_error($this->conn));

            if (mysqli_affected_rows($this->conn) > 0) {
                return true;
            } else {
                return false;
            }
        }

    }

?>
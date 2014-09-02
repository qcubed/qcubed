<?php

// This Database Adapter depends on MySqliDatabase	
if (!class_exists('QMySqli5Database')) {
    require(__QCUBED_CORE__ . '/database/QMySqli5Database.class.php');
}

class QMySqli5ClusterDatabase extends QMySqli5Database {

    const Adapter = 'MySqli cluster database';

    /**
     * this will randomize the connection to the mysql servers. If one mysql server loses connection, it will try again on another one
     * @param string[] $servers
     * @return \MySqli|boolean
     */
    private function ConnectToRandomServer($servers) {
        if (count($servers)) { //if we have servers, select a random one else return false, 
            $randomkey = array_rand($servers);
            $randomserver = $servers[$randomkey];
            try {
                mysqli_report(MYSQLI_REPORT_STRICT);
                return new MySqli($randomserver, $this->Username, $this->Password, $this->Database, $this->Port);
            } catch (Exception $ex) {
                //failed to connect to this server, try again, but without the faulty server
                unset($servers[$randomkey]);
                //we might want to report this... 
                return $this->ConnectToRandomServer($servers);
            }
        } else {
            return false;
        }
    }

    public function Connect() {
        // Connect to the Database Server
        $this->objMySqli = $this->ConnectToRandomServer($this->Server); //in this->Server is an array containing servers (eg: array('mysql1', 'mysql2','10.0.0.101',...) 

        if (!$this->objMySqli) {
            throw new QMySqliDatabaseException("Unable to connect to Database", -1, null);
        }

        if ($this->objMySqli->error) {
            throw new QMySqliDatabaseException($this->objMySqli->error, $this->objMySqli->errno, null);
        }

        // Update "Connected" Flag
        $this->blnConnectedFlag = true;

        // Set to AutoCommit
        $this->NonQuery('SET AUTOCOMMIT=1;');

        // Set NAMES (if applicable)
        if (array_key_exists('encoding', $this->objConfigArray)) {
            $this->NonQuery('SET NAMES ' . $this->objConfigArray['encoding'] . ';');
        }
    }

}

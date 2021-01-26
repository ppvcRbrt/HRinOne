<?php

class Database {
    /**
     * @var Database
     */
    protected static $_dbInstance = null;

    /**
     * @var PDO
     */
    protected $_dbHandle;

    /**
     * This method is used to connect to a database.
     * It checks whether an instance of a database object exists and
     * if it doesn't, it creates a new one using the given details.
     * The username and password are base64 encoded.
     *
     * @return Database
     */
    public static function getInstance() {
       $username = base64_decode('aGMyMS0xNQ=='); //$username ='hc21-15';
       $password = base64_decode('RVdaR1FXVUpXbjBXckhl'); //$password = 'EWZGQWUJWn0WrHe';
       $host = 'poseidon.salford.ac.uk';
       $dbName = 'hc21_15_v5finaltesting';
       
       if(self::$_dbInstance === null) { //checks if the PDO exists
            // creates new instance if not, sending in connection info
            self::$_dbInstance = new self($username, $password, $host, $dbName);
        }
        return self::$_dbInstance;
    }

    /**
     * This represents the constructor of the Database class.
     * It creates the handle using the specified information
     *
     * @param $username
     * @param $password
     * @param $host
     * @param $database
     */
    private function __construct($username, $password, $host, $database) {
        try { 
            $this->_dbHandle = new PDO("mysql:host=$host;dbname=$database",  $username, $password); // creates the database handle with connection info
        //$this->_dbHandle = new PDO('mysql:host=' . $host . ';dbname=' . $database,  $username, $password); // creates the database handle with connection info
        }
        catch (PDOException $e) { // catch any failure to connect to the database
	    echo $e->getMessage();
	}
    }

    /**
     * This method is used to get the PDO handle.
     *
     * @return PDO
     */
    public function getdbConnection() {
        return $this->_dbHandle; // returns the PDO handle to be used elsewhere
    }

    /**
     * This method is used to destroy the handle when it is
     * not needed anymore
     */
    public function __destruct() {
        $this->_dbHandle = null; // destroys the PDO handle when no longer needed
    }
}

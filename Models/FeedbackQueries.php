<?php

require_once ('Database.php');
require_once('FeedbackTable.php');

class FeedbackQueries
{
    protected $_dbInstance;
    protected $_dbHandle;

    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    /**
     * Function to query and return all info from table
     * @return array: will return an array of our rows
     */
    public function getAll()
    {
        $sqlQuery = 'SELECT * FROM motivationFeedback';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement
        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new FeedbackTable($row);
        }
        return $dataSet;
    }
}
<?php

require_once('Database.php');
require_once('AssessorFdbkTable.php');

/**
 * Class FeedbackQueries. This class is used to manipulate data in the Feedback table
 * and it contains SQL queries to aid this requirement.
 */

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
     * Function to query and return information from the feedback table.
     * @return array: will return an array of our rows
     */
    public function getAll()
    {
        $sqlQuery = 'SELECT * FROM feedback';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement
        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new AssessorFdbkTable($row);
        }
        return $dataSet;
    }
}
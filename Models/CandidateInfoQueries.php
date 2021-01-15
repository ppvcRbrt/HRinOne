<?php
require('Database.php');
require('CandidateInfoTable.php');

class CandidateInfoQueries
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
    public function getCandidateName($candidateID)
    {
        $sqlQuery = 'SELECT name FROM Candidate_information 
                     WHERE ID = :candID';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':candID', $candidateID, PDO::PARAM_STR);
        $statement->execute(); // execute the PDO statement
        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new CandidateInfoTable($row);
        }
        return $dataSet;
    }


}
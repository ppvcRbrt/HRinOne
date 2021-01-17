<?php

require_once('Database.php');
require_once('DomainTable.php');

class DomainQueries
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

    public function DeleteDomain($domainName)
    {
        $sqlQuery = 'DELETE FROM Work_domain
                        WHERE domain_name = :domainName';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':domainName', $domainName, PDO::PARAM_STR);
        $statement->execute(); // execute the PDO statement
    }

    public function InsertDomain($domainName)
    {
        $sqlQuery = 'INSERT INTO Work_domain (domain_name) 
                        VALUES (:domainName)';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':domainName', $domainName, PDO::PARAM_STR);
        $statement->execute(); // execute the PDO statement
    }

    public function GetDomainID($domainName)
    {
        $sqlQuery = 'SELECT work_domain_ID 
                        FROM Work_domain 
                        WHERE domain_name = :domainName';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':domainName', $domainName, PDO::PARAM_STR);
        $statement->execute(); // execute the PDO statement
        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new DomainTable($row);
        }
    return $dataSet;
    }

    public function getAll()
    {
        $sqlQuery = 'SELECT domain_name FROM Work_domain';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement
        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new DomainTable($row);
        }
        return $dataSet;
    }
}
<?php

require_once('Database.php');
require_once('DomainTable.php');

/**
 * Class DomainQueries. This class is used to manipulate data in the Work_domain table
 * and it contains SQL queries to aid this requirement.
 */

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
        $sqlQuery = 'LOCK TABLE Work_domain WRITE;
                     DELETE FROM Work_domain
                        WHERE domain_name = :domainName;
                     UNLOCK TABLES';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':domainName', $domainName, PDO::PARAM_STR);
        $statement->execute(); // execute the PDO statement
    }


    /**
     * This function is used to add a new domain into the database given its name.
     *
     * @param $domainName
     */
    public function InsertDomain($domainName)
    {
        $sqlQuery = 'LOCK TABLE Work_domain WRITE;
                     INSERT INTO Work_domain (domain_name) 
                        VALUES (:domainName);
                     UNLOCK TABLES';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':domainName', $domainName, PDO::PARAM_STR);
        $statement->execute(); // execute the PDO statement
    }

    /**
     * This function is used to gather a work domain ID given its name.
     *
     * @param $domainName
     * @return mixed
     */
    public function GetDomainID($domainName)
    {
        $sqlQuery = 'SELECT work_domain_ID 
                        FROM Work_domain 
                        WHERE domain_name = :domainName';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':domainName', $domainName, PDO::PARAM_STR);
        $statement->execute(); // execute the PDO statement
    return $statement->fetch();
    }

    /**
     * This function is used to gather all work domain names from the database.
     *
     * @return array
     */
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
<?php
require('Database.php');
require('AssessmentTypeTable.php');

class AssessmentTypeQueries
{
    protected $_dbInstance;
    protected $_dbHandle;

    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    public function DeleteAssessmentType($typeName)
    {
        $sqlQuery = 'DELETE FROM Assessment_type
                        WHERE name = :name';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':name', $typeName, PDO::PARAM_STR);
        $statement->execute(); // execute the PDO statement
    }

    public function InsertAssessmentType($name, $description, $workDomainID)
    {
        $sqlQuery = 'INSERT INTO Assessment_type (name, description, work_domain_ID) 
                        VALUES (:name, :description, :workDomainID)';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':name', $name, PDO::PARAM_STR);
        $statement->bindValue(':description', $description, PDO::PARAM_STR);
        $statement->bindValue(':workDomainID', $workDomainID, PDO::PARAM_INT);
        $statement->execute(); // execute the PDO statement
    }

    public function GetAssessmentTypeByName($name)
    {
        $sqlQuery = 'SELECT name 
                        FROM Assessment_type 
                        WHERE name = :name';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':name', $name, PDO::PARAM_STR);
        $statement->execute(); // execute the PDO statement
        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new AssessmentTypeTable($row);
        }
        return $dataSet;
    }

    public function getAll()
    {
        $sqlQuery = 'SELECT name FROM Assessment_type';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement
        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new AssessmentTypeTable($row);
        }
        return $dataSet;
    }


}
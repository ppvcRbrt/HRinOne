<?php
require_once('Database.php');
require_once('AssessmentTypeTable.php');

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
        $sqlQuery = 'LOCK TABLE Assessment_type WRITE;
                     DELETE FROM Assessment_type
                        WHERE name = :name;
                     UNLOCK TABLES';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':name', $typeName, PDO::PARAM_STR);
        $statement->execute(); // execute the PDO statement
    }

    public function InsertAssessmentType($name, $description, $workDomainID)
    {
        $sqlQuery = 'LOCK TABLE Assessment_type WRITE;
                     INSERT INTO Assessment_type (name, description, work_domain_ID) 
                        VALUES (:name, :description, :workDomainID);
                     UNLOCK TABLES';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':name', $name, PDO::PARAM_STR);
        $statement->bindValue(':description', $description, PDO::PARAM_STR);
        $statement->bindValue(':workDomainID', $workDomainID, PDO::PARAM_INT);
        $statement->execute(); // execute the PDO statement
    }

    public function GetAssessmentTypeID($name)
    {
        $sqlQuery = 'SELECT assessment_type_ID 
                        FROM Assessment_type 
                        WHERE name = :name';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':name', $name, PDO::PARAM_STR);
        $statement->execute(); // execute the PDO statement
        return $statement->fetch();
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
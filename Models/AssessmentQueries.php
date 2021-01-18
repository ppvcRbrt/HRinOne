<?php
require_once('Database.php');
require_once('AssessmentTable.php');

class AssessmentQueries
{
    protected $_dbInstance;
    protected $_dbHandle;

    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    public function DeleteAssessment($name)
    {
        $sqlQuery = 'LOCK TABLE Assessment WRITE;;
                     DELETE FROM Assessment
                        WHERE name = :name;
                     UNLOCK TABLES';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':name', $name, PDO::PARAM_STR);
        $statement->execute(); // execute the PDO statement
    }

    public function InsertAssessment($name, $description, $candidateID, $assessmentTypeID)
    {
        $sqlQuery = 'LOCK TABLE Assessment WRITE;
                     INSERT INTO Assessment (name, description, candidate_ID, assessment_type_ID) 
                        VALUES (:name, :description, :candidateID, :assessmentTypeID);
                     UNLOCK TABLES';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':name', $name, PDO::PARAM_STR);
        $statement->bindValue(':description', $description, PDO::PARAM_STR);
        $statement->bindValue(':candidateID', $candidateID, PDO::PARAM_INT);
        $statement->bindValue(':assessmentTypeID', $assessmentTypeID, PDO::PARAM_INT);
        $statement->execute(); // execute the PDO statement
    }

    public function GetAssessmentID($name)
    {
        $sqlQuery = 'SELECT assessment_ID 
                        FROM Assessment
                        WHERE name = :name';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':name', $name, PDO::PARAM_STR);
        $statement->execute(); // execute the PDO statement
        return $statement->fetch();
    }

    public function getAssessmentName($assessmentID)
    {
        $sqlQuery = 'SELECT name FROM Assessment
                     WHERE ID = :assessmentID';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':assessmentID', $assessmentID, PDO::PARAM_STR);
        $statement->execute(); // execute the PDO statement
        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new AssessmentTable($row);
        }
        return $dataSet;
    }

    public function getAssessmentDescription($assessmentID)
    {
        $sqlQuery = 'SELECT description FROM Assessment
                     WHERE ID = :assessmentID';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':assessmentID', $assessmentID, PDO::PARAM_STR);
        $statement->execute(); // execute the PDO statement
        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new AssessmentTable($row);
        }
        return $dataSet;
    }

    public function getAll()
    {
        $sqlQuery = 'SELECT name FROM Assessment';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement
        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new AssessmentTable($row);
        }
        return $dataSet;
    }

    /**
     * This needs to be tested before deployment
     */


    /**
     * This method should get assessment details given a work domain name
     */
    public function getAssessmentDetails($workDomainName)
    {
        $sqlQuery = 'SELECT Assessment_type.name, description
                     FROM Assessment_type, Work_domain
                     WHERE  Work_domain.work_domain_ID = Assessment_type.work_domain_ID
                     AND Work_domain.domain_name = :workDomainName';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':workDomainName', $workDomainName, PDO::PARAM_STR);
        $statement->execute(); // execute the PDO statement
        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new AssessmentTable($row);
        }
        return $dataSet;
    }

}
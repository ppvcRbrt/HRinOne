<?php
require_once('Database.php');
require_once('AssessmentTable.php');

/**
 * Class AssessmentQueries. This class represents the queries for manipulating data
 * regarding assessments.
 */

class AssessmentQueries
{
    protected $_dbInstance;
    protected $_dbHandle;

    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    /**
     * This method is used to delete an assessment based on name
     *
     * @param $name
     */
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

    /**
     * This method is used to insert an assessment into the database, given the required
     * information (name, description, candidate ID and assessment type ID)
     *
     * @param $name
     * @param $description
     * @param $candidateID
     * @param $assessmentTypeID
     */
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

    /**
     * This method is used to get the assessment ID of an assessment given its name.
     *
     * @param $name
     * @return mixed
     */
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


    /**
     * This method is used to get the assessment name given its ID.
     *
     * @param $assessmentID
     * @return array
     */
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


    /**
     * This method is used to get the description of an assessment based on its ID.
     *
     * @param $assessmentID
     * @return array
     */
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


    /**
     * This method is used to gather all assessment names from the database.
     */
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


}
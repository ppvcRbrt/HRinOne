<?php
require_once('Database.php');
require_once('AssessmentTypeTable.php');

/**
 * Class AssessmentQueries. This class represents the queries for manipulating data
 * regarding assessment types.
 */

class AssessmentTypeQueries
{
    protected $_dbInstance;
    protected $_dbHandle;

    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }


    /**
     * This function is used to delete an assessment type based on its name.
     *
     * @param $typeName
     */
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


    /**
     * This function is used to insert an assessment type into the database
     * given the required information (name, description and work domain ID).
     *
     * @param $name
     * @param $description
     * @param $workDomainID
     */
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

    /**
     * This method is used to gather an assessment type ID given its name.
     *
     * @param $name
     * @return mixed
     */
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

    /**
     * This method is used to gather all names of the assessment types.
     *
     * @return array
     */
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
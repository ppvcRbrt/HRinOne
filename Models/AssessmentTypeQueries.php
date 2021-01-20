<?php
require_once('Database.php');
require_once('AssessmentTypeTable.php');

/**
 * Class AssessmentTypeQueries. This class is used to manipulate data in the Assessment_type table
 * and it contains SQL queries to aid this requirement.
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
     * This method is used to delete an assessment type given its name.
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
     * This function is used to insert an assessment type given the necessary information (name, descritpion and work domain ID)
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
     * This function is used to gather an assessment type ID given its name.
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
     * This function is used to gather an assessment type name given a work domain name.
     *
     * @param $workDomName
     * @return array
     */
    public function getAssessmentTypeByWorkDom($workDomName)
    {
        $sqlQuery = 'SELECT Assessment_type.name 
                        FROM Assessment_type, Work_domain
                        WHERE Assessment_type.work_domain_ID = Work_domain.work_domain_ID
                        AND Work_domain.domain_name = :domainName';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':domainName', $workDomName, PDO::PARAM_STR);
        $statement->execute(); // execute the PDO statement
        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new AssessmentTypeTable($row);
        }
        return $dataSet;
    }

    /**
     * This function is used to gather all assessment type names.
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
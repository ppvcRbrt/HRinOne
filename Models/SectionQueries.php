<?php
require_once('Database.php');
require_once('SectionTable.php');

class SectionQueries
{
    protected $_dbInstance;
    protected $_dbHandle;

    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    public function DeleteSection($sectionName)
    {
        $sqlQuery = 'LOCK TABLE Section WRITE;
                     DELETE FROM Section
                        WHERE name = :name;
                     UNLOCK TABLES';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':name', $sectionName, PDO::PARAM_STR);
        $statement->execute(); // execute the PDO statement
    }

    public function InsertSection($name, $description, $weight, $assessmentTypeID)
    {
        $sqlQuery = 'LOCK TABLE Section WRITE;
                     INSERT INTO Section (name, weight, description, assessment_type_ID) 
                        VALUES (:name, :weight, :description, :assessmentTypeID);
                     UNLOCK TABLES'; //<--Smol mistake that's been fixed
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':name', $name, PDO::PARAM_STR);
        $statement->bindValue(':weight', $weight, PDO::PARAM_STR);
        $statement->bindValue(':description', $description, PDO::PARAM_STR);
        $statement->bindValue(':assessmentTypeID', $assessmentTypeID, PDO::PARAM_INT);
        $statement->execute(); // execute the PDO statement

    }

    public function GetSectionIDByName($name)
    {
        $sqlQuery = 'SELECT section_ID 
                        FROM Section 
                        WHERE name = :name';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':name', $name, PDO::PARAM_STR);
        $statement->execute(); // execute the PDO statement
        return $statement->fetch();
    }

    public function getAll()
    {
        $sqlQuery = 'SELECT name FROM Section';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement
        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new SectionTable($row);
        }
        return $dataSet;
    }

    /**
     * This needs to be tester before deployment
     */

    /**
     * THE SQL NEEDS ADJUSTMENTS, IT DOESN'T WORK.
     * This method should get section details based on assessment type name and work domain name
     */
    public function getSectionDetails($assessmentTypeName, $workDomainName)
    {
        $sqlQuery = 'SELECT Section.name, Section.description, Section.weight
                     FROM Assessment_type, Work_domain,Section
                     WHERE Section.assessment_type_ID = Assessment_type.assessment_type_ID
                     AND Assessment_type.name= :assessmentTypeName
                     AND Work_domain.work_domain_ID = Assessment_type.work_domain_ID
                     AND Work_domain.domain_name = :workDomainName';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':assessmentTypeName', $assessmentTypeName, PDO::PARAM_STR);
        $statement->bindValue(':workDomainName', $workDomainName, PDO::PARAM_STR);
        $statement->execute(); // execute the PDO statement
        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new SectionTable($row);
        }
        return $dataSet;
    }


}
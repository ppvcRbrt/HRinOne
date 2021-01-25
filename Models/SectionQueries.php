<?php
require_once('Database.php');
require_once('SectionTable.php');

/**
 * Class SectionQueries. This class is used to manipulate data in the Section table
 * and it contains SQL queries to aid this requirement.
 */

class SectionQueries
{
    protected $_dbInstance;
    protected $_dbHandle;

    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    /**
     * Thisn function is used to delete a section using its name.
     *
     * @param $sectionName
     */
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

    /**
     * This function is used to insert a new section into the database using its details
     * (name, description, weight and the assessment type ID)
     *
     * @param $name
     * @param $description
     * @param $weight
     * @param $assessmentTypeID
     */
    public function InsertSection($name, $description, $weight, $assessmentTypeID)
    {
        $sqlQuery = 'LOCK TABLE Section WRITE;
                     INSERT INTO Section (name, description, weight, assessment_type_ID) 
                        VALUES (:name, :description, :weight, :assessmentTypeID);
                     UNLOCK TABLES';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':name', $name, PDO::PARAM_STR);
        $statement->bindValue(':weight', $weight, PDO::PARAM_STR);
        $statement->bindValue(':description', $description, PDO::PARAM_STR);
        $statement->bindValue(':assessmentTypeID', $assessmentTypeID, PDO::PARAM_INT);
        $statement->execute(); // execute the PDO statement

    }

    /**
     * This function is used to gather a section ID given its name.
     *
     * @param $name
     * @return mixed
     */
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

    /**
     * This function is used to gather all section names from the Section table.
     *
     * @return array
     */
    public function getAll()
    {
        $sqlQuery = 'SELECT name FROM Section ORDER BY section_ID';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement
        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new SectionTable($row);
        }
        return $dataSet;
    }

    public function getAllIDs()
    {
        $sqlQuery = 'SELECT section_ID FROM Section ORDER BY section_ID';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement
        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new SectionTable($row);
        }
        return $dataSet;
    }
    /**
     * This function is used to gather a section name from the database, where
     * its ID is specified.
     *
     * @param $secID
     * @return mixed
     */
    public function getSectionNameByID($secID)
    {
        $sqlQuery = 'SELECT name FROM Section
                     WHERE section_ID = :secID';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':secID', $secID, PDO::PARAM_INT);
        $statement->execute(); // execute the PDO statement
        return $statement->fetch();
    }
    public function getSectionDescByID($secID)
    {
        $sqlQuery = 'SELECT description FROM Section
                     WHERE section_ID = :secID';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':secID', $secID, PDO::PARAM_INT);
        $statement->execute(); // execute the PDO statement
        return $statement->fetch();
    }

    /**
     * This function is used to gather a section name which is linked with an
     * assessment type and with a work domain.
     *
     * @param $assessmentTypeID
     * @param $workDomID
     * @return array
     */
    public function getSectionsByAssessmentTypeID($assessmentTypeID, $workDomID)
    {
        $sqlQuery = 'SELECT Section.name 
                        FROM Section, Assessment_type, Work_domain
                        WHERE Section.assessment_type_ID = Assessment_type.assessment_type_ID
                        AND Assessment_type.work_domain_ID = Work_domain.work_domain_ID
                        AND Assessment_type.assessment_type_ID = :assessmentTypeID
                        AND Work_domain.work_domain_ID = :workDomID';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':assessmentTypeID', $assessmentTypeID, PDO::PARAM_INT);
        $statement->bindValue(':workDomID', $workDomID, PDO::PARAM_INT);
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
<?php
require_once('Database.php');
require_once('SectionTable.php');

/**
 * Class AssessmentQueries. This class represents the queries for manipulating data
 * regarding section queries.
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
     * This function is intended to delete a section based on its name
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
     * This function is intended to be used to insert a new section into the database using the
     * required information (section name, description, score weight and assessment type ID
     *
     * @param $name
     * @param $description
     * @param $weight
     * @param $assessmentTypeID
     */
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

    /**
     * This method is used to gather a section ID using its name
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


}
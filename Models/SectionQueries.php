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


}
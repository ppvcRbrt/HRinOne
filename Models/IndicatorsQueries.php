<?php

require_once('Database.php');
require_once('IndicatorsTable.php');

/**
 * Class IndicatorsQueries. This class is used to manipulate data in the Indicator table
 * and it contains SQL queries to aid this requirement.
 */

class IndicatorsQueries
{
    protected $_dbInstance;
    protected $_dbHandle;

    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    /**
     * Function to query and return all info from table
     * @return array: will return an array of our rows
     */

    public function DeleteIndicator($indicatorID)
    {
        $sqlQuery = 'LOCK TABLE Indicator WRITE;
                     DELETE FROM Indicator
                        WHERE indicator_ID = :indicatorID;
                     UNLOCK TABLES';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':indicatorID', $indicatorID, PDO::PARAM_INT);
        $statement->execute(); // execute the PDO statement
    }


    /**
     * This function is used to insert a new indicator in the database given its details
     * (Indicator description, feedback, score, witght and section ID)
     *
     * @param $description
     * @param $feedback
     * @param $score
     * @param $weight
     * @param $sectionID
     */
    public function InsertIndicator($description, $feedback, $score, $weight, $sectionID)
    {
        $sqlQuery = 'LOCK TABLE Indicator WRITE;
                     INSERT INTO Indicator (indicator_description,feedback,score,weight,section_ID) 
                        VALUES (:description,:feedback,:score,:weight,:section_ID);
                     UNLOCK TABLES';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':description', $description, PDO::PARAM_STR);
        $statement->bindValue(':feedback', $feedback, PDO::PARAM_STR);
        $statement->bindValue(':score', $score, PDO::PARAM_STR);
        $statement->bindValue(':weight', $weight, PDO::PARAM_STR);
        $statement->bindValue(':section_ID', $sectionID, PDO::PARAM_STR);
        $statement->execute(); // execute the PDO statement
    }


    /**
     * This function is used to gather information about indicators based on a section name.
     *
     * @param $sectionName
     * @return array
     */
    public function GetIndicator($sectionName)
    {
        $sqlQuery = 'SELECT feedback, score, Indicators.weight, Indicators.section_ID 
                        FROM Indicators, Section 
                        WHERE Section.section_ID = Indicators.section_ID and Section.name= :sectName';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':sectName', $sectionName, PDO::PARAM_STR);
        $statement->execute(); // execute the PDO statement
        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new IndicatorsTable($row);
        }
        return $dataSet;
    }
}
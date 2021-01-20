<?php

require_once('Database.php');
require_once('IndicatorsTable.php');

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

    public function InsertIndicator($description, $feedback, $score, $weight, $questionID)
    {
        $sqlQuery = 'LOCK TABLE Indicator WRITE;
                     INSERT INTO Indicator (indicator_description,feedback,score,weight,question_ID) 
                        VALUES (:description,:feedback,:score,:weight,:question_ID);
                     UNLOCK TABLES';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':description', $description, PDO::PARAM_STR);
        $statement->bindValue(':feedback', $feedback, PDO::PARAM_STR);
        $statement->bindValue(':score', $score, PDO::PARAM_STR);
        $statement->bindValue(':weight', $weight, PDO::PARAM_STR);
        $statement->bindValue(':question_ID', $questionID, PDO::PARAM_INT);
        $statement->execute(); // execute the PDO statement
    }

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
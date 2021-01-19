<?php
require_once('Database.php');
require_once('CandidateInfoTable.php');

class CandidateInfoQueries
{
    protected $_dbInstance;
    protected $_dbHandle;

    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }
    // normalizare plm normalized = (x-min(x))/(max(x)-min(x))

    /**
     * Function to query and return all info from table
     * @return array: will return an array of our rows
     */
    public function getCandidateName($candidateID)
    {
        $sqlQuery = 'SELECT name FROM Candidate_info
                     WHERE candidate_ID = :candID';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':candID', $candidateID, PDO::PARAM_STR);
        $statement->execute(); // execute the PDO statement
        return $statement->fetch();
    }


    public function getCandIDByName($candidateName)
    {
        $sqlQuery = "SELECT MATCH(name) AGAINST((:candName'*') IN BOOLEAN MODE) score, candidate_ID, name  
                        FROM Candidate_info
                        HAVING score>0
                        ORDER BY score 
                        DESC limit 10";
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':candName', $candidateName, PDO::PARAM_STR);
        $statement->execute(); // execute the PDO statement
        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new CandidateInfoTable($row);
        }
        return $dataSet;
    }

    public function getCandidateWorkDomName($candidateID)
    {
        $sqlQuery = 'SELECT Work_domain.domain_name 
                        FROM Work_domain, Candidate_info 
                        WHERE Work_domain.work_domain_ID = Candidate_info.work_domain_ID
                        AND Candidate_info.candidate_ID = :candID';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':candID', $candidateID, PDO::PARAM_INT);
        $statement->execute(); // execute the PDO statement
        return $statement->fetch();
    }

    public function getCandidateAssTypes($candidateID)
    {

        $sqlQuery = 'SELECT Assessment_type.name 
                        FROM Assessment_type, Work_domain, Candidate_info 
                        WHERE Work_domain.work_domain_ID = Candidate_info.work_domain_ID
                        AND Assessment_type.work_domain_ID = Work_domain.work_domain_ID
                        AND Candidate_info.candidate_ID = :candID';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':candID', $candidateID, PDO::PARAM_INT);
        $statement->execute(); // execute the PDO statement
        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new CandidateInfoTable($row);
        }
        return $dataSet;
    }
}
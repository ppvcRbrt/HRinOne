<?php
require_once('Database.php');
require_once('UserTable.php');

/**
 * Class UserQueries. This class is used to manipulate data in the User table
 * and it contains SQL queries to aid this requirement.
 */

class UserQueries
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
    public function getPrivileges($name)
    {
        $sqlQuery = 'SELECT user_category_ID FROM Users 
                     WHERE name = :name';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':name', $name, PDO::PARAM_STR);
        $statement->execute(); // execute the PDO statement
        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new UserTable($row);
        }
        return $dataSet;
    }

    /**
     * This function is used to return the name of an User based
     * on its ID.
     *
     * @param $ID
     * @return array
     */
    public function getName($ID)
    {
        $sqlQuery = 'SELECT name FROM Users 
                     WHERE ID = :ID';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':ID', $ID, PDO::PARAM_STR);
        $statement->execute(); // execute the PDO statement
        return $statement->fetch();
    }
}
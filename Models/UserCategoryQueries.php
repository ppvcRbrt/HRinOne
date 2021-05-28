<?php
require_once('Database.php');
require_once('UserCategoryTable.php');

/**
 * Class UserQueries. This class is used to manipulate data in the User table
 * and it contains SQL queries to aid this requirement.
 */

class UserCategoryQueries
{
    protected $_dbInstance;
    protected $_dbHandle;


    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    /**
     * Will get the category name based on category id
     * @param $catID
     * @return mixed
     */
    public function getCategory($catID)
    {
        $sqlQuery = 'SELECT category FROM User_category 
                     WHERE user_category_ID = :catID';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':catID', $catID, PDO::PARAM_STR);
        $statement->execute(); // execute the PDO statement
        return $statement->fetch();
    }
}
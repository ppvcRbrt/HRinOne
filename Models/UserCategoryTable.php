<?php

/**
 * Class UserTable. This class is used to manipulate data regarding an user
 */
class UserCategoryTable
{

    protected $_user_category_ID, $_category;

    /**
     * Constructor of the UserTable class
     */
    public function __construct($dbRow)
    {
        $this->_user_category_ID = $dbRow['user_category_ID']?? null;
        $this->_category = $dbRow['category']?? null;

    }

    public function getCategoryID()
    {
        
    }
    public function getCategory()
    {
        return $this->_category;
    }

}
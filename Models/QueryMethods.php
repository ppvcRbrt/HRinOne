<?php
class QueryMethods{

    /**
     * Function that helps us bind multiple values to a statement
     * @param $valueToBind : category ID
     * @param $stmt : statement
     */
    public function bind($valueToBind , $stmt)
    {
        if(is_array($valueToBind))
        {
            foreach($valueToBind as $currentValue)
            {
                $stmt->bindValue(":" . $currentValue, $currentValue, PDO::PARAM_INT);
            }
        }
        else
        {
            $stmt->bindValue(":" . $valueToBind, (int)$valueToBind, PDO::PARAM_INT);
        }
    }

}
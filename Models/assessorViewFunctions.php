<?php

/**
 * Class assessorViewFunctions : This class should allow us to print some indicators and questions for every section in the right order
 */
class assessorViewFunctions
{
    /**
     * @param $multiDimArray : an array with a key=>value pair of indicator ids => question ids
     * @param $currentQuestionID
     * @param $sectionNo
     * @param $questionNo
     * @return array
     */
    function getIndicatorForQuestion($multiDimArray, $currentQuestionID, $sectionNo, $questionNo)
    {
        $indicatorQuery = new IndicatorsQueries();
        $descriptions = array();
        $indCount = 0;
        $_SESSION["allIndicatorNames"] = array();

        //our main indicator loop that will print all indicators per a question
        for($x = 0; $x < count($multiDimArray); $x++)
        {
            $indicatorID = array_search($currentQuestionID, $multiDimArray); //we search for a key which is going to be the indicator id
            unset($multiDimArray[$indicatorID]);
            $indicatorScore = $indicatorQuery->getIndicatorScoreByID($indicatorID);
            $indicatorDesc = $indicatorQuery->getIndicatorDescByID($indicatorID);

            //here we get our desriptions for the indicators and we put them in an array
            if(isset($indicatorDesc))
            {
                foreach($indicatorDesc as $currentDesc)
                {
                    array_push($descriptions, $currentDesc->getDescription());
                }
            }

            if(isset($indicatorScore[0]))
            {
                if(isset($_SESSION["indicatorIDForSection"]))
                {
                    $indAdded = false;
                    //in this loop we add the indicators
                    foreach($_SESSION["indicatorIDForSection"] as $currentIndicatorID)
                    {
                        if($currentIndicatorID === $indicatorID)
                        {
                            echo'<label class="btn btn-secondary active" data-toggle="collapse" data-target="#descriptionInd'.$indCount.'q'.$questionNo.'sec'.$sectionNo.'">
                             <input type="radio" name="indicatorValueQ'.$questionNo.'" autocomplete="off" value = "'.$indicatorID.'" checked>'.$indicatorScore[0].'
                             </label>';
                            $indCount++;
                            $indAdded = true;
                        }
                    }
                    if(!$indAdded)
                    {
                        echo'<label class="btn btn-secondary active disabled" data-toggle="collapse" data-target="#descriptionInd'.$indCount.'q'.$questionNo.'sec'.$sectionNo.'">
                             <input type="radio" name="indicatorValueQ'.$questionNo.'" autocomplete="off" value = "'.$indicatorID.'">'.$indicatorScore[0].'
                             </label>';
                        $indCount++;
                    }
                }
                else
                {
                    echo'<label class="btn btn-secondary active" data-toggle="collapse" data-target="#descriptionInd'.$indCount.'q'.$questionNo.'sec'.$sectionNo.'">
                         <input type="radio" name="indicatorValueQ'.$questionNo.'" autocomplete="off" value = "'.$indicatorID.'" required>'.$indicatorScore[0].'
                         </label>';
                    $indCount++;
                }

            }
        }
        return $descriptions;
    }

    /**
     * @param $multiDimArray : a key => value pair of question ids => section ids
     * @param $currentSectionID : the current section we are in
     * @param $sectionNo : the section number out of total
     */
    function getQuestionForSection($multiDimArray, $currentSectionID, $sectionNo)
    {
        $questionQuery = new QuestionQueries(); //creates a new query class
        $allIndicators = $this->getAllIndicatorsWithQuestions();
        $currentMultiDimCount = count($multiDimArray);//count how many variables in the array
        $questionCount = 0; //will be just used to print the name of questions

        //this will be our main question loop and inside will go our indicator loop from the function above
        //this will help print out our questions
        for($x = 0 ; $x < $currentMultiDimCount ; $x++)
        {
            $questionID = array_search($currentSectionID, $multiDimArray); // we search for the question ids here that should be the "key"
            unset($multiDimArray[$questionID]); // and since array_search only returns the first element that matches the criteria we need to unset that specific key=>value pair
            $currentMultiDimCount = count($multiDimArray);
            $questionName = $questionQuery->getQuestionName($questionID);
            if(isset($questionName[0])) //if we can find a question by that name
            {
                if($questionID) //if question id is not false since its originated query
                {
                    echo '<p>'. $questionName[0] . '</p>';
                    echo '<div id = "question'.$questionCount.'sec'.$sectionNo.'">';
                    echo '<div class = "row justify-content-center">';
                    echo '<div class="btn-group btn-group-toggle" data-toggle="buttons" id = "buttonsForQ'.$questionCount.'Sec'.$sectionNo.'">';
                    $indDesc = $this->getIndicatorForQuestion($allIndicators, $questionID, $sectionNo, $questionCount); //method above to print idicators for questions
                    $indCount = 0;
                    echo '</div>';
                    echo '</div>';
                    //here we print the indicator descriptions that will be linked to the indicators we printed above
                    foreach($indDesc as $currentDescription)
                    {
                        echo '<div class="row justify-content-center collapse" id="descriptionInd'.$indCount.'q'.$x.'sec'.$sectionNo.'" data-parent="#question'.$x.'sec'.$sectionNo.'">
                                    <div class="card card-body col-4">
                                        '.$currentDescription.'
                                    </div>
                            </div>';
                        $indCount++;
                    }
                    echo '</div>';

                    $questionCount++;
                }
            }

        }
    }

    /**
     * @return array : returns an array with a key => value pair of question ids => section ids
     */
    function getAllQuestionsWithSections()
    {
        $questionsPerSections['questionID']['sectionID'] = array();
        $questionID = array();
        $sectionID = array();
        for($x = 0; $x < count($_SESSION["questionIDs"]['qID']); $x++)
        {
            array_push($questionID, $_SESSION["questionIDs"]['qID'][$x]);
            array_push($sectionID, $_SESSION["questionIDs"]['secID'][$x]);
        }

        $multiDimArray = array_combine($questionID, $sectionID);
        return $multiDimArray;
    }

    /**
     * @return int : how many sections we have per assessment(stored in a session variable)
     */
    function getAllSectionsCount()
    {
        return count($_SESSION["sectionIDs"]);
    }

    /**
     * @return array : returns an array with the key => value pair of indicator ids => question ids
     */
    function getAllIndicatorsWithQuestions()
    {
        $indicatorID = array();
        $questionID = array();
        for($x = 0; $x < count($_SESSION["indicatorIDs"]['indID']); $x++)
        {
            array_push($indicatorID, $_SESSION["indicatorIDs"]['indID'][$x]);
            array_push($questionID, $_SESSION["indicatorIDs"]['qID'][$x]);
        }
        $multiDimArray = array_combine($indicatorID, $questionID);
        return $multiDimArray;
    }
}
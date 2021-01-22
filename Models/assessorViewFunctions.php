<?php

class assessorViewFunctions
{
    function getIndicatorForQuestion($multiDimArray, $currentQuestionID)
    {
        $indicatorQuery = new IndicatorsQueries();
        for($x = 0; $x < count($multiDimArray); $x++)
        {
            $indicatorID = array_search($currentQuestionID, $multiDimArray);
            unset($multiDimArray[$indicatorID]);
            $indicatorScore = $indicatorQuery->getIndicatorScoreByID($indicatorID);
            if(isset($indicatorScore[0]))
                echo'<label class="btn btn-secondary active">
                        <input type="radio" name="options" id="option'.$indicatorScore[0].'" autocomplete="off" value = "'.$indicatorScore[0].'">'.$indicatorScore[0].'
                    </label>';
               // echo '<p>Indicator Score: ' . $indicatorScore[0] . '</p>';
        }
    }

    function getQuestionForSection($multiDimArray, $currentSectionID)
    {
        $questionQuery = new QuestionQueries();
        $allIndicators = $this->getAllIndicatorsWithQuestions();
        for($x = 0 ; $x < count($multiDimArray) ; $x++)
        {
            $questionID = array_search($currentSectionID, $multiDimArray);
            unset($multiDimArray[$questionID]);
            $questionName = $questionQuery->getQuestionName($questionID);
            if(isset($questionName[0]))
                if(!empty($questionName[0]))
                    echo '<p>Question Name: '. $questionName[0] . '</p>';
                    echo '<div id = "question'.$x.'" class = "row justify-content-center">';
                    echo '<div class="btn-group btn-group-toggle h-50" data-toggle="buttons">';
                    $this->getIndicatorForQuestion($allIndicators, $questionID);
                    echo '</div>';
                    echo '</div>';

        }
    }
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
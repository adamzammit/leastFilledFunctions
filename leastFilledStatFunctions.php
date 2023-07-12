<?php

/**
 * This file extends the statFunctions plugin
 * @version 0.0.1
 */

namespace leastFilledFunctions;

use Yii;
use CHtml;
use LimeExpressionManager;
use Survey;
use SurveyDynamic;
use CDbCriteria;
use Permission;
use LimeSurvey\PluginManager\LimesurveyApi as LimesurveyApi;
use statFunctions;

class leastFilledStatFunctions
{
    /**
     * Return the least filled response on current ExpressionScript Engine survey question
     * @param string $qCode : code of question, currently must be existing sgqa. Sample Q01.sgqa.
     * @param boolean $submitted (or not) response
     * @param boolean $self include (or not) current response
     * @return integer|string
     */

    public static function statLF($qCode, $submitted = true, $self = true)
    {
        $api = new LimesurveyApi();
        $surveyId = $api->getCurrentSurveyid(true);
        if (!$surveyId) {
            return 0;
        }
        
        $qtmp = explode("X",$qCode);
        $qid = null;
        
        if (isset($qtmp[2])) {
            $qid = $qtmp[2];
        } else {
            return null;
        }
        
        $oQuestion = Question::model()->find(
                "qid = :qid and sid = :sid",
                array(":qid" => $qid, ":sid" => $surveyId)
        );
        if ($oQuestion && $oQuestion->parent_qid) {
            $oQuestion = Question::model()->find(
                "qid = :qid and sid = :sid",
                array(":qid" => $oQuestion->parent_qid, ":sid" => $surveyId)
            );
        }
 
        if (empty($oQuestion)) {
            if (Permission::model()->hasSurveyPermission($surveyId, 'surveycontent')) { // update ???
                return sprintf(gT("Invalid question code or ID “%s”"), CHtml::encode($qCode));
            }
            return null;
        }
    
        // get all answer codes then getcount on all of them
        $aAnswersFilled=array();
        
        $countFunctions = new countFunctions();
        
        foreach($oQuestion->answers as $key => $value) {
            $aAnswersFilled[$value['code']]=$countFunctions->statCountIf($qCode,$value['code'],$submitted,$self);
        }
        if(!empty($aAnswersFilled)) {
            //return least filled code
            asort($aAnswersFilled);
            return key($aAnswersFilled);
        }
        return "";
    }
}

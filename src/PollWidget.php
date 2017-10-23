<?php

namespace davidjeddy\poll;

use \Yii;
use yii\base\Widget;

/**
 * Class PollWidget
 *
 * @author David J Eddy <me@davidjeddy.com>
 *
 * @package davidjeddy\poll
 */
class PollWidget extends Widget
{
    /**
     * @var array
     */
    public $answerOptions = [];

    /**
     * @var
     */
    public $answerOptionsData;

    /**
     * @var array
     */
    public $answers = [];

    /**
     * @var
     */
    public $isVote;

    /**
     * @var array
     */
    public $params = [
        'backgroundLinesColor' => '#D3D3D3',
        'linesColor'           => '#4F9BC7',
        'linesHeight'          => 15,
        'maxLineWidth'         => 300,
    ];

    /**
     * @var
     */
    public $pollData;

    /**
     * @var string
     */
    public $questionText = '';

    /**
     * @var int
     */
    public $sumOfVoices = 0;

    /**
     * @var array
     */

    // experimental ajax success override
    public $ajaxSuccess = [];

    /**
     * DB interface object
     *
     * @var null
     */
    private $pollDB = null;

    /**
     * @param $params
     */
    public function setParams($params)
    {

        $this->params = array_merge($this->params, $params);
    }

    /**
     * @param $param
     * @return mixed
     */
    public function getParams($param)
    {

        return $this->params[$param];
    }

    /**
     * @return mixed
     */
    public function init()
    {
        $this->pollDB = new PollDb;

        if ($this->answerOptions !== null) {
            $this->answerOptionsData = serialize($this->answerOptions);
        }

        // Check the DB for the poll, if not found treat the poll as a new poll and save it.
        if (!$this->pollDB->doesPollExist($this->questionText)) {
            $this->pollDB->saveNewPoll($this);
        }

        // check that all Poll answers exist
        $this->pollDB->pollAnswerOptions($this);

        if (\Yii::$app->request->isAjax) {

            if ($_POST['questionText'] === $this->questionText
                && isset($_POST['VoicesOfPoll']['voice'])
            ) {
                $this->pollDB->updateAnswers(
                    $this->questionText,
                    $_POST['VoicesOfPoll']['voice'],
                    $this->answerOptions
                );

                $this->pollDB->updatePollUsers($this->questionText);
            }
        }

        $this->answerOptionsData = $this->pollDB->getDbData($this);
        $this->answers = $this->pollDB->getVoicesData($this->questionText);

        $answerCount = count($this->answers);
        for ($i = 0; $i < $answerCount; $i++) {
            $this->sumOfVoices = $this->sumOfVoices + $this->answers[$i]['value'];
        }

        $this->isVote = $this->pollDB->isVote($this->questionText);

        return parent::init();
    }

    /**
     * @return bool
     */
    public function run()
    {
        if ($this->pollDB->doTablesExist() < 3) {
            return false;
        }

        $model = new VoicesOfPoll;

        return $this->render('index', [
            'ajaxSuccess' => $this->ajaxSuccess,
            'answers'     => $this->answerOptions,
            'answersData' => $this->answers,
            'isVote'      => $this->isVote,
            'model'       => $model,
            'params'      => $this->params,
            'pollData'    => $this->pollData,
            'sumOfVoices' => $this->sumOfVoices,
        ]);
    }
}

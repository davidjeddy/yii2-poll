<?php

namespace davidjeddy\poll;

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
    public $isExist;

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
    public $pollName = '';

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
     * @param $name
     */
    public function setPollName($name)
    {

        $this->pollName = $name;
    }

    /**
     *
     */
    public function getDbData()
    {

        $db = \Yii::$app->db;

        $command = $db->createCommand('SELECT * FROM poll_question WHERE poll_name=:pollName')
            ->bindParam(':pollName', $this->pollName);

        $this->pollData = $command->queryOne();
        $this->answerOptionsData = unserialize($this->pollData['answer_options']);
    }

    /**
     * @return int
     */
    public function setDbData()
    {
        return \Yii::$app->db->createCommand()->insert('poll_question', [
            'answer_options' => $this->answerOptionsData,
            'poll_name'      => $this->pollName,
        ])->execute();
    }

    /**
     * @param $params
     */
    public function setParams($params)
    {

        $this->params = array_merge($this->params, $params);
    }

    /**
     * @param $param
     *
     * @return mixed
     */
    public function getParams($param)
    {

        return $this->params[$param];
    }

    /**
     *
     */
    public function init()
    {

        parent::init();

        $pollDB = new PollDb;
        $this->isExist = $pollDB->isTableExists();

        if (count($this->isExist) == 0) {
            $pollDB->createTables();
        }
        if ($this->answerOptions !== null) {
            $this->answerOptionsData = serialize($this->answerOptions);
        }

        // crate DB TBOs if they do not exist for this poll
        if (!$pollDB->isPollExist($this->pollName)) {
            $this->setDbData();
        }

        // check that all Poll answers exist
        $pollDB->pollAnswerOptions($this);

        if (\Yii::$app->request->isAjax) {
            if (isset($_POST['VoicesOfPoll'])) {
                if ($_POST['poll_name'] == $this->pollName && isset($_POST['VoicesOfPoll']['voice'])) {
                    $pollDB->updateAnswers(
                        $this->pollName,
                        $_POST['VoicesOfPoll']['voice'],
                        $this->answerOptions
                    );

                    $pollDB->updateUsers($this->pollName);
                }
            }
        }
        $this->getDbData();
        $this->answers = $pollDB->getVoicesData($this->pollName);

        $answerCount = count($this->answers);
        for ($i = 0; $i < $answerCount; $i++) {

            $this->sumOfVoices = $this->sumOfVoices + $this->answers[$i]['value'];
        }

        $this->isVote = $pollDB->isVote($this->pollName);
    }

    /**
     * @return string
     */
    public function run()
    {
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

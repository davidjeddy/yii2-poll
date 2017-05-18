<?php

namespace davidjeddy\yii2poll;

use yii;
use yii\base\Widget;
use \davidjeddy\yii2poll\models\PollResponse;

/**
 * Class Yii2Poll
 *
 * @package davidjeddy\yii2poll
 */
class Module extends Widget
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
    public $ajaxSuccess = [];

    /**
     * @param string $name
     *
     * @return string
     */
    public function setQuestionText(string $name) : string
    {
        $this->questionText = $name;
    }

    /**
     *
     */
    public function getDbData()
    {
        $db = Yii::$app->db;

        $command = $db->createCommand('SELECT * FROM poll_question WHERE question_text=:questionText')
            ->bindParam(':questionText', $this->questionText);

        $this->pollData = $command->queryOne();
        $this->answerOptionsData = unserialize($this->pollData['answer_options']);
    }

    /**
     * @return int
     */
    public function setDbData()
    {
        return Yii::$app->db->createCommand()->insert('poll_question', [
            'answer_options' => $this->answerOptionsData,
            'question_text'      => $this->questionText,
        ])->execute();
    }

    /**
     * @param array $params
     */
    public function setParams(array $params)
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

        $pollDB = new PollResponse;

        if ($this->answerOptions !== null) {
            $this->answerOptionsData = serialize($this->answerOptions);
        }

        // check that all Poll answers exist
        $pollDB->pollAnswerOptions($this);

        if (Yii::$app->request->isAjax) {
            if (!empty(\Yii::$app->request->post('VoicesOfPoll'))) {
                if (\Yii::$app->request->post('question_text') == $this->questionText
                    && !empty(\Yii::$app->request->post('VoicesOfPoll')['voice'])
                ) {
                    $pollDB->updateAnswers(
                        $this->questionText,
                        \Yii::$app->request->post('VoicesOfPoll')['voice'],
                        $this->answerOptions
                    );

                    $pollDB->updateUsers($this->questionText);
                }
            }
        }
        $this->getDbData();
        $this->answers = $pollDB->getVoicesData($this->questionText);

        for ($i = 0; $i < count($this->answers); $i++) {

            $this->sumOfVoices = $this->sumOfVoices + $this->answers[$i]['value'];
        }

        $this->isVote = $pollDB->isVote($this->questionText);
    }

    /**
     * @return string
     */
    public function run()
    {
        return $this->render('index', [
            'ajaxSuccess' => $this->ajaxSuccess,
            'answers'     => $this->answerOptions,
            'answersData' => $this->answers,
            'isVote'      => $this->isVote,
            'model'       => new \davidjeddy\yii2poll\models\VoicesOfPoll(),
            'params'      => $this->params,
            'pollData'    => $this->pollData,
            'sumOfVoices' => $this->sumOfVoices,
        ]);
    }
}

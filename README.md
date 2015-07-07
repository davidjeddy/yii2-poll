yii2-poll
=========

Poll widget for yii2

The Poll widget for the Yii2 framework allows you to create custom polls for authenticated users to vote on.

Installing 
==========

Add `"davidjeddy/yii2poll": "dev-master"` to your composer.json. Then run an update.

Usage 
=====

Basic:

        echo \davidjeddt\yii2poll\Poll::widget([

            'pollName'      => 'Do you like PHP?',

            'answerOptions' => ['Yes', 'No'],

        ]); 



Advanced:

        echo \davidjeddt\yii2poll\Poll::widget([

            'pollName'      => 'Do you like PHP?',

            'answerOptions' => ['Yes', 'No'],

            'params'        => [

                'backgroundLinesColor' => '#DCDCDC',// html hex 

                'linesColor'           => '#DC0079' // html hex 

                'linesHeight'          => 20,       // in pixels

                'maxLineWidth'         => 200,      // in pixels

            ]

        ]); 

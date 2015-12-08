yii2-poll
=========

Poll widget for Yii 2.x allows you to create basic custom polls for authenticated users to vote on.

Installing 
==========

- Run composer `require davidjeddy/yii2poll` on the terminal in your {project root}, OR add `"davidjeddy/davidjeddyyii2poll": "~2"` to your projects composer.json in the "required": [...] section then run `composer update`.
- Enbable the module in your apps config/web.config module list

Usage 
=====

Basic:
```PHP
    echo \davidjeddt\yii2poll\Poll::widget([
        'pollName'      => 'Do you like PHP?',
        'answerOptions' => ['Yes', 'No'],
    ]); 
```


Advanced:
```PHP
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
```

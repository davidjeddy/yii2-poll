yii2-poll
=========

## Badges
[![Latest Stable Version](https://poser.pugx.org/davidjeddy/yii2poll/v/stable)](https://packagist.org/packages/davidjeddy/yii2poll)
[![Total Downloads](https://poser.pugx.org/davidjeddy/yii2poll/downloads)](https://packagist.org/packages/davidjeddy/yii2poll)
[![Latest Unstable Version](https://poser.pugx.org/davidjeddy/yii2poll/v/unstable)](https://packagist.org/packages/davidjeddy/yii2poll)
[![License](https://poser.pugx.org/davidjeddy/yii2poll/license)](https://packagist.org/packages/davidjeddy/yii2poll)
[![Monthly Downloads](https://poser.pugx.org/davidjeddy/yii2poll/d/monthly)](https://packagist.org/packages/davidjeddy/yii2poll)
[![Daily Downloads](https://poser.pugx.org/davidjeddy/yii2poll/d/daily)](https://packagist.org/packages/davidjeddy/yii2poll)

## About
Create a basic custom polls for Yii 2.x.

Installing
==========

- Run composer `require davidjeddy/yii2-poll` on the terminal in your {project root}, OR add `"davidjeddy/yii2-poll": "~2"` to your projects composer.json in the "required": [...] section then run `composer update`.
- Run migration via Yii's migration command providing `php ./console/yii migrate/up --migrationPath=./vendor/davidjeddy/yii2-poll/migrations`

Usage
=====

Basic:
```PHP
    echo \davidjeddy\poll\PollWidget::widget([
        'pollName'      => \Yii::t('poll', 'Do you like PHP?'),
        'answerOptions' => ['Yes', 'No'],
    ]);
```


Advanced:
```PHP
    echo \davidjeddy\poll\PollWidget::widget([
        'pollName'      => \Yii::t('poll', 'Do you like PHP?'),
        'answerOptions' => ['Yes', 'No'],
        'params'        => [
            'backgroundLinesColor' => '#DCDCDC',// html hex
            'linesColor'           => '#DC0079' // html hex
            'linesHeight'          => 20,       // in pixels
            'maxLineWidth'         => 200,      // in pixels
        ]
    ]);
```

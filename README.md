[!WARNING]
**⚠️ This project has been archived and is no longer maintained. ⚠️**

Github has shown it does not respect its users. Other have said it better than I can.

- https://www.theregister.com/2022/06/30/software_freedom_conservancy_quits_github/
- https://www.andrlik.org/dispatches/migrating-from-github-motivation/
- https://techresolve.blog/2025/12/27/looking-to-migrate-company-off-github-whats-the/
- https://lord.io/leaving-github/
- https://dev.to/alanwest/how-to-actually-migrate-from-github-to-codeberg-without-losing-your-mind-33bf>
> Development has moved to Codeberg:
> **➡️ https://codeberg.org/DavidJEddy/yii2-poll**
>
> Please update your remotes:
> ```bash
> git remote set-url origin https://codeberg.org/DavidJEddy/yii2-poll
> ```

---
yii2-poll
=========

## Badges
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/e45f66cb-8718-4dee-9597-d40b745aeb29/mini.png)](https://insight.sensiolabs.com/projects/e45f66cb-8718-4dee-9597-d40b745aeb29)
[![Latest Stable Version](https://poser.pugx.org/davidjeddy/yii2-poll/v/stable)](https://packagist.org/packages/davidjeddy/yii2-poll)
[![Total Downloads](https://poser.pugx.org/davidjeddy/yii2-poll/downloads)](https://packagist.org/packages/davidjeddy/yii2-poll)
[![Latest Unstable Version](https://poser.pugx.org/davidjeddy/yii2-poll/v/unstable)](https://packagist.org/packages/davidjeddy/yii2-poll)
[![License](https://poser.pugx.org/davidjeddy/yii2-poll/license)](https://packagist.org/packages/davidjeddy/yii2-poll)
[![Monthly Downloads](https://poser.pugx.org/davidjeddy/yii2-poll/d/monthly)](https://packagist.org/packages/davidjeddy/yii2-poll)
[![Daily Downloads](https://poser.pugx.org/davidjeddy/yii2-poll/d/daily)](https://packagist.org/packages/davidjeddy/yii2-poll)

## About
Create a basic custom polls for Yii 2.x.

Installing
==========

- Run `composer require davidjeddy/yii2-poll` on the terminal in your {project root}
  - OR add `"davidjeddy/yii2-poll": "~2"` to your projects composer.json in the "required": [...] section then run `composer update`.
- Run migration via Yii's migration command providing `php ./console/yii migrate/up --migrationPath=./vendor/davidjeddy/yii2-poll/migrations`

Usage
=====

Basic:
```PHP
    echo \davidjeddy\poll\PollWidget::widget([
        'questionText'  => \Yii::t('poll', 'Do you like PHP?'),
        'answerOptions' => ['Yes', 'No'],
    ]);
```


Advanced:
```PHP
    echo \davidjeddy\poll\PollWidget::widget([
        'questionText'  => \Yii::t('poll', 'Do you like PHP?'),
        'answerOptions' => ['Yes', 'No'],
        'params'        => [
            'backgroundLinesColor' => '#DCDCDC',// html hex
            'linesColor'           => '#DC0079' // html hex
            'linesHeight'          => 20,       // in pixels
            'maxLineWidth'         => 200,      // in pixels
        ]
    ]);
```
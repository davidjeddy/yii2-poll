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
# yii2-poll
Poll widget for Yii 2.x create basic custom poll(s) for authenticated users to vote on.

# Badges
[![Latest Stable Version](https://poser.pugx.org/davidjeddy/yii2-poll/v/stable?format=flat-square)](https://packagist.org/packages/davidjeddy/yii2-poll)
[![Total Downloads](https://poser.pugx.org/davidjeddy/yii2-poll/downloads)](https://packagist.org/packages/davidjeddy/yii2-poll)
[![Latest Unstable Version](https://poser.pugx.org/davidjeddy/yii2-poll/v/unstable?format=flat-square)](https://packagist.org/packages/davidjeddy/yii2-poll)
[![License](https://poser.pugx.org/davidjeddy/yii2-poll/license?format=flat-square)](https://packagist.org/packages/davidjeddy/yii2-poll)
[![Monthly Downloads](https://poser.pugx.org/davidjeddy/yii2-poll/d/monthly?format=flat-square)](https://packagist.org/packages/davidjeddy/yii2-poll)
[![Daily Downloads](https://poser.pugx.org/davidjeddy/yii2-poll/d/daily?format=flat-square)](https://packagist.org/packages/davidjeddy/yii2-poll)
[![composer.lock](https://poser.pugx.org/davidjeddy/yii2-poll/composerlock?format=flat-square)](https://packagist.org/packages/davidjeddy/yii2-poll)

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/0137c455-b0f7-482b-982e-182521bc2a11/big.png)](https://insight.sensiolabs.com/projects/0137c455-b0f7-482b-982e-182521bc2a11)

# REQUIREMENTS
PHP 7+

MySQL 5.5+

Composer

Yii 2+ (advanced tempplate recommended)

# Installing
- Run composer `require davidjeddy/yii2poll` on the terminal in your {project root},
    - OR add `"davidjeddy/davidjeddyyii2poll": "~2"` to your projects composer.json in the "required": [...] section, then run `composer update`.
- Run migration `php ./console/yii migrate/up --migrationPath=./vendor/davidjeddy/yii2-poll/migration/`

# Usage
Add to your applications configuration in the module section:

```PHP
return [
    ...
    'modules' => [
        ...
        'poll' => [
            'class' => davidjeddy\yii2poll\Module::class,
        ],
        ...
    ],
];

```

Add the poll to a view file:

Basic:
```PHP
    echo \davidjeddy\yii2poll\Poll::widget([
        'questionText'  => 'Do you like PHP?',
        'answerOptions' => ['Yes', 'No'],
    ]);
```

Advanced:
```PHP
    echo \davidjeddy\yii2poll\Poll::widget([
        'questionText'      => 'Do you like PHP?',
        'answerOptions' => ['Yes', 'No'],
        'params'        => [
            'backgroundLinesColor' => '#DCDCDC',// html hex
            'linesColor'           => '#DC0079' // html hex
            'linesHeight'          => 20,       // in pixels
            'maxLineWidth'         => 200,      // in pixels
        ]
    ]);
```
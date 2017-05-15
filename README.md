# yii2-poll
Poll widget for Yii 2.x create basic custom poll(s) for authenticated users to vote on.

# Installing
- Run composer `require davidjeddy/yii2poll` on the terminal in your {project root},
    - OR add `"davidjeddy/davidjeddyyii2poll": "~2"` to your projects composer.json in the "required": [...] section, then run `composer update`.
- Run migration `php ./console/yii migrate/up --migrationPath=./vendor/davidjeddy/yii2-poll/migration/`

# Usage
Add to your applications configuration in the module section:

```PHP
    [
        'label'     => Yii::t('backend', 'Free Radius'),
        'icon'      => '<i class="fa fa-id-card-o"></i>',
        'url'       => ['/free-radius/default/index'],
        'visible'   => Yii::$app->user->can('administrator')
    ],
```

Add the poll to a view file:

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

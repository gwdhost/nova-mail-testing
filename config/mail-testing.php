<?php

return [
    'mails' => [
        [
            'label' => 'Welcome mail',
            'class' => \App\Mail\WelcomeMail::class,
            'args' => [
                \App\Nova\User::class,
                [
                    'type' => 'text',
                    'label' => 'Textfield',
                    'placeholder' => 'Placeholder'
                ],
                [
                    'type' => 'textarea',
                    'label' => 'Textarea',
                    'placeholder' => 'Placeholder'
                ],
                [
                    'type' => 'select',
                    'label' => 'Select',
                    'options' => [
                        'a' => 'Answer A',
                        'b' => 'Answer B',
                        'c' => 'Answer C',
                    ]
                ],
            ]
        ],
    ]
];

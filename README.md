# Mail testing for Laravel Nova

This tool will give you an view to show mail templates with correct data, which you can setup.
The only thing you need to do is to setup the config file after that every thing will take care of it self.

## To install you can run the following:

```
composer require gwdhost/nova-mail-testing
```

And then add the following to the `tools()` method array in the `NovaServiceProvider`:

```php
return [
     new \Gwdhost\MailTesting\MailTesting()
];
```

Here you can see an example of the mail testing config file.

```php
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
```

You can use Nova resources or custom fields like `text`, `textarea` or `select`. These fields will add as arguments to your mails.

So if you need the following for an Welcome mail, as example:

```php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    ...
}
```

Then you need a `App\Models\User` instance to generate this email. Therefor you would add the following config file:

```php
return [
    'mails' => [
        [
            'label' => 'Welcome mail',
            'class' => \App\Mail\WelcomeMail::class,
            'args' => [
                \App\Nova\User::class,
            ]
        ],
    ]
];
```

And the you are able to send test mails and show previews directly in the UI.

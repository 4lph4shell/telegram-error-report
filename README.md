# Telegram-error-report

Is a simple implementation of the Telegram bot to keep developers updated about applications´ errors.

This is a code-level tool, which means that is useful mainly for developers.

With this implementation, the developer can receive exceptions, error 500, brute-force attempts, user cheating behavior, login failed attempts, etc. Use your imagination.

## Installing

Type `composer require andrebian/telegram-error-report` or download the [zip](https://github.com/4lph4shell/telegram-error-report/archive/refs/heads/main.zip).
or 
```
git clone https://github.com/4lph4shell/telegram-error-report.git
```

## Tests

Copy file `config/config.php.dist` to `config/config.php`, set your bot and channel configuration and run `./vendor/bin/phpunit`

## Using

```php
<?php

use Andrebian\TelegramErrorReport\TelegramErrorReport;

require __DIR__ . '/vendor/autoload.php';

// initializing
$telegramErrorReport = new TelegramErrorReport('YOUR_CHANNEL_ID_HERE', 'YOUR_BOT_ID_HERE');

// sending info message
$telegramErrorReport->sendInfoMessage('Your message');

// sending error message
$telegramErrorReport->sendErrorMessage('Your message');

// sending debug message
$telegramErrorReport->sendDebugMessage('Your message');


// Using with try/catch
try {
    // your stuff here
} catch (Exception $exception) {
    $message = $exception->getMessage();
    $message .= TelegramErrorReport::LINE_BREAK . $exception->getFile();
    $message .= ':' . $exception->getLine();
    $message .= TelegramErrorReport::LINE_BREAK . $exception->getTraceAsString();
    
    $telegramErrorReport->sendErrorMessage($message);
}

```

## Contributing

You can open [issues](https://github.com/4lph4shell/telegram-error-report/issues) or fork this repository and make [PR](https://github.com/4lph4shell/telegram-error-report/pulls).



 

<?php declare(strict_types=1);

namespace Test\Andrebian\TelegramErrorReport;

use Andrebian\TelegramErrorReport\TelegramErrorReport;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * Class TelegramErrorReportTest
 * @package Andrebian\TelegramErrorReport
 */
class TelegramErrorReportTest extends TestCase
{
    /**
     * @var TelegramErrorReport|null
     */
    private $telegramErrorReport = null;

    protected function setUp(): void
    {
        parent::setUp();

        if (! is_file(__DIR__ . '/../config/config.php')) {
            throw new RuntimeException('You must copy config/config.php.dist to config/config.php and set your bot and channel info');
        }

        $config = require __DIR__ . '/../config/config.php';

        $this->telegramErrorReport = new TelegramErrorReport($config['channel_id'], $config['bot']);
    }

    public function testSendErrorMessage()
    {
        $message = 'An error has occurred';

        $result = $this->telegramErrorReport->sendErrorMessage($message);

        $this->assertTrue($result);
    }

    public function testSendInfoMessage()
    {
        $message = 'This is an info';

        $result = $this->telegramErrorReport->sendInfoMessage($message);

        $this->assertTrue($result);
    }

    public function testSendDebugMessage()
    {
        $message = 'I am at file: ' . __FILE__ . ':' . __LINE__;

        $result = $this->telegramErrorReport->sendDebugMessage($message);

        $this->assertTrue($result);
    }

    public function testException()
    {
        $message = 'I am at file: ' . __FILE__ . ':' . __LINE__;

        $telegramErrorReport = new TelegramErrorReport('xpto', 'xpt');

        $result = $telegramErrorReport->sendErrorMessage($message);

        $this->assertFalse($result);
    }
}

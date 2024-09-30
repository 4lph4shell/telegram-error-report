<?php declare(strict_types=1);

namespace Andrebian\TelegramErrorReport;

use Exception;

/**
 * Class TelegramErrorReport
 * @package Andrebian\TelegramErrorReport
 */
class TelegramErrorReport
{
    const API_URL = "https://api.telegram.org/bot";
    const LINE_BREAK = "\r\n";
    const MAX_ALLOWED_CHARS = 4096;
    const LINE_BREAK_REPLACEMENTS = ["<br>", "<br/>", "<br />"];
    const PARSE_MODES = ["html", "markdown", "markdownv2"];

    /**
     * @var string
     */
    private $channelId = "";

    /**
     * @var string
     */
    private $bot = "";

    /**
     * @var string
     */
    private $parseMode = "html";

    /**
     * TelegramErrorReport constructor.
     * @param string $channelId
     * @param string $bot
     * @param string|null $parseMode
     */
    public function __construct(
        string $channelId,
        string $bot,
        $parseMode = null
    ) {
        $this->channelId = $channelId;
        $this->bot = $bot;
        $this->parseMode = ! is_null($parseMode) ? $parseMode : 'html';
    }

    /**
     * @param string $message
     * @return bool
     */
    public function sendErrorMessage(string $message): bool
    {
        $message = 'Error' . self::LINE_BREAK . self::LINE_BREAK . $message;
        return $this->sendMessage($message);
    }

    /**
     * @param string $message
     * @return bool
     */
    public function sendInfoMessage(string $message): bool
    {
        $message = 'Info' . self::LINE_BREAK . self::LINE_BREAK . $message;
        return $this->sendMessage($message);
    }

    /**
     * @param string $message
     * @return bool
     */
    public function sendDebugMessage(string $message): bool
    {
        $message = 'Debug' . self::LINE_BREAK . self::LINE_BREAK . $message;

        $message .= self::LINE_BREAK . self::LINE_BREAK;
        $message .= 'GET:' . self::LINE_BREAK . print_r($_GET, true) . self::LINE_BREAK . self::LINE_BREAK;
        $message .= 'POST:' . self::LINE_BREAK . print_r($_POST, true) . self::LINE_BREAK . self::LINE_BREAK;
        $message .= 'SERVER:' . self::LINE_BREAK . print_r($_SERVER, true) . self::LINE_BREAK . self::LINE_BREAK;

        return $this->sendMessage($message);
    }

    /**
     * @param string $message
     * @return bool
     */
    private function sendMessage(string $message): bool
    {
        $endpoint = self::API_URL . $this->bot . '/sendMessage';

        $data = [
            'chat_id' => $this->channelId,
            'text' => substr(
                htmlentities(
                    str_replace(
                        self::LINE_BREAK_REPLACEMENTS,
                        self::LINE_BREAK,
                        $message
                    )
                ),
                0,
                self::MAX_ALLOWED_CHARS
            ),
            'parse_mode' => $this->parseMode
        ];

        try {
            $status = true;

            $ch = curl_init($endpoint);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

            $result = curl_exec($ch);
            $error = curl_error($ch);
            curl_close($ch);

            $response = json_decode($result, true);

            if ($error || json_last_error() != JSON_ERROR_NONE || $response['ok'] === false) {
                $status = false;
            }

            return $status;
        } catch (Exception $exception) {
            return false;
        }
    }
}

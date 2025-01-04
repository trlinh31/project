<?php


namespace App\Logging;


use Monolog\Formatter\LineFormatter;
use Monolog\Formatter\NormalizerFormatter;

class CustomizeLineFormatter extends LineFormatter
{
    /**
     * @param string $format                     The format of the message
     * @param string $dateFormat                 The format of the timestamp: one supported by DateTime::format
     * @param bool   $allowInlineLineBreaks      Whether to allow inline line breaks in log entries
     * @param bool   $ignoreEmptyContextAndExtra
     */
    public function __construct($format = null, $dateFormat = null,
                                $allowInlineLineBreaks = false,
                                $ignoreEmptyContextAndExtra = false)
    {
        parent::__construct($format, $dateFormat, $allowInlineLineBreaks, $ignoreEmptyContextAndExtra);
    }

    /**
     * {@inheritdoc}
     */
    public function format($record): string
    {
        $vars = NormalizerFormatter::format($record);
        $output = array_merge([
            'timestamp' => $vars['datetime'],
            'level' => $vars['level_name'],
            'message' => $vars['message']
        ], $vars['context']);
        return json_encode($output, JSON_UNESCAPED_UNICODE) . "\n";
    }
}

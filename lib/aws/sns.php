<?php
declare(strict_types=1);

/**
 * created by Ali Masood<ali@jsys.pk>
 * Date: 2019-07-30 20:31
 */

namespace J\Cloud\aws;

use Aws\Result;
use Aws\Sns\SnsClient;

class SNS
{
    private $sns;

    public function __construct(SnsClient $sns)
    {
        $this->sns = $sns;
    }

    public function publish(string $recipient, string $message, string $subject, string $msgType): Result
    {
        $result = $this->sns->publish([
            'Message' => $message,
            'MessageStructure' => $msgType,
            'Subject' => $subject,
            'TopicArn' => $recipient
        ]);
        return $result;
    }
}

<?php
declare(strict_types=1);

/**
 * created by Ali Masood<ali@jsys.pk>
 * Date: 2019-07-30 20:28
 */

namespace J\Cloud\aws;

class SNSFactory
{
    private $sns;

    public function __construct(string $key, string $secret, string $scheme = 'https', string $apiVersion = 'latest', string $region = 'ap-northeast-1', bool $debug = false)
    {
        $this->client = new S3Client([
            'signature_version' => $signatureVersion, // needed to enable download files
            'version'     => $apiVersion,
            'region'      => $region,
            'credentials' => [
                'key'    => $key,
                'secret' => $secret
            ],
            'scheme' => $scheme,
            'debug' => $debug
        ]);
        $this->sns->registerStreamWrapper();
    }

    public function gateway()
    {
        return new SNS($this->sns);
    }

}

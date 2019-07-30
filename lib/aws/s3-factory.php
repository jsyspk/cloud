<?php
declare(strict_types=1);

/**
 * created by Ali Masood<ali@jsys.pk>
 * Date: 2019-07-30 20:09
 */

namespace J\Cloud\AWS;

use Aws\S3\S3Client;
use J\Cloud\Storage;
use J\Cloud\StorageFactory;

class S3Factory implements StorageFactory
{
    private $client;

    public function __construct(string $key, string $secret, string $signatureVersion = 'v4', string $apiVersion = 'latest', string $region = 'ap-northeast-1')
    {
        $this->client = new S3Client([
            'signature_version' => $signatureVersion, // needed to enable download files
            'version'     => $apiVersion,
            'region'      => $region,
            'credentials' => [
                'key'    => $key,
                'secret' => $secret
            ]
        ]);
        $this->client->registerStreamWrapper();
    }

    public function gateway(): Storage
    {
        return new S3($this->client);
    }
}

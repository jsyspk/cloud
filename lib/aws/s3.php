<?php
declare(strict_types=1);

/**
 * created by Ali Masood<ali@jsys.pk>
 * Date: 2019-07-30 19:37
 */

namespace J\Cloud\AWS;

use Aws\S3\S3Client;
use J\Cloud\Storage;

class S3 implements Storage
{
    private $client;

    public function __construct(S3Client $client)
    {
        $this->client = $client;
    }

    public function getBucketsList(): array {
        return $this->client->listBuckets([]);
    }

    public function putFile(string $sourceFile, string $bucketName, string $targetFile, array $metaData): bool {
        $result = $this->client->putObject([
            'Bucket'     => $bucketName,
            'Key'        => $targetFile,
            'SourceFile' => $sourceFile,
            'Metadata'   => $metaData
        ]);

        // We can poll the object until it is accessible
        $this->client->waitUntil('ObjectExists', [
            'Bucket' => $bucketName,
            'Key'    => $targetFile
        ]);
        return $result;
    }


    public function getFileHandle(string $bucketName, string $fileName)
    {
        $url = "s3://{$bucketName}/{$fileName}";
        $fileHandle = new \SplFileObject($url, 'r');
        return $fileHandle;
    }

    public function getFile(string $bucketName, string $fileName, string $target)
    {
        $result = $this->client->getObject([
            'Bucket' => $bucketName,
            'Key'    => $fileName,
            'SaveAs' => $target
        ]);
        return $result;
    }

    public function getHeaders(string $bucketName, string $fileName)
    {
        $headers = $this->client->headObject(array(
            "Bucket" => $bucketName,
            "Key" => $fileName
        ));
        return $headers;
    }

    public function getBucketItemsList(string $bucketName):array
    {
        $this->checkConnection($bucketName);
        $iterator = $this->client->getIterator('ListObjects',
            [
                'Bucket' => $bucketName
            ]
        );
        $items = [];
        foreach ($iterator as $item) {
            $items[] = $item;
        }
        return $items;
    }

    private function checkConnection(string $bucketName){
        try {
            $result = $this->client->listObjects(['Bucket' => $bucketName]);
        }catch (S3Exception $e) {
            // Catch an S3 specific exception.
            echo $e->getMessage() . PHP_EOL;
        } catch (AwsException $e) {
            // This catches the more generic AwsException. You can grab information
            // from the exception using methods of the exception object.
            echo $e->getAwsRequestId() . PHP_EOL;
            echo $e->getAwsErrorType() . PHP_EOL;
            echo $e->getAwsErrorCode() . PHP_EOL;
        }
    }

    public function getUrl(string $bucketName, string $fileName):string
    {
        return $this->client->getObjectUrl($bucketName, $fileName);
    }

    public function getSignedUrl(string $bucketName, string $fileName): string {

        $cmd = $this->client->getCommand('GetObject', [
            'Bucket' => $bucketName,
            'Key'    => $fileName
        ]);
        $request = $this->client->createPresignedRequest($cmd, '+20 minutes');

        // Get the actual pre-signed url
        $url = (string) $request->getUri();
        return $url;
    }


    public function deleteBucketItem(string $bucketName, string $fileName): bool {
        $result = $this->client->deleteObject([
            "Bucket" => $bucketName,
            "Key" => $fileName
        ]);
        return $result;
    }

    public function uploadDirectory(string $directory, string $bucket, string $keyPrefix = null, array $options = [] )
    {
        $this->client->uploadDirectory(
            $directory,
            $bucket,
            $keyPrefix,
            $options);
    }
}

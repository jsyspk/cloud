<?php
declare(strict_types=1);

/**
 * created by Ali Masood<ali@jsys.pk>
 * Date: 2019-07-30 19:36
 */

namespace J\Cloud;

interface Storage
{

    public function getBucketsList(): array ;

    public function putFile(string $sourceFile, string $bucketName, string $targetFile, array $metaData): bool ;

    public function getFileHandle(string $bucketName, string $fileName);

    public function getFile(string $bucketName, string $fileName, string $target);

    public function getHeaders(string $bucketName, string $fileName);

    public function getBucketItemsList(string $bucketName):array;

    public function getUrl(string $bucketName, string $fileName):string ;

    public function getSignedUrl(string $bucketName, string $fileName): string ;

    public function deleteBucketItem(string $bucketName, string $fileName): bool ;

    public function uploadDirectory(string $directory, string $bucket, string $keyPrefix = null, array $options = [] );
}

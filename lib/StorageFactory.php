<?php
declare(strict_types=1);

/**
 * created by Ali Masood<ali@jsys.pk>
 * Date: 2019-07-30 20:17
 */

namespace J\Cloud;

interface StorageFactory
{
    public function gateway(): Storage;
}

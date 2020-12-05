<?php

namespace App;

use DirectoryIterator;
use TeraBlaze\Core\Kernel\Kernel;
use TeraBlaze\Core\Kernel\KernelInterface;

class AppKernel extends Kernel implements KernelInterface
{
    public function boot(): void
    {
        parent::boot();
        $path = $this->getProjectDir() . "/src/Plugins";
        $iterator = new DirectoryIterator($path);

        foreach ($iterator as $item) {
            if (!$item->isDot() && $item->isDir()) {
                if (file_exists($path . '/' . $item->getFilename() . '/initialize.php')) {
                    include($path . '/' . $item->getFilename() . '/initialize.php');
                }
            }
        }
    }
}
<?php

namespace App;

use Nelmio\ApiDocBundle\NelmioApiDocBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    // public function registerBundles(): iterable
    // {
    //     $bundles = [
    //         new NelmioApiDocBundle(),
    //     ];

    //     return $bundles;
    // }
}

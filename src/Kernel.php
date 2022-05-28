<?php

namespace App;

use App\Infrastructure\CompilerPass\BuildingMetadataCompilerPass;
use App\Infrastructure\CompilerPass\ResourceMetadataCompilerPass;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    protected function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new BuildingMetadataCompilerPass());
        $container->addCompilerPass(new ResourceMetadataCompilerPass());
    }
}

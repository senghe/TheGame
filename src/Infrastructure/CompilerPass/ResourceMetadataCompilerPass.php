<?php

declare(strict_types=1);

namespace App\Infrastructure\CompilerPass;

use App\Component\Resource\Domain\Factory\SnapshotFactoryInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class ResourceMetadataCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if ($container->has(SnapshotFactoryInterface::class) === false) {
            return;
        }

        $definition = $container->findDefinition(SnapshotFactoryInterface::class);
        $taggedServices = $container->findTaggedServiceIds('resource.metadata');

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall('addResourceMetadata', [new Reference($id)]);
        }
    }
}
<?php

declare(strict_types=1);

namespace App\Infrastructure\CompilerPass;

use App\Component\Building\Domain\Service\BuildingMetadataResolverInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class BuildingMetadataCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if ($container->has(BuildingMetadataResolverInterface::class) === false) {
            return;
        }

        $definition = $container->findDefinition(BuildingMetadataResolverInterface::class);
        $taggedServices = $container->findTaggedServiceIds('building.metadata');

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall('addBuildingMetadata', [new Reference($id)]);
        }
    }
}
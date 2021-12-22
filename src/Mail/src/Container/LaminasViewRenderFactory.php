<?php

declare(strict_types=1);

namespace Mail\Container;

use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver;
use Mezzio\LaminasView\LaminasViewRenderer;
use Psr\Container\ContainerInterface;

class LaminasViewRenderFactory
{
    public function __invoke(ContainerInterface $container): LaminasViewRenderer
    {
        $renderer = new PhpRenderer();

        $resolver = new Resolver\AggregateResolver();
        $resolver->attach(
            (new Resolver\TemplatePathStack())->setPaths([__DIR__ . '/../templates'])
        );
        $renderer->setResolver($resolver);

        return new LaminasViewRenderer($renderer);
    }
}

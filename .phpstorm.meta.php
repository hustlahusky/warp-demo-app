<?php
// @formatter:off

namespace PHPSTORM_META {

    use Psr\Container\ContainerInterface;
    use Spiral\Core\FactoryInterface;

    override(ContainerInterface::get(0), map([
        '' => '@',
    ]));
    override(FactoryInterface::make(0), map([
        '' => '@',
    ]));
}

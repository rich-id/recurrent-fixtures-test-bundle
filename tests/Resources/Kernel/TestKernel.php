<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\Tests\Resources\Kernel;

use RichCongress\WebTestBundle\Kernel\DefaultTestKernel;

class TestKernel extends DefaultTestKernel
{
    public function __construct()
    {
        parent::__construct('test', false);
    }

    public function getProjectDir(): string
    {
        return __DIR__ . '/../../..';
    }

    public function getConfigurationDir(): ?string
    {
        return __DIR__ . '/config';
    }
}

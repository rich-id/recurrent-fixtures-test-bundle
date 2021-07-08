<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle;

use RichCongress\BundleToolbox\Configuration\AbstractBundle;
use RichCongress\RecurrentFixturesTestBundle\DependencyInjection\Compiler\DataFixturesPass;
use RichCongress\RecurrentFixturesTestBundle\DependencyInjection\Compiler\TestAuthenticatorCompilerPass;

/**
 * Class RichCongressRecurrentFixturesTestBundle
 *
 * @package   RichCongress\RecurrentFixturesTestBundle
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
class RichCongressRecurrentFixturesTestBundle extends AbstractBundle
{
    public const COMPILER_PASSES = [DataFixturesPass::class, TestAuthenticatorCompilerPass::class];
}

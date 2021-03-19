<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\Doctrine;

use RichCongress\RecurrentFixturesTestBundle\TestCase\Internal\FixtureTestCase;

/**
 * Class TestConnectionFactory
 *
 * @package    RichCongress\RecurrentFixturesTestBundle\Doctrine
 * @author     Nicolas Guilloux <nicolas.guilloux@rich-id.fr>
 * @copyright  2014 - 2021 RichID (https://www.rich-id.fr)
 */
class TestConnectionFactory extends \RichCongress\WebTestBundle\Doctrine\TestConnectionFactory
{
    protected function processParameters(array $params): array
    {
        $params['dbname'] = $params['dbname'] ?? 'dbTest';

        if (!FixtureTestCase::isEnabled()) {
            $params['dbname'] .= '_empty';
        }

        return parent::processParameters($params);
    }
}

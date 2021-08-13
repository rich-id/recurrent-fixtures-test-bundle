<?php declare(strict_types=1);

return [
    Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle::class                            => ['all' => true],
    RichCongress\FixtureTestBundle\Bridge\Symfony\RichCongressFixtureTestBundle::class      => ['all' => true],
    RichCongress\WebTestBundle\RichCongressWebTestBundle::class                             => ['all' => true],
    RichCongress\RecurrentFixturesTestBundle\RichCongressRecurrentFixturesTestBundle::class => ['all' => true],
];

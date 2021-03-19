<?php

namespace PHPSTORM_META;

use RichCongress\RecurrentFixturesTestBundle\Manager\FixtureManagerInterface;
use RichCongress\RecurrentFixturesTestBundle\TestCase\TestCase;

override(
    FixtureManagerInterface::getReference(0, 1),
    type(0)
);

override(
    TestCase::getReference(0, 1),
    type(0)
);

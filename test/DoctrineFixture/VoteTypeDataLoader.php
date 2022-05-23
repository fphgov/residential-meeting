<?php

declare(strict_types=1);

namespace DoctrineFixture;

use App\Entity\VoteType;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;

class VoteTypeDataLoader extends AbstractFixture implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $voteType = new VoteType();
        $voteType->setId(1);
        $voteType->setName('Kategóriánként egy');

        $this->addReference('vote-type-1', $voteType);

        $manager->persist($voteType);

        $voteType = new VoteType();
        $voteType->setId(2);
        $voteType->setName('Kategóriánként kicsi és nagy');

        $this->addReference('vote-type-2', $voteType);

        $manager->persist($voteType);

        $manager->flush();
    }
}

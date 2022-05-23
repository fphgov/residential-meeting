<?php

declare(strict_types=1);

namespace DoctrineFixture;

use App\Entity\Vote;
use DateTime;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;

class VoteDataLoader extends AbstractFixture implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $date = new DateTime();

        $vote = new Vote();
        $vote->setUser(
            $this->getReference('user-active-1')
        );
        $vote->setVoteType(
            $this->getReference('vote-type-2')
        );
        $vote->setProject(
            $this->getReference('project-active-1')
        );
        $vote->setCreatedAt($date);
        $vote->setUpdatedAt($date);

        $manager->persist($vote);
        $manager->flush();

        $this->addReference('vote-active-1', $vote);
    }
}

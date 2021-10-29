<?php

declare(strict_types=1);

namespace DoctrineFixture;

use App\Entity\Tag;
use DateTime;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TagDataLoader extends AbstractFixture implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $tag = new Tag();
        $tag->setName('sport');
        $tag->setActive(true);
        $tag->setCreatedAt(new DateTime());
        $tag->setUpdatedAt(new DateTime());

        $manager->persist($tag);
        $manager->flush();

        $this->addReference('tag-active-1', $tag);
    }
}

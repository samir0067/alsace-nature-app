<?php


namespace App\DataFixtures;

use App\Entity\InterventionArea;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class InterventionAreaFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $intervArea = new InterventionArea();
        $intervArea->setInterventionArea(8);
        $manager->persist($intervArea);
        $intervArea = new InterventionArea();
        $intervArea->setInterventionArea(10);
        $manager->persist($intervArea);
        $intervArea = new InterventionArea();
        $intervArea->setInterventionArea(51);
        $manager->persist($intervArea);
        $intervArea = new InterventionArea();
        $intervArea->setInterventionArea(52);
        $manager->persist($intervArea);
        $intervArea = new InterventionArea();
        $intervArea->setInterventionArea(54);
        $manager->persist($intervArea);
        $intervArea = new InterventionArea();
        $intervArea->setInterventionArea(55);
        $manager->persist($intervArea);
        $intervArea = new InterventionArea();
        $intervArea->setInterventionArea(57);
        $manager->persist($intervArea);
        $intervArea = new InterventionArea();
        $intervArea->setInterventionArea(67);
        $manager->persist($intervArea);
        $intervArea = new InterventionArea();
        $intervArea->setInterventionArea(68);
        $manager->persist($intervArea);
        $intervArea = new InterventionArea();
        $intervArea->setInterventionArea(88);
        $manager->persist($intervArea);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['group1'];
    }
}

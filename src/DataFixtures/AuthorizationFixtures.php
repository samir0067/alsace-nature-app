<?php

namespace App\DataFixtures;

use App\Entity\Authorization;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class AuthorizationFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {


        $authori = new Authorization();
        $authori->setName('Intervenant');
        $authori->setDescription('Un intervenant peut créer des événements lié à la structure');
        $manager->persist($authori);

        $authori = new Authorization();
        $authori->setName('admin Structure');
        $authori->setDescription('Un admin structure peut determiner qui peut devenir intervenant 
        dans la structure');
        $manager->persist($authori);

        $authori = new Authorization();
        $authori->setName('admin Territoire');
        $authori->setDescription('Un admin territoire peut determiner qui peut devenir administrateur 
        d\'une structure');
        $manager->persist($authori);

        $authori = new Authorization();
        $authori->setName('SuperAdmin');
        $authori->setDescription('Un SuperAdmin peut opérer toutes sortes de manipulation sur le site');
        $manager->persist($authori);


        $manager->flush();
    }
}

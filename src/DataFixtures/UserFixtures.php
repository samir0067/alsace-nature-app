<?php
namespace App\DataFixtures;

use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('us_US');
        //user de base
        $participant = new Users();
        $participant->setFirstName($faker->firstName)
            ->setLastName($faker->lastName)
            ->setMail('user@gmail.com')
            ->setPhone($faker->randomNumber(8))
            ->setCity($faker->city)
            ->setZipCode($faker->randomNumber(5))
            ->setAddress($faker->address)
            ->setPassword($this->passwordEncoder->encodePassword($participant, 'userpassword'))
            ->setRoles(['ROLE_USER']);

        $manager->persist($participant);
        //intervenant
        $intervenant = new Users();
        $intervenant->setFirstName($faker->firstName)
            ->setLastName($faker->lastName)
            ->setMail('event@gmail.com')
            ->setPhone($faker->randomNumber(8))
            ->setCity($faker->city)
            ->setZipCode($faker->randomNumber(5))
            ->setAddress($faker->address)
            ->setPassword($this->passwordEncoder->encodePassword($intervenant, 'eventpassword'))
            ->setRoles(['ROLE_EVENT']);

        $manager->persist($intervenant);

        //admin structure
        $aStructure = new Users();
        $aStructure->setFirstName($faker->firstName)
            ->setLastName($faker->lastName)
            ->setMail('structure@gmail.com')
            ->setPhone($faker->randomNumber(8))
            ->setCity($faker->city)
            ->setZipCode($faker->randomNumber(5))
            ->setAddress($faker->address)
            ->setPassword($this->passwordEncoder->encodePassword($aStructure, 'structurepassword'))
            ->setRoles(['ROLE_ORGA']);

        $manager->persist($aStructure);

        //admin territoire
        $territory = new Users();
        $territory->setFirstName($faker->firstName)
            ->setLastName($faker->lastName)
            ->setMail('territory@gmail.com')
            ->setPhone($faker->randomNumber(8))
            ->setCity($faker->city)
            ->setZipCode($faker->randomNumber(5))
            ->setAddress($faker->address)
            ->setPassword($this->passwordEncoder->encodePassword($territory, 'territorypassword'))
            ->setRoles(['ROLE_TERRITORY']);

        $manager->persist($territory);

        //super admin
        $admin = new Users();
        $admin->setFirstName($faker->firstName)
            ->setLastName($faker->lastName)
            ->setMail('admin@gmail.com')
            ->setPhone($faker->randomNumber(8))
            ->setCity($faker->city)
            ->setZipCode($faker->randomNumber(5))
            ->setAddress($faker->address)
            ->setPassword($this->passwordEncoder->encodePassword($admin, 'adminpassword'))
            ->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);

        // Sauvegarde des nouveaux utilisateurs :
        $manager->flush();
    }
}

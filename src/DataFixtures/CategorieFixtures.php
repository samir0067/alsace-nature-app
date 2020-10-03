<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Evenement;
use App\Entity\Report;
use App\Entity\Structure;
use App\Entity\Theme;
use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

/**
 * Class CategorieFixtures
 * @package App\DataFixtures
 * @SuppressWarnings(PHPMD)
 */
class CategorieFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('us_US');

        for ($i = 0; $i <= 10; $i++) {
            $catArray = ['Visite', 'Cine', 'Conf', 'Chantier', 'Exposition',
                'Info', 'Formation', 'Atelier', 'Manif', 'Loisir', 'Salon'];
            $catDisplayArray = ['Visite guidée - Balade contée', 'Ciné-débat', 'Conférence - Table ronde',
                'Chantier participatif', 'Éxposition', 'Stand d’information', 'Formation', 'Atelier',
                'Manifestation - Mobilisation', 'Accueil de loisir', 'Salon'];
            $categ = new Category();
            $categ->setDisplayName($catDisplayArray[$i]);
            $categ->setName($catArray[$i]);

            $manager->persist($categ);
            $this->addReference('categorie_' . $i, $categ);
        }

        for ($i = 0; $i < 33; $i++) {
            $user = new Users();
            $user->setFirstName($faker->firstName)
                ->setLastName($faker->lastName)
                ->setMail($faker->email)
                ->setPhone($faker->randomNumber(8))
                ->setCity($faker->city)
                ->setZipCode($faker->randomNumber(5))
                ->setAddress($faker->address)
                ->setPassword($faker->password);
//                ->setAuthorization($this->getReference('authori_' . rand(1, 5)));

            $manager->persist($user);
            $this->addReference('user_' . $i, $user);
        }
        for ($i = 0; $i < 11; $i++) {
            $structure = new Structure();
            $structure->setCompleteName($faker->name)
                ->setUsualName($faker->word)
                ->setDescription($faker->paragraph(3))
                ->setStructureType($faker->word)
                ->setWebsite($faker->url)
                ->setExternalPaymentLink($faker->url)
                ->setlegalRpFirstName($faker->firstName)
                ->setlegalRpLastName($faker->lastName)
                ->setLegalRepresentMail($faker->email)
                ->setLegalRepresentRole($faker->word)
                ->setOfficeMail($faker->email)
                ->setPostalCode($faker->randomNumber(5))
                ->setCity($faker->city)
                ->setOfficePhone($faker->randomNumber(8))
                ->setOfficeAddress($faker->address)
                ->setTerritoryOffice($faker->state);

            $manager->persist($structure);
            $this->addReference('structure_' . $i, $structure);
        }
        for ($i = 0; $i < 6; $i++) {
            $themeDisplayArray = ['Eau - Milieux aquatiques', 'Faune/Flore - Milieux naturels',
                'Agriculture - Bien-être animal', 'Enjeux de société climat - Énergie - Déchets',
                'Aménagement Territoire - Mobilités', 'Santé - Alimentation'];
            $themeArray = ['Eau', 'Faune', 'Agriculture', 'Enjeux', 'Aménagement', 'Santé'];
            $theme = new Theme();
            $theme->setDisplayName($themeDisplayArray[$i]);
            $theme->setName($themeArray[$i]);

            $manager->persist($theme);
            $this->addReference('theme_' . $i, $theme);
        }

        for ($i = 0; $i < 20; $i++) {
            $event = new Evenement();
            $event->setTitle($faker->title);
            $event->setDescription($faker->paragraph(3, true));
            $event->setIllustration($faker->word);
            $event->setOrganisatorPhoneNum($faker->randomNumber(8));
            $event->setOrganisatorMail($faker->email);
            $event->setTypeUsers($faker->title);
            $event->setTargetAudience($faker->word);
            $event->setAccessibility($faker->title);
            $event->setMaxParticipants($faker->randomDigitNotNull);
            $event->setNumberSubscribAdult($faker->randomDigitNotNull);
            $event->setRegisterRequired($faker->boolean);
            $event->setCost($faker->boolean);
            $event->setInitialPriceAdult($faker->randomDigitNotNull);
            $event->setInitialPriceChild($faker->randomDigitNotNull);
            $event->setLocation($faker->city);
            $event->setLatitude($faker->latitude($min = 48, $max = 50));
            $event->setLongitude($faker->longitude($min = 7, $max = 8));
            $event->setDateStart(new \DateTime('2020-08-08'));
            $event->setDateStop($faker->dateTime);
            $event->setZipCode($faker->randomNumber(2));
            $event->setCity($faker->city);
            $event->setRegion($faker->word);
//            $event->setReport($this->getReference('report_' . $i++));
            $event->setStructure($this->getReference('structure_' . rand(1, 10)));
            $event->addParticipant($this->getReference('user_' . rand(1, 30)));
            $event->addCategory($this->getReference('categorie_' . rand(0, 10)));
            $event->addTheme($this->getReference('theme_' . rand(0, 5)));


            $manager->persist($event);
            $this->addReference('event_' . $i, $event);
        }

        for ($i = 0; $i < 20; $i++) {
            $report = new Report();
            $report->setReportTimeSpent($faker->numberBetween(1, 20))
                ->setReportDescrip($faker->text)
                ->setEvenement($this->getReference('event_' . $i))
                ->setReportMember($faker->randomNumber(2));

            $manager->persist($report);
            $this->addReference('report_' . $i, $report);
        }

        $manager->flush();
    }
}

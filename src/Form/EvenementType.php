<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Evenement;
use App\Entity\Report;
use App\Entity\Structure;
use App\Entity\Theme;
use App\Entity\Users;
use DateTime;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        Laisser Option pour le moment et voir comment l'utiliser
        $options=[];
        $builder
            ->add('title', TextType::class, ['label'=>'Titre de l\'événement*'])
            ->add('description', TextareaType::class, ['label'=>'Descriptif* (800 caractères max)'])
            ->add('organisatorPhoneNum', IntegerType::class, ['label'=>'Numéro de téléphone de l\'organisateur*'])
            ->add('organisatorMail', EmailType::class, ['label'=>'Email de l\'organisateur*'])
            ->add('typeUsers', ChoiceType::class, ['choices'=>['Salarié'=> "salarié",'Bénévole'=> "bénévole"],
                'label'=>'Statut de l\'organisateur'])
            ->add('targetAudience', ChoiceType::class, ['choices'=>['Tout public'=> "tout public",
                'Adulte uniquement'=> "adultes uniquement", 'Enfant de plus de 16 ans'=> "enfants de plus de 16 ans"],
                'label'=>'Public concerné'])
            ->add('accessibility', null, ['label'=>'Condition d\'accessibilité*'])
            ->add('region', TextType::class, ['label'=>'Region*'])
            ->add('city', TextType::class, ['label'=>'Ville*'])
            ->add('zipCode', IntegerType::class, ['label'=>'Code Postal*'])
            ->add('maxParticipants', IntegerType::class, ['attr'=>['min'=>0,],
                                                        'label'=>'Nombre max de participants',
                                                        'required' => false])
            ->add('numberSubscribAdult', IntegerType::class, ['attr'=>['min'=>0],
                                                        'label'=>'Nombre max d\'adulte',
                                                        'required' => false])
            ->add('registerRequired', CheckboxType::class, ['label' => 'Enregistrement obligatoire',
                                                                        'required' => false ])
            ->add('cost', ChoiceType::class, ['choices'=>['Gratuit'=> false,'Payant'=>true],
                                                        'label'=>'Coût*',
                                                        'expanded' => true])
            ->add('reduction', IntegerType::class, ['label'=> 'Prix réduit','required'=>false])
            ->add('reductionCondition', TextType::class, ['label'=>'Conditions du tarif réduit','required'=>false])
            ->add('initialPriceAdult', IntegerType::class, ['label'=> 'Prix de base (adulte)','required'=>false])
            ->add('initialPriceChild', IntegerType::class, ['label'=> 'Prix de base (enfant)','required'=>false])
            ->add('location', TextType::class, ['label'=> 'Lieu*'])
            ->add('dateStart', DateTimeType::class, [
                'label' => 'Date de début*',
                'placeholder' => 'Select a value',
                'input' => 'datetime',
                'widget' => 'single_text',
                'data' => new DateTime('now')])
            ->add('dateStop', DateTimeType::class, [
                'label' => 'Date de Fin*',
                'placeholder' => 'Select a value',
                'input' => 'datetime','widget' => 'single_text'])
// EventStatus a enlever du formulaire et automatiser le fait qu'il est en attente jusqu'a validation
            ->add('eventStatus', HiddenType::class, ['empty_data' => 'En attente'])
// Inserer un uplo de fichier pour illustration
            ->add('imageFile', FileType::class, ['required' => false])
// Theme et category: ajouter des choix ds la bdd pour choisir par la suite
            ->add('category', EntityType::class, [
                'class'=> Category::class,
                'choice_label'=>'name',
                'multiple' => true,
                'label'=>'Catégorie*'])
            ->add('theme', EntityType::class, [
                'class'=> Theme::class,
                'choice_label'=>'name',
                'multiple' => true,
                'label'=>'Theme*'])
            ->add('structure', EntityType::class, [
                'class'=> Structure::class,
                'label' => 'Structure*',
                'choice_label'=>function (?Structure $structure) {
                    if ($structure) {
                        $completeName = $structure->getCompleteName();
                        $usualName = $structure->getUsualName();

                        return $completeName . ' (' . $usualName . ')';
                    }
                }])
// Je ne sais pas encore comment faire
//            ->add('participant', EntityType::class, [
//                'class'=>Users::class,
//                'choice_label'=> function (?Users $user) {
//
//                    return $user ? $user->getFirstName().' '.$user->getLastName(): [];
//                }, 'label'=>'Intervenant'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}

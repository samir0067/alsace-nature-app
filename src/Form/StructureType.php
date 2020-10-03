<?php

namespace App\Form;

use App\Entity\InterventionArea;
use App\Entity\Structure;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StructureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options = [];
        $builder
            ->add('completeName', TextType::class, [
                'label' => 'Nom complet de la structure*',
            ])
            ->add('usualName', TextType::class, [
                'label' => 'Acronyme ou nom usuel',
                'required' => false,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description de la structure (500 caractères max.)',
                'required' => false,
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Logo de la structure',
                'required' => false,
            ])
            ->add('structureType', ChoiceType::class, [
                'choices' => [
                    'Association' => 1,
                    'Collectif' => 2,
                    'Parc' => 3,
                ],
                'label' => 'Type de structure*',
            ])
            ->add('website', TextType::class, [
                'label' => 'Site Web',
                'required' => false,
            ])
            ->add('externalPaymentLink', TextType::class, [
                'label' => 'Lien HelloAsso',
                'required' => false,
            ])
            ->add('legalRpFirstName', TextType::class, [
                'label' => 'Prénom*',
            ])
            ->add('legalRpLastName', TextType::class, [
                'label' => 'Nom*',
            ])
            ->add('legalRepresentMail', TextType::class, [
                'label' => 'Email*',
            ])
            ->add('legalRepresentRole', ChoiceType::class, [
                'choices' => [
                    'Président' => 1,
                    'Trésorier' => 2
                ],
                'label' => 'Fonction*',
            ])
            ->add('officeMail', TextType::class, [
                'label' => 'Email*',
            ])
            ->add('officePhone', IntegerType::class, [
                'label' => 'Téléphone*',
            ])
            ->add('officeAddress', TextType::class, [
                'label' => 'Adresse du siège*',
            ])
            ->add('territoryOffice', TextType::class, [
                'label' => 'Département*',
            ])
            ->add('interventionArea', EntityType::class, [
                'class' => InterventionArea::class,
                'choice_label' => 'intervention_area',
                'label' => 'Zone(s) d’intervention de la structure',
                'multiple' => true,
                'expanded' => false,
            ])
            ->add('postalCode', IntegerType::class, [
                'label' => 'Code postal*',
            ])
            ->add('city', TextType::class, [
                'label' => 'Commune*',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Structure::class,
        ]);
    }
}

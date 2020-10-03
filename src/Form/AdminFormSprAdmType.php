<?php

namespace App\Form;

use App\Entity\Authorization;
use App\Entity\Structure;
use App\Entity\Users;
use App\Entity\ValidationAuthorization;
use App\Repository\AuthorizationRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminFormSprAdmType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options=[];
        $builder->add('authorization', EntityType::class, [
            'class'=>Authorization::class,
            'choice_label'=>'name',
            'query_builder' => function (AuthorizationRepository $auth) {
                return $auth->createQueryBuilder('t')
                    ->where('t.name = :int')
                    ->setParameter('int', 'SuperAdmin');
            },
            'label' => 'Selection des droits',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ValidationAuthorization::class,
        ]);
    }
}

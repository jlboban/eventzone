<?php

namespace App\Form;

use App\Entity\Musician;
use App\Entity\Venue;
use App\Repository\MusicianRepository;
use App\Repository\VenueRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;


class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('start_date')
            ->add('end_date')
            ->add('start_time')
            ->add('end_time')
            ->add('price')
            ->add('discount')
            ->add('image', FileType::class, [
                'mapped' => false,
                'required' => false
            ])
            ->add('musicians', EntityType::class, [
                'class' => Musician::class,
                'query_builder' => function(MusicianRepository $mr){
                    return $mr->createQueryBuilder('m')->orderBy('m.name', 'ASC');
                },
                'choice_label' => function(Musician $musician){
                    return $musician->getName();
                },
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('venues', EntityType::class, [
                'class' => Venue::class,
                'query_builder' => function(VenueRepository $vr){
                    return $vr->createQueryBuilder('v')->orderBy('v.name', 'ASC');
                },
                'choice_label' => function(Venue $venue){
                    return $venue->getName();
                },
                'multiple' => true,
                'expanded' => true,
            ])
        ;
    }

}

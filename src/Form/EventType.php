<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Musician;
use App\Entity\Venue;
use App\Repository\MusicianRepository;
use App\Repository\VenueRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('start_date', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'required' => true
            ])
            ->add('start_time', TimeType::class, [
                'required' => true
            ])
            ->add('end_date', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'required' => false
            ])
            ->add('end_time', TimeType::class, [
                'required' => false
            ])
            ->add('price')
            ->add('discount', NumberType::class, [
                'label' => 'Discount %',
                'help' => 'Initial discount percentage that will be scaled to 0.'
            ])
            ->add('discount_begin', IntegerType::class, [
                'help' => 'Discount scaling begins on X days before the event. Default: 365 days.',
                'required' => true
            ])
            ->add('discount_end', IntegerType::class, [
                'help' => 'Discount scaling ends on X days before the event. Default: 30 days.',
                'required' => true
            ])
            ->add('image', FileType::class, [
                'mapped' => false,
                'required' => true,
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
                'expanded' => false,
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
                'expanded' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
            'validation_groups' => ['create'],
        ]);
    }

}

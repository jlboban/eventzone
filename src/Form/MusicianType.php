<?php

namespace App\Form;

use App\Entity\Genre;
use App\Entity\Musician;
use App\Repository\GenreRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MusicianType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description', TextareaType::class)
            ->add('image', FileType::class, [
                'mapped' => false,
                'label' => false,
            ])
            ->add('genre', EntityType::class, [
                'class' => Genre::class,
                'query_builder' => function(GenreRepository $gr){
                    return $gr->createQueryBuilder('g')->orderBy('g.name', 'ASC');
                },
                'choice_label' => function(Genre $genre){
                    return $genre->getName();
                },
                'multiple' => true,
                'expanded' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Musician::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Genre;
use App\Entity\Publisher;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ISBN', TextType::class, [
                'label' => 'Isbn',
            ])
            ->add('title')
            ->add('author', EntityType::class,[
                'class' => Author::class
            ])
            ->add('genre', EntityType::class,[
                'class' => Genre::class
            ])
            ->add('publisher', EntityType::class,[
                'class' => Publisher::class
            ])
            ->add('year', DateType::class, array(
                'label' => 'Year of publication',
                'format' => 'yyyy-MM-dd',
                'years' => range(date('Y') - 40, date('Y')),
                'placeholder' => '--',
            ))
            ->add('description')
            ->add('quantity')
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}

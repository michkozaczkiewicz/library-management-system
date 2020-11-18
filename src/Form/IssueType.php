<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Issue;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IssueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('book', EntityType::class,[
                'class' => Book::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.quantity > :quantity')
                        ->setParameter('quantity', 0);
                },
            ])
            ->add('user', EntityType::class,[
                'class' => User::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
            ->where('JSON_CONTAINS(u.roles, :role) = 1')
            ->setParameter('role', '"ROLE_USER"');
                },
            ])
            ->add('dateOfIssue', DateType::class, array(
                'label' => 'Date of issue',
                'format' => 'yyyy-MM-dd',
                'years' => range(date('Y'), date('Y')),
                'data' => new \DateTime()
            ))
            ->add('dueDate', DateType::class, array(
                'label' => 'Due date',
                'format' => 'yyyy-MM-dd',
                'years' => range(date('Y'), date('Y')),
                'data' => new \DateTime('now +14 day')
            ))
            ->add('issueReturn', HiddenType::class, [
                'data' => 'Pending',
            ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Issue::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Customer;
use App\Entity\Invoice;
use App\Repository\ArticleRepository;
use Doctrine\DBAL\Types\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType as TypeDateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvoiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
        $builder
            //->add('name')
            ->add('date', TypeDateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control']
            ])
            ->add('customer', EntityType::class, [
                'class' => Customer::class,
                'attr' => ['class' => 'form-control']
            ]);

            $formOptions = [
                'class' => Article::class,
                'multiple' => true,
                'choice_label' => 'name',
                'query_builder' => function (ArticleRepository $articleRepository) {
                    return $articleRepository->queryOwnedBy(0);
                },
                'attr' => ['class' => 'form-control']
            ];

            $builder->add('article', EntityType::class, $formOptions); 

            //->add('article', EntityType::class, [
              //  'class' => Article::class,
                //'multiple' => true,
                //'choice_label' => 'name',
                //'query_builder' => function(ArticleRepository $articleRepository) {
                  //  return $articleRepository->findArticlesInStock(),
               // },
                //'attr' => ['class' => 'form-control']
            //])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Invoice::class,
        ]);
    }
}

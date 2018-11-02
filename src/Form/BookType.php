<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Category;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    /**
     * @var ObjectManager
     */
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'required' => false
            ])
            ->add('content')
            ->add('yearBook')
            ->add('authorFirstname')
            ->add('authorLastname')
            ->add('category', EntityType::class, [
                'class'         => 'App\Entity\Category',
                'placeholder'   => 'Category select'
            ])
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $data = $event->getData();

                if (!$data) {
                    return;
                }
                $category = $data['category'];
                if ($this->manager->getRepository(Category::class)->find($category)) {
                    return;
                }

                $newCategory = new Category();
                $newCategory->setTitle($category);
                $this->manager->persist($newCategory);
                $this->manager->flush();

                $data['category'] = $newCategory->getId();
                $event->setData($data);
            });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
            'translation_domain' => 'formBook'
        ]);
    }
}

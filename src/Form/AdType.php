<?php

namespace App\Form;

use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\SubjectRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdType extends AbstractType
{
    protected $categoryRepository;
    protected $subjectRepository;

    public function __construct(CategoryRepository $categoryRepository, SubjectRepository $subjectRepository){
        $this->categoryRepository = $categoryRepository;
        $this->subjectRepository = $subjectRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $categories = [];
        foreach ($this->categoryRepository->findAll() as $category){
            $categories[$category->getName()] = $category;
        }

        $subjects = [];
        foreach ($this->subjectRepository->findAll() as $subject){
            $subjects[$subject->getName()] = $subject;
        }

        $builder
            ->add('title', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Nom de votre annonce'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description de votre annonce',
                'attr' => [
                    'placeholder' => 'Tapez la description de votre annonce'
                ]
            ])
            ->add('category', ChoiceType::class, [
                'placeholder' => 'choisir une catégorie',
                'choices' => [$categories]
            ])
            ->add('subject', ChoiceType::class, [
                'placeholder' => "choisir une matière",
                'choices' => [$subjects]
            ])
            ->add('Enregistrer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {

    }
}

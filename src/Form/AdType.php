<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Subject;
use App\Repository\CategoryRepository;
use App\Repository\SubjectRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdType extends AbstractType
{
    protected CategoryRepository $categoryRepository;
    protected SubjectRepository $subjectRepository;

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
                'label' => 'Titre de votre annonce :',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description de votre annonce :',
            ])
            ->add('category', EntityType::class, [
                'placeholder' => 'choisir une catégorie',
                'class' => Category::class,
                'choice_label' => 'name'
            ])
            ->add('subject', EntityType::class, [
                'placeholder' => "choisir une matière",
                'class' => Subject::class,
                'choice_label' => 'name'
            ])
            ->add('Enregistrer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {

    }
}

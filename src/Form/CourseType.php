<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Course;
use App\Entity\Level;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class CourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
                'label' => 'Nom de la formation',
            ])
            ->add('small_description', TextAreaType::class,[
                'label' => 'Brève description',
            ])
            ->add('full_description', TextareaType::class,[
                'label' => 'Description complète',
            ])
            ->add('duration', TextType::class,[
                'label' => 'Durée de la formation'
            ])
            ->add('price', MoneyType::class,[
                'label' => 'Prix de la formation',
            ])
            ->add('created_at', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date de création'
            ])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Photo de la formation',
                'required' => false,
                'allow_delete' => false,
                'download_uri' => false,
                'image_uri' => false,
                'asset_helper' => true,
            ])
            ->add('programFile', VichFileType::class, [
                'label' => 'Programme (PDF)',
                'required' => false,
                'allow_delete' => false,
                'download_uri' => false,
                'asset_helper' => true,
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisissez catégorie',
            ])
            ->add('level', EntityType::class, [
                'class' => Level::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisissez niveau',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Course::class,
        ]);
    }
}

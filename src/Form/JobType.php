<?php

namespace App\Form;

use App\Entity\Job;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', TextType::class)
            ->add('company', TextType::class)
            ->add('logo', TextType::class)
            ->add('url', UrlType::class)
            ->add('position', TextType::class)
            ->add('location', TextType::class)
            ->add('description', TextareaType::class)
            ->add('howToApply', TextType::class)
            ->add('public', TextType::class)
            ->add('activated', TextType::class)
            ->add('email', EmailType::class)
            ->add('category', TextType::class)
            ->add('token', TextType::class);
    }

    /**
     * @param OptionsResolver $resolver
     *
     * @throws \Symfony\Component\OptionsResolver\Exception\AccessException
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Job::class
        ]);
    }
}

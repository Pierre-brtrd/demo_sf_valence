<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Vich\UploaderBundle\Form\Type\VichImageType;

class UserType extends AbstractType
{
    public function __construct(
        private Security $security
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $user = $event->getData();
            $form = $event->getForm();

            if ($user === $this->security->getUser()) {
                $form
                    ->add('username', TextType::class, [
                        'label' => false,
                        'required' => true,
                        'attr' => [
                            'placeholder' => 'Votre Username',
                        ],
                    ])
                    ->add('prenom', TextType::class, [
                        'label' => false,
                        'attr' => [
                            'placeholder' => 'Votre prénom',
                        ],
                    ])
                    ->add('nom', TextType::class, [
                        'label' => false,
                        'attr' => [
                            'placeholder' => 'Votre nom',
                        ],
                    ])
                    ->add('age', NumberType::class, [
                        'label' => false,
                        'attr' => [
                            'placeholder' => 'Votre age',
                        ],
                    ])
                    ->add('email', EmailType::class, [
                        'label' => false,
                        'attr' => [
                            'placeholder' => 'Votre email',
                        ],
                    ])
                    ->add('ville', TextType::class, [
                        'label' => false,
                        'attr' => [
                            'placeholder' => 'Votre ville',
                        ],
                    ])
                    ->add('imageFile', VichImageType::class, [
                        'required' => false,
                        'download_uri' => false,
                        'image_uri' => true,
                        'label' => 'Image:',
                    ])
                    ->add('address', TextType::class, [
                        'required' => false,
                        'label' => false,
                        'attr' => [
                            'placeholder' => 'Votre adresse',
                        ],
                    ])
                    ->add('zipCode', TextType::class, [
                        'required' => false,
                        'label' => false,
                        'attr' => [
                            'placeholder' => 'Code postal',
                        ],
                    ]);
            }

            if ($this->security->isGranted('ROLE_ADMIN')) {
                $form
                    ->add('roles', ChoiceType::class, [
                        'choices' => [
                            'Editeur' => 'ROLE_EDITOR',
                            'Administrateur' => 'ROLE_ADMIN',
                        ],
                        'label' => 'Roles:',
                        'required' => false,
                        'expanded' => true,
                        'multiple' => true,
                    ]);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

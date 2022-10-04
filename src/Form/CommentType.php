<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre:',
                'attr' => [
                    'placeholder' => 'Titre de votre commentaire',
                ],
                'required' => true,
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu:',
                'attr' => [
                    'placeholder' => 'Contenu de votre commentaire',
                ],
                'required' => true,
            ])
            ->add('note', RangeType::class, [
                'label' => 'Note:',
                'attr' => [
                    'min' => 0,
                    'max' => 5,
                    'value' => 3,
                ],
                'help' => 'Déplacer le curseur pour attribuer une note, la note est entre 0 et 5.',
                'required' => true,
            ])
            ->add('rgpd', CheckboxType::class, [
                'label' => 'En soumettant le formulaire, vous acceptez les mentions légales ainsi que la politique de confidentialité.',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez cocher la case RGPD',
                    ]),
                ],
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}

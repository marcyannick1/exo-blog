<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Categorie;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Titre')
            ->add('Contenu')
            ->add('Etat', ChoiceType::class, [
                'choices' => [
                    'Brouillon' => Article::ETAT_BROUILLON,
                    'Publié' => Article::ETAT_PUBLIE
                ]
            ])
            ->add('Auteur', EntityType::class, [
                'class' => Utilisateur::class,
'choice_label' => 'nom',
            ])
            ->add('Categorie', EntityType::class, [
                'class' => Categorie::class,
'choice_label' => 'titre',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}

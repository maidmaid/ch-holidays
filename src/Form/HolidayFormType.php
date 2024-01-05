<?php

namespace App\Form;

use App\Entity\Canton;
use App\Repository\CantonRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use function Symfony\Component\String\s;

class HolidayFormType extends AbstractType
{
    public function __construct(private CantonRepository $cantonRepository)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $cantons = $this->cantonRepository->findAll();

        $builder
            ->add('cantons', ChoiceType::class, [
                'choices' => $cantons,
                'choice_value' => 'id',
                'choice_label' => static fn (Canton $canton): string => (string) $canton,
                'multiple' => true,
                'attr' => [
                    'size' => count($cantons) + 2,
                ],
                'label' => false,
                'group_by' => static fn (Canton $canton): string => s($canton->getLanguage())->upper()->toString(),
            ])
        ;
    }
}

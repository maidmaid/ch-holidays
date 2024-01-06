<?php

namespace App\Form;

use App\Entity\HolidayType;
use App\Repository\CantonRepository;
use App\Repository\HolidayTypeRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Wnx\SwissCantons\Canton;
use Wnx\SwissCantons\Cantons;

class HolidayFormType extends AbstractType
{
    public function __construct(private CantonRepository $cantonRepository, private HolidayTypeRepository $holidayTypeRepository)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $cantons = (new Cantons())->getAll();

        $builder
            ->add('cantons', ChoiceType::class, [
                'choices' => $cantons,
                'choice_value' => 'abbreviation',
                'choice_label' => static fn (Canton $canton): string => sprintf('%s - %s', $canton->getAbbreviation(), $canton->getName()),
                'multiple' => true,
                'attr' => [
                    'size' => count($cantons),
                ],
                'label' => false,
            ])
            ->add('types', ChoiceType::class, [
                'choices' => $this->holidayTypeRepository->findAll(),
                'choice_value' => 'id',
                'choice_label' => static fn (HolidayType $type): string => $type->getId(),
                'multiple' => true,
                'label' => false,
            ])
        ;
    }
}

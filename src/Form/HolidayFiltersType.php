<?php

namespace App\Form;

use App\Entity\HolidayType;
use App\Form\Model\HolidayFilters;
use App\Repository\CantonRepository;
use App\Repository\HolidayTypeRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Wnx\SwissCantons\Canton;
use Wnx\SwissCantons\Cantons;
use function Symfony\Component\String\s;

class HolidayFiltersType extends AbstractType
{
    public function __construct(private CantonRepository $cantonRepository, private HolidayTypeRepository $holidayTypeRepository)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $cantons = (new Cantons())->getAll();
        $holidayTypes = $this->holidayTypeRepository->findBy([], ['id' => 'ASC']);

        $builder
            ->add('year', ChoiceType::class, [
                'choices' => range(2020, 2024),
                'choice_label' => static fn (int $year): string => (string) $year,
                'data' => date('Y'),
                'label' => false,
            ])
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
                'choices' => $holidayTypes,
                'choice_value' => 'id',
                'choice_label' => static fn (HolidayType $type): string => s($type->getId())->replace('_', ' ')->title(true)->toString(),
                'multiple' => true,
                'attr' => [
                    'size' => count($holidayTypes),
                ],
                'label' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => HolidayFilters::class,
        ]);
    }
}

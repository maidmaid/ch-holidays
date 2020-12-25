<?php

namespace App\Form;

use App\Domain\Canton;
use App\Domain\HolidayManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use function Symfony\Component\String\s;

class HolidayFormType extends AbstractType
{
    private $holidayManager;

    public function __construct(HolidayManager $holidayManager)
    {
        $this->holidayManager = $holidayManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $cantons = $this->holidayManager->getCantons();

        $builder
            ->add('cantons', ChoiceType::class, [
                'choices' => $cantons,
                'choice_value' => 'id',
                'choice_label' => function (Canton $canton) {
                    return (string) $canton;
                },
                'multiple' => true,
                'attr' => [
                    'size' => count($cantons) + 2,
                ],
                'label' => false,
                'group_by' => function (Canton $canton) {
                    return s($canton->language)->upper();
                }
            ])
            ->add('canton_weight_type', ChoiceType::class, [
                'choices' => [
                    'by cantons count' => Canton::WEIGHT_TYPE_COUNT,
                    'by cantons population' => Canton::WEIGHT_TYPE_POPULATION,
                ],
                'choice_attr' => [
                    'by cantons count' => ['data-legend' => json_encode($this->legend(Canton::WEIGHT_TYPE_COUNT))],
                    'by cantons population' => ['data-legend' => json_encode($this->legend(Canton::WEIGHT_TYPE_POPULATION))],
                ],
                'help' => 'Defines the weight of each canton on the heatmap',
            ])
        ;
    }

    private function legend(string $weightType): array
    {
        [$min, $max] = $this->holidayManager->getBoundariesWeights($weightType);

        return array_map('ceil', range($min, $max, ($max - $min) / 3));
    }
}

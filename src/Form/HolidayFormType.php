<?php

namespace App\Form;

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
                'choices' => array_combine($cantons, $cantons),
                'multiple' => true,
                'attr' => [
                    'size' => count($cantons) + 2,
                ],
                'label' => false,
                'group_by' => function($choice) {
                    return s($this->holidayManager->getCantonLanguage($choice))->upper();
                }
            ])
        ;
    }
}

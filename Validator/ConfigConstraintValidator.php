<?php
/**
 * Created by PhpStorm.
 * User: alexsholk
 * Date: 23.10.15
 * Time: 0:34
 */

namespace AxS\ConfigBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use AxS\ConfigBundle\Entity\Config;

/**
 * @Annotation
 */
class ConfigConstraintValidator extends ConstraintValidator
{
    protected $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param Config $config
     * @param Constraint $constraint
     */
    public function validate($config, Constraint $constraint)
    {
        // Проверка правильности типа
        if (!array_key_exists($config->getType(), $config->getAvailableTypes())) {
            $this->context->buildViolation($constraint->invalidTypeMessage)
                ->atPath('type')
                ->addViolation();
        }

        // Если поле "тип" изменилось, валидация завершается
        if (in_array('type', $config->getChangedFields())) {
            return;
        }

        $valueConstraints = [];
        if ($config->getRequired()) {
            $valueConstraints[] = new Assert\NotBlank();
        }

        // Валидация поля "значение" в зависимости от типа
        switch ($config->getType()) {
            case Config::TYPE_TEXT:
            case Config::TYPE_TEXTAREA:
            case Config::TYPE_WYSIWYG:
                $valueConstraints[] = new Assert\Type('string');
                break;
            case Config::TYPE_INTEGER:
                $valueConstraints[] = new Assert\Type('integer');
                break;
            case Config::TYPE_BOOLEAN:
                $valueConstraints[] = new Assert\Type('boolean');
                break;
            case Config::TYPE_SELECT:
            case Config::TYPE_MULTICHOICE:
                $valueConstraints[] = new Assert\Choice([
                    'choices' => array_keys((array)json_decode($config->getChoices())),
                ]);
                break;
            case Config::TYPE_EMAIL:
                $valueConstraints[] = new Assert\Email();
                break;
            case Config::TYPE_URL:
                $valueConstraints[] = new Assert\Url();
                break;
            case Config::TYPE_REGEX:
                $valueConstraints[] = new Assert\Regex(['pattern' => $config->getChoices()]);
                break;
        }

        if ($valueConstraints) {
            $errorList = $this->validator->validate($config->getValue(), $valueConstraints);

            if ($errorList->count()) {
                $this->context->buildViolation($errorList)
                    ->atPath('value')
                    ->addViolation();
            }
        }
    }
}
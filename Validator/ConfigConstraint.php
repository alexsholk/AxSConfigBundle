<?php
/**
 * Created by PhpStorm.
 * User: alexsholk
 * Date: 23.10.15
 * Time: 0:31
 */

namespace AxS\ConfigBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ConfigConstraint extends Constraint
{
    public $invalidTypeMessage = 'Config type is incorrect.';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy()
    {
        return 'axs_config_validator';
    }
}
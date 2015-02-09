<?php
/*
 * Copyright (c) 2014 Eltrino LLC (http://eltrino.com)
 *
 * Licensed under the Open Software License (OSL 3.0).
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://opensource.org/licenses/osl-3.0.php
 *
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@eltrino.com so we can send you a copy immediately.
 */

namespace Diamante\DeskBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class AnyValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (null === $value) {
            return;
        }

        foreach ($constraint->constraints as $constraintEntity) {
            $validatedBy = $constraintEntity->validatedBy();
            $validator = new $validatedBy();
            $validator->initialize($this->context);
            $validator->validate($value, $constraintEntity);
        }

        $violationsCount = $this->context->getViolations()->count();
        if (count($constraint->constraints) == $violationsCount) {
            $this->context->addViolation($constraint->message);
        } else {
            for ($i = 0; $i < $violationsCount; $i++) {
                $this->context->getViolations()->remove($i);
            }
        }
    }
}

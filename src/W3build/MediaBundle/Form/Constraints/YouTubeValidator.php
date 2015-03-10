<?php
namespace W3build\MediaBundle\Form\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class YouTubeValidator extends ConstraintValidator {

    /**
     * @param mixed $value
     * @param Constraint $constraint
     * @throws \Exception
     */
    public function validate($value, Constraint $constraint)
    {
        if (!preg_match('#https?://www\.youtube\.com/watch\?v=([^&]*)#', $value, $matches)) {
            $this->context->addViolation(
                $constraint->getMessage()
            );
        }
    }


}
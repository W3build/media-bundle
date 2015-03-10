<?php
namespace W3build\MediaBundle\Form\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class YouTube
 * @package W3build\MediaBundle\Form\Constraints
 *
 * @Annotation
 */
class YouTube extends Constraint {

    /**
     * @var string
     */
    private $message = 'YouTubeLinkInvalid';

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: lukas_jahoda
 * Date: 18.12.14
 * Time: 10:22
 */

namespace W3build\MediaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class YouTubeType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('path', 'text', array(
            'label' => 'YouTubeUrl',
            'attr' => array(
                'class' => 'form-control'
            )
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'W3build\MediaBundle\Entity\YouTube',
        ));
    }


    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'youTube';
    }


}
<?php
/**
 * Created by PhpStorm.
 * User: lukas_jahoda
 * Date: 22.1.15
 * Time: 19:44
 */

namespace W3build\MediaBundle\Twig;

use Symfony\Component\Form\FormView;

class ImageWidget extends \Twig_Extension {

    public function getFunctions(){
        return array(
            new \Twig_SimpleFunction('image_widget', array($this, 'render'), array('pre_escape' => 'html', 'is_safe' => array('html'), 'needs_environment' => true)),
        );
    }

    public function render(\Twig_Environment $twig, FormView $element){
        return $twig->render('W3buildMediaBundle:Template:image_widget.html.twig', array('imageElement' => $element));
    }

    public function getName(){
        return 'image_widget';
    }

}
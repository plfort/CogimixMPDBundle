<?php
namespace Cogipix\CogimixMPDBundle\Form;
use Symfony\Component\Form\FormInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\AbstractType;
/**
 *
 * @author plfort - Cogipix
 *
 */
class MPDServerInfoFormType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
        ->add('name', 'text', array(
                'label' => 'Display name'))
        ->add('alias', 'text', array(
                        'label' => 'Unique alias'))
        ->add('streamUrl', 'text', array('label'=>'Stream URL'))
        ->add('host','text',array('label'=>'host'))
        ->add('port','text',array('label'=>'port'))
        ->add('password','text',array('label'=>'Password','required'=>false));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('data_class' => 'Cogipix\CogimixMPDBundle\Entity\MPDServerInfo',
                'validation_groups' => function(FormInterface $form) {
                                $default = array('Create');

                                return $default;
                            },
        ));
    }

    public function getName() {
        return 'mpd_server_info_create_form';
    }
}
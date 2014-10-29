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
                'label' => 'cogimix.mpd.display_name'))
        ->add('alias', 'text', array(
                        'label' => 'cogimix.mpd.alias'))
        ->add('streamUrl', 'text', array('label'=>'cogimix.mpd.stream_url'))
        ->add('host','text',array('label'=>'cogimix.mpd.host'))
        ->add('port','text',array('label'=>'cogimix.mpd.port'))
        ->add('password','text',array('label'=>'cogimix.mpd.password','required'=>false));
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
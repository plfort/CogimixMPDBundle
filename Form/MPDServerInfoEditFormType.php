<?php
namespace Cogipix\CogimixMPDBundle\Form;
/**
 *
 * @author plfort - Cogipix
 *
 */
use Symfony\Component\Form\FormBuilderInterface;

class MPDServerInfoEditFormType extends MPDServerInfoFormType{

    public function buildForm(FormBuilderInterface $builder, array $options) {
        parent::buildForm($builder, $options);
        $builder->remove('alias');

    }

    public function getName() {
        return 'mpd_server_info_edit_form';
    }
}
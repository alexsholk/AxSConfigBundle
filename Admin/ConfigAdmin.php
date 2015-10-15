<?php
/**
 * Created by PhpStorm.
 * User: alexsholk
 * Date: 15.10.15
 * Time: 20:27
 */

namespace AxS\ConfigBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ConfigAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('group')
            ->add('title')
            ->add('mask')
            ->add('value');
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('group')
            ->add('title')
            ->add('mask');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title')
            ->add('mask')
            ->add('value');
    }
}
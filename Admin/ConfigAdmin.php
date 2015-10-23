<?php
/**
 * Created by PhpStorm.
 * User: alexsholk
 * Date: 15.10.15
 * Time: 20:27
 */

namespace AxS\ConfigBundle\Admin;

use AxS\ConfigBundle\Entity\Config;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\CallbackTransformer;

class ConfigAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        /** @var Config $config */
        $config = $this->getSubject();

        $formMapper
            ->add('group')
            ->add('title')
            ->add('mask', 'text', ['help' => 'form.help_mask'])
            ->add('type', 'choice', ['choices' => $config->getAvailableTypes()])
            ->add('required');

        $options['required'] = $config->getRequired();

        switch ($config->getType()) {
            case Config::TYPE_TEXT:
                $formMapper->add('value', 'text', $options);
                break;
            case Config::TYPE_TEXTAREA:
                $formMapper->add('value', 'textarea', $options);
                break;
            case Config::TYPE_WYSIWYG:
                $formMapper->add('value', 'textarea', $options + ['attr' => ['class' => 'wysiwyg']]);
                break;
            case Config::TYPE_INTEGER:
                $formMapper->add('value', 'integer', $options);
                break;
            case Config::TYPE_FLOAT:
                $formMapper->add('value', 'number', $options);
                break;
            case Config::TYPE_BOOLEAN:
                $formMapper->add('value', 'checkbox', $options)
                    ->get('value')
                    ->addModelTransformer(new CallbackTransformer(function ($value) {
                        return (bool)$value;
                    }, function ($value) {
                        return $value;
                    }));
                break;
            case Config::TYPE_SELECT:
                $choices = (array)json_decode($config->getChoices());
                $formMapper->add('value', 'choice', $options + ['choices' => $choices]);
                $formMapper->add('choices', 'text', ['label' => 'form.label_choices_array']);
                break;
            case Config::TYPE_MULTICHOICE:
                $choices = (array)json_decode($config->getChoices());
                $formMapper
                    ->add('value', 'choice', $options + [
                            'choices' => $choices,
                            'multiple' => true,
                            'expanded' => true,
                        ])
                    ->get('value')
                    ->addModelTransformer(new CallbackTransformer(function ($value) {
                        return (array)json_decode($value);
                    }, function ($value) {
                        return json_encode($value);
                    }));

                $formMapper->add('choices', 'text', ['label' => 'form.label_choices_array']);
                break;
            case Config::TYPE_EMAIL:
                $formMapper->add('value', 'email', $options);
                break;
            case Config::TYPE_URL:
                $formMapper->add('value', 'url', $options);
                break;
            case Config::TYPE_REGEX:
                $formMapper->add('value', 'text', $options);
                $formMapper->add('choices', 'text', ['label' => 'form.label_choices_regex']);
                break;
        }
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
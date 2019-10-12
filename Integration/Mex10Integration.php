<?php

namespace MauticPlugin\MauticMex10SMSBundle\Integration;

use Mautic\PluginBundle\Integration\AbstractIntegration;

/**
 * Class Mex10Integration.
 */
class Mex10Integration extends AbstractIntegration
{
    public function getName()
    {
        return 'Mex10';
    }

    public function getIcon()
    {
        return 'plugins/MauticMex10SMSBundle/Assets/img/icon.png';
    }

    public function getSecretKeys()
    {
        return ['password'];
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function getRequiredKeyFields()
    {
        return [
            'username' => 'mautic.plugin.mex10.username',
            'password' => 'mautic.plugin.mex10.password',
        ];
    }

    /**
     * @return array
     */
    public function getFormSettings()
    {
        return [
            'requires_callback'      => false,
            'requires_authorization' => false,
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getAuthenticationType()
    {
        return 'none';
    }

    /**
     * @param \Mautic\PluginBundle\Integration\Form|FormBuilder $builder
     * @param array                                             $data
     * @param string                                            $formArea
     */
    public function appendToForm(&$builder, $data, $formArea)
    {
    }
}

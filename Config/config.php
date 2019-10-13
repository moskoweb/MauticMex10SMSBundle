<?php

/*
 * @copyright   2018 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

return [
    'name'        => 'Mex10',
    'description' => 'Mex10 SMS integration',
    'author'      => 'https://alanmosko.com.br/',
    'version'     => '1.0.0',
    'services' => [
        'events'  => [],
        'forms'   => [],
        'helpers' => [],
        'other'   => [
            'mautic.sms.transport.mex10' => [
                'class'     => \MauticPlugin\MauticMex10SMSBundle\Services\Mex10Api::class,
                'arguments' => [
                    'mautic.page.model.trackable',
                    'mautic.helper.phone_number',
                    'mautic.helper.integration',
                    'monolog.logger.mautic',
                ],
                'alias' => 'mautic.sms.config.transport.mex10',
                'tag'          => 'mautic.sms_transport',
                'tagArguments' => [
                    'integrationAlias' => 'Mex10',
                ],
            ],
        ],
        'models'       => [],
        'integrations' => [
            'mautic.integration.mex10' => [
                'class' => \MauticPlugin\MauticMex10SMSBundle\Integration\Mex10Integration::class,
            ],
        ],
    ],
    'routes'     => [],
    'menu' => [
        'main' => [
            'items' => [
                'mautic.sms.smses' => [
                    'route'  => 'mautic_sms_index',
                    'access' => ['sms:smses:viewown', 'sms:smses:viewother'],
                    'parent' => 'mautic.core.channels',
                    'checks' => [
                        'integration' => [
                            'Mex10' => [
                                'enabled' => true,
                            ],
                        ],
                    ],
                    'priority' => 70,
                ],
            ],
        ],
    ],
    'parameters' => [],
];

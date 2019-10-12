<?php

namespace MauticPlugin\MauticMex10SMSBundle\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Response;
use Mautic\SmsBundle\Api\AbstractSmsApi;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Mautic\CoreBundle\Helper\PhoneNumberHelper;
use Mautic\LeadBundle\Entity\Lead;
use Mautic\PageBundle\Model\TrackableModel;
use Mautic\PluginBundle\Helper\IntegrationHelper;
use Monolog\Logger;

class Mex10Api extends AbstractSmsApi
{
    /**
    * @var Logger
    */
    protected $logger;

    /**
     * MessageBirdApi constructor.
     *
     * @param TrackableModel    $pageTrackableModel
     * @param PhoneNumberHelper $phoneNumberHelper
     * @param IntegrationHelper $integrationHelper
     * @param Logger            $logger
     *
     * @param Http              $http
     */
    public function __construct(TrackableModel $pageTrackableModel, PhoneNumberHelper $phoneNumberHelper, IntegrationHelper $integrationHelper, Logger $logger)
    {
        $this->logger = $logger;
        $this->integrationHelper = $integrationHelper;
        parent::__construct($pageTrackableModel);
    }
    /**
    * @param $number
    *
    * @return string
    *
    * @throws NumberParseException
    */
    protected function sanitizeNumber($number)
    {
        $util   = PhoneNumberUtil::getInstance();
        $parsed = $util->parse($number, 'BR');
        return $util->format($parsed, PhoneNumberFormat::E164);
    }
    
    /**
    * @param Lead   $contact
    * @param string $content
    *
    * @return bool|mixed|string
    */
    public function sendSms(Lead $contact, $content)
    {
        $number = $contact->getLeadPhoneNumber();
        
        if ($number === null) {
            return false;
        }
        
        $integration = $this->integrationHelper->getIntegrationObject('Mex10');
        if ($integration && $integration->getIntegrationSettings()->getIsPublished()) {
            $data   = $integration->getDecryptedApiKeys();
            if (isset($data['username']) && isset($data['password'])) {
                $body = [
                    'n' => $this->sanitizeNumber($number),
                    'u' => $data['username'],
                    'p' => $data['password'],
                    'm' => $content,
                    't' => 'send',
                ];
  
                try {
                    $client = new Client();
                    $response = $client->get(
                        'https://mex10.com/api/shortcode.aspx', [
                            'query' => $body,
                        ]
                    );

                    return ($response->getStatusCode() == 200) ? true : false;
                } catch (ServerException $exception) {
                    $this->parseResponse($exception->getResponse(), $body);
                } catch (Exception $e) {
                    if (method_exists($e, 'getErrorMessage')) {
                        return $e->getErrorMessage();
                    } elseif (!empty($e->getMessage())) {
                        return $e->getMessage();
                    }

                    return false;
                }
            }
        }
    }
}




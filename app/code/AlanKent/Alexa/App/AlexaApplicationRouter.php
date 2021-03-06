<?php

namespace AlanKent\Alexa\App;


use AlanKent\Alexa\Model\AlexaRouter\Config\Data;
use Magento\Framework\ObjectManagerInterface;

/**
 * Used to collect Alexa Listeners and use proper one to handle request
 */
class AlexaApplicationRouter extends AlexaApplicationAbstract
{
    /**
     * @inheritdoc
     */
    public function intentRequest(SessionDataInterface $sessionData,
                                  CustomerDataInterface $customerData,
                                  $intentName,
                                  $slots)
    {
        /** @var AlexaApplicationInterface $handler */
        $handler = false;
        if ($intents = $this->configData->get('alexaRouter/intents')) {
            if (array_key_exists($intentName, $intents)) {
                $handler = $this->objectManager->get($intents[$intentName]);
            } elseif ($default = $this->configData->get('alexaRouter/default')) {
                $handler = $this->objectManager->get($default);
            }

            if ($handler) {
                return $handler->intentRequest($sessionData, $customerData, $intentName, $slots);
            }
        }

        $response = $this->responseDataFactory->create();
        $response->setShouldEndSession(true);

        return $response;
    }
}

<?php

declare(strict_types = 1);

namespace Telegram\Service;

use Telegram\Framework\Http\Response\ResponseInterface;
use Telegram\Framework\Object\FactoryInterface;
use Telegram\Framework\Object\GodFactory;

/**
 * Class Result
 * @package Telegram\Service
 */
class Result implements ResultInterface
{
    /**
     * @var ResponseInterface
     */
    private $response;
    
    /**
     * @var GodFactory
     */
    private $godFactory;
    
    /**
     * @var string
     */
    private $objectTypeClass;
    
    /**
     * Result constructor.
     *
     * @param GodFactory        $godFactory
     * @param ResponseInterface $response
     * @param FactoryInterface  $objectTypeFactory
     */
    public function __construct(
        GodFactory $godFactory,
        ResponseInterface $response,
        $objectTypeClass = null
    ) {
        $this->godFactory = $godFactory;
        $this->response = $response;
        $this->objectTypeClass = $objectTypeClass;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getResponse()
    {
        return $this->response;
    }
    
    /**
     * {@inheritdoc}
     */
    public function parse()
    {
        if (!$this->response->canParse()) {
            return false;
        }
        
        /** @var array $body */
        $body = $this->getResponse()->getBody();
        
        $objectType = $this->godFactory->createObject($this->objectTypeClass, [
            'data' => $body['result']
        ]);
        
        return $objectType;
    }
}

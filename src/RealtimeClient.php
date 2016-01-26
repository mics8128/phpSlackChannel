<?php
require_once("phpSlackChannel/vendor/autoload.php");

class RealtimeClient{
    private $client;
    public function __construct($token, $channel){
        $loop = \React\EventLoop\Factory::create();
        
        $logger = new \Zend\Log\Logger();
        $writer = new Zend\Log\Writer\Stream("php://output");
        $logger->addWriter($writer);
        
        $this->client = new \Devristo\Phpws\Client\WebSocket($url, $loop, $logger);
        
        $this->client->on("request", function($headers) use ($logger){
            $logger->notice("Request object created!");
        });
        
        $this->client->on("handshake", function() use ($logger) {
            $logger->notice("Handshake received!");
        });
        
        $this->client->on("connect", function($headers) use ($logger, $client){
            $logger->notice("Connected!");
        });
        
        $this->client->on("message", function($message) use ($client, $logger){
            $logger->notice("Got message: ".$message->getData());
        });
    }
    
    public function setEvent($event, $function){
        $client->on($event, $function);
    }
    
    public function loop(){
        $client->open();
        $loop->run();   
    }
}
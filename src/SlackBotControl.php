<?php
namespace Mics8128\PhpSlackChannel;

class SlackBotControl
{
    private $token;
    private $channel;
    public function __construct($token, $channel){
        $this->token = $token;
        $this->channel = $channel;
    }

    public function getToken(){
        return $this->token;
    }
    public function setToken($token){
        $this->token = $token;
    }
    
    
    public function getChannel(){
        return $this->channel;
    }
    public function setChannel($channel){
        $this->channel = $channel;
    }
    
    
    public function test()
    {
        return $this->sendData("api.test");
    }
    
    public function postMsg($msg)
    {
        $data['token'] = $this->token;
        $data['channel'] = $this->channel;
        $data['text'] = $msg;
        return $this->sendData("chat.postMessage", $data);
    }
    
    public function getHistory($latest = NULL, $count = 10)
    {
        $data['token'] = $this->token;
        $data['channel'] = $this->channel;
        $data['count'] = $count;
        if($latest){
            $data['latest'] = $latest;
        }
        var_dump($data);
        return $this->sendData("channels.history", $data);
    }
    
    public function getWebsocket(){
        $data['token'] = $this->token;
        return $this->sendData("rtm.start", $data)->url;
    }
    
    
    /* Send data to API */
    private function sendData($method, $data = []){
        $api_url = "https://slack.com/api/" . $method;
        
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data),
            ),
        );
        $context  = stream_context_create($options);
        $result = file_get_contents($api_url, false, $context);
        if ($result === FALSE) { return "Error"; }
        
        return json_decode($result);
    }
}
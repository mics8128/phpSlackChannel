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
    public function getUsersRaw(){
        return $this->users;
    }
    
    public function getUsers(){
        //TODO fix run this have to run getWsUrl before.
        $output_users = [];
        foreach($this->users as $user){
            if(!$user->deleted){
                $output_users[$user->id] = [
                    "name" => $user->name,
                    "realname" => $user->profile->first_name . " " . $user->profile->last_name,
                    "image" => $user->profile->image_48,
                    "status" => $user->status,
                ];
            }
        }
        return $output_users;
    }
    
    private function getWsUrl(){
        $data['token'] = $this->token;
        $data['simple_latest'] = "1";
        $data['no_unreads'] = "1";
        $response = $this->sendData("rtm.start", $data);
        $this->users = $response->users;
        return $response->url;
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
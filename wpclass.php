<?php
class WordPress {
    private $username;
    private $password;
    private $endpoint;
    private $blogid;
    
    private $ch;
    
    public function __construct($username, $password, $endpoint, $blogid = 1) {
        $this->username = $username;
        $this->password = $password;
        $this->endpoint = $endpoint;
        $this->blogid = $blogid;
        
        $this->ch = curl_init($this->endpoint);
        curl_setopt($this->ch, CURLOPT_HEADER, false);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));
    }
        
    private function execute($request) {
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $request);
        $response = curl_exec($this->ch);
        $result = xmlrpc_decode($response);
        if (is_array($result) && xmlrpc_is_fault($result)) {
            throw new Exception($result['faultString'], $result['faultCode']);
        }
        else {
            return $result;
        }
    }
    
    public function publish_post($title, $content, array $tags, array $categories, $status = 'publish', $date = Null) {
        // Set datetime for post
        if ($date == Null) {
            $post_date = date("Ymd\TH:i:s", time());
        }
        else {
            $post_date = $date;
        }
        xmlrpc_set_type($post_date, 'datetime');
        
        $params = array(
            $this->blogid,
            $this->username,
            $this->password,
            array(
                'post_type' => 'post',
                'post_status' => $status,
                'post_title' => $title,
                'post_content' => $content,
                'post_date' => $post_date,
                'terms_names' => array('category' => $categories, 'post_tag' => $tags)
            )
        );
        
        $request = xmlrpc_encode_request('wp.newPost', $params);
        
        $response = $this->execute($request);
        return $response;
    }
}
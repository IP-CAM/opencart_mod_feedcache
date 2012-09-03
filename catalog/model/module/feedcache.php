<?php class ModelModuleFeedcache extends Model {
    
    private $module = "feedcache";
    
    public function getFeedCache($limit, $timeout, $url)
    {
        $limit   = (int)$limit;
        $timeout = (int)$timeout;
        
        $language_id       = (int)$this->config->get('config_language_id');
        $store_id          = (int)$this->config->get('config_store_id');
        $customer_group_id = (int)$this->config->get('config_customer_group_id');
        
        if($this->customer->isLogged()) {
            $customer_group_id = $this->customer->getCustomerGroupId();
        }
        
        $cache_data   = $this->cache->get($this->module.'.'.$language_id.'.'.$store_id.'.'.$customer_group_id.'.'.$limit);
        $cache_status = true;
        
        if(empty($cache_data["date_added"])) {
            $cache_data   = array('items' => array(), 'timestamp' => null);
            $cache_status = false;
        } elseif($timeout == 0) {
            $cache_status = false;
        } else {
            $date_added = $cache_data["date_added"] + (60 * 60 * $timeout);
            
            if($date_added < strtotime('now')) {
                $cache_status = false;
            }
        }
        
        if(!$cache_status) {
            $_curl = curl_init();
            
            curl_setopt($_curl, CURLOPT_URL, 'http://ajax.googleapis.com/ajax/services/feed/load?v=1.0&num='.$limit.'&q='.$url);
            curl_setopt($_curl, CURLOPT_RETURNTRANSFER, 1);
            
            $curl_data = json_decode(curl_exec($_curl), true);
            
            foreach($curl_data["responseData"]["feed"]["entries"] as $item) {
                $cache_data["items"][] = array('title' => $item["title"],
                    'link'            => $item["link"],
                    'author'          => $item["author"],
                    'date_published'  => $item["publishedDate"],
                    'content'         => $item["content"],
                    'content_snippet' => $item["contentSnippet"],
                    'tags'            => $item["categories"]);
            }
            
            $cache_data["date_added"] = strtotime('now');
            
            $this->cache->set($this->module.'.'.$language_id.'.'.$store_id.'.'.$customer_group_id.'.'.$limit, $cache_data);
        }
        
        return $cache_data["items"];
    }
    
}
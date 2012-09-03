<?php class ControllerModuleFeedcache extends Controller {
    
    private $module = "feedcache";
    
    protected function index($setting)
    {
        $this->load->language('module/'.$this->module);
        
        $this->load->model('module/'.$this->module);
        
        $this->data["heading_title"] = $this->language->get('heading_title');
        
        eval('$this->data["feeditems"] = $this->model_module_'.$this->module.'->get'.$this->module.'($setting["limit"], $setting["timeout"], $setting["url"]);');
        
        if(file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/template/module/'.$this->module.'.tpl')) {
            $style = "catalog/view/theme/".$this->config->get('config_template')."/stylesheet/feed_display.css";
            
            $this->template = $this->config->get('config_template')."/template/module/".$this->module.".tpl";
        } else {
            $this->template = "default/template/module/".$this->module.".tpl";
        }
        
        if(file_exists(DIR_TEMPLATE.$this->config->get('config_template').'/stylesheet/'.$this->module.'.css')) {
            $this->document->addstyle('catalog/view/theme/'.$this->config->get('config_template').'/stylesheet/'.$this->module.'.css');
        } else {
            $this->document->addstyle('catalog/view/theme/default/stylesheet/'.$this->module.'.css');
        }
        
        $this->render();
    }
    
}
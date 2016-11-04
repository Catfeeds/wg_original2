<?php
class ShopAction extends BaseAction
{
    public $userGroup;
    public $token;
    public $user;               
    public $userFunctions;     
    public $wxuser;             
    protected function _initialize()
    {
        parent::_initialize();

        $this->token = session('token');
        if (MODULE_NAME != 'Upyun') {
            if (session('sid') == false) {
               $this->redirect('Shop/Retail/departset');
            }

        } 
        define('UNYUN_BUCKET', C('up_bucket'));
        define('UNYUN_USERNAME', C('up_username'));
        define('UNYUN_PASSWORD', C('up_password'));
        define('UNYUN_FORM_API_SECRET', C('up_form_api_secret'));
        define('UNYUN_DOMAIN', C('up_domainname'));
        $this->assign('upyun_domain', 'http://' . UNYUN_DOMAIN);
        $this->assign('upyun_bucket', UNYUN_BUCKET);
        $token = $this->_session('token');
        if (!$token) {
            if (isset($_GET['token'])) {
                $token = $this->_get('token');
            } else {
                $token = 'admin';
            }
        }
        $options = array();
        $now = time();
        $options['bucket'] = UNYUN_BUCKET;
        $options['expiration'] = $now + 600;
        $options['save-key'] = '/' . $token . '/{year}/{mon}/{day}/' . $now . '_{random}{.suffix}';
        $options['allow-file-type'] = C('up_exts');
        $options['content-length-range'] = '0,' . intval(C('up_size')) * 1000;
        if (intval($_GET['width'])) {
            $options['x-gmkerl-type'] = 'fix_width';
            $options['fix_width '] = $_GET['width'];
        }
        $policy = base64_encode(json_encode($options));
        $sign = md5($policy . '&' . UNYUN_FORM_API_SECRET);
        $this->assign('editor_upyun_sign', $sign);
        $this->assign('editor_upyun_policy', $policy);
    }
}
<?php
final class payHandle
{
    public $from;
    public $db;
    public $payType;
    public $token;
    public function __construct($token, $from, $paytype = 'tenpay')
    {
        $this->from = $from;
        $this->from = $from ? $from : 'Groupon';
        $this->from = $this->from != 'groupon' ? $this->from : 'Groupon';
        switch (strtolower($this->from)) {
            default:
            case 'groupon':
            case 'store':
                $this->db = M('Product_cart');
                break;
            case 'repast':
                $this->db = M('Dish_order');
                break;
            case 'hotels':
                $this->db = M('Hotels_order');
                break;
            case 'business':
                $this->db = M('Reservebook');
                break;
            case 'card':
                $this->db = M('Member_card_pay_record');
                break;
        }
        $this->token = $token;
        $this->payType = $paytype;
    }
    public function getFrom()
    {
        return $this->from;
    }
    public function beforePay($id)
    {
        $thisOrder = $this->db->where(array('token' => $this->token, 'orderid' => $id))->find();
        switch (strtolower($this->from)) {
            default:
                $price = $thisOrder['price'];
                break;
            case 'business':
                $price = $thisOrder['payprice'];
                break;
        }
        return array('orderid' => $thisOrder['orderid'], 'price' => $price, 'wecha_id' => $thisOrder['wecha_id'], 'token' => $thisOrder['token']);
    }
    public function afterPay($id, $transaction_id = '')
    {
        $thisOrder = $this->beforePay($id);
        $wecha_id = $thisOrder['wecha_id'];
        $order_model = $this->db;
        $order_model->where(array('orderid' => $id))->setField('paid', 1);
        //记录优惠券使用情况
        if (strtolower($this->getFrom()) == 'store') {
            $thisOrder = $this->db->where(array('token' => $this->token, 'orderid' => $id))->find();
            if($thisOrder['couponId']){
                M('Coupons')->where('id='.$thisOrder['couponId'])->save(array('status'=>1,'usedtime'=>time()));
            }
        }
        if (strtolower($this->getFrom()) == 'groupon') {
            $order_model->where(array('orderid' => $thisOrder['orderid']))->save(array('transactionid' => $transaction_id, 'paytype' => $this->payType));
        }
        return $thisOrder;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: LM-SAMA
 * Date: 2018/3/14
 * Time: 11:17
 */

namespace frontend\controllers;


use app\models\Address;
use app\models\Cart;
use frontend\models\Delivery;
use frontend\models\Order;
use frontend\models\OrderGoods;
use frontend\models\Payment;
use yii\db\Exception;
use yii\web\Controller;

class OrderController extends Controller
{
    /**
     * 订单列表
     */
    public function actionIndex(){
        if(\Yii::$app->user->isGuest){
            return $this->redirect(['member/login']);
        }else{
            $address=Address::find()->where(['member_id'=>\Yii::$app->user->id])->all();
            //从数据库中获取购物车信息
            $member_id=\Yii::$app->user->id;
            $cart=Cart::find()->where(['member_id'=>$member_id])->all();
            $carts=[];
            foreach ($cart as $v){
                $carts[$v->goods_id]=$v->amount;
            }
            //运费表
            $deliverys=Delivery::find()->all();
            //支付方式
            $payment=Payment::find()->all();
            return $this->render('index',['address'=>$address,'carts'=>$carts,'deliverys'=>$deliverys,'payments'=>$payment]);
        }
    }
    /**
     * 提交订单
     */
    public function actionAdd(){
        $request=\Yii::$app->request;
        if($request->isPost){
            $order=new Order();

            $address=Address::findOne(['id'=>$request->post('address')]);
            $delivery=Delivery::findOne(['id'=>$request->post('delivery')]);
            $payment=Payment::findOne(['id'=>$request->post('payment')]);

            $order->total = 0;
            //var_dump($address);die;

            //订单表
            $order->member_id=\Yii::$app->user->id;
            $order->name=$address->name;
            $order->province=$address->province;
            $order->city=$address->city;
            $order->area=$address->county;
            $order->address=$address->address;
            $order->tel=$address->tel;
            //配送方式
            $order->delivery_id=$delivery->id;
            $order->delivery_name=$delivery->name;
            $order->delivery_price=$delivery->price;
            //支付方式
            $order->payment_id=$payment->id;
            $order->payment_name=$payment->name;
            $order->status=1;
            $order->trade_no=\Yii::$app->security->generateRandomString();
            $order->create_time=time();
            //在操作数据表之前开启事务
            $transaction=\Yii::$app->db->beginTransaction();
            try{
                $order->save();
                //var_dump();die;
                $carts=Cart::find()->where(['member_id'=>\Yii::$app->user->id])->all();
                foreach ($carts as $cart){
                    $goods=\backend\models\Goods::findOne(['id'=>$cart->goods_id]);
                    //检查库存
                    if($goods->stock < $cart->amount){
                        //如果商品库存不足,抛出异常
                        throw new Exception('商品('.$goods->name.')库存不足');
                    }
                    //扣减商品库存
                    $goods->stock -= $cart->amount;
                    $goods->save();

                    $orderGoods=new OrderGoods();
                    $orderGoods->order_id = $order->id;
                    $orderGoods->goods_id = $goods->id;
                    $orderGoods->goods_name = $goods->name;
                    $orderGoods->logo = $goods->logo;
                    $orderGoods->price = $goods->shop_price;
                    $orderGoods->amount = $cart->amount;
                    $orderGoods->total  = $orderGoods->price * $orderGoods->amount;
                    $order->total+=$orderGoods->total;
                    $orderGoods->save();
                }
                //加上配送费
                $order->total+=$order->delivery_price;
                $order->save();
                //清除购物车
                Cart::deleteAll(['member_id'=>\Yii::$app->user->id]);
                //提交事务
                $transaction->commit();
                //显示页面
                return $this->render('success');
            }catch(Exception $e){
                //事务回滚
                $transaction->rollBack();
            }
        }
    }
    /**
     * 订单状态
     */
    public function actionOrder(){
        $orders=Order::find()->where(['member_id'=>\Yii::$app->user->id])->all();
        /*$orderGoods=OrderGoods::find()->where(['member_id'=>\Yii::$app->user->id])->all();*/
        /*$result=\Yii::$app->db->createCommand('select * from `order_goods` inner join `order` on `order_goods`.`order_id`=`order`.`id`;')->execute();
        var_dump($result);die;*/
        return $this->render('order',['orders'=>$orders]);
    }
}
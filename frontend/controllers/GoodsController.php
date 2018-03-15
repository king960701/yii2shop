<?php
/**
 * Created by PhpStorm.
 * User: LM-SAMA
 * Date: 2018/3/11
 * Time: 16:02
 */

namespace frontend\controllers;


use app\models\Cart;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use yii\web\Controller;
use yii\web\Cookie;

class GoodsController extends Controller
{
    /**
     * 首页面
     */
    public function actionIndex(){
        $tops=\backend\models\GoodsCategory::find()->where(['parent_id'=>0])->all();
        return $this->render('index',['tops'=>$tops]);
    }
    /**
     * 列表页
     */
    public function actionList($cat){
        //$goods=Goods::find()->where(['goods_category_id'=>$cat])->all();
        //叶子节点
        $categorys=GoodsCategory::findOne(['id'=>$cat]);
        /*$leaves=$categorys->leaves()->all();
        //var_dump($leaves);die;
        if($leaves){//如果有子分类
            $goods=[];
            foreach ($leaves as $leave){//找出所有子分类的商品
                $leave=Goods::find()->where(['goods_category_id'=>$leave->id])->all();
                //合并
                $goods=array_merge($goods,$leave);
            }
        }else{//没有子分类
            $goods=Goods::find()->where(['goods_category_id'=>$cat])->all();
        }*/
        switch($categorys->depth){
            case 0://1级分类
            case 1://2级分类
                $ids=$categorys->children()->select(['id'])->andWhere(['depth'=>2])->asArray()->column();
                break;
            case 2://3级分类
                $ids=[$cat];
                break;
        }
        $goods=Goods::find()->where(['in','goods_category_id',$ids])->all();
        return $this->render('list',['goods'=>$goods]);

    }
    /**
     * 商品详情页
     */
    public function actionGoods($id){
        $good=Goods::find()->where(['id'=>$id])->one();
        $good->view_times=$good->view_times+1;
        $good->save();
        $button=GoodsCategory::findOne(['id'=>$good->goods_category_id]);
        //var_dump($button);die;
        $middle=GoodsCategory::findOne(['id'=>$button->parent_id]);
        $top=GoodsCategory::findOne(['id'=>$middle->parent_id]);
        $gallery=GoodsGallery::find()->where(['goods_id'=>$id])->asArray()->all();
        //var_dump($gallery[0]['path']);die;
        $content=GoodsIntro::findOne(['goods_id'=>$id]);
        return $this->render('goods',['good'=>$good,'middle'=>$middle,'top'=>$top,'gallery'=>$gallery,'content'=>$content]);
    }
    /**
     * 加入购物车成功提示(加入购物车)
     */
    public function actionAddToCart($good_id,$amount){
        if(\Yii::$app->user->isGuest){//未登录
            $cookies=\Yii::$app->request->cookies;
            $value=$cookies->getValue('carts');
            if($value){
                $carts=unserialize($value);
            }else{
                $carts=[];
            }
            //如果购物车存在该商品 则该商品的数量累加
            if(array_key_exists($good_id,$carts)){
                $carts[$good_id]+=$amount;
            }else{
                $carts[$good_id]=$amount;
            }
            //保存到cookie
            $cookie=new Cookie();
            $cookie->name='carts';
            $cookie->value=serialize($carts);
            $cookies=\Yii::$app->response->cookies;
            //$cookies->remove('carts');
            $cookies->add($cookie);
            return $this->redirect(['goods/success']);
        }else{
            //已登录,购物车数据存放到数据库
            $model=new Cart();
            $member_id=\Yii::$app->user->id;
            $result=Cart::findOne(['member_id'=>$member_id,'goods_id'=>$good_id]);
            if($result){//如果用户已存该商品 累加
                $result->amount+=$amount;
                $result->save();
            }else{
                $model->amount=$amount;
                $model->member_id=$member_id;
                $model->goods_id=$good_id;
                $model->save();
            }
            return $this->redirect(['goods/success']);
        }
    }
    /**
     * 成功页面
     */
    public function actionSuccess(){
        return $this->render('success');
    }
    /**
     * 购物车页面
     */
    public function actionCart(){
        if(\Yii::$app->user->isGuest){//未登录
            $cookies=\Yii::$app->request->cookies;
            $value=$cookies->getValue('carts');
            if($value){
                $carts=unserialize($value);
            }else{
                $carts=[];
            }
        }else{
            //从数据库中获取购物车信息
            $member_id=\Yii::$app->user->id;
            $cart=Cart::find()->where(['member_id'=>$member_id])->all();
            $carts=[];
            foreach ($cart as $v){
                $carts[$v->goods_id]=$v->amount;
            }
        }
        return $this->render('cart',['carts'=>$carts]);
    }
    /**
     * ajax操作购物车
     */
    public function actionAjaxCart($goods_id,$amount){
        if(\Yii::$app->user->isGuest){
            //获取cookie中的购物车
            $cookies = \Yii::$app->request->cookies;
            $value = $cookies->getValue('carts');
            if($value){
                $carts = unserialize($value);
            }else{
                $carts = [];
            }
            if($amount){
                $carts[$goods_id] = $amount;
            }else{
                unset($carts[$goods_id]);
            }
            //将购物车数据保存到cookie
            $cookie = new Cookie();
            $cookie->name = 'carts';
            $cookie->value = serialize($carts);
            $cookies = \Yii::$app->response->cookies;
            $cookies->add($cookie);
        }else{//如果登录将购物车数据添加到数据库
            //已登录,购物车数据存放到数据库
            $model=new Cart();
            $member_id=\Yii::$app->user->id;
            $result=Cart::findOne(['member_id'=>$member_id,'goods_id'=>$goods_id]);
            if($result){//如果用户已存该商品 累加
                $result->amount=$amount;
                $result->save();
            }else{
                $model->amount=$amount;
                $model->member_id=$member_id;
                $model->goods_id=$goods_id;
                $model->save();
            }
        }
    }
    public function actionAjaxDelete($goods_id,$amount){
        if(\Yii::$app->user->isGuest){
            //获取cookie中的购物车
            $cookies = \Yii::$app->request->cookies;
            $value = $cookies->getValue('carts');
            if($value){
                $carts = unserialize($value);
            }else{
                $carts = [];
            }
            if($amount){
                $carts[$goods_id] = $amount;
            }else{
                unset($carts[$goods_id]);
                //将购物车数据保存到cookie
                $cookie = new Cookie();
                $cookie->name = 'carts';
                $cookie->value = serialize($carts);
                $cookies = \Yii::$app->response->cookies;
                $cookies->add($cookie);
                return 'success';
            }
        }else{
            $result=Cart::findOne(['member_id'=>\Yii::$app->user->id,'goods_id'=>$goods_id]);
            if($result){//如果用户已存该商品 累加
                $result->delete();
                return 'success';
            }
        }
    }
}
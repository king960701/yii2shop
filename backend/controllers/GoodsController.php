<?php

namespace backend\controllers;

use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use backend\models\SearchForm;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use yii\data\Pagination;
use yii\web\UploadedFile;

class GoodsController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $request=\Yii::$app->request;
        if($request->isGet){
            $name=$request->get('SearchForm')['name']??'';
            $sn=$request->get('SearchForm')['sn']??'';
        }
        $query=Goods::find()->where(['status'=>1])->andWhere(['and',['like','name',$name],['like','sn',$sn]]);
        //分页工具类
        $pager=new Pagination();
        //总条数
        $pager->totalCount=$query->count();
        //每页显示多少条
        $pager->defaultPageSize=6;
        $goods=$query->offset($pager->offset)->limit($pager->limit)->all();
        $model=new SearchForm();
        $model->name=$name;
        $model->sn=$sn;
        return $this->render('index',['goods'=>$goods,'pager'=>$pager,'model'=>$model]);
    }
    public function actionAdd(){
        $request=\Yii::$app->request;
        //创建模型对象
        $model=new Goods();
        $intro=new GoodsIntro();
        $day=GoodsDayCount::find()->orderBy(['day'=>SORT_DESC])->limit(1)->one();
        //var_dump(strtotime($day->day));die;
        $addDay=new GoodsDayCount();
        $create=date('Y-m-d',time());
//        var_dump(strtotime($day->day));
//        var_dump(strtotime($create));die;
        if (empty($day)){
            //保存最新的day
            $addDay->day=$create;
            $addDay->count=0;
            $addDay->save();
        }
        if(strtotime($day->day) != strtotime($create)){
            //保存最新的day
            $addDay->day=$create;
            $addDay->count=0;
            $addDay->save();
        }else{
            //更新count添加数
            $day->count++;
            //$day->save();
        }


        if($request->isPost){
            //接收保存数据
            $model->load($request->post());
            $intro->load($request->post());
            if ($model->validate() && $intro->validate()){
                $model->create_time=time();
                $model->save();
                $intro->goods_id=$model->id;
                $intro->save();
                $day->save();
                \Yii::$app->session->setFlash('success','添加成功!');
                return $this->redirect(['goods/index']);
            }else {
                var_dump(
                    $model->getErrors()
                );
                die;
            }
        }
        //分类
        $code=date('Ymd',time()).sprintf('%06s',$day->count);
        $nodes=GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        $nodes[]=['id'=>0,'parent_id'=>0,'name'=>'顶级分类'];
        return $this->render('add',['model'=>$model,'intro'=>$intro,'nodes'=>json_encode($nodes),'code'=>$code]);
    }
    public function actions()
    {
        $time=time().uniqid();
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix"  => "http://admin.yii2shop.cn",//图片访问路径前缀
                    "imagePathFormat" => "/upload/image/{$time}",//上传保存路径
                    "imageRoot" => \Yii::getAlias("@webroot")
                ]
            ]
        ];
    }
    public function actionDelete($id){
        \Yii::$app->db->createCommand("update `goods` set `status`=0 where `id`={$id}")->execute();
        \Yii::$app->session->setFlash('success','删除成功,已添加到回收站!');
        return $this->redirect(['goods/index']);
    }
    public function actionEdit($id){
        $request=\Yii::$app->request;
        //创建模型对象
        $model=Goods::findOne(['id'=>$id]);
        $intro=GoodsIntro::findOne(['goods_id'=>$id]);
        if($request->isPost){
            //接收保存数据
            $model->load($request->post());
            $intro->load($request->post());
            if ($model->validate() && $intro->validate()){
                //$model->create_time=time();
                $model->save();
                $intro->goods_id=$model->id;
                $intro->save();
                \Yii::$app->session->setFlash('success','添加成功!');
                return $this->redirect(['goods/index']);
            }else {
                var_dump(
                    $model->getErrors()
                );
                die;
            }
        }
        //分类
        $nodes=GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        $nodes[]=['id'=>0,'parent_id'=>0,'name'=>'顶级分类'];
        return $this->render('edit',['model'=>$model,'intro'=>$intro,'nodes'=>json_encode($nodes)]);
    }
    public function actionRecycle(){
        $query=Goods::find()->where(['status'=>0]);
        //分页工具类
        $pager=new Pagination();
        //总条数
        $pager->totalCount=$query->count();
        //每页显示多少条
        $pager->defaultPageSize=6;
        //执行查询
        $goods=$query->offset($pager->offset)->limit($pager->limit)->all();
        return $this->render('recycle',['goods'=>$goods,'pager'=>$pager]);
    }
    public function actionRecover($id){
        \Yii::$app->db->createCommand("update `goods` set `status`=1 where `id`={$id}")->execute();
        \Yii::$app->session->setFlash('success','恢复成功!');
        return $this->redirect(['goods/recycle']);
    }
    public function actionPhoto($id){
        $model=GoodsGallery::find()->where(['goods_id'=>$id])->all();
        return $this->render('photo',['model'=>$model]);
    }
    public function actionGallery($goods_id){
        //var_dump($_POST['url']);
        $model=new GoodsGallery();
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->goods_id=$goods_id;
            $model->path=$request->post('url');
            $result=$model->save();
            if($result){
                return json_encode(['url'=>$model->path,'id'=>$model->id]);
            }
        }
    }
    public function actionDel($id){
        $model=GoodsGallery::findOne(['id'=>$id]);
        if($model){
            if(!$model->delete()){
                return 'fail';
            }
        }

        return 'success';

    }
    public function actionSee($id){
        $content=GoodsIntro::findOne(['goods_id'=>$id]);
        $photo=GoodsGallery::find()->where(['goods_id'=>$id])->asArray()->all();
        return $this->render('see',['content'=>$content,'photo'=>$photo]);
    }
}

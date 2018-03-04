<?php
/**
 * @var $this \yii\web\View
 */
//使用表单组件配合表单模型创建表单
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'sn')->textInput(['readonly'=>"readonly"]);
echo $form->field($model,'logo')->hiddenInput();
//引入js和css
$this->registerCssFile('@web/webuploader-0.1.5/webuploader.css');
$this->registerJsFile('@web/webuploader-0.1.5/webuploader.js',[
    //解决依赖
    'depends'=>\yii\web\JqueryAsset::className()
]);
//html
echo <<<HTML
<!--dom结构部分-->
<div id="uploader-demo">
    <!--用来存放item-->
    <div id="fileList" class="uploader-list"></div>
    <div id="filePicker">选择图片</div>
</div>
HTML;
//js
$logo_upload_url=\yii\helpers\Url::to(['brand/logo-upload']);
$this->registerJs(
    <<<JS
    // 初始化Web Uploader
var uploader = WebUploader.create({

    // 选完文件后，是否自动上传。
    auto: true,

    // swf文件路径
    swf: '/webuploader-0.1.5/Uploader.swf',

    // 文件接收服务端。
   server: '{$logo_upload_url}',

    // 选择文件的按钮。可选。
    // 内部根据当前运行是创建，可能是input元素，也可能是flash.
    pick: '#filePicker',

    // 只允许选择图片文件。
    accept: {
        title: 'Images',
        extensions: 'gif,jpg,jpeg,bmp,png',
        mimeTypes: 'image/*'
    }
});
// 文件上传成功，给item添加成功class, 用样式标记上传成功。
uploader.on( 'uploadSuccess', function( file,response ) {
    //$( '#'+file.id ).addClass('upload-state-done');
    var imgUrl=response.url;
    $('#goods-logo').val(imgUrl);
    //图片回显
    $('#logo_view').attr('src',imgUrl);
    $('#logo_view').attr('width','120px');
});
JS
);
if($model->logo){
    echo '<img id="logo_view" src="'.$model->logo.'" width="120px">';
}else{
    echo '<img id="logo_view" width="120px">';
}

echo $form->field($model,'goods_category_id')->hiddenInput();
//引入css文件
//$this->registerCssFile('@web/ztree/css/demoStyle/demo.css');
$this->registerCssFile('@web/ztree/css/zTreeStyle/zTreeStyle.css');
$this->registerJsFile('@web/ztree/js/jquery.ztree.core.js',[
    'depends'=>\yii\web\JqueryAsset::className()
]);
//js代码
$this->registerJs(<<<JS
 var zTreeObj;
   // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
   var setting = {
       data: {
		simpleData: {
			enable: true,
			idKey: "id",
			pIdKey: "parent_id",
			rootPId: 0
		}
       },
		callback: {
		onClick:function(event, treeId, treeNode){
		        //将点击的id写入到parent_id
		        $("#goods-goods_category_id").val(treeNode.id);
	     }
	
       }
   };
   // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
   var zNodes = {$nodes};

    zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
    zTreeObj.expandAll(true);
    zTreeObj.selectNode(zTreeObj.getNodeByParam("id", "{$model->goods_category_id}", null));
JS
);

//HTML代码
echo '<div>
   <ul id="treeDemo" class="ztree"></ul>
</div>';

echo $form->field($model,'market_price')->textInput();
echo $form->field($model,'shop_price')->textInput();
echo $form->field($model,'stock')->textInput();
$model->is_on_sale=1;
echo $form->field($model,'is_on_sale',['inline'=>1])->radioList([1=>'在售',0=>'停售']);
echo $form->field($model,'sort')->textInput();
echo $form->field($model,'brand_id')->dropDownList(\backend\models\Goods::getBrandName());

echo $form->field($intro,'content')->widget('kucha\ueditor\UEditor',[]);
if($model->getIsNewRecord()){
    echo '<button type="submit" class="btn btn-info">添加</button>';
}else{
    echo '<button type="submit" class="btn btn-info">更新</button>';
}
\yii\bootstrap\ActiveForm::end();
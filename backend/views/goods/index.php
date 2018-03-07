<h1>
    商品列表
</h1>
<?php
$form =\yii\bootstrap\ActiveForm::begin(['layout'=>'inline','method'=>'get']);
echo $form->field($model,'name')->textInput(['placeholder'=>'关键字'])->label(false);
echo $form->field($model,'sn')->textInput(['placeholder'=>'货号'])->label(false);
echo $form->field($model,'min')->textInput(['placeholder'=>'¥'])->label(false);
echo $form->field($model,'max')->textInput(['placeholder'=>'¥'])->label(false);
echo '<button type="submit" class="btn btn-default glyphicon glyphicon-search"  style="font-size: 12px;">搜索</button>';
echo '<a href="index" class="btn btn-default glyphicon glyphicon-refresh"  style="font-size: 12px;">重置</a>';
\yii\bootstrap\ActiveForm::end();
?>
<table class="table table-bordered" style="text-align: center">
    <tr>
        <th style="text-align: center">id</th>
        <th style="text-align: center">商品名称</th>
        <th style="text-align: center">货号</th>
        <th style="text-align: center">LOGO图片</th>
        <th style="text-align: center">商品分类id</th>
        <th style="text-align: center">品牌分类</th>
        <th style="text-align: center">市场价格</th>
        <th style="text-align: center">商品价格</th>
        <th style="text-align: center">库存</th>
        <th style="text-align: center">是否在售</th>
        <th style="text-align: center">添加时间</th>
        <th style="text-align: center">浏览次数</th>
        <th style="text-align: center">操作</th>
    </tr>
    <?php foreach($goods as $good):?>
        <tr data-id="<?=$good->id?>">
            <td><?=$good->id?></td>
            <td><?=$good->name?></td>
            <td><?=$good->sn?></td>
            <td><img src="<?=$good->logo?>" alt="" width="120px"></td>
            <td><?=$good->categoryName->name?></td>
            <td><?=$good->brand->name?></td>
            <td><?=$good->market_price?></td>
            <td><?=$good->shop_price?></td>
            <td><?=$good->stock?></td>
            <td><?=$good->is_on_sale?'<a href="#" class="btn btn-success" style="font-size: 12px">在售</a>':'<a href="#" class="btn btn-danger" style="font-size: 12px">下架</a>'?></td>
            <td><?=date('Y-m-d H:i:s',$good->create_time)?></td>
            <td><?=$good->view_times?></td>
            <td>
                <?=\yii\helpers\Html::a('照片',['goods/photo','id'=>$good->id],['class'=>'btn btn-primary glyphicon glyphicon-picture','style'=>"font-size: 12px;"]);?>
                <a href="javascript:;" style="font-size: 12px;" class="btn btn-danger glyphicon glyphicon-trash">删除</a>
                <?=\yii\helpers\Html::a('修改',['goods/edit','id'=>$good->id],['class'=>'btn btn-info glyphicon glyphicon-pencil','style'=>"font-size: 12px;"]);?>
                <?=\yii\helpers\Html::a('查看',['goods/see','id'=>$good->id],['class'=>'btn btn-warning glyphicon glyphicon-eye-open','style'=>"font-size: 12px;"]);?>
            </td>
        </tr>
    <?php endforeach;?>

</table>
<div>
    <?=\yii\helpers\Html::a('添加',['goods/add'],['class'=>'btn btn-success glyphicon glyphicon-plus-sign','style'=>"font-size: 12px;"]);?>
    <?=\yii\helpers\Html::a('回收站',['goods/recycle'],['class'=>'btn btn-primary glyphicon glyphicon-refresh','style'=>"font-size: 12px;"]);?>

</div>

<?php
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager,
    'nextPageLabel'=>'下一页',
    'prevPageLabel'=>'上一页',
    'firstPageLabel'=>'第一页',
    'lastPageLabel'=>'最后页'
]);
$del=\yii\helpers\Url::to(['goods/delete']);
$this->registerJs(
        <<<JS
$('.table').on('click','.btn-danger',function(){
    var tr=$(this).closest('tr');
            var id=tr.attr('data-id');
            //删除二次确认
    layer.confirm('确认删除？', {
            btn: ['确认','取消'] //按钮
        }, function(){
            $.get('{$del}',{id:id},function(data){
                if(data=='success'){
                tr.remove();
            }else {
                    layer.msg(data.msg);
                }
            });
            layer.msg('删除成功!', {icon: 1});
        }, function(){
            layer.msg('成功取消!', {
            });
        });
})
JS
);

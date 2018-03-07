<table class="table table-bordered" style="text-align: center">
    <tr>
        <th style="text-align: center">id</th>
        <th style="text-align: center">名称</th>
        <th style="text-align: center">简介</th>
        <th style="text-align: center">LOGO图片</th>
        <th style="text-align: center">排序</th>
        <th style="text-align: center">操作</th>
    </tr>
    <?php foreach($brands as $brand):?>
        <tr data-id="<?=$brand->id?>">
            <td><?=$brand->id?></td>
            <td><?=$brand->name?></td>
            <td><?=$brand->intro?></td>
            <td><img src="<?=$brand->logo?>" alt="" width="100px"></td>
            <td><?=$brand->sort?></td>
            <td>
                <a href="javascript:;" style="font-size: 12px;" class="btn btn-danger glyphicon glyphicon-trash">删除</a>
                <?=\yii\helpers\Html::a('修改',['brand/edit','id'=>$brand->id],['class'=>'btn btn-info glyphicon glyphicon-pencil','style'=>"font-size: 12px;"]);?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?=\yii\helpers\Html::a('添加',['brand/add'],['class'=>'btn btn-primary glyphicon glyphicon-plus-sign','style'=>"font-size: 12px;"]);?>
<?php
$del=\yii\helpers\Url::to(['brand/delete']);
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

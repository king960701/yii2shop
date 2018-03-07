<table class="table table-bordered" style="text-align: center">
    <tr>
        <th style="text-align: center">id</th>
        <th style="text-align: center">名称</th>
        <th style="text-align: center">简介</th>
        <th style="text-align: center">文章分类id</th>
        <th style="text-align: center">排序</th>
        <th style="text-align: center">创建时间</th>
        <th style="text-align: center">操作</th>
    </tr>
    <?php foreach($article as $row):?>
        <tr data-id="<?=$row->id?>">
            <td><?=$row->id?></td>
            <td><?=$row->name?></td>
            <td><?=$row->intro?></td>
            <td><?=$row->categoryName->name?></td>
            <td><?=$row->sort?></td>
            <td><?=date('Y-m-d H:i:s',$row->create_time)?></td>
            <td>
                <a href="javascript:;" style="font-size: 12px;" class="btn btn-danger glyphicon glyphicon-trash">删除</a>
                <?=\yii\helpers\Html::a('修改',['article/edit','id'=>$row->id],['class'=>'btn btn-info']);?>
                <?=\yii\helpers\Html::a('查看',['article/detail','id'=>$row->id],['class'=>'btn btn-warning']);?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?=\yii\helpers\Html::a('添加',['article/add'],['class'=>'btn btn-primary']);?>
<?php
$del=\yii\helpers\Url::to(['article/delete']);
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

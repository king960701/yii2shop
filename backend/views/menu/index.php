<table class="table table-bordered">
    <tr>
        <th>名称</th>
        <th>路由</th>
        <th>操作</th>
    </tr>

    <?php foreach($menus as $menu):?>
        <tr data-id="<?=$menu->id?>">
            <td><?=$menu->name?></td>
            <td><?=$menu->url?></td>
            <td>
                <a href="javascript:;" style="font-size: 12px;" class="btn btn-danger glyphicon glyphicon-trash">删除</a>
                <?=\yii\helpers\Html::a('修改',['menu/edit','id'=>$menu->id],['class'=>'btn btn-info glyphicon glyphicon-pencil','style'=>"font-size: 12px;"]);?>
            </td>
        </tr>
        <?php $childMenus=\backend\models\Menu::find()->where(['parent_id'=>$menu->id])->all();?>
        <?php if($childMenus):?>
            <?php foreach ($childMenus as $child):?>
                <tr data-id="<?=$child->id?>">
                    <td>┉┉<?=$child->name?></td>
                    <td><?=$child->url?></td>
                    <td>
                        <a href="javascript:;" style="font-size: 12px;" class="btn btn-danger glyphicon glyphicon-trash">删除</a>
                        <?=\yii\helpers\Html::a('修改',['menu/edit','id'=>$child->id],['class'=>'btn btn-info glyphicon glyphicon-pencil','style'=>"font-size: 12px;"]);?>
                    </td>
                </tr>
            <?php endforeach;?>
        <?php endif;?>
    <?php endforeach;?>
</table>
    <?=\yii\helpers\Html::a('添加',['menu/add'],['class'=>'btn btn-primary glyphicon glyphicon-plus-sign','style'=>"font-size: 12px;"]);?>
<?php
$del=\yii\helpers\Url::to(['menu/delete']);
$this->registerJs(
    <<<JS
$('.display').on('click','.btn-danger',function(){
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
                layer.msg('删除失败', {icon: 1});
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


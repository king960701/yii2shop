<table class="table table-bordered" style="text-align: center">
    <tr>
        <th style="text-align: center">id</th>
        <th style="text-align: center">用户名</th>
        <th style="text-align: center">邮箱</th>
        <th style="text-align: center">创建时间</th>
        <th style="text-align: center">修改时间</th>
        <th style="text-align: center">最后登录时间</th>
        <th style="text-align: center">最后登录ip</th>
        <th style="text-align: center">操作</th>
    </tr>
    <?php foreach($admin as $row):?>
        <tr data-id="<?=$row->id?>">
            <td><?=$row->id?></td>
            <td><?=$row->username?></td>
            <td><?=$row->email?></td>
            <td><?=date('Y-m-d H:i:s',$row->created_at)?></td>
            <td><?=$row->updated_at?date('Y-m-d H:i:s',$row->updated_at):''?></td>
            <td><?=$row->last_login_time?date('Y-m-d H:i:s',$row->last_login_time):''?></td>
            <td><?=long2ip($row->last_login_ip)?></td>
            <td>
                <a href="javascript:;" style="font-size: 12px;" class="btn btn-danger glyphicon glyphicon-trash">删除</a>
                <?=\yii\helpers\Html::a('修改',['admin/update','id'=>$row->id],['class'=>'btn btn-info glyphicon glyphicon-pencil','style'=>"font-size: 12px;"]);?>
                <?=\yii\helpers\Html::a('重置密码',['admin/re-password','id'=>$row->id],['class'=>'btn btn-primary glyphicon glyphicon-refresh','style'=>"font-size: 12px;"]);?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?=\yii\helpers\Html::a('添加',['admin/add'],['class'=>'btn btn-success glyphicon glyphicon-plus-sign','style'=>"font-size: 12px;"]);?>
<?php
$del=\yii\helpers\Url::to(['admin/delete']);
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

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
        <tr>
            <td><?=$row->id?></td>
            <td><?=$row->username?></td>
            <td><?=$row->email?></td>
            <td><?=date('Y-m-d H:i:s',$row->created_at)?></td>
            <td><?=$row->updated_at?date('Y-m-d H:i:s',$row->updated_at):''?></td>
            <td><?=$row->last_login_time?date('Y-m-d H:i:s',$row->last_login_time):''?></td>
            <td><?=long2ip($row->last_login_ip)?></td>
            <td>
                <?=\yii\helpers\Html::a('删除',['admin/delete','id'=>$row->id],['class'=>'btn btn-danger glyphicon glyphicon-trash','style'=>"font-size: 12px;"]);?>
                <?=\yii\helpers\Html::a('修改',['admin/edit','id'=>$row->id],['class'=>'btn btn-info glyphicon glyphicon-pencil','style'=>"font-size: 12px;"]);?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?=\yii\helpers\Html::a('添加',['admin/add'],['class'=>'btn btn-success glyphicon glyphicon-plus-sign','style'=>"font-size: 12px;"]);?>
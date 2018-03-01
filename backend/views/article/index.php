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
        <tr>
            <td><?=$row->id?></td>
            <td><?=$row->name?></td>
            <td><?=$row->intro?></td>
            <td><?=$row->categoryName->name?></td>
            <td><?=$row->sort?></td>
            <td><?=date('Y-m-d H:i:s',$row->create_time)?></td>
            <td><?=\yii\helpers\Html::a('删除',['article/delete','id'=>$row->id],['class'=>'btn btn-danger']);?>
                <?=\yii\helpers\Html::a('修改',['article/edit','id'=>$row->id],['class'=>'btn btn-info']);?>
                <?=\yii\helpers\Html::a('查看',['article/detail','id'=>$row->id],['class'=>'btn btn-warning']);?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?=\yii\helpers\Html::a('添加',['article/add'],['class'=>'btn btn-primary']);?>
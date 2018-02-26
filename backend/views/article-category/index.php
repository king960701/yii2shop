<table class="table table-bordered" style="text-align: center">
    <tr>
        <th style="text-align: center">id</th>
        <th style="text-align: center">名称</th>
        <th style="text-align: center">简介</th>
        <th style="text-align: center">排序</th>
        <th style="text-align: center">操作</th>
    </tr>
    <?php foreach($model as $row):?>
        <tr>
            <td><?=$row->id?></td>
            <td><?=$row->name?></td>
            <td><?=$row->intro?></td>
            <td><?=$row->sort?></td>
            <td><?=\yii\helpers\Html::a('删除',['article-category/delete','id'=>$row->id],['class'=>'btn btn-danger']);?>
                <?=\yii\helpers\Html::a('修改',['article-category/edit','id'=>$row->id],['class'=>'btn btn-info']);?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?=\yii\helpers\Html::a('添加',['article-category/add'],['class'=>'btn btn-primary']);?>
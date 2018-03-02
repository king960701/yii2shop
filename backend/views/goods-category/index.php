<table class="table table-bordered" style="text-align: center">
    <tr>
        <th style="text-align: center">id</th>
        <th style="text-align: center">树id</th>
        <th style="text-align: center">左值</th>
        <th style="text-align: center">右值</th>
        <th style="text-align: center">层级</th>
        <th style="text-align: center">名称</th>
        <th style="text-align: center">上级分类id</th>
        <th style="text-align: center">简介</th>
        <th style="text-align: center">操作</th>
    </tr>
    <?php foreach($model as $row):?>
        <tr>
            <td><?=$row->id?></td>
            <td><?=$row->tree?></td>
            <td><?=$row->lft?></td>
            <td><?=$row->rgt?></td>
            <td><?=$row->depth?></td>
            <td><?=$row->name?></td>
            <td><?=$row->parent_id?></td>
            <td><?=$row->intro?></td>
            <td><?=\yii\helpers\Html::a('删除',['goods-category/delete','id'=>$row->id],['class'=>'btn btn-danger']);?>
                <?=\yii\helpers\Html::a('修改',['goods-category/edit','id'=>$row->id],['class'=>'btn btn-info']);?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?=\yii\helpers\Html::a('添加',['goods-category/add'],['class'=>'btn btn-primary']);?>
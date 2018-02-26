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
        <tr>
            <td><?=$brand->id?></td>
            <td><?=$brand->name?></td>
            <td><?=$brand->intro?></td>
            <td><img src="<?=$brand->logo?>" alt="" width="100px"></td>
            <td><?=$brand->sort?></td>
            <td><?=\yii\helpers\Html::a('删除',['brand/delete','id'=>$brand->id],['class'=>'btn btn-danger']);?>
                <?=\yii\helpers\Html::a('修改',['brand/edit','id'=>$brand->id],['class'=>'btn btn-info']);?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?=\yii\helpers\Html::a('添加',['brand/add'],['class'=>'btn btn-primary']);?>
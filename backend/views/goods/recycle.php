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
        <tr>
            <td><?=$good->id?></td>
            <td><?=$good->name?></td>
            <td><?=$good->sn?></td>
            <td><img src="<?=$good->logo?>" alt="" width="100px"></td>
            <td><?=$good->categoryName->name?></td>
            <td><?=$good->brand->name?></td>
            <td><?=$good->market_price?></td>
            <td><?=$good->shop_price?></td>
            <td><?=$good->stock?></td>
            <td><?=$good->is_on_sale?'在售':'下架'?></td>
            <td><?=date('Y-m-d H:i:s',$good->create_time)?></td>
            <td><?=$good->view_times?></td>
            <td>
                <?=\yii\helpers\Html::a('恢复',['goods/recover','id'=>$good->id],['class'=>'btn btn-info glyphicon glyphicon-repeat','style'=>"font-size: 12px;"]);?>
            </td>
        </tr>
    <?php endforeach;?>

</table>
<div>

    <?=\yii\helpers\Html::a('返回',['goods/index'],['class'=>'btn btn-primary glyphicon glyphicon-refresh','style'=>"font-size: 12px;"]);?>
</div>

<?php
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager,
    'nextPageLabel'=>'下一页',
    'prevPageLabel'=>'上一页',
    'firstPageLabel'=>'第一页',
    'lastPageLabel'=>'最后页'
]);

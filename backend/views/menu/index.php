<?php
$this->registerCssFile('@web/DataTables-1.10.15/media/css/jquery.dataTables.css');
$this->registerJsFile('@web/DataTables-1.10.15/media/js/jquery.dataTables.js',[
    //解决依赖
    'depends'=>\yii\web\JqueryAsset::className()
]);
?>
    <table id="example" class="display">
        <thead>
        <tr>
            <th>名称</th>
            <th>路由</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($menus as $menu):?>
            <tr data-id="<?=$menu->id?>">
                <td><?=$menu->parent_id?'┉┉':''?><?=$menu->name?></td>
                <td><?=$menu->url?></td>
                <td>
                    <a href="javascript:;" style="font-size: 12px;" class="btn btn-danger glyphicon glyphicon-trash">删除</a>
                    <?=\yii\helpers\Html::a('修改',['menu/edit','id'=>$menu->id],['class'=>'btn btn-info glyphicon glyphicon-pencil','style'=>"font-size: 12px;"]);?>
                </td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
<?=\yii\helpers\Html::a('添加',['menu/add'],['class'=>'btn btn-primary glyphicon glyphicon-plus-sign','style'=>"font-size: 12px;"]);?>
<?php
$del=\yii\helpers\Url::to(['menu/delete']);
$this->registerJs(
    <<<JS
$('#example').DataTable({
    language: {
        "sProcessing": "处理中...",
        "sLengthMenu": "显示 _MENU_ 项结果",
        "sZeroRecords": "没有匹配结果",
        "sInfo": "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
        "sInfoEmpty": "显示第 0 至 0 项结果，共 0 项",
        "sInfoFiltered": "(由 _MAX_ 项结果过滤)",
        "sInfoPostFix": "",
        "sSearch": "搜索:",
        "sUrl": "",
        "sEmptyTable": "表中数据为空",
        "sLoadingRecords": "载入中...",
        "sInfoThousands": ",",
        "oPaginate": {
            "sFirst": "首页",
            "sPrevious": "上页",
            "sNext": "下页",
            "sLast": "末页"
        },
        "oAria": {
            "sSortAscending": ": 以升序排列此列",
            "sSortDescending": ": 以降序排列此列"
        }
    }
});
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

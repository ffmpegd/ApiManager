<?php
    defined('API') or exit();
    if(!is_supper()){die('只有超级管理员才可进行操作');}
?>

<!--接口排序管理start-->
<?php
    //操作类型{type}
    $type = $_GET['type'];
    if(empty($type)){
        //已经分类下的所有接口start
        $sql = "select * from user order by id desc";
        $users = select($sql);
    }else if($type == 'do'){
        $ord = count($_POST['api']);
        foreach($_POST['api'] as $v){
            $sql = "update api set ord = '{$ord}' where id='{$v}' and aid='{$_GET['tag']}'";
            update($sql);
            $ord--;
        }
        $url = U(array('act'=>'api','tag'=>$_GET['tag']));
        go($url);
    }
?>
<div style="border:1px solid #ddd;margin-bottom:20px;">
    <div style="background:#ffffff;padding:20px;">
        <h5 class="textshadow" >接口列表</h5>
            <table class="table">
                <thead>
                <tr>
                    <th class="col-md-2">昵称</th>
                    <th class="col-md-4">登录名称</th>
                    <th class="col-md-2">管理员</th>
                    <th class="col-md-2">状态</th>
                    <th class="col-md-2">操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($users as $user){?>
                    <tr>
                        <td><?php echo $user['nice_name'];?></td>
                        <td><?php echo $user['login_name'];?></td>
                        <td><?php echo $user['issuper'] ? "是" : '否'?></td>
                        <td><?php echo $user['isdel'] ? '冻结' : '正常';?></td>
                        <td>
                            <a href="<?php echo U(['act'=>'updateuser','id'=>$user['id']])?>"><button class="btn btn-success">编辑</button></a>
                            <a style="display: none;"><button class="btn "><?php echo $user['isdel'] ? '解冻' : '冻结'?></button>

                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
    </div>
</div>
<script>
    //上移
    function up(obj){
        var $TR = $(obj).parents('tr');
        var prevTR = $TR.prev();
        prevTR.insertAfter($TR);
        $('tr.info').removeClass('info');
        $TR.addClass('info');
        $TR.hide();
        $TR.show(300);
    }
    //下移
    function down(obj){
        var $TR = $(obj).parents('tr');
        var nextTR = $TR.next();
        nextTR.insertBefore($TR);
        $('tr.info').removeClass('info');
        $TR.addClass('info');
        $TR.hide();
        $TR.show(300);
    }
</script>
<!--接口排序管理end-->
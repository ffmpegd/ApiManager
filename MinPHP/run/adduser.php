<!--修改密码start-->
<?php defined('API') or exit('http://gwalker.cn');?>
<?php

if(!is_supper()){die('只有超级管理员才可添加用户操作');}

    $type= I($_GET['type']);


    if($type  == 'do'){
        $_VAL = I($_POST);
        $nice_name = $_VAL['nice_name'];
        $login_name = $_VAL['login_name'];
        $new_pwd = md5($_VAL['new_pwd']);
        $new_pwd2 = md5($_VAL['new_pwd2']);
        $aid = $_VAL['aid'];
        $is_admin = $_VAL['is_admin'];
        if ($new_pwd != $new_pwd2) {//判断新密码和确认密码是否一致
            echo '<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> 两次输入密码不一致</div>';
        } else {
            $addUserSql = "insert into user (nice_name,login_name,login_pwd,issuper) values('{nice_name}','{$login_name}','{$new_pwd}', '{$is_admin}')";            insert($sql);
            $re = insert($addUserSql);
            if($re !== false){
                go(U());
            }else{
                echo '<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> 用户创建失败</div>';
            }

        }
    }
?>
<div style="border:1px solid #ddd">
    <div style="background:#f5f5f5;padding:20px;position:relative">
        <h4>添加用户</h4>
        <div>
            <form action="?act=adduser&type=do" method="post">
                <div class="form-group">
                    <input type="text" class="form-control" name="nice_name" placeholder="昵称" required="required">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="login_name" placeholder="登录名" required="required">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="new_pwd" placeholder="密码" required="required">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="new_pwd2" placeholder="确认密码" required="required">
                </div>
                <div class="form-group">
                    <h4>超级管理员</h4>
                    <label>否 <input type="radio" name="is_admin" value="0" checked required=""> </label>
                    <label>是 <input type="radio" name="is_admin" value="1" required=""></label>
                </div>


                <button class="btn btn-success">保存</button>
            </form>
        </div>
    </div>
</div>
<!--修改密码end-->

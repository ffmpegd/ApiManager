<!--修改密码start-->
<?php defined('API') or exit('http://gwalker.cn');?>
<?php

if(!is_supper() || !isset($_GET['id'])){die('只有超级管理员才可添加用户操作');}

    $type= I($_GET['type']);
    $id= I($_GET['id']);
    $user = find('select * from user where id='.$id);
    $cates = select('select * from cate where isdel=0 order by addtime desc');
    $userCates = array_map(function($d) { return $d['aid'];}, select('select aid from auth where uid='.$id));

    if($type  == 'do'){
        $_VAL = I($_POST);
        $new_pwd = $_VAL['new_pwd'];
        $new_pwd2 = $_VAL['new_pwd2'];
        $nice_name = $_VAL['nice_name'];
        $isdel = $_VAL['isdel'];
        $aids = $_VAL['aid'];
        $is_admin = $_VAL['is_admin'];
        if (($new_pwd || $new_pwd2) && $new_pwd != $new_pwd2) {//判断新密码和确认密码是否一致
            echo '<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> 两次输入密码不一致</div>';
        } else {

            $updateUserSql = "update user set nice_name = '{$nice_name}',issuper = '{$is_admin}', isdel = '{$isdel}'";
            $new_pwd &&  $updateUserSql .= ", login_pwd ='".md5($new_pwd)."'";
            $updateUserSql .= ' where id='.$id;
            $re = update($updateUserSql);

            // 更新 auth 表
            // 1.查询交集
            // 新增的权限
            $newCates = array_diff($aids, $userCates);
             // 需要删除的权限
            $delCates = array_diff($userCates, $aids);
            if($delCates){
                $delAidSql = "delete from auth where uid={$id} and aid in (".implode(',',$delCates).")";
                $re =  delete($delAidSql);
            }
            if(!empty($newCates)){
                foreach($newCates as $aid){
                   $newCatesSql = "insert into auth (uid,aid) values('{$id}','{$aid}')";
                   $re = insert($newCatesSql);
                }
            }
            if($re !== false){
                go(U(['act'=>'userlist']));
            }else{
                echo '<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> 修改失败</div>';
            }

        }
    }
?>
<div style="border:1px solid #ddd">
    <div style="background:#f5f5f5;padding:20px;position:relative">
        <h4>编辑用户</h4>
        <div>
            <form action="?act=updateuser&type=do&id=<?=$user['id']?>" method="post">
                <div class="form-group">
                    <input type="text" class="form-control" name="nice_name" placeholder="昵称" required="required" value="<?=$user['nice_name']?>">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="login_name" placeholder="登录名" readonly value="<?=$user['login_name']?>">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="new_pwd" placeholder="密码: 不修改留空">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="new_pwd2" placeholder="确认密码: 不修改留空">
                </div>
                <div class="form-group">
                    <h4>超级管理员</h4>
                    <label>否 <input type="radio" name="is_admin" value="0" <?= $user['issuper'] ? '':'checked'?>> </label>
                    <label>是 <input type="radio" name="is_admin" value="1" <?= $user['issuper'] ? 'checked':''?> ></label>
                </div>
                <div class="form-group">
                    <h4>冻结</h4>
                    <label>否 <input type="radio" name="isdel" value="0" <?= $user['isdel'] ? '':'checked'?>> </label>
                    <label>是 <input type="radio" name="isdel" value="1" <?= $user['isdel'] ? 'checked':''?> ></label>
                </div>

                <div class="form-group">
                    <h4>权限：</h4>
                    <?php foreach ($cates as $key => $cate){ ?>
                    <label><?=$key+1?>. <?=$cate['cname']?> <input type="checkbox" name="aid[]" <?=in_array($cate['aid'],$userCates) ? 'checked' : ''?> value="<?=$cate['aid']?>">
                        <br>
                        <?php } ?>
                    </label>
                </div>

                <button class="btn btn-success">保存</button>
            </form>
        </div>
    </div>
</div>

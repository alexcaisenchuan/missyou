
<?php echo validation_errors(); ?>

<h2>登录</h2>

<?php echo form_open('missyou/action/login') ?>
    <label for="title">手机号</label> 
    <input type="text" name="phonenum" /><br />
    <input type="submit" name="submit" value="登录" /> 
</form>

<h2>注册</h2>

<?php echo form_open('missyou/action/register') ?>
    <label for="phonenum">手机号</label> 
    <input type="text" name="phonenum" /><br />
    
    <label for="name">名字</label> 
    <input type="text" name="name" /><br />
    
    <input type="submit" name="submit" value="注册" /> 
</form>

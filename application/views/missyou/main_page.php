<?php echo validation_errors(); ?>

<h2><?php echo $hint ?></h2>

<h2>欢迎你, <?php echo $name ?></h2>

<p><?php echo anchor("/missyou/action/logout", "退出登录"); ?> </p>

<h2>添加</h2>
<?php echo form_open('missyou/action/add') ?>
    <label for="title">手机号</label> 
    <input type="text" name="phonenum" /><br />
    <input type="submit" name="submit" value="添加" /> 
</form>

<h2>我想谁了</h2>
<?php echo form_open('missyou/action/fromMe') ?>
    <label for="title">手机号</label> 
    <input type="text" name="phonenum" /><br />
    <input type="submit" name="submit" value="查询" /> 
</form>

<h2>谁想我了</h2>
<?php echo form_open('missyou/action/toMe') ?>
    <label for="title">手机号</label> 
    <input type="text" name="phonenum" /><br />
    <input type="submit" name="submit" value="查询" /> 
</form>


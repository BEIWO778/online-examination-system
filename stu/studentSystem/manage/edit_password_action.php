<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>正在修改密码</title>
</head>
<body>
<?php include '../../config.php';?>
<?php
    $oldpassword=$_POST["old_password"];
    $newpassword=$_POST["new_password"];
    $confirmpassword=$_POST["confirm_password"];
    
        $result=mysqli_query($con,"select * from student_account where student_id='${_SESSION["student_id"]}'");
		$bookinfo=mysqli_fetch_array($result);
        $dbpassword=$bookinfo["student_password"];
        if($dbpassword!=$oldpassword){
?>
<script type="text/javascript">
            alert("输入的原密码不正确！");
            window.location.href="edit_password.php";
</script>
<?php
        }
        if($newpassword!=$confirmpassword){
?>
<script type="text/javascript">
            alert("两次输入的密码不匹配！");
            window.location.href="edit_password.php";
</script>
<?php
        }
        if(($dbpassword==$oldpassword)&&($newpassword==$confirmpassword)){
			mysqli_query($con,"update student_account set student_password='{$newpassword}' where student_id='${_SESSION["student_id"]}'") or die ( "修改密码失败，请检查你的内容！".'<br>'."错误信息：". mysqli_error ($con));
        }
?>
<script type="text/javascript">
        alert("密码修改成功！");
        window.location.href="edit_password.php";
</script>
<?php mysqli_close($con);?>
</body>
</html>
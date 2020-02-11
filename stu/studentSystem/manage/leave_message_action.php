<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>发送留言中</title>
</head>
<body>
<?php include '../../config.php';?>
<?php
    $teacher_id_temp=$_POST['teacher_id'];
    $message_contents=$_POST['message_contents'];
    $teacher_id=substr($teacher_id_temp,0,strrpos($teacher_id_temp,' - '));
    $message_time=date("Y-m-d h:i:s");
	//正常老师学生均未删除留言
    $del=0;
        $result=mysqli_query($con,"select * from student_account where student_id='${_SESSION["student_id"]}'");
        $bookinfo=mysqli_fetch_array($result);
        $student_id=$bookinfo['student_id'];
        mysqli_free_result($result);
        mysqli_query($con,"insert into student_messages values('{$student_id}','{$teacher_id}','{$message_time}','{$message_contents}','{$del}');") or die ( "发送失败，请检查你的内容！".'<br>'."错误信息：". mysqli_error ($con));
?>
<script type="text/javascript">
        alert("发送留言成功！");
        window.location.href="leave_message.php";
</script>
<?php mysqli_close($con);?>
</body>
</html>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>删除留言中</title>
</head>
<body>
<?php include '../../config.php';?>
<?php
    $student_id=$_GET['student_id'];
    $teacher_id=$_GET['teacher_id'];
    $message_time=$_GET['message_time'];
    $result=mysqli_query($con,"select * from teacher_message where student_id='{$student_id}' and teacher_id='{$teacher_id}' and message_time='{$message_time}'");
	$temp=mysqli_fetch_array($result);
	$del=$temp["del"];//获取信息:是否已被老师删除了留言记录
	if($del==0)    
        mysqli_query($con,"update teacher_message set del=1 where student_id='{$student_id}' and teacher_id='{$teacher_id}' and message_time='{$message_time}'") or die ( "删除失败，请检查你的内容！".'<br>'."错误信息：". mysqli_error ($con));
	else
		mysqli_query($con,"delete from teacher_message where student_id='{$student_id}' and teacher_id='{$teacher_id}' and message_time='{$message_time}'")or die ( "删除失败，请检查你的内容！".'<br>'."错误信息：". mysqli_error ($con));

?>
<script type="text/javascript">
        alert("删除留言成功！");
        window.location.href="leave_message.php";
</script>

<?php mysqli_close($con);?>
</body>
</html>
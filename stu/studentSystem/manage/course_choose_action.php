<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>选择课程中</title>
</head>
<body>
<?php include '../../config.php';?>
<?php
    $course_id=$_GET['id'];
    

  
        $result=mysqli_query($con,"select * from student_account where student_id='${_SESSION["student_id"]}'");
        $bookinfo=mysqli_fetch_array($result);
        $student_id=$bookinfo["student_id"];
        $result=mysqli_query($con,"select * from student_course where student_id='{$student_id}'");
        $bookinfo=mysqli_fetch_array($result);
        if($bookinfo){
			mysqli_query($con,"update student_course set course_id='{$course_id}' where student_id='{$student_id}'") or die ( "选择课程失败，请检查你的内容！".'<br>'."错误信息：". mysqli_error ($con));
        }else{
			mysqli_query($con,"insert into student_course values('{$student_id}','{$course_id}');") or die ( "选择课程失败，请检查你的内容！".'<br>'."错误信息：". mysqli_error ($con));
        }
?>
<script type="text/javascript">
        alert("选择该课程成功！");
        window.location.href="course_choose.php";
</script>

<?php mysqli_close($con);?>
</body>
</html>
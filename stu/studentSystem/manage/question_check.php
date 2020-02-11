<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>检查题目中</title>
</head>
<body>
<?php include '../../config.php';?>
<?php
    $student_id=$_GET['student_id'];
    $question_no=$_GET['question_no'];
    $teacher_id=$_GET['teacher_id'];
    $course_id=$_GET['course_id'];
	//choice是选择题提交的答案
    $choice=$_GET['choice'];
    $degree=$_GET['degree'];
	//fill_blank是填空题提交的答案
	$fill_blank=$_GET['fill_blank'];
    $question_do_time=date("Y-m-d h:i:s");
	$result=mysqli_query($con,"select * from {$course_id}_questions where id='{$question_no}'");
    $bookinfo=mysqli_fetch_array($result);
    $correct_choice=$bookinfo['question_correct_answer'];
	//去掉doit,用mysqli_num_rows控制
	$result1=mysqli_query($con,"select * from do_question where student_id='{$student_id}' and question_no='{$question_no}' and teacher_id='{$teacher_id}' and course_id='{$course_id}' and set_of_question='{$degree}'");	
    if(($choice==$correct_choice||$fill_blank==$correct_choice)&&(mysqli_num_rows($result1)==0)){
        mysqli_query($con,"insert into do_question values ('{$student_id}','{$question_no}','{$teacher_id}','{$course_id}','{$question_do_time}','1','{$degree}');") or die ( "写入做题记录失败，请检查你的内容！".'<br>'."错误信息：". mysqli_error ($con));
?>
          <script type="text/javascript">
            alert("答案正确！");
            window.location.href="do_question.php?degree=<?php echo $degree ?>";
          </script>
<?php	
    }else if($choice!=$correct_choice&&(mysqli_num_rows($result1)==0)){
		mysqli_query($con,"insert into do_question values ('{$student_id}','{$question_no}','{$teacher_id}','{$course_id}','{$question_do_time}','0','{$degree}');") or die ( "写入做题记录失败，请检查你的内容！".'<br>'."错误信息：". mysqli_error ($con));

?>
          <script type="text/javascript">
            alert("答案错误！正确答案为：<?php echo $correct_choice?>");		
            window.location.href="do_question.php?degree=<?php echo $degree ?>";
          </script>
<?php
    }else{
?>
          <script type="text/javascript">
            alert("你已经做过这道题了！");
            window.location.href="do_question.php?degree=<?php echo $degree ?>";
          </script>		
<?php	
	}
?>

<?php mysqli_close($con);?>
</body>
</html>
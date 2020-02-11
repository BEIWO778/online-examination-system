<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>筛选 跳转中</title>
</head>
<body>
<?php include '../../config.php';?>
<?php
	$degree_temp=$_POST['degree'];
	$degree=substr($degree_temp,15,1);   
?>	
    <script type="text/javascript">
        window.location.href="do_question.php?degree=<?php echo $degree ?>";
    </script>
<?php mysqli_close($con);?> 
</body>
</html>
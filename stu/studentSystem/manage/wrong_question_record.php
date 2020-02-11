<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"> 
    <title>学生 错题记录</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
   	<!-- DataTables CSS -->
	<link rel="stylesheet" type="text/css" href="../../css/jquery.dataTables.min.css">
</head>
<body background="..\login\images\back.png">
<?php include '../../config.php';?>
<script src="../../js/bootstrap.js"></script>
<!-- jQuery -->
<script src="../../js/jquery.js"></script>
<!-- DataTables -->
<script src="../../js/jquery.dataTables.js"></script>
<div class="container">
	<div class="row clearfix">
		<div class="col-md-12 column">
            <p><br><br></p>
		</div>
	</div>
	<div class="row clearfix">
		<div class="col-md-4 column">
            <h3>题库管理与随堂测验系统<br>学生界面</h3>
		</div>
		<div class="col-md-4 column"></div>
		<div class="col-md-2 column">
            <p class="text-right" style="font-size:18px">
			<br><br><strong>当前账号：<?php echo "${_SESSION["student_id"]}";//显示登录用户名 ?></strong>
			</p>
		</div>
		<div class="col-md-2 column">
			<br><br><button type="button" class="btn btn-default btn-block btn-warning" onclick="window.location.href='exit.php'">注销登录</button>
		</div>
	</div>
	<div class="row clearfix">
		<div class="col-md-12 column"><p></p></div>
	</div>
	<div class="row clearfix">
		<div class="col-md-12 column">
			<div class="alert alert-dismissable alert-info">
			<?php
				$result=mysqli_query($con,"select * from student_account where student_id='${_SESSION["student_id"]}'");
				$bookinfo=mysqli_fetch_array($result);
				$student_name=$bookinfo["student_name"];
				$student_id=$bookinfo["student_id"];
				$result=mysqli_query($con,"select * from student_course where student_id='{$student_id}'");
				$bookinfo=mysqli_fetch_array($result);
				$student_course_id=$bookinfo["course_id"];
			?>
                <h4><strong>欢迎你，<?php echo "$student_name"?>！</strong></h4>
			<?php
				$notice_check=mysqli_query($con,"select * from notice");
				$notice=mysqli_fetch_array($notice_check);
				if(!$notice){
					echo '<strong>目前暂无管理员发布的通知。</strong><br>'.'在开始使用本系统前，请详细阅读界面首页的操作使用提示！';
				}else{
					echo '<strong>'.'现有通知：'.'</strong>'.'<br>';
					mysqli_free_result($notice_check);
					$notice_check=mysqli_query($con,"select * from notice");
					while($row_notice=mysqli_fetch_assoc($notice_check)){
						echo '<strong>'.$row_notice['name'].'：</strong>'.$row_notice['content'].'<strong>发布于：</strong>'.$row_notice['time'].'<br>';
					}
				}
			?>
			</div>
		</div>
	</div>
	<div class="row clearfix">
		<div class="col-md-3 column">
			 <button type="button" class="btn btn-default btn-block btn-info" onclick="window.location.href='index.php'">首页显示</button>
			 <button type="button" class="btn btn-default btn-block btn-info" onclick="window.location.href='course_choose.php'">学习课程</button>
			 <button type="button" class="btn btn-default btn-block btn-info" onclick="window.location.href='do_question.php?degree=1'">题目测验</button>
			 <button type="button" class="btn btn-default btn-block btn-info" onclick="window.location.href='question_condition.php'">做题情况</button>
			 <button type="button" class="btn btn-default btn-block btn-warning" onclick="window.location.href='wrong_question_record.php'">错题记录</button>
			 <button type="button" class="btn btn-default btn-block btn-info" onclick="window.location.href='leave_message.php'">教师留言</button>
			 <button type="button" class="btn btn-default btn-block btn-info" onclick="window.location.href='edit_password.php'">修改密码</button>
		</div>
		<div class="col-md-9 column">
		<?php
            $result=mysqli_query($con,"select * from {$student_course_id}_questions,do_question where {$student_course_id}_questions.id=do_question.question_no and do_question.student_id='{$student_id}' and if_correct=0");
            if($result && mysqli_num_rows($result)){
				echo '<p><strong>你的错题：</strong><br>下面列举出了你所做过的题目的错题，你可以参考答案解析。';
				echo '<table id="table_1" class="display"><thead>';
				echo '<tr><th>题号</th><th>题目</th><th>答案</th><th>课程</th><th>答案解析</th><th>时间</th><th>第几次测验</th></tr>';
				echo '</thead><tbody>';
                while($row=mysqli_fetch_assoc($result)){
                    echo '<tr>';
                    echo '<td>'.$row['question_no'].'</td>';
					echo '<td>'.$row['question_describe'].'</td>';
					if($row['question_correct_answer']=="A"){
						$ca=$row['question_choice_A'];
					}else if($row['question_correct_answer']=="B"){
						$ca=$row['question_choice_B'];
					}else if($row['question_correct_answer']=="C"){
						$ca=$row['question_choice_C'];
					}else if($row['question_correct_answer']=="D"){
						$ca=$row['question_choice_D'];
					}else{
						$ca=$row['question_correct_answer'];
					}
					echo '<td>'.$ca.'</td>';
					$result_temp=mysqli_query($con,"select * from id_course where course_id='{$row['course_id']}'");
                    $bookinfo=mysqli_fetch_array($result_temp);
					echo '<td>'.$bookinfo['course_name'].'</td>';
					echo '<td>'.$row['answer_analysis'].'</td>';
					echo '<td>'.substr($row['question_do_time'],0,10).'</td>';
					echo '<td>'.$row['set_of_question'].'</td>';
					echo '</tr>';
				}
				echo '</tbody></table>';
		?>
<script type="text/javascript">
		$(document).ready( function () {
			$('#table_1').DataTable({
				"aLengthMenu": [5, 10, 20, 50], 
				"order": [[ 6, "asc" ]],
				language: {
					"sProcessing": "处理中...",
					"sLengthMenu": "显示 _MENU_ 项结果",
					"sZeroRecords": "没有匹配结果",
					"sInfo": "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
					"sInfoEmpty": "显示第 0 至 0 项结果，共 0 项",
					"sInfoFiltered": "(由 _MAX_ 项结果过滤)",
					"sInfoPostFix": "",
					"sSearch": "搜索:",
					"sUrl": "",
					"sEmptyTable": "表中数据为空",
					"sLoadingRecords": "载入中...",
					"sInfoThousands": ",",
					"oPaginate": {
						"sFirst": "首页",
						"sPrevious": "上页",
						"sNext": "下页",
						"sLast": "末页"
					},
					"oAria": {
								"sSortAscending": ": 以升序排列此列",
								"sSortDescending": ": 以降序排列此列"
					}
				}
			});
		});
</script>
<?php
            }else{
				echo '<br><strong>你还没犯过错，多犯点错吧！</strong>';
			}
?>
		</div>
	</div>
	<div class="row clearfix">
		<div class="col-md-12 column">
			<p><br></p>
		</div>
	</div>
</div>
<?php mysqli_close($con);?>
</body>
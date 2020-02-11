<!DOCTYPE html>
<html>
<head>
   <meta charset="utf-8"> 
   <title>学生 教师留言</title>
   <link rel="stylesheet" href="../../css/bootstrap.min.css">
</head>
<body background="..\login\images\back.png">
<?php include '../../config.php';?>
<script src="../../js/bootstrap.js"></script>
<div class="container">
	<div class="row clearfix">
		<div class="col-md-12 column">
            <p>
				<br><br>
			</p>
		</div>
	</div>
	<div class="row clearfix">
		<div class="col-md-1 column">
		</div>
		<div class="col-md-4 column">
            <h3>
				题库管理与随堂测验系统<br>学生界面
			</h3>
		</div>
		<div class="col-md-2 column">
		</div>
		<div class="col-md-2 column">
            <p class="text-right" style="font-size:18px">
			<br><br><strong>当前账号：<?php echo "${_SESSION["student_id"]}";//显示登录用户名 ?></strong>
			</p>
		</div>
		<div class="col-md-2 column">
			<br><br><button type="button" class="btn btn-default btn-block btn-warning" onclick="window.location.href='exit.php'">注销登录</button>
		</div>
		<div class="col-md-1 column">
		</div>
	</div>
	<div class="row clearfix">
		<div class="col-md-12 column">
            <p>
			</p>
		</div>
	</div>
	<div class="row clearfix">
		<div class="col-md-1 column">
		</div>
		<div class="col-md-10 column">
			<div class="alert alert-dismissable alert-info">
            <?php
				$result=mysqli_query($con,"select * from student_account where student_id='${_SESSION["student_id"]}'");
				$bookinfo=mysqli_fetch_array($result);
				$student_id=$bookinfo["student_id"];
                $student_name=$bookinfo["student_name"];
                $student_class=$bookinfo["student_class"];
			?>
            <h4>
			<strong>欢迎你，<?php echo "$student_name"?>！</strong></h4>
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
		<div class="col-md-1 column">
		</div>
	</div>
	<div class="row clearfix">
		<div class="col-md-1 column">
		</div>
		<div class="col-md-3 column">
			 <button type="button" class="btn btn-default btn-block btn-info" onclick="window.location.href='index.php'">首页显示</button>
			 <button type="button" class="btn btn-default btn-block btn-info" onclick="window.location.href='course_choose.php'">学习课程</button>
			 <button type="button" class="btn btn-default btn-block btn-info" onclick="window.location.href='do_question.php?degree=1'">题目测验</button>
			 <button type="button" class="btn btn-default btn-block btn-info" onclick="window.location.href='question_condition.php'">做题情况</button>
			 <button type="button" class="btn btn-default btn-block btn-info" onclick="window.location.href='wrong_question_record.php'">错题记录</button>
			 <button type="button" class="btn btn-default btn-block btn-warning" onclick="window.location.href='leave_message.php'">教师留言</button>
			 <button type="button" class="btn btn-default btn-block btn-info" onclick="window.location.href='edit_password.php'">修改密码</button>
		</div>
		<div class="col-md-7 column">
			<?php
                $result=mysqli_query($con,"select student_messages.*,teacher_account.teacher_name from student_messages,teacher_account where student_messages.teacher_id=teacher_account.teacher_id and student_messages.student_id='{$student_id}'");
                $bookinfo=mysqli_fetch_array($result);
                if(!$bookinfo){
                    echo '<p><strong>你还未写过任何留言，赶紧在下面的留言板中留言吧！</strong></p>';
                }else{
					$num=1;
					$line=1;
                    echo '<p><strong>以下为你发出的留言信息，你可以查看或删除他们。</strong></p>';
                    echo '<table class="table table-bordered table-hover">';
                    echo '<thead><tr><th></th><th>教师信息</th><th>留言时间</th><th>留言内容</th><th>删除</th></tr></thead>';
                    echo '<tbody>';
                    $result=mysqli_query($con,"select * from student_messages,teacher_account where student_messages.teacher_id=teacher_account.teacher_id and (del=0 or del=1)");
                    while($row=mysqli_fetch_assoc($result)){
						if($line%2==0){
							echo '<tr class="success">';
						}else{
							echo '<tr>';
						}
                        echo '<td>'.$num.'</td>';
                        echo '<td>'.$row['teacher_id'].' - '.$row['teacher_name'].'</td>';
                        echo '<td>'.$row['message_time'].'</td>';
                        echo '<td>'.$row['message_contents'].'</td>';
                        echo '<td>';
                        echo '<button type="button" class="btn btn-default btn-block btn-danger btn-xs" onclick="window.location.href=\'message_delete.php?student_id='.$row['student_id'].'&teacher_id='.$row['teacher_id'].'&message_time='.$row['message_time'].'\''.'">删除留言</button>';
                        echo '</td>';
                        echo '</tr>';
						$num++;
						$line++;
                    }
                    echo '</tbody>';
                    echo '</table>';
                }
            ?>
			<br><br><br>
			<!--收到留言部分，数据库部分未修改-->
			<?php
                mysqli_free_result($result);
                $result=mysqli_query($con,"select * from teacher_message,teacher_account where teacher_message.teacher_id=teacher_account.teacher_id and teacher_message.student_id='{$student_id}'");
                $bookinfo=mysqli_fetch_array($result);
                if($bookinfo){
                    echo '<p><strong>以下为你收到的留言信息，你可以查看或删除他们。</strong></p>';
                    echo '<table class="table table-bordered table-hover">';
                    echo '<thead><tr><th></th><th>教师信息</th><th>留言时间</th><th>留言内容</th><th>删除</th></tr></thead>';
                    echo '<tbody>';
                    $num=1;
                    $line=1;
                    $result=mysqli_query($con,"select * from teacher_message,teacher_account where teacher_message.teacher_id=teacher_account.teacher_id and (del=0 or del=2)");
                    while($row=mysqli_fetch_assoc($result)){
                        if($line%2==0){
                            echo '<tr class="success">';
                        }else{
                            echo '<tr>';
                        }
                        echo '<td>'.$num.'</td>';
                        echo '<td>'.$row['teacher_id'].' - '.$row['teacher_name'].'</td>';
                        echo '<td>'.$row['message_time'].'</td>';
                        echo '<td>'.$row['message_contents'].'</td>';
                        echo '<td>';
                        echo '<button type="button" class="btn btn-default btn-block btn-danger btn-xs" onclick="window.location.href=\'message_delete1.php?student_id='.$row['student_id'].'&teacher_id='.$row['teacher_id'].'&message_time='.$row['message_time'].'\''.'">删除留言</button>';
                        echo '</td>';
                        echo '</tr>';
                        $num++;
                        $line++;
                    }
                    echo '</tbody></table>';
                }else{
                    echo '<p><strong>对不起，你还未收到任何留言。</strong></p>';
                }
            ?>
			<br><br><br>
            <p><br><strong>你可以在下面的输入留言内容，并选择你要留言的对象。</strong></p>
            <form action="leave_message_action.php" method="post" role="form">
			<div class="row clearfix">
                <div class="col-md-3 column">
                    <p><strong>留言对象：</strong></p>
                    <select class="form-control" name="teacher_id">
                        <?php
                            mysqli_free_result($result);
                            $result=mysqli_query($con,"select * from teacher_account");
                            while($row=mysqli_fetch_assoc($result)){
                                echo '<option>'.$row['teacher_id'].' - '.$row['teacher_name'].'</option>';
                            }
                        ?>
                    </select>
                </div>
                <div class="col-md-9 column">
                    <p><strong>留言内容：</strong></p>
                    <input type="text" name="message_contents" class="form-control" id="name" placeholder="请输入留言内容">
                </div>
	        </div>
            <div class="row clearfix">
                <div class="col-md-2 column">
                </div>
                <div class="col-md-8 column">
                    <br><button type="submit" class="btn btn-default btn-block btn-success">发送留言</button>
                </div>
                <div class="col-md-2 column">
                </div>
            </div>
            </form>
		</div>
		<div class="col-md-1 column">
		</div>
	</div>
	<div class="row clearfix">
		<div class="col-md-1 column">
		</div>
		<div class="col-md-10 column">
            <p>
			<br>
			</p>
		</div>
		<div class="col-md-1 column">
		</div>
	</div>
</div>
<?php mysqli_close($con);?>


</body>
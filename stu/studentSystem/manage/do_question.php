<!DOCTYPE html>
<html>
<head>
   <meta charset="utf-8"> 
   <title>学生 题目测验</title>
   <link rel="stylesheet" href="../../css/bootstrap.min.css">
   <link rel="stylesheet" href="../../css/buttons.css">
</head>
<body background="..\login\images\back.png">
<?php include '../../config.php';?>
<script src="../../js/bootstrap.js"></script>
<div class="container">
	<div class="row clearfix">
		<div class="col-md-12 column">
            <p><br><br></p>
		</div>
	</div>
	<div class="row clearfix">
		<div class="col-md-1 column"></div>
		<div class="col-md-4 column">
            <h3>
				题库管理与随堂测验系统<br>学生界面
			</h3>
		</div>
		<div class="col-md-2 column"></div>
		<div class="col-md-2 column">
            <p class="text-right" style="font-size:18px">
			<br><br><strong>当前账号：<?php echo "${_SESSION["student_id"]}";//显示登录用户名 ?></strong>
			</p>
		</div>
		<div class="col-md-2 column">
			<br><br><button type="button" class="btn btn-default btn-block btn-warning" onclick="window.location.href='exit.php'">注销登录</button>
		</div>
		<div class="col-md-1 column"></div>
	</div>
	<div class="row clearfix">
		<div class="col-md-12 column"><p></p></div>
	</div>
	<div class="row clearfix">
		<div class="col-md-1 column"></div>
		<div class="col-md-10 column">
			<div class="alert alert-dismissable alert-info">
                <?php
					$result=mysqli_query($con,"select * from student_account where student_id='${_SESSION["student_id"]}'");
					$bookinfo=mysqli_fetch_array($result);
					$student_id=$bookinfo["student_id"];
                    $student_name=$bookinfo["student_name"];
                    $student_class=$bookinfo["student_class"];
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
						$notice_check=mysqli_query($con,"select * from notice");
						while($row_notice=mysqli_fetch_assoc($notice_check)){
							echo '<strong>'.$row_notice['name'].'：</strong>'.$row_notice['content'].'<strong>发布于：</strong>'.$row_notice['time'].'<br>';
						}
					}
				?>
			</div>
		</div>
		<div class="col-md-1 column"></div>
	</div>
	<div class="row clearfix">
		<div class="col-md-1 column"></div>
		<div class="col-md-3 column">
			 <button type="button" class="btn btn-default btn-block btn-info" onclick="window.location.href='index.php'">首页显示</button>
			 <button type="button" class="btn btn-default btn-block btn-info" onclick="window.location.href='course_choose.php'">学习课程</button>
			 <button type="button" class="btn btn-default btn-block btn-warning" onclick="window.location.href='do_question.php?degree=1'">题目测验</button>
			 <button type="button" class="btn btn-default btn-block btn-info" onclick="window.location.href='question_condition.php'">做题情况</button>
			 <button type="button" class="btn btn-default btn-block btn-info" onclick="window.location.href='wrong_question_record.php'">错题记录</button>
			 <button type="button" class="btn btn-default btn-block btn-info" onclick="window.location.href='leave_message.php'">教师留言</button>
			 <button type="button" class="btn btn-default btn-block btn-info" onclick="window.location.href='edit_password.php'">修改密码</button>
		</div>
		<div class="col-md-7 column">
            <?php
                $degree=$_GET['degree'];
                $result=mysqli_query($con,"select * from {$student_course_id}_questions,student_course,id_course where {$student_course_id}_questions.course_id=student_course.course_id and {$student_course_id}_questions.course_id=id_course.course_id");
				$bookinfo=mysqli_fetch_array($result);
                $course_id=$bookinfo['course_id'];
				$teacher_id=$bookinfo['teacher_id'];
                $course_name=$bookinfo['course_name'];
                echo '<strong>以下信息为目前显示的题目类别信息、第几次测验：</strong><br><br>';
                //显示选择框插件
                echo '<div class="row clearfix">';
                //当前信息部分
                echo '<div class="col-md-3 column">';
                echo '<strong>当前选择：</strong><br>';
                echo '课程ID：<strong>'.$course_id.'</strong><br>';
                echo '课程名：<strong>'.$course_name.'</strong><br>';
				echo '第几次测验：';
                        echo '<strong>'.$degree.'</strong>';
                echo '</div>';
                
				echo '<form action="do_question_temp.php" method="post" role="form">';
				//选择测验次数
                    echo '<div class="col-md-3 column">';
                        echo '<strong>第几次测验选择：</strong>';
                        echo '<br><br><select class="form-control" name="degree">';
                            $result_temp=mysqli_query($con,"select distinct set_of_question from {$student_course_id}_questions where course_id='{$student_course_id}' order by set_of_question asc");
                            echo '<option>'.'</option>';
							while($row=mysqli_fetch_assoc($result_temp)){
                                echo '<option>'.'测验次数 - '.$row['set_of_question'].'</option>';
                            }
                        echo '</select>';
                    echo '</div>';
                    echo '<div class="col-md-3 column">';
                        echo '<strong>条件筛选按钮：</strong>';
                        echo '<br><br><button type="submit" class="btn btn-default btn-block btn-warning">筛选</button>';
                    echo '</div>';
                echo '</div>';
                echo '</form>';

				//题目编号赋初值，因为数据表是一个科目的所有题放在一个表，数据表中题号是按存入时间记录的，单套题的序号不是按顺序的
                $question_num=0;

                //下面这个result只要第几套的题库有题就进入if语句
                $result=mysqli_query($con,"select * from {$student_course_id}_questions where set_of_question='{$degree}'");  
                $bookinfo=mysqli_fetch_array($result);
				
				//如果不再查一次，直接用result会丢失第一条数据
			    $result1=mysqli_query($con,"select * from {$student_course_id}_questions where set_of_question='{$degree}'");  

                if($bookinfo){
					//如果题目表中有内容查看筛选情况，否则输出抱歉
                    echo '<br><br>';			
					while($row=mysqli_fetch_assoc($result1)){
                        $question_num++;
                        echo '<div class="panel panel-info">';
                        echo '<div class="panel-heading"><h3 class="panel-title">';
                        echo '<strong>题目编号：</strong>'.$question_num;

                        echo '，'.'<strong>教师：</strong>'.$row['teacher_id'];
                        $row_temp=mysqli_query($con,"select * from teacher_account where teacher_id='{$row['teacher_id']}'");
                        $temp_info=mysqli_fetch_array($row_temp);
                        echo '-'.$temp_info['teacher_name'];

                        echo '，'.'<strong>课程：</strong>'.$row['course_id'];
                        $row_temp=mysqli_query($con,"select * from id_course where course_id='{$row['course_id']}'");
                        $temp_info=mysqli_fetch_array($row_temp);
                        echo '-'.$temp_info['course_name'];   
                        echo '</h3></div>';
                     
                        echo '<div class="panel-body">';
                            echo '<p style="font-size:21px"><strong>'.$row['question_describe'].'</strong></p>';
                        echo '</div>';
				
							
						//不用doit字段控制
						$result2=mysqli_query($con,"select * from do_question where student_id='{$student_id}' and question_no='{$row['id']}' and teacher_id='{$row['teacher_id']}' and course_id='{$row['course_id']}' and set_of_question='{$row['set_of_question']}'");	
						if(mysqli_num_rows($result2)==0){
							$doit=0;
						}else{
							$doit=1;
						}

						$ifchoice=$row['question_correct_answer'];
						
				//		如果是选择题的答案显示
						if($ifchoice=='A'||$ifchoice=='B'||$ifchoice=='C'||$ifchoice=='D'){
							//颜色区分题目是否做完，选择题提交填空题答案为S，不然检查界面报错
							if($doit==1){
								//做完的题目，粉色
								echo '<div class="row clearfix">';
									echo '<div class="col-md-6 column">';
								//	echo '<br>';
									echo '<a href="'.'question_check.php?question_no='.$row['id'].'&teacher_id='.$row['teacher_id'].'&course_id='.$row['course_id'].'&student_id='.$student_id.'&fill_blank=S'.'&choice='.'A'.'&degree='.$degree.'"'.'class="button button-block button-rounded button-caution button-small">'.'A选项：'.$row['question_choice_A'].'</a>';
									echo '<br>';
									echo '<a href="'.'question_check.php?question_no='.$row['id'].'&teacher_id='.$row['teacher_id'].'&course_id='.$row['course_id'].'&student_id='.$student_id.'&fill_blank=S'.'&choice='.'C'.'&degree='.$degree.'"'.'class="button button-block button-rounded button-caution button-small">'.'C选项：'.$row['question_choice_C'].'</a>';
									echo '</div>';
									echo '<div class="col-md-6 column">';
								//	echo '<br>';
									echo '<a href="'.'question_check.php?question_no='.$row['id'].'&teacher_id='.$row['teacher_id'].'&course_id='.$row['course_id'].'&student_id='.$student_id.'&fill_blank=S'.'&choice='.'B'.'&degree='.$degree.'"'.'class="button button-block button-rounded button-caution button-small">'.'B选项：'.$row['question_choice_B'].'</a>';
									echo '<br>';
									echo '<a href="'.'question_check.php?question_no='.$row['id'].'&teacher_id='.$row['teacher_id'].'&course_id='.$row['course_id'].'&student_id='.$student_id.'&fill_blank=S'.'&choice='.'D'.'&degree='.$degree.'"'.'class="button button-block button-rounded button-caution button-small">'.'D选项：'.$row['question_choice_D'].'</a>';
									echo '</div>';
								echo '</div>';
							 
							}else{//没做的题目，蓝色
								echo '<div class="row clearfix">';
									echo '<div class="col-md-6 column">';
								//	echo '<br>';
									echo '<a href="'.'question_check.php?question_no='.$row['id'].'&teacher_id='.$row['teacher_id'].'&course_id='.$row['course_id'].'&student_id='.$student_id.'&fill_blank=S'.'&choice='.'A'.'&degree='.$degree.'"'.'class="button button-block button-rounded button-primary button-small">'.'A选项：'.$row['question_choice_A'].'</a>';
									echo '<br>';
									echo '<a href="'.'question_check.php?question_no='.$row['id'].'&teacher_id='.$row['teacher_id'].'&course_id='.$row['course_id'].'&student_id='.$student_id.'&fill_blank=S'.'&choice='.'C'.'&degree='.$degree.'"'.'class="button button-block button-rounded button-primary button-small">'.'C选项：'.$row['question_choice_C'].'</a>';
									echo '</div>';
									echo '<div class="col-md-6 column">';
								//	echo '<br>';
									echo '<a href="'.'question_check.php?question_no='.$row['id'].'&teacher_id='.$row['teacher_id'].'&course_id='.$row['course_id'].'&student_id='.$student_id.'&fill_blank=S'.'&choice='.'B'.'&degree='.$degree.'"'.'class="button button-block button-rounded button-primary button-small">'.'B选项：'.$row['question_choice_B'].'</a>';
									echo '<br>';
									echo '<a href="'.'question_check.php?question_no='.$row['id'].'&teacher_id='.$row['teacher_id'].'&course_id='.$row['course_id'].'&student_id='.$student_id.'&fill_blank=S'.'&choice='.'D'.'&degree='.$degree.'"'.'class="button button-block button-rounded button-primary button-small">'.'D选项：'.$row['question_choice_D'].'</a>';
									echo '</div>';
								echo '</div>';
							}
						}else{//填空题答题框和提交按钮
							if($doit==1){//做完粉色
?>							
								<div class="row clearfix">
									<form action="question_check.php?" method="get" role="form">								
									<div class="col-md-6 column">
									<input type="text" name="fill_blank" class="form-control" placeholder="请输入答案">
									<input type="hidden" name="question_no" value="<?php echo $row['id'];?>" />
									<input type="hidden" name="teacher_id" value="<?php echo $teacher_id;?>"/>
									<input type="hidden" name="student_id" value="<?php echo $student_id;?>"/>
									<input type="hidden" name="course_id" value="<?php echo $course_id;?>"/>
									<input type="hidden" name="degree" value="<?php echo $row['set_of_question'];?>"/>
									<!--填空题提交choice值为0，不然检查页面有报错-->
									<input type="hidden" name="choice" value="0"/>
									</div>						
									<div class="col-md-6 column">
										<button type="submit" class="btn btn-block button-rounded button-caution">提交答案</button>
									</div>
									</form>
								</div>
							
<?php																						
							}else{//没做蓝色
?>
								<div class="row clearfix">
									<form action="question_check.php?" method="get" role="form">								
									<div class="col-md-6 column">
									<input type="text" name="fill_blank" class="form-control" placeholder="请输入答案">
									<input type="hidden" name="question_no" value="<?php echo $row['id'];?>" />
									<input type="hidden" name="teacher_id" value="<?php echo $teacher_id;?>"/>
									<input type="hidden" name="student_id" value="<?php echo $student_id;?>"/>
									<input type="hidden" name="course_id" value="<?php echo $course_id;?>"/>
									<input type="hidden" name="degree" value="<?php echo $row['set_of_question'];?>"/>
									<!--填空题提交choice值为0，不然检查页面有报错-->
									<input type="hidden" name="choice" value="0"/>
									</div>						
									<div class="col-md-6 column">
										<button type="submit" class="btn btn-block button-rounded button-primary">提交答案</button>
									</div>
									</form>
								</div>							
<?php															
							}
						}
                        echo '</div>';
                    }					
				}else{
					echo '<div class="row clearfix"><div class="col-md-12 column">';
						echo '<br><br><strong>抱歉！<br>在你的筛选条件下没有相应的题目，请尝试其他筛选条件！</strong>';
						echo '</div>';
					echo '</div>';
				}
            ?>
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
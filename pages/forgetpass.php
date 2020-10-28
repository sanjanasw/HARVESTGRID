<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../styles/weuse.css">
	<title> forget passwords</title>
</head>
<body>
<div class="container-fluid">
		<div class="row mt-4">
			<div class="col-12 text-center">
				<?php
				include('../includes/db.php');
				if(isset($_POST["email"]) && (!empty($_POST["email"]))){
					$email = $_POST["email"];
					$email = filter_var($email, FILTER_SANITIZE_EMAIL);
					$email = filter_var($email, FILTER_VALIDATE_EMAIL);
					$sel_query = "SELECT * FROM users WHERE user_email='$email'";
					$results = mysqli_query($con,$sel_query);
					$row = mysqli_num_rows($results);
					$row1 = mysqli_fetch_assoc($results);
					if(isset($row1['user_name'])){
						$user_name=$row1['user_name'];
					}
					if ($row==""){
						$error .= "<script>
						window.onload = function() {
							Swal.fire({
								title: '',
								text: 'no user is registerd with this email address',
								imageUrl: '../images/nouser.svg',
								imageHeight: 250,
								imageAlt: 'no user',
								confirmButtonColor: '#ff5454',
								confirmButtonText: 'Oh Okay',
							}).then( function() {
								window.location.href = 'javascript:history.go(-1)';
							})
						};
					</script>";
						}
					if($error!=""){
					echo $error;
						}else{
					$expFormat = mktime(date("H"), date("i"), date("s"), date("m")  , date("d")+1, date("Y"));
					$expDate = date("Y-m-d H:i:s",$expFormat);
					$key = md5((2418*2) . $email);
					$addKey = substr(md5(uniqid(rand(),1)),3,10);
					$key = $key . $addKey;
				    $query = "INSERT INTO verify (email, skey, expDate)
				VALUES ('$email', '$key', '$expDate');";
				// Insert Temp Table
				mysqli_query($con, $query);
						
				$output='<p>Dear '.$user_name.',</p>';
				$output.='<p>Please click on the following link to reset your password.</p>';
				$output.='<p>-------------------------------------------------------------</p>';
				$output.='<p><a href="localhost/harvestgrid/includes/reset_password.php?key='.$key.'&email='.$email.'&action=reset" target="_blank">click me</a></p>';		
				$output.='<p>-------------------------------------------------------------</p>';
				$output.='<p>Please be sure to copy the entire link into your browser.
				The link will expire after 1 day for security reasons.</p>';
				$output.='<p>If you did not request this forgotten password email, no action 
				is needed, your password will not be reset. However, you may want to log into 
				your account and change your security password as someone may have guessed it.</p>';   	
				$output.='<p>Thanks,</p>';
				$output.='<p>Harvestgrid Team</p>';
				$body = $output; 
				$subject = "Password Recovery - harvestgrid.com";
				
				$email_to = $email;
				$fromserver = "noreply@yourwebsite.com"; 
				require("../phpmailer/PHPMailerAutoload.php");
				$mail = new PHPMailer();
				$mail->IsSMTP();
				$mail->Host = "smtp.gmail.com"; // Enter your host here
				$mail->SMTPAuth = true;
                $mail->Username = "weuse.work@gmail.com"; // Enter your email here
				$mail->Password = "lebdkufvlhhmlvvv"; //Enter your passwrod here
				$mail->Port = 587;
				$mail->IsHTML(true);
				$mail->From = "noreply@yourwebsite.com";
				$mail->FromName = "Harvestgrid";
				$mail->Sender = $fromserver; // indicates ReturnPath header
				$mail->Subject = $subject;
				$mail->Body = $body;
				$mail->AddAddress($email_to);
				if(!$mail->Send()){
				echo "Mailer Error: " . $mail->ErrorInfo;
				}else{
				echo "<script>
				window.onload = function() {
					Swal.fire({
						title: '',
						text: 'An email has been sent to you with instructions on how to reset your password. Make sure to check your SPAM and JUNK folders',
						imageUrl: '../images/passinstructionsent.svg',
						imageHeight: 250,
						imageAlt: 'password reset instructions sent',
						confirmButtonColor: '#ff5454',
						confirmButtonText: 'lemme check',
					}).then( function() {
						window.location.href = '../index.php';
					})
				};
			</script>";
					}
				
						}	
					
				}else{
				?>
				    <form method="post" action="" name="reset" class="mb-5">
						<p class="mx-auto d-block">Enter Your Email Address</p>
				        <input type="email" name="email" placeholder="username@email.com" id="forminput" class="col-12  m-2 mx-auto d-block form-control"/>
				        <input class="btn mx-auto d-block" id="button" type="submit" value="⟳ RESET PASSWORD" />
				    </form>
				    <?php } ?>
            </div>
		</div>
</div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
</body>
</html>
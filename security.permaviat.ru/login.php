<?php
	session_start();
	
	if (isset($_SESSION['user']) && !empty($_SESSION['user']) && $_SESSION['user'] != -1) {
		$role = isset($_SESSION['role']) ? $_SESSION['role'] : 0;
		if($role == 1) {
			header("Location: admin.php");
		} else {
			header("Location: user.php");
		}
		exit;
 	}
?>
<html>
	<head> 
		<meta charset="utf-8">
		<title> Авторизация </title>
		<script src="https://code.jquery.com/jquery-1.8.3.js"></script>
		<link rel="stylesheet" href="style.css">
	</head>
	<body>
		<div class="top-menu">
			<a href=#><img src = "img/logo1.png"/></a>
			<div class="name">
				<a href="index.php">
					<div class="subname">БЗОПАСНОСТЬ  ВЕБ-ПРИЛОЖЕНИЙ</div>
					Пермский авиационный техникум им. А. Д. Швецова
				</a>
			</div>
		</div>
		<div class="space"> </div>
		<div class="main">
			<div class="content">
				<div class = "login">
					<div class="name">Авторизация</div>
					<div class = "sub-name">Логин:</div>
					<input name="_login" type="text" onkeypress="return PressToEnter(event)"/>
					<div class = "sub-name">Пароль:</div>
					<input name="_password" type="password" onkeypress="return PressToEnter(event)"/>
					
					<input type="button" class="button" value="Войти" onclick="LogIn()"/>
					<img src = "img/loading.gif" class="loading" style="display:none"/>
				</div>
                <div class="footer">© КГАПОУ "Авиатехникум", 2020</div>
			</div>
		</div>
		
		<script>
			function LogIn() {
				var loading = document.getElementsByClassName("loading")[0];
				var button = document.getElementsByClassName("button")[0];
				var _login = document.getElementsByName("_login")[0].value;
				var _password = document.getElementsByName("_password")[0].value;
				
				loading.style.display = "block";
				button.className = "button_diactive";

				$.ajax({
					url: 'http://auth.permaviat.ru/index.php',
					type: 'GET',
					beforeSend: function (xhr) {
						xhr.setRequestHeader ("Authorization", "Basic " + btoa(_login + ":" + _password));
					},
					success: function (data, textStatus, request) {
						var token = request.getResponseHeader('token');
						if(token) {
							$.ajax({
								url: 'ajax/login_user.php',
								type: 'POST',
								data: { token: token },
								success: function(_data) {
									localStorage.setItem("token", token);
									location.reload();
								},
								error: function() {
									alert("Ошибка сохранения сессии");
                                    resetUI();
								}
							});
						} else {
							alert("Токен не получен");
                            resetUI();
						}
					},
					error: function(){
						alert('Неверный логин или пароль');
                        resetUI();
					}
				});
                
                function resetUI() {
                    loading.style.display = "none";
                    button.className = "button";
                }
			}
			
			function PressToEnter(e) {
				if (e.keyCode == 13) LogIn();
			}
		</script>
	</body>
</html>
<?php
	session_start();

	if (!isset($_SESSION['user']) || empty($_SESSION['user']) || $_SESSION['user'] == -1) {
		header("Location: login.php");
		exit;
	}

?>
<!DOCTYPE HTML>
<html>
	<head> 
		<script src="https://code.jquery.com/jquery-1.8.3.js"></script>
		<meta charset="utf-8">
		<title>Личный кабинет</title>
		
		<link rel="stylesheet" href="style.css">
	</head>
	<body>
		<div class="top-menu">
			<a href=#><img src = "img/logo1.png"/></a>
			<div class="name">
				<a href="index.php">
					<div class="subname">БЕЗОПАСНОСТЬ  ВЕБ-ПРИЛОЖЕНИЙ</div>
					Пермский авиационный техникум им. А. Д. Швецова
				</a>
			</div>
		</div>
		<div class="space"> </div>
		<div class="main">
			<div class="content">
				<input type="button" class="button" value="Выйти" onclick="logout()"/>
				
				<div class="name">Личный кабинет</div>
			
				<p>Добро пожаловать в систему!</p>
				<p>Ваш ID пользователя: <b><?php echo $_SESSION['user']; ?></b></p>
				<p>Ваша роль: <b><?php echo isset($_SESSION['role']) ? $_SESSION['role'] : 'user'; ?></b></p>

				<div>
					<b>Ваш JWT токен:</b><br>
					<span id="jwt-token-display">Загрузка...</span>
				</div>
			
				<div class="footer">
					© КГАПОУ "Авиатехникум", 2020
					<a href=#>Конфиденциальность</a>
					<a href=#>Условия</a>
				</div>
			</div>
		</div>
		
		<script>
			$(document).ready(function() {
				var token = localStorage.getItem("token");
				if(token) {
					$("#jwt-token-display").text(token);
				} else {
					$("#jwt-token-display").text("Токен не найден.");
				}
			});

			function logout() {
				localStorage.removeItem("token");
				$.ajax({
					url         : 'ajax/logout.php',
					type        : 'POST',
					success: function (_data) {
						location.reload();
					},
					error: function( ){
						console.log('Системная ошибка!');
					}
				});
			}
		</script>
	</body>
</html>
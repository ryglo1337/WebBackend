<?php

/**
 * Файл login.php для не авторизованного пользователя выводит форму логина.
 * При отправке формы проверяет логин/пароль и создает сессию,
 * записывает в нее логин и id пользователя.
 * После авторизации пользователь перенаправляется на главную страницу
 * для изменения ранее введенных данных.
 **/

// Отправляем браузеру правильную кодировку,
// файл login.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');

// Начинаем сессию.
session_start();

// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  if (!empty($_SESSION['login'])) {
  header('Location: index.php');
  }else{
?>
<style>
  body{
     background-image: url("bab.png");
     background-size: no-repeat;
     display: block;
     justify-content:center;
  }
	
  .button {
     text-align: center;
  }
	
  .log-in{
    font-family: "Montserrat", sans-serif;
    max-width: 960px;
    margin: 20% auto;
    padding: 30px 40px 5px 40px;
    width: 250px;
    background: linear-gradient(135deg,#ff8200,#198754);
border-radius: 10px;
animation: animate 1.5s linear infinite;
  }
  @keyframes animate{
    100%{filter: hue-rotate(360deg);}
  }
</style>
<div class="log-in">
<form action="login.php" method="post">
  <input name="login" /> Логин<br><br>
  <input name="password" type="password"/> Пароль<br>
  <p class = "button">
     <input type="submit" value="Войти" />
  </p>
</form>
</div>
<?php
  }
}
// Иначе, если запрос был методом POST, т.е. нужно сделать авторизацию с записью логина в сессию.
else {
  $login=$_POST['login'];
  $pswrd=$_POST['password'];
  $uid=0;
  $error=TRUE;
  $user = 'u55100';
  $pass = '6571891';
  $db1 = new PDO('mysql:host=localhost;dbname=u55100', $user, $pass, array(PDO::ATTR_PERSISTENT => true));
  if(!empty($login) and !empty($pswrd)){
    try{
      $chk=$db1->prepare("SELECT * FROM userlogin WHERE login=?");
      $chk->bindParam(1,$login);
      $chk->execute();
      $username=$chk->fetchALL();
	  print($username[0]['password']);
      if(password_verify($pswrd,$username[0]['password'])){
        $uid=$username[0]['id'];
        $error=FALSE;
      }
    }
    catch(PDOException $e){
      print('Error : ' . $e->getMessage());
      exit();
    }
  }
  if($error==TRUE){
    print('Неправильные логин или пароль? <br> Создайте нового <a href="index.php">пользователя</a> или <a href="login.php">попробовать войти снова</a> ');
    session_destroy();
    exit();
  }
  // Если все ок, то авторизуем пользователя.
  $_SESSION['login'] = $login;
  // Записываем ID пользователя.
  $_SESSION['uid'] = $uid;
  // Делаем перенаправление.
  header('Location: index.php');
}
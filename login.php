<?php 
  $user = $_POST['user'];
  $senha = ($_POST['senha']);
  
$conexao = mysqli_connect("localhost","root","","projetoppw");   
      $sql = "SELECT * FROM cliente WHERE nome = '$user' AND cpf = '$senha'";
	  $verifica = mysqli_query($conexao,$sql) or die("erro ao selecionar");
        if (mysqli_num_rows($verifica)<=0){
          echo"<script language='javascript' type='text/javascript'>alert('Login e/ou senha incorretos');window.location.href='login.html';</script>";
          exit();
        }else{
		 include "index.html";
		 setcookie("login",$user);
          header("Location:index.html");
		  
        }

?>
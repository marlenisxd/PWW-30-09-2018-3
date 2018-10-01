<?php 
// RECEBENDO OS DADOS PREENCHIDOS DO FORMULÁRIO !
$nome	= $_POST ["nome"];	//atribuição do campo "nome" vindo do formulário para variavel	
$sexo	= $_POST ["sexo"];	//atribuição do campo "sexo" vindo do formulário para variavel
$idade	= $_POST ["idade"];	//atribuição do campo "idade" vindo do formulário para variavel
$cpf	= $_POST ["cpf"];	//atribuição do campo "cpf" vindo do formulário para variavel


$conexao = mysqli_connect("localhost","root","","projetoppw");

if (!$conexao) {
    echo "deu pau. Pega ae" . php_eol;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

echo "Success: A proper connection to MySQL was made! The my_db database is great." . PHP_EOL;
echo "Host information: " . mysqli_get_host_info($conexao) . PHP_EOL;

mysqli_query($conexao, "INSERT INTO cliente ( nome , sexo , idade , cpf , status ) 
VALUES ('$nome', '$sexo', '$idade', '$cpf' , 1)");
mysqli_close($conexao);
?> 
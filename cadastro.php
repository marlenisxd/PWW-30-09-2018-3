<?php 
// RECEBENDO OS DADOS PREENCHIDOS DO FORMUL�RIO !
$nome	= $_POST ["nome"];	//atribui��o do campo "nome" vindo do formul�rio para variavel	
$sexo	= $_POST ["sexo"];	//atribui��o do campo "sexo" vindo do formul�rio para variavel
$idade	= $_POST ["idade"];	//atribui��o do campo "idade" vindo do formul�rio para variavel
$cpf	= $_POST ["cpf"];	//atribui��o do campo "cpf" vindo do formul�rio para variavel


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
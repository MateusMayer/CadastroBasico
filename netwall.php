<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['botao-salvar'])) { //Se o botao-salvar foi clicado então:
  include_once('conexao.php'); //conecta com o banco de dados

  $nome = $_POST['nome']; //cria a variavel $nome e atribui o valor digitado no formulario
  $email = $_POST['email']; //cria a variavel $email e atribui o valor digitado no formulario

  if (!empty($email) && !empty($nome)) { // verifica se o campo de e-mail e nome foi preenchido
    // Verifica se já existe um registro com o mesmo e-mail
    $stmt = $conexao->prepare("SELECT COUNT(*) FROM pessoas WHERE email = ?"); //inserindo a consulta na variavel $stmt, este select conta se já existe na base o e-mail igual ao digitato pelo usuario
    $stmt->bind_param("s", $email); //definindo que a variavel $email será trocada pelo interrogação na consulta e o tipo "s" é String.
    $stmt->execute(); //executa a query que está dentro da variavel $stmt
    $result = $stmt->get_result(); //obtem o resultado da execução
    $row = $result->fetch_row(); //a variavel $row recebe como valor o resultado da execução da consulta SQL

    // Se o resultado for igual a 0 quer dizer que esse e-mail não existe na base
    if ($row[0] == 0) {
      // Insere o registro no banco de dados
      $stmt = $conexao->prepare("INSERT INTO pessoas (nome, email) VALUES (?, ?)");//prepare muito importante para não sofrer ataque de injeção SQL
      $stmt->bind_param("ss", $nome, $email);
      $stmt->execute();
      if ($conexao->affected_rows > 0) {//aqui verifica se afetou linhas na hora de inserir os dados na base, se sim:
        echo "<script>alert('Cadastro realizado com sucesso!');</script>"; //exibe o alerta de que o cadastro foi realizado
      } else {//se nao
        echo "<script>alert('Ocorreu um erro ao salvar o registro. Por favor, tente novamente mais tarde.');</script>";//erro ao salvar
      }
    } else {//se o resultado for maior que zero, o e-mail já existe na base.
      echo "<script>alert('Esse e-mail já existe, por favor utilize outro.');</script>";
    }
  } else {//se caiu aqui é porque alguns dos campos não estão preenchidos
    switch (true) {
      case empty($nome) && empty($email)://se o nome e email estiverem vazios então exibe o alerta abaixo
        echo "<script>alert('Não foi possível salvar, todos os campos precisam ser preenchidos.');</script>";
        break;//encerra a execução do switch
      case empty($nome)://se somente o nome estiver vazio exibe o alerta abaixo
        echo "<script>alert('É necessário informar um nome.');</script>";
        break;//encerra a execução do switch
      case empty($email)://se somente o email estiver vazio exibe o alerta abaixo
        echo "<script>alert('É necessário informar um e-mail.');</script>";
        break;//encerra a execução do switch
    }
  }
}//Fim do código PHP
?>
<!-- Início do código HTML-->
<!DOCTYPE html> <!-- O tipo do documento é HTML-->
<html lang="pt-br"> <!-- Idioma da página "pt-br" = Português Brasileiro-->

<head>
  <!--  o <head>/cabeçalho é usado para fornecer informações importantes sobre a página da web que não fazem 
    parte do conteúdo principal, mas que ajudam na renderização e na indexação da página pelos motores de busca. -->
  <meta charset="UTF-8">
  <!-- Incluir o elemento <meta charset="UTF-8"> garante que todos os caracteres no documento HTML sejam 
  exibidos corretamente.-->
  <title>Conta</title>
  <!-- Esse é o título da aba na Web-->
  <link rel="stylesheet" href="css\layout.css">
  <style>
    /* Define o estilo da borda do campo nome */
    section#formulario input[type="text"] {
      border: 1px solid
        <?php echo !empty($nome_style) ? 'red' : '#ccc'; ?>
      ;
      <?php echo $nome_style; ?>
    }
  </style>
  <!-- Aqui é a chamada do arquivo CSS de layout para que consigamos fazer a comunicação entre o HTML e o CSS-->
</head>
<!-- Fim do cabeçalho -->

<body>
  <!-- Conteúdo da Página -->
  <div class="borda">
    <!-- Divisão para poder destacar um retângulo de cadastro na página Web -->
    <section id="formulario">
      <!-- Seção para agrupar a interface de cadastro -->
      <h2>Informações básicas</h2>
      <!-- Título da section -->
      <form method="POST" action="netwall.php">
        <label for="nome">Nome</label>
        <!-- label para informar o usuário que deve digitar seu nome -->
        <input type="text" id="nome" name="nome" autocomplete="off">
        <!-- input para o usuario digitar seu nome -->
        <br>
        <!-- quebra de linha -->
        <label for="email">E-mail</label>
        <!-- label para informar o usuário que deve digitar seu e-mail -->
        <input type="email" id="email" name="email" autocomplete="off">
        <!-- input para o usuario digitar seu e-mail -->
        <br>
        <!-- quebra de linha -->
        <button type="submit" id="botao-salvar" name="botao-salvar">Salvar</button>
        <!-- botão para o usuario clicar e salvar seu cadastro -->
      </form>
    </section>
    <!-- Fim da section-->
  </div>
  <!-- Fim da Div-->
</body>
<!-- Fim do conteúdo da página Web-->

</html>
<!-- Fim do HTML-->
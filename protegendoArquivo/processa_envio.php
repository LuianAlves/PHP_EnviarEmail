<?php 

    // Incluindo a Biblioteca PHPMailer
    require "./bibliotecas/PHPMailer/Exception.php";
    require "./bibliotecas/PHPMailer/OAuth.php";
    require "./bibliotecas/PHPMailer/PHPMailer.php";
    require "./bibliotecas/PHPMailer/POP3.php";
    require "./bibliotecas/PHPMailer/SMTP.php"; 

    // Abrindo os namespaces de PHPMailer e Exeption
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    //----------------------------------

    class Mensagem {
        // Atributos
        private $para = null;
        private $assunto = null;
        private $mensagem = null;
        public $status = array('codigo_status' => null, 'descricao_status' => ''); // Atributo criado para quando enviar o email (Utilizado no final do bloco try)

        // Métodos

        public function __get($atributo) {
            return $this->$atributo;
        }

        public function __set($atributo, $valor) {
            $this->$atributo = $valor;
        }

        public function mensagemValida(){
            if(empty($this->para) || empty($this->assunto) || empty($this->mensagem)) { // empty() verifica se o atributo está vazio
                return false; // Return false pois falta alguma coisa na aplicação, algum campo está vazio
            }

            return true; // Caso não entre na condição, retornará true
        }
    }


    // Instanciando o Objeto
    $mensagem = new Mensagem();

    // Recuperando os dados da super global POST -- | -- Os indices [' AQUI SÃO DEFINIDOS PELOS NAMES DOS CAMPOS NO FORML']
    $mensagem->__set('para', $_POST['para']);
    $mensagem->__set('assunto', $_POST['assunto']);
    $mensagem->__set('mensagem', $_POST['mensagem']);

    // Recuperando a instancia e executar o método mensagemValida com base no seu retorno, caso for true entrará no if PORÉM utilizando o operador de negação o retorno será false
    if(!$mensagem->mensagemValida()){ // Operador ! Converte o retorno false para true entrando no if
        echo 'Mensagem não é Válida';
        header('Location: index.php');
        // die(); // Essa instrução destroi o restante do documento caso caia nessa condição
    }
    // Caso a Mensagem seja válida, não entrará na condição IF e continuará o script

    // --------------------------------

    // Instanciando o Objeto [PHPMailer]
    $mail = new PHPMailer(true);
    try {

        // ------ Atribuindo Valores aos Atributos da instancia $mail do Objeto [PHPMailer]        
        $mail->SMTPDebug  = false; // Retira toda a tela de debug que mostra o log de envio do email                     
        $mail->isSMTP();                                            
        $mail->Host       = 'smtp.xxx.com'; // SMTP do Email que irá ser utilizado           
        $mail->SMTPAuth   = true;                                   
        $mail->Username   = 'suporte@luianalves.com.br'; // Email de Login                   
        $mail->Password   = '*'; // Senha   
        $mail->SMTPSecure = 'tls';         
        $mail->Port       = 587;                                    

        // ------ Atribuindo Valores aos Métodos da instancia $mail do Objeto [PHPMailer]  
        $mail->setFrom('suporte@luianalves.com.br', 'Teste da Aplicação Remetente'); // Seta o Remetente
        $mail->addAddress($mensagem->__get('para')); // Seta o Destinatário || Utilizar a instanciação para recupera o valor recebido via post, porém como o atributo é private precisa utilizar o método get
        
        // $mail->addAddress('ellen@example.com'); // Utilizando o método addAddress podemos adicionar quantos destinatarios quisermos             
        // $mail->addReplyTo('info@example.com', 'Information'); // Aqui o email recebe a Resposta do Destinatário [addAddress]
        // $mail->addCC('cc@example.com'); // Destinatários de Cópia
        // $mail->addBCC('bcc@example.com'); // Destinatários de Cópia Oculta

        // Possibilidade de Incluir Anexos ao Email
        // $mail->addAttachment('/var/tmp/file.tar.gz');         
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    

        // ------ Conteudo do Email
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8'; // Formatação do Texto                                  
        $mail->Subject = $mensagem->__get('assunto'); // Assunto ||  Utilizar a instanciação para recupera o valor recebido via post, porém como o atributo é private precisa utilizar o método get
        $mail->Body    = $mensagem->__get('mensagem'); // Corpo do email [Podemos utilizar tag de html] ||  Utilizar a instanciação para recupera o valor recebido via post, porém como o atributo é private precisa utilizar o método get
        $mail->AltBody = 'É necessário utilizar um Client que suporte HTML para visualizar corretamente este Email!'; // Corpo do email alternativo onde não existe tags html

        $mail->send();

        // Atribuindo o atributo $status para notificar se foi enviado com sucesso ou não
        $mensagem->status['codigo_status'] = 1;
        $mensagem->status['descricao_status'] = 'Email Enviado com Sucesso!';

    } catch (Exception $e) {

        $mensagem->status['codigo_status'] = 2;
        $mensagem->status['descricao_status'] = 'Não foi possível enviar este Email! Tente novamente em alguns instantes. Detalhe do erro: ' . $mail->ErrorInfo;

    }

?>

<!-- Página de Exibição após o Envio -->

<!DOCTYPE html>
<html lang="pt">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>App Mail Send</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    </head>
    <body>

        <div class="container">
            <div class="py-3 text-center">
				<img class="d-block mx-auto mb-2" src="logo.png" alt="" width="72" height="72">
				<h2>Send Mail</h2>
				<p class="lead">Seu app de envio de e-mails particular!</p>
			</div>
        </div>

        <div class="row">
            <div class="col-md-12">
                
                <!-- Recuperando o valor de $status para retornar se foi enviado com sucesso ou não -->
                <!-- Caso tenha Sucesso -->
                <?php if($mensagem->status['codigo_status'] == 1) { ?>
                    <div class="container">
                        <h1 class="display-4 text-success">Sucesso</h1>
                        <p><?= $mensagem->status['descricao_status'] ?></p> <!-- Tag de impressão para recuperar o atributo status -->
                        <a href="index.php" class="btn btn-success btn-sm mt-5 text-white">Voltar</a>
                    </div>
                <?php } ?>

                <!-- Caso não tenha Sucesso -->
                <?php if($mensagem->status['codigo_status'] == 2) { ?>
                    <div class="container">
                        <h1 class="display-4 text-danger">Ops!</h1>
                        <p><?= $mensagem->status['descricao_status'] ?></p> <!-- Tag de impressão para recuperar o atributo status -->
                        <a href="index.php" class="btn btn-danger btn-sm mt-5 text-white">Voltar</a>
                    </div>
                <?php } ?>

            </div>
        </div>
    </body>
</html>
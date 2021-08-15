# PHP EnviarEmail

#Necessário utilizar o Wamp ou algum parecido para rodar a aplicação.

#A pasta protegendoArquivo foi criada para manter os arquivos com dados 'privados' e mantê-los fora da pasta 'public' no servidor de hospendagem. Caso queira mudar os arquivos para outro local é necessário mudar o caminho de redirecionamento em:

./processa_envio.php


#No arquivo processa_envio é necessário adicionar as configurações para que a aplicação funcione.


        $mail->Host       = 'smtp.xxx.com'; // SMTP do Email que irá ser utilizado           
                                         
        $mail->Username   = 'suporte@luianalves.com.br'; // Email de Login                   
        $mail->Password   = '*****'; // Senha   
       
        $mail->Port       = 587;  Porta                                   

     
        $mail->setFrom('suporte@luianalves.com.br', Setar o Remetente
        $mail->addAddress($mensagem->__get('para')); // Destinatário || Utilizar a instanciação para recupera o valor recebido via post ('para').














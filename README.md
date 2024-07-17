# Desafio Técnico PHP - Desenvolvedor Full Stack Pleno

<br>
<hr>
<br>

## 🚀 Instalando dependências

<h3>Windows:</h3>
O projeto foi desenvolvido utilizando <strong>PHP 8.3.9</strong> na opção <strong>x64 Non Thread Safe</strong>
<br>
Download do PHP: <a href="https://windows.php.net/downloads/releases/php-8.3.9-nts-Win32-vs16-x64.zip" target="_blank">Clique aqui</a>
<br>
Basta baixar o arquivo ZIP, e extrair o conteúdo no diretório <strong>C:/php/</strong>
<br><br>
Depois disso, é necessário criar uma variável de ambiente para que o CMD reconheça o PHP. Abra o menu Iniciar do Windows, pesquise por "<strong>Configurações Avançadas do Sistema</strong>". Irá abrir uma janela, clique em "<strong>Variáveis de Ambiente</strong>" (último botão no canto inferior direito), e dentro da caixa de Variáveis de Usuário para SEU_NOME, dê um duplo clique em "<strong>Path</strong>
<br>
Clique em Novo, e coloque o caminho strong>C:\php</strong> (com barra invertida).
<br>
Clique em OK, depois OK novamente até fechar as janelas abertas. Para testar, abra o CMD e digite <strong>php -v</strong> - você deverá ver uma mensagem informando a versão do PHP que você baixou.
<br>
Em caso de dúvidas, basta seguir <a href="https://youtu.be/JQU8CmmHJpM?si=wPdcIspOH146FZG1" target="_blank">este tutorial no YouTube</a>.
<br><br><br>
Com o PHP devidamente instalado, agora é necessário configurar para que ele funcione com SQL Server.
<br>
Para isso, <a href="https://download.microsoft.com/download/2/c/6/2c62d969-ea0d-48af-95cc-6838ac93e872/SQLSRV512.ZIP" target="_blank">baixe estes arquivos clicando aqui</a> ou acessando o site https://learn.microsoft.com/pt-br/sql/connect/php/download-drivers-php-sql-server?view=sql-server-ver16
<br>
Nesta pasta irá conter diversos arquivos. Antes de extraí-los, assista a <a href="https://youtu.be/3VJ1njom9F4?si=-jRVJXfDiqcWTJH1&t=196">esse vídeo do Youtube</a> a partir de 3:16. Ele irá instruir para acessar uma tela com as informações do PHP. Como não iremos utilizar o xampp como ele utiliza no vídeo, siga as etapas abaixo:
<br><br>
Crie uma pasta em algum diretório do seu computador. Abra essa pasta e crie um arquivo de texto, renomeie-o para "<strong>index.php</strong>" e coloque o código abaixo:

```
<?php;

phpinfo();

?>
```
Depois disso, abra o CMD no diretório em que você criou o arquivo e digite "<strong>php -S localhost:8010</strong>" sem aspas. Após, abra o navegador e acesse o link <a href="http://localhost:8010/index.php" target="_blank">http://localhost:8010/index.php</a>
<br><br>
Apartir daqui pode seguir o vídeo do YouTube mencionado para localizar as DLLs que você pecisará extrair do arquivo que foi baixado.
<br>
As DLLs em questão deverão ser extraídas para a pasta <strong>C:/php/ext</strong>, e após isso renomeadas para <strong>php_pdo_sqlsrv.dll</strong> e <strong>php_sqlsrv.dll</strong>
<br>
ATENTE-SE PARA EXTRAIR AS DLLS CORRETAS - SIGA AS ETAPAS DO VÍDEO MINUCIOSAMENTE.
<br><br>
Após extrair as DLLs, para a pasta, volte para a pasta raíz do PHP no disco C:/php, abra o arquivo <strong>php.ini</strong> com qualquer editor de texto, e localize a linha <strong>;extension=mysql</strong> - normalmente fica próximo à linha 920
<br>
Essa linha provavelmente estará comentada, no caso, com um ";" antes dela. Nós não iremos descomentá-la (caso já esteja descomentada, não tem problema, não irá interferir no projeto).
<br>
Copie essa linha e cole abaixo da ultima extension disponível.
<br>
Agora sim, descomente-a e renomeie para <strong>extension=pdo_sqlsvr</strong>
<br>
Copie essa linha e cole logo abaixo com o nome <strong>extension=pdo_pdo_sqlsvr</strong>
<br>
Seu arquivo ficará mais ou menos assim:
```
;extension=xxx
;extension=yyy
;extension=zzz
extension=pdo_sqlsvr
extension=pdo_pdo_sqlsvr
```
Depois disso, basta voltar no CMD na pasta que você criou o index.php, aperte CTRL+C para parar o servidor, e inicie-o novamente.
<br>
Abra o navegador e acesse o endereço <strong>http://localhost:8010/index.php</strong> novamente. Aperte CTRL+F e pesquise por <strong>sqlsvr</strong>
<br>
Caso não traga nenhum resultado, então repita as etapas para evitar quaiquer erros.
<br><br>
Estas são as configurações essenciais para funcionamento do PHP com SQL Server.
Em casos de dúvidas, pode entrar em contato comigo via WhatsApp ou Email.
<br><br>

## ☕ Usando projeto

Para usar o projeto, siga estas etapas:
<br><br>

1- Abra o Microsoft SQL Server Management Studio, e acesso o banco local com as seguintes informações:
<ul><strong>Tipo de Servidor:</strong> Mecanismo de Banco de Dados</ul>
<ul><strong>Nome do Servidor:</strong> localhost</ul>
<ul><strong>Autenticação:</strong> Autenticação do Windows</ul>
<br><br>
2- Após logado, no menu "Pesquisador de Objetos" à esquerda, clique com o botão direito em "<strong>Banco de Dados</strong>"
<ul>Selecione a opção "<strong>Restaurar Banco de Dados</strong>"</ul>
<ul>Na janela que abrir, selecione a opção "<strong>Dispositivo</strong>" e clique no "<strong>...</strong>"</ul>
<ul>Clique em "Adicionar" e navege até a pasta em que você baixou o repositório. Acesse a pasta "<strong>bkpBanco</strong>" e selecione o arquivo "<strong>backup_softexpert.bak</strong>" e clique em OK.</ul>
<ul>Agora, sem alterar nada, basta clicar em "OK" que o banco de dados será criado.</ul>
<br>
Caso tenha dúvidas, pode acompanhar por <a href="https://youtu.be/eUY4yy73pTQ?si=MpYFvILnB9ilOfQX&t=428" target "_blank">este vídeo</a>, a partir de 7:08, que é bem explicadinho, sem segredo.
<br>
Ou se preferir, o link do vídeo é https://youtu.be/eUY4yy73pTQ?si=MpYFvILnB9ilOfQX&t=429
<br><br>
3- Depois de ter restaurado o banco, chegamos à ultima etapa, que é acessar o projeto!
<ul>Primeiro, abra o CMD no diretório em que clonou o repositório, e digite o comando "<strong>php -S localhost:8010</strong>"</ul>
<ul>Após isso, basta acessar o arquivo index.php <a href="http://localhost:8010/index.php" target="_blank">clicando aqui</a>, ou acessando pelo link <a href="http://localhost:8010/index.php" target="_blank">http://localhost:8010/index.php</a> </ul>
<br>
Prontinho! Caso tenha alguma dúvida ou problema para acessar o projeto, pode me ligar por telefone, mandar um email ou mesmo me chamar no WhatsApp. Estarei à disposição!
<br><br><br><br>
Att.
<h3>Felipe Cunha Marchetti</h3>

---
title: Acesse arquivos remoto com SFTP e FileZilla
category_label: Segurança
category: seguranca
---

**FTP** ([File Transfer Protocol][ftp]) é o protrocolo muito utilizado para transferência de arquivos, porém o [uso do FTP não é seguro][1], a maneira mais segura para transferência de arquivos entre um computador local e servidor remoto é através do SFTP (Secure Shell File Transfer Protocol).

Embora a semelhança com nome FTP, o SFTP possui características diferentes, uma delas é o tipo de autenticação e transferência de arquivos que é baseado em SSH Keys uma método com criptografia de chave pública, o que torna a comunicação entre um computador local e o servidor remoto totalmente segura.

A maneira para conectar a um servidor SFTP é um pouco diferente do FTP pois você deve utilizar sua SSH keys para estabelecer a autenticação com o servidor remoto. Neste post explico passo a passo como configurar uma conexão com servidor SFTP utilizando o FileZilla.

### Requisitos

Para configurar uma conexão SFTP com FileZilla você precisa:

1. [Criar sua SSH Key-pair][2].
2. Endereço do servidor remoto devidamente configurado com SSH e SFTP.
3. FileZilla.

## Passo 1: Adicionar Private Key ao FileZilla

O FileZilla precisa saber onde esta sua chave privada (*Private Key*) criada anteriormente para ser usada toda vez que for estabelecer uma conexão com o servidor SFTP. Para dizer ao FileZilla o caminho até sua chave:

<div class="img-wrap text-center">
<img src="/images/2015/01/sftp-filezilla-1.jpg" alt="Tela de configurações do FileZilla" title="Tela de configurações do FileZilla" class="img-thumbnail" width="400">
<span>imagem 1: Tela de configurações do FileZilla</span>
</div>

- Abra o FileZilla.

- Vá ao menu superior ***Editar > Configurações***.

- Na tela de configurações <small>imagem 1</small> expanda o item ***Conexão*** e clique em ***SFTP***.

- Clique no botão ***Adicionar Arquivo de Chave***.

- Localize sua Private Key e clique ***Abrir***, em seguida clique em ***OK*** na tela de configurações. 

> NOTA: O FileZilla suporta Private Keys no formato `.ppk` Se sua Private Key é um arquivo no formato `.pem` ou não foi gerada usando PuTTY o FileZilla exibirá uma janela de dialogo dizendo: *arquivo não esta no formato suportado pelo FileZilla*, e ainda se você definiu um Passphrase para sua chave a seguinte mensagem também será exibida: *O arquivo esta protegido por senha deseja criar uma chave desprotegida no formato reconhecido*. Se isso acontecer clique em ***Sim*** e digite sua senha se for o caso de ter definido Passphrase e depois salve e uma cópia da Private Key no formato `.ppk` em algum lugar seguro no seu computador.


## Passo 2: Criar uma conexão SFTP

Agora que o FileZilla sabe o caminho de sua Private Key você precisa criar uma nova entrada no ***Gerenciador de Sites*** e informar os dados do servidor SFTP:

<div class="img-wrap text-center">
<img src="/images/2015/01/sftp-filezilla-2.jpg" alt="Tela Gerenciamento de Sites do FileZilla" title="Tela Gerenciamento de Sites do FileZilla" class="img-thumbnail" width="400">
<span>imagem 2.1: Tela Gerenciamento de Sites do FileZilla</span>
</div>

- Vá ao menu superior clique em ***Arquivo > Gerenciamento de Sites*** ou pressione ***crtl+S*** ou ***cmd+S*** no OSX.

- Clique no botão ***Novo Site***.

- Na guia ***Geral*** preencha os dados do servidor SFTP:  
	- **Host**: Informe o endereço IP do servidor
	- **Porta**: geralmente esse valor é 22
	- **Protocolo**: selecione ***SFTP - SSH File Transfer Protocol***
	- **Tipo de Login**: selecione ***Normal***
	- **Usuário**: informe seu nome usuário
	- **Senha**: deixe em branco pois não é necessário

Clique em ***Conectar***, e pronto se tudo ocorreu como previsto deverá visualizar a janela comum de transferência de arquivos:

<div class="img-wrap text-center">
<img src="/images/2015/01/sftp-filezilla-3.jpg" alt="Tela de transferência de arquivo via SFTP do FileZilla" title="Tela de transferência de arquivo via SFTP do FileZilla" class="img-thumbnail" width="400">
<span>imagem 2.2: Tela de transferência de arquivo via SFTP do FileZilla</span>
</div>


[1]: /blog/seguranca/ftp-e-nao-e-seguro-saiba-por-que.html
[2]: /blog/seguranca/como-criar-ssh-key-pair-windows.html
[3]: https://filezilla-project.org/

[ftp]: http://en.wikipedia.org/wiki/File_Transfer_Protocol


[ref1]: https://www.digitalocean.com/community/tutorials/how-to-use-sftp-to-securely-transfer-files-with-a-remote-server
[ref2]: http://engineering.deccanhosts.com/2013/02/why-is-ftp-insecure.html
[ref3]: http://www.raditha.com/php/ftp/security.php

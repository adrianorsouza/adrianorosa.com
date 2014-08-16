---
title: Configurar nginx HTTPS server com self-signed SSL certificado
category_label: Nginx
category: nginx
---

Certificado SSL é uma maneira de criar uma conexão mais segura criptografando as informações transmitidas entre o cliente e o servidor onde o certificado está instalado. Este certificado ainda exibe informações de identificação do servidor diretamente no browser do usuário. Certificados SSL normalmente são emitidos por empresas chamadas de Certificate Authorities que verifica os detalhes do servidor, mas também é possível emitir Self-signed SSL certificate. Este post mostra passo a passo como criar self-signed certificate e configurar nginx com um HTTPS server.

## 1. Criar self-signed SSL certificado

### Requisitos
1. Nginx webserver previamente [instalado e configurado](/blog/nginx/instalar-nginx-no-osx-mavericks.html).
2. OpenSSL library.

Openssl é requerido para gerar seu próprio certificado para verificar se essa library existe no sistema abra o terminal e execute o seguinte comando:

	which openssl
	# /usr/bin/openssl

Após verificar todos os requisitos, crie um diretório para armazenar o certificado:

	mkdir /usr/local/nginx/ssl

Mude o diretório antes de executar o comando `openssl`:

	cd /usr/local/nginx/ssl
	
É possível gerar o certificado com apenas um comando:

	openssl req -x509 -nodes -newkey rsa:2048 -keyout cert.key -out cert.crt -days 365

O comando acima irá criar dois arquivos `cert.key` e `cert.crt` necessários para configurar nginx HTTPS server.

Todo o setup deste post pode ser feito em apenas um comando, o script esta disponibilizado neste [gist](https://gist.github.com/adrianorsouza/2bbfe5e197ce1c0b97c8).

## 2. Configurar nginx HTTPS server com certificado SSL
No arquivo de configuração do nginx vamos configurar um virtual HTTPS server e inserir as informações do certificado dentro do [server block](http://nginx.org/en/docs/http/ngx_http_core_module.html#server):

```
server {
    listen              443 ssl;
    server_name         localhost;
    ssl_certificate     /usr/local/nginx/ssl/cert.crt;
    ssl_certificate_key /usr/local/nginx/ssl/cert.key;
    ssl_protocols       SSLv3 TLSv1 TLSv1.1 TLSv1.2;
    ssl_ciphers         HIGH:!aNULL:!MD5;
    ...
}
```	

Reinicie o nginx

	sudo nginx -s reload

Testar, acesse no navegador o seguinte endereço: 

	https://localhost

Se tudo ocorreu de forma correta deverá ser exibido uma tela de aviso: 

<img src="/images/2014/04/ssl-error.png" alt="" class="img-responsive img-thumbnail">

A tela de *aviso certificado inválido* pode ser diferente dependendo do browser que você usa, no Chrome é exibido a tela acima, onde é possível ignorar clicando em avançado em seguida prosseguir.

### Conclusão:
Criar Self-signed SSL certificate é uma maneira rápida e sem custo para adicionar uma camada de criptografia SSL no seu servidor de testes ou de desenvolvimento. Este certificado, por ser self-signed, não é recomendado para o ambiente de produção pois não possui uma assinatura validada por uma Certificate Authority confiável do seu browser, então será comum aparecer uma tela de aviso quando seu site for acessado através do protocolo `https://` utilizando esse tipo de certificado.

**Referências:**<br>
[http://nginx.org/en/docs/http/configuring_https_servers.html](http://nginx.org/en/docs/http/configuring_https_servers.html)  
[https://www.openssl.org/docs/apps/openssl.html](https://www.openssl.org/docs/apps/openssl.html)


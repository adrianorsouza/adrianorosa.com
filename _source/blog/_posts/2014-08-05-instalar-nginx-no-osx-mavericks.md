---
title: Instalar NGINX no OSX Mavericks
category_label: Nginx
category: nginx
---
Esse post é um guia passo a passo para compilar e instalar o [Nginx](http://nginx.org/) no Mac OSX Mavericks. Existem outros meios para instalar o Nginx usando *package manager* por ex. o [Homebrew](http://brew.sh/) ou [MacPorts](https://www.macports.org/), no Mountain Lion utilizava o Homebrew, no entanto, não tenho mais feito o uso de *package manager* pois eles instalam uma tonelada de códigos arbitrários no sistema e ambos dependem do **Xcode** para sua execução, então não vejo mais a necessidade uma vez que é possível customizar e compilar qualquer programa facilmente só com Xcode e Command Line Tools instalado.

## Requisitos

- [XCode](http://itunes.apple.com/us/app/xcode/id497799835?ls=1&mt=12) com Command Line Tools instalado.

Baixe e instale o XCode e após sua instalação é preciso instalar Command Line Tools executando o seguinte comando no terminal:

	xcode-select --install
	
Geralmente arquivos de programas instalados pelo usuário são salvos no diretório `/usr/local` e para o download dos sources vou utilizar o diretório `usr/local/src`. Se esses diretórios não existir crie e altere as permissões para seu usuário.

	sudo mkdir -p /usr/local
	sudo mkdir -p /usr/local/src
	sudo chown -R $USER:staff /usr/local
	
## Instalar o nginx

Nesse post vou utilizar a versão 1.6.1 do Nginx mais recente e estável mas o procedimento é o mesmo para versões anteriores ou posterior.

### Detalhes da instalação:
**Link:** [http://nginx.org](http://nginx.org)<br>
**Versão:** 1.6.1<br>
**Dependências:**<br>

1. **PCRE**: 
  - Link: [http://www.pcre.org](http://www.pcre.org)  
  - Versão: 8.33 
	- Detalhes: Essa library é requisito para suporte as expressões regulares.
2. **ZLIB**:
  - Link: http://zlib.net:
  - Versão: 1.2.8  
	- Detalhes: Essa library é requisito para o `ngx_http_gzip_module` módulo.


> As dependencias não precisam ser compiladas e instaladas separadamente, basta desempacotar os arquivos e dizer ao Nginx durante o [Compiler Time](http://en.wikipedia.org/wiki/Compile_time) o Nginx faz o restante do serviço.

### Baixar os sources:
Abra o terminal e altere o diretório

	cd /usr/local/src
	
**Download PCRE**

    curl -OL ftp://ftp.csx.cam.ac.uk/pub/software/programming/pcre/pcre-8.33.tar.gz 
    tar -xvzf pcre-8.33.tar.gz 
	
**Download ZLIB**

    curl -OL http://zlib.net/zlib-1.2.8.tar.gz
    tar -xvzf zlib-1.2.8.tar.gz

**Download NGINX v1.6.1 stable**

    curl -OL http://nginx.org/download/nginx-1.6.1.tar.gz
    tar -zvxf nginx-1.6.1.tar.gz	

**Compilar e instalar nginx**
	
Altere para o diretório do nginx sources:

    cd nginx-1.6.1

> NOTA: O nginx deve ser configurado e instalado usando o usuário `root` caso contrário poderá ocorrer alguns problemas durante o [processo de inicialização](https://developer.apple.com/library/mac/documentation/Darwin/Reference/Manpages/man1/launchctl.1.html#//apple_ref/doc/man/1/launchctl) nesse caso vamos usar `sudo`

Customizar a instalação passando os devidos parâmetros para o comando `./configure`.
	
	sudo ./configure \
	--prefix=/usr/local/nginx-1.6.1 \
	--conf-path=/usr/local/nginx-1.6.1/conf/nginx.conf \
	--http-log-path=/var/log/nginx-access.log \
	--error-log-path=/var/log/nginx-error.log \
	--user=_www \
	--group=_www \
	--with-http_ssl_module \
	--with-http_realip_module \
	--with-http_addition_module \
	--with-http_sub_module \
	--with-http_dav_module \
	--with-http_flv_module \
	--with-http_mp4_module \
	--with-http_gunzip_module \
	--with-http_gzip_static_module \
	--with-http_random_index_module \
	--with-http_secure_link_module \
	--with-http_stub_status_module \
	--with-http_auth_request_module \
	--with-mail \
	--with-mail_ssl_module \
	--with-http_spdy_module \
	--with-ipv6 \
	--with-http_xslt_module \
	--with-pcre=../pcre-8.33 \
	--with-zlib=../zlib-1.2.8 \
	--with-cc-opt=-Wno-deprecated-declarations

Compilar e Instalar	

	make
	make install

Pós Instalação
---

Criar symlink apontando para `/usr/local/nginx`.

    ln -sv /usr/local/nginx-1.6.1 /usr/local/nginx 

Adicionar nginx ao PATH

    echo 'export PATH=/usr/local/nginx/sbin:$PATH' >> ~/.bash_profile

Reload bash
    
    source ~/.bash_profile

Verificar a instalação do nginx

    nginx -v

Deverá ser exibido:

    # nginx version: nginx/1.6.1    
   
Iniciar o nginx

    sudo nginx

Autoload nginx durante a inicialização do sistema.
---

Crie uma plist `com.nginx.nginx.plist` no diretório `/Library/LaunchDaemons`.  
 

```
sudo nano /Library/LaunchDaemons/com.nginx.nginx.plist
```
    
Conteúdo da plist: 

```
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
    <key>Label</key>
    <string>com.nginx.nginx</string>
    <key>ProgramArguments</key>
	  <array>
	    <string>/usr/local/nginx/sbin/nginx</string>
	    <string>-c</string>
	    <string>/usr/local/nginx/conf/nginx.conf</string>
	  </array>
    <key>KeepAlive</key>
    <true/>
    <key>LaunchOnlyOnce</key>
    <true/>
</dict>
</plist>
```  

Esta plist auto inicia o nginx durante o system startup.

**Testar a plist durante o system startup:**  

> NOTA: O processo nginx deve ser finalizado para executar a plist.

Comando para finalizar processo nginx antes de carregar a plist:
    
    sudo nginx -s stop

Testar auto-start nginx com a recém criada plist:

    sudo launchctl load /Library/LaunchDaemons/com.nginx.nginx.plist
        
Comando para verificar se o processo do nginx foi corretamente inicializado:

    ps axw -o pid,ppid,user,%cpu,command | egrep '(nginx|PID)'

Deverá exibir algo como:
     
      PID  PPID USER             %CPU COMMAND
    29733     1 root              0.0 nginx: master process /usr/local/nginx/sbin/nginx -c /usr/local/nginx/conf/nginx.conf  
    29784 29733 _www              0.0 nginx: worker process  
    29785 29733 _www              0.0 nginx: worker process  
    29786 29733 _www              0.0 nginx: worker process     
    30007 28694 username          0.0 egrep (nginx|PID)

Recarregar o nginx processo:

    sudo nginx -s reload

>NOTA: A PLIST tem a função de iniciar o processo nginx uma vez durante a inicialização do sistema. Se tentar executar a plist mais de uma vez o seguinte erro pode acontecer:

    [emerg] 29763#0: bind() to 0.0.0.0:80 failed (48: Address already in use)
	
Se precisar que o nginx carregue alterações feitas nos arquivos de configuração a melhor maneira de fazer o reload do nginx é através do comando `sudo nginx -s reload` como mencionado anteriormente.

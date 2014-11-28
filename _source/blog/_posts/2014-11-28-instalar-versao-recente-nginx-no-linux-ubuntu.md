---
title: Instalar versão mais recente do Nginx no Linux Ubuntu
category_label: Nginx
category: nginx
---

Instalar o Nginx no Linux é muito simples usando package manager, basta executar comando `sudo apt-get install nginx` e pronto, mas o problema que atualmente o Ubuntu 14.10 é entregue com a versão `1.4.6` do Nginx pre-instalado em seu repositório de pacotes. É recomendado instalar a versão mais recente e estável de um pacote, no caso do Nginx, hoje a versão mais recente e estável é `1.6.2`. Para instalar a versão do Nginx mais recente é necessário algumas configurações no package manager.

1. Remover o Apache completamente
-------------------------------------------
Caso não tenha o Apache instalado pule para o passo 2.

Se o Apache foi instalado anteriormente e você não deseja utilizar os dois webserver Apache e Nginx é recomendado que remova completamente o Apache para evitar conflitos e erro de bind na porta 80.

Para remover o Apache:

	sudo apt-get purge apache2*	
	sudo apt-get autoremove

2. Configure Nginx PGP signing_key
--------------------------------
É necessário adicionar PGP Key usado nos pacotes nginx para autenticação ao repositório do nginx e evitar alertas de PGP key durante a instalação.

Baixe nginx PGP signin key e adicione ao `apt`:

	curl http://nginx.org/keys/nginx_signing.key 

Alternativa, se não tiver o `curl` instalado utilize `wget`

	sudo wget http://nginx.org/keys/nginx_signing.key

Adicionar a chave ao `apt`

	sudo apt-key add nginx_signing.key

3. Update source.list
----------------------
Adicione o repositório `http://nginx.org/packages/ubuntu` ao source.list para isso é necessário saber qual o codename do Ubuntu que esta usando, esse nome varia de acordo com a distribuição do Ubuntu, isso é importante para que possa instalar a versão correta do Nginx no seu sistema.

Para descobrir a qual o codename do sua versão do Ubuntu execute o comando:

	cat /etc/lsb-release | grep DISTRIB_CODENAME

Será exibido algo como `DISTRIB_CODENAME=trusty` onde nesse exemplo *trusty* será o seu codename.

Agora, finalmente crie uma nova *source.list*

	sudo nano /etc/apt/sources.list.d/nginx.list

E adicione as seguintes linhas nesse arquivo:

	deb http://nginx.org/packages/ubuntu trusty nginx
	deb-src http://nginx.org/packages/ubuntu trusty nginx
> NOTA: substitua os valores *trusty* se seu codename for diferente.

4. Instalar a versão recente do Nginx
--------------------------------------
Primeiro se você instalou alguma versão antiga do Nginx(como a versão nativa pre-instalado nas distribuições) você pode remover completamente essa versão utilizando o seguinte comando:

	sudo aptitude purge nginx nginx-light nginx-full nginx-extras nginx-common

> Nota: usando o `purge` removerá também arquivos de configuração do nginx

Re-sincronize o index dos repositorios de seus pacotes    

	sudo apt-get update

Verifique a versão do Nginx na sua lista de repositórios:

	sudo apt-cache show nginx
	Package: nginx
	Version: 1.6.2-1~trusty

Agora que já tem o versão recente do Nginx poderá instalar executando o comando:

	sudo apt-get install nginx
	
… Ou se você já tem o nginx instalado e deseja apenas fazer o upgrade:

	apt-get dist-upgrade

Após instalar verifique se o nginx esta em execução:
	
	service nginx status

Se o Nginx não estiver está em execução significa que algum erro aconteceu, talvez porque outro processo esta sendo usado na porta 80, verifique usando o seguinte comando:

	sudo netstat -tulpn

Se outro processo estiver utilizando a porta 80 finalize este processo com o comando:
	
	kill -9 xxxx

Comandos úteis do Nginx
-------------------------

Inicializar o Nginx:

	service nginx start

Reiniciar o Nginx:

	sudo nginx -s reload

Parar o Nginx:
	
	sudo nginx -s stop

Teste a instalação acesse http://localhost

<div class="img-wrap text-center">
<img src="/images/2014/11/nginx.jpg" alt="Nginx" title="Nginx" class="img-thumbnail" width="400">
<span>imagem 1: Tela inicial do Nginx</span>
</div>

### Referências
[http://nginx.org/en/linux_packages.html][nginx]

[nginx]: http://nginx.org/en/linux_packages.html
[server_guide]: https://help.ubuntu.com/lts/serverguide/php5.html 

[1]: http://blog.chrismeller.com/configuring-and-optimizing-php-fpm-and-nginx-on-ubuntu-or-debian
[2]: http://wiki.nginx.org/Install
[3]: https://bjornjohansen.no/install-latest-version-of-nginx-on-ubuntu
[4]: https://www.digitalocean.com/community/articles/how-to-install-the-latest-version-of-nginx-on-ubuntu-12-10
[5]: http://ubuntuhandbook.org/index.php/2013/10/install-nginx-php5-mysql-lemp-ubuntu-1310/ 

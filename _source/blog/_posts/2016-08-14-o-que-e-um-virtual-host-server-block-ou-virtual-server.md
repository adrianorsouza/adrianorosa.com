---
title: O que é um Virtual Host, Server Block ou Virtual Server?
category_label: Webserver
category: webserver
---

**Virtual Host (Apache)** ou **Server Block / [Virtual Server (Nginx)][1]**, em configurações de um webserver, refere-se a uma prática de servir mais de um site no mesmo servidor. Um Virtual Host pode ser baseado em IP, ou seja, diferentes IPs servindo vários sites no mesmo server, ou ainda baseado em nome de domínio, ou seja, vários domínios servindo no mesmo server.

> O termo Virtual Host é normalmente conhecido no Apache, no Nginx a mesma prática é chamada de Server Block ou Virtual Server, pois um Virtual Server é configurado dentro da diretiva `server` block.

## Apache Virtual Host

A configuração de um Virtual Host no Apache é relativamente simples:

### Name-based 
Virtual Host baseado em nome é mais simples pois o server vai servir diferentes sites usando o mesmo endereço IP.

	<VirtualHost *:80>
	    ServerName www.example.com
	    ServerAlias example.com 
	    DocumentRoot "/www/domain"
	</VirtualHost>

	<VirtualHost *:80>
	    ServerName other.example.com
	    DocumentRoot "/www/otherdomain"
	</VirtualHost>

### Ip-based
Virtual Host baseado em IPs requer setup extra de DNS para que funcione corretamente, geralmente configurar name-based é mais simples por conta disso, e a configuração no Apache é assim:

	<VirtualHost 172.20.30.40:80>
	  DocumentRoot "/www/vhosts/www1"
	  ServerName www1.example.com
	</VirtualHost>

	<VirtualHost 172.20.30.50:80>
	  DocumentRoot "/www/vhosts/www2"
	  ServerName www2.example.org
	</VirtualHost>

## Nginx Virtual Host (Server Block / Virtual Server)

No nginx configurar um Virtual Host é muito mais simples, é possível definir um Virtual Host ip-based ou name-based ou ambos no mesmo server block, veja como fica a configuração:

### Name-based

    server {
      listen      80;
       server_name example.net www.example.net;
       root /var/www/site1
       ...
    }

    server {
      listen      80;
      server_name example.com www.example.com;
      root /var/www/site2
      ...
    }

Nesse exemplo acima o webserver determina o host de acordo com request e faz o roteamento para o server de destino.

### Ip-based ou mixed

No mesmo Server Block podemos definir um Virtual Server para ambos ip-based e name-based, veja só:

	server {
	  listen      192.168.1.1:80;
	  server_name example.org www.example.org;
	  ...
	}

	server {
	  listen      192.168.1.2:80;
	  server_name example.com www.example.com;
	  ...
	}
	
### Mais detalhes

https://httpd.apache.org/docs/current/vhosts/  
https://nginx.org/en/docs/http/request_processing.html

[1]: /blog/nginx/setup-server-block-virtual-host-com-nginx.html
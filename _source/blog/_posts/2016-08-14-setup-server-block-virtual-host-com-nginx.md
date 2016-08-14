---
title: Setup Virtual Server com NGINX 
category_label: Nginx
category: nginx
---

Para quem esta acostumado com Apache, já deve ter configurado ou ouvido falar sobre [Virtual Host][vh], eu cheguei a explicar a [diferença entre Server Block/Virtual Server(Nginx) e Virtual Host(Apache)][1]. Bem, isso também é possível no Nginx, mas aqui chamamos de `Server Block` ou `Virtual Server`. 

A configuração no Nginx é feita dentro de blocks `{}`, logo existem diversas diretivas. E para configurar um Virtual Server usamos a diretiva `server`. Dentro dessa diretiva fazemos o setup de um Virtual Server.

Como o Nginx permite vários block no mesmo arquivo de configuração, nesse exemplo vou criar 3 Virtual Servers dentro do block `http`.

```
http {

  # Virtual Server 1 (Site principal)
  server {
    listen      80;
    server_name example.com www.example.com;
    root /var/www/www.example.org
    location / {
        try_files $uri $uri/ =404;
    }    
  }

  # Virtual Server 2 (CMS site)
  server {
    listen      80;
    server_name admin.example.com;
    root /var/www/admin.example.com
    location / {
        try_files $uri $uri/ =404;
    }
  }
  
  # Virtual Server 3 (API site)
  server {
    listen      80;
    server_name api.example.com;
    root /var/www/api.example.com
    location / {
        try_files $uri $uri/ =404;
    }
  }
}
```

### Virtual Host no ambiente de desenvolvimento local
No ambiente de desenvolvimento local também é necessário configurar o arquivo de `hosts`, localizado em `/etc/hosts` em sistemas Unix ou em `%SystemRoot%\System32\drivers\etc\hosts ` no Windows.

No arquivo de `hosts` é necessário adicionar um roteamento para que o domínio que estamos testando seja apontado para o nosso servidor local.

```
127.0.0.1 example.com www.example.com
127.0.0.1 admin.example.com
127.0.0.1 api.example.com
```

Concluído todas as configurações no webserver e arquivo de `hosts`, agora é necessário reiniciar o nginx para alterações terem efeito.

#### Linux

    sudo service nginx reload
    
#### OSX

    sudo nginx -s reload
    
### Conclusão

Com o Nginx percebemos como é fácil o setup de um Virtual Server, o modo de configuração em block permite maior flexibilidade no setup de um ou vários webserver, talvez por isso que eu prefiro Nginx ao invés do Apache, não só por essa questão mais também por outras questão de performance.

[apache]: https://httpd.apache.org/docs/current/vhosts/
[vh]: https://httpd.apache.org/docs/current/vhosts/
[nginx]: https://nginx.org/
[sb]: https://nginx.org/en/docs/http/server_names.html

[1]: /blog/webserver/o-que-e-um-virtual-host-server-block-ou-virtual-server.html
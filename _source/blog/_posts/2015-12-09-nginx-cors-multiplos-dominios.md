---
title: Nginx CORS com múltiplos domínios
category_label: Nginx
category: nginx
---

**CORS** (Cross-origin resource sharing) ou (compartilhamento de recursos de origem cruzada) é um mecanismo que permite que os recursos de acesso restrito (por exemplo, fontes) em uma página web possa ser acessado a partir de outro domínio de origem do qual o recurso foi originado. [Wikipedia][1]

### Como posso definir CORS com Nginx
Implementar CORS é simples basta enviar o seguinte Header:
    
    Access-Control-Allow-Origin: *
    
Para implementar **CORS** de uma maneira mais refinada quando um virtual host manipula mais de um domínio é bem simples.

Basicamente o que precisa ser feito é utilizar um Regex para capturar a lista de domínios que deseja enviar.

Esse exemplo é útil quando seu vhost serve webfonts para múltiplos domínios:

```
location ~* \.(?:ttf|ttc|otf|eot|woff|woff2)$ {
   if ( $http_origin ~* (https?://(.+\.)?(domain1|domain2|domain3)\.(?:net|com)$) ) {
      add_header "Access-Control-Allow-Origin" "$http_origin";
   }
}
```

Nesse exemplo os domínios que poderão acessar os recursos deste server serão:

- domain1.net
- domain1.com
- domain2.net
- domain2.com
- domain3.net
- domain3.com

[1]: https://en.wikipedia.org/wiki/Cross-origin_resource_sharing
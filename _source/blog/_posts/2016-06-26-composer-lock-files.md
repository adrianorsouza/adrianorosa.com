---
title: Composer lock file
category_label: Composer
category: composer
---

Devo ou não fazer o commit do arquivo `composer.lock`. Quando um ou vários packages é instalado como dependência em seu projeto, o Composer vai atualizar as informações de cada package no arquivo `composer.lock`.

Ao fazer o commit do arquivo `composer.lock`, e sempre que utilizar o comando `composer install`, o [**Composer**][1] vai então instalar as versões exatas definidos anteriormente no seu projeto.

Por isso é importante fazer o commit do arquivo `composer.lock` para garantir que as dependências de seu projeto sejam sempre as mesmas, isso vai evitar inconsistências e possíveis erros.

### O que acontece seu eu não fazer o commit do `composer.lock`

Mesmo que esteja trabalhando um projeto sem a colaboração de outros developers pode acontecer inconsistências nas dependências do seu projeto.

Imagine o seguinte cenário onde você instalou o seguinte package como dependência, isso em seu ambiente de desenvolvimento.

```
"require": {
  "league/flysystem-aws-s3-v3": "^1.0"
},
```

Quando você publicar seu projeto no ambiente de produção ou até mesmo em executá-lo em outro local de desenvolvimento, tipicamente você vai executar o comando:

    composer install 
    
Como não vai existir o arquivo `composer.lock` na raiz do projeto, o Composer então vai instalar a versão mais recente do package `league/flysystem-aws-s3-v3`. 

Até aqui tudo bem, mas digamos que alguns dias após o início do desenvolvimento do seu projeto a versão `v1.2.0` do package `league/flysystem-aws-s3-v3` foi lançado, então quando você instalar as dependências ao invés de obter a versão `v1.0.0`, que foi testado no ambiente de desenvolvimento, será instalado a versão `v1.2.x`. 

Nesse momento que surge o problema! Se por ventura, alguma mudança no package teve algum *breaking changes* seu projeto pode estar com algum tipo de bug.

Por isso é fundamental fazer o commit do `composer.lock` sempre que desejar manter a consistência e instalar as versões exatas de suas dependências em seu projeto.

### Em que situação não é necessário *comitar* `composer.lock`

As vezes, é desnecessário *comitar* `composer.lock` quando estiver desenvolvendo uma library.

### Mais detalhes
[https://getcomposer.org/doc/01-basic-usage.md#composer-lock-the-lock-file][2]

[1]: https://getcomposer.org
[2]: https://getcomposer.org/doc/01-basic-usage.md#composer-lock-the-lock-file
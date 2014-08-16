---
title: Como alterar o collate do SQL Server
category_label: Banco de Dados
category: banco-de-dados
date: 2008-10-14 11:25:10
wp_id: 39
wp_slug: como-alterar-o-collate-do-sql-server
---

Algumas vezes, durante a migração de uma base de dados ou na exportação de alguns dados para uma base de outro servidor, podemos ter certos problemas com acentuação. Isto ocorre se os dados da base de origem forem armazenados com seu collate (formato) diferentes da base de destino, ou seja, se temos uma base com seu collate em Latin1_General_CI_AI e exportarmos seus dados para uma outra base no qual o collate definido seja diferente a acentuação destes registros ficarão de forma truncada.

**Vejamos um exemplo**  
A palavra "Nova Zelândia" foi registrada no banco com collate em `Latin1_General_CI_AI` . Ao exportamos estes dados para uma outra base onde o collate padrão seja `SQL_Latin1_General_CP850_CI_AI`, iremos visualizar a mesma palavra desta forma "Nova Zel?ndia".

Você pode evitar esse tipo de transtorno antes de exportar ou migrar os dados, mas antes verifique o collate da base de destino, executando o comando:

    SP_HELPDB

No assistente Query Analizer, na a coluna status poderá ser visto o collate que está sendo usado,

<img class="size-full wp-image-40" title="sql1" src="/images/2011/04/sql1.jpg" alt="sql1" width="480" height="228" />

Para alterar o collate padrão da base, precisamos antes ajustar o banco para o modo `SINGLE_USER`, caso contrário teremos a mensagem de erro:

    The database could not be exclusively locked to perform the operation.

Veja os comandos:
{% highlight sql %}
ALTER DATABASE NOMEBANCO SET SINGLE_USER WITH ROLLBACK IMMEDIATE
ALTER DATABASE NOMEBANCO COLLATE NOVO_COLLATION
ALTER DATABASE NOMEBANCO SET MULTI_USER
{% endhighlight %}

Explicando o código:

1º linha altera o banco para o modo `single_user`  
2º linha modifica o `Collate` para `Latin1_General_CI_AI`  
3º linha é redefinido o banco para o modo `multi_user`  

Executando no <em>Query Analizer</em> teremos assim:

<img class="size-full wp-image-41" title="sql2" src="/images/2011/04/sql2.jpg" alt="sql2" width="490" height="283" />

A mensagem: 

    Nonqualified transactions are being rolled back. Estimated rollback completion: 100%.
    
Significa que o procedimento foi efetuado com sucesso.

Agora é possível exportar ou migrar os dados de uma base para outra sem que tenhamos problemas com acentuação.

Lembrando que este procedimento irá alterar o collate do banco de dados. Na criação de novas tabelas estará com o novo collation definido, porém as tabelas já existentes permanecerão com o collation anterior.

---
title: Como copiar estrutura de tabelas no SQL Server
category_label: Banco de Dados
category: banco-de-dados
date: 2008-10-20 10:58:29
wp_id: 44
wp_slug: como-copiar-estrutura-de-tabelas-no-sql-server
---

Em algumas situações o desenvolvedor deseja copiar a estrutura e/ou estrutura e dados de tabelas no SQL Server. A primeira coisa que tentaria seria usar ctrl+c e ctrl+v, mas logo verá que esta condição não será possível.

Você pode efetuar este procedimento usando consultas T-SQL, a sintaxe é bem simples. Basta executar alguns comandos no SQL Query Analyzer ou até mesmo no modo Query da tabela que deseja copiar.

Veja abaixo o código T-SQL para copiar toda a estrutura da tabela e seus dados:

{% highlight sql %}
SELECT * INTO NOME_NOVATABELA FROM TABELAEXISTENTE WHERE 1=1
{% endhighlight %}

Explicando:  
Neste exemplo, selecionamos a tabela TABELAATUAL e copiamos sua estrutura para NOME_NOVATABELA utilizando a cláusula INTO.

Na cláusula WHERE = 1=1 dizemos que a expressão é verdadeira. Então, além da estrutura, os dados também são copiados para a nova tabela.

Outro exemplo é onde podemos copiar apenas a estrutura da tabela sem os dados:

{% highlight sql %} 
SELECT * INTO NOME_NOVATABELA FROM TABELAEXISTENTE WHERE 1=0
{% endhighlight %}

O código é semelhante ao anterior. O que muda são os valores da cláusula `WHERE`. Dizemos que a expressão não é verdadeira, ou seja, `1 = 0`, portanto não será retornado nenhum registro. Então, a tabela é copiada sem registros.

Até a próxima.

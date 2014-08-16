---
title: Resolver problemas com apostrofe ou aspas
category_label: ASP
category: asp
date: 2008-09-23 05:52:16
wp_id: 24
wp_slug: resolver-problemas-com-apostrofe-ou-aspas
---

Como resolver problemas com apóstrofes (“) ou aspas(') em uma declaração SQL? Essa é umas das perguntas mais freqüentes em fórum de discussão sobre ASP e Banco de Dados.

O apóstrofo e as Aspas são caracteres ilegais na linguagem T-SQL porque é interpretado como um delimitador de série para permitir a inserção de um novo registro no Banco de Dados,

No entanto, se você tem um formulário ou QueryString onde receberá um certo tipo de dado e depois registrar esses dados no banco de dados é necessário criar uma rotina para remoção desses caracteres,

Um exemplo - se em um formulário onde usuário preenche campo nome com os seguintes valores (Marco D’Alberto ou Adriano “Rosa”), você terá alguns problemas para executar a inserção desses valores,

Para isso utilize uma função <a href="/blog/asp/funcao-replace-do-asp.html">Replace</a> ou Expressão Regular para substituir esses caracteres Especiais, de modo que não afete o resultado final da coleta destes dados.

Uma demonstração de forma simples de uma função para substituir esses caracteres, mas existem diversas funções bem mais complexas onde é possível até se prevenir contra ataques de SQL-INJECTION não irei abordar este assunto neste artigo. Em um próximo artigo falarei como se prevenir de Injeção SQL.

```VB.NET
<%
 texto1  = "texto com (') e ("")"
 Function sEncode(str)
     strChr = Replace(str,"""","&quot;",1,-1,0)
     strChr = Replace(str,"'","&rsquo;",1,-1,0)
     sEncode = strChr
 End Function
 With Response
.Write ("INSERT tabela (campo1)VALUES(" & sEncode(texto1) & ")")
End With
%>
```

output: 
{% highlight sql %}
INSERT tabela (campo1)VALUES(texto com (&amp;rsquo;) e ("))
{% endhighlight %}

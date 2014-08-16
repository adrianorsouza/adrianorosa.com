---
title: Função Replace do Asp
category_label: ASP
category: asp
date: 2008-08-25 11:13:40
wp_id: 1
wp_slug: funcao-replace-do-asp
---

Neste artigo vou explicar alguns exemplos sobre a função Replace do ASP (VBScript) e seus parâmetros que utilizamos para substituir uma parte de uma determinada string por outra especificada. Poucos conhecem estes parâmetros do Replace que são úteis e necessários em alguns casos.

Lembrando que o Replace pode ser Case Sensitive ou Insensitive, portanto fique atento ao seu uso de parâmetros.

Sintaxe:

<em>**O seu modo simples:**</em> Replace (Texto, Encontre, Substitua)

<em>**Especifico:**</em> Replace(Texto, Encontre, Substitua, inicio, count, compara )

**Os parâmetros do Replace são:**

- Texto = (Exigido) Texto a substituir
- Encontre = (Exigido) Parte do Texto a ser substituído
- Substitua = (Exigido) O Termo de substituição
- Inicio = (Opcional) Especifique a posição de inicio, o padrão é 1.
- Count = (Opcional) Especifique o numero de substituições o padrão é -1, significa que todos as substituições será efetuada,
- Compara = (Opcional) Especifique a comparação da string se Textual ou Binário o padrão é 0 (binária).

**Veja:**  

`0` = `vbBinaryCompare` comparação da string de forma binária. (Sensitive)  
`1` = `vbTextCompare` comparação da string de forma Textual. (Insensitive)  

Vejamos alguns Exemplos:

**Exemplo 1 Simples:**  
Retorna o texto substituído (Case Sensitive/Insensitive)

```VB.NET
<%
Dim texto
Texto = "Lorem ipsum dolor sit amet, consetetur sadipscing elitr"
Response.Write Replace(texto,"m","xxx")
output: Lorexx ipsuxx dolor sit axxet, consetetur sadipscing elitr
Response.Write Replace(texto,"M","xxx")
%>
```

output: Lorem ipsum dolor sit amet, consetetur sadipscing elitr
Exemplo 2, usando o parâmetro Inicio: Retorna o texto substituído a partir da posição informada.

```VB.NET
<%
Dim texto
Texto = "Lorem ipsum dolor sit amet, consetetur sadipscing elitr"
Response.Write Replace(texto,"m","xx",3,-1,1)
%>
```

output: rexx ipsuxx dolor sit axxet, consetetur sadipscing elitr
Exemplo 3 usando parâmetro count:Retorna o texto substituindo apenas a quantidade informada.

```VB.NET
<%
Dim texto
Texto = "Lorem ipsum dolor sit amet, consetetur sadipscing elitr"
Response.Write Replace(texto,"m","xx",1,2,1)
%>
```
output: Lorexx ipsuxx dolor sit amet, consetetur sadipscing elitr
Exemplo 4 usando o parâmetro compare:Retorna o texto Case Sensitive/Insensitive

```VB.NET
<%
Dim texto
Texto = "Austrália"
Response.Write Replace(texto,"a","xx",1,-1,0)
output: Austrálixx
Response.Write Replace(texto,"a","xx",1,-1,1)
%>
```

output: xxustrálixx
bom é isso!.


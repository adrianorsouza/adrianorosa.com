---
title: Transformar em maiúsculo primeira letra de cada sentença
category_label: ASP
category: asp
date: 2008-10-23 17:42:33
wp_id: 46
wp_slug: transformar-em-maiusculo-primeira-letra-de-cada-sentenca
---

Não sabe ao certo a forma em que os usuários do seu site se comportam, ainda mais com relação a preenchimento de formulários de cadastro entre outros, Muitas vezes o usuário que se cadastra no seu site não se preocupa com a maneira em que escreve se em MAIUSCULO ou minúsculo.

Pensando em uma solução quanto a esse problema, criei uma função para formatar certos tipos de textos, é muito útil no preenchimento de campos por ex. nome de usuário em um formulário de cadastro. Essa função formata a primeira letra de uma string em maiúsculo.

Um exemplo é se temos em uma string : 

`joão mendes da silva` ou `JOAO MENDES DA SILVA`


é retornado formato:   

```
João Mendes da Silva
```

Dessa forma podemos armazenar nome de clientes sempre no mesmo padrão.

Veja alguns exemplos da função em ASP 3.0:

```VB.NET
<%
Function UPPER(texto)
    Dim str, i, termo, cont
    quebra = Replace(texto,".","")
    str = Split(quebra," ")
    For i = LBound(str) to UBound(str)
     cont = Len(str(i))
      If ( cont = 1 ) Then
        If ( isNumeric(str(i)) ) Then
          termo = termo & str(i) & " "
        Else
          termo = termo & UCase(str(i)) & ". "
        End If
      ElseIf ( cont = 2 ) Then ' Se caso usar abreviação EX adriano r souza Retorna R.
         termo = termo & LCase(str(i)) & " "
      ElseIf ( cont > 2 ) Then
         termo = termo & UCase(Mid(str(i),1,1)) & LCase(Mid(str(i),2))& " "
      End If
    Next
  UPPER = Trim(termo)
End Function

Dim Ex1,Ex2,Ex3
Ex1 = "joão mendes da silva"
Ex2 = "JOÃO MENDES DA SILVA"
Ex3 = "joão m. silva"
With Response
 .Write UPPER(Ex1) & "<br />"
 .Write UPPER(Ex2) & "<br />"
 .Write UPPER(Ex3) & "<br />"
End With
%>
```

**Retorno da função:**   
1 = João Mendes da Silva  
2 = João Mendes da Silva  
3 = João M. Silva  


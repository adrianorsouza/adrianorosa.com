---
title: Classe Para Enviar Email Usando Cdosys
category_label: ASP
category: asp
date: 2008-08-27 19:50:12
wp_id: 7
wp_slug: classe-para-enviar-email-usando-cdosys
---

Olá pessoal, neste artigo irei mostrar um modo prático para enviar e-mails através do seu site usando Classe do ASP e seus métodos e propriedades,

Em alguns casos, no site precisamos inserir um formulário para enviar algum tipo de E-mails como contato, indicação do site e etc. A maioria das pessoas costuma criar funções do objeto e-mail para facilitar, mas só isso não basta, quando enviamos uma mensagem no formato html também temos que criar a estrutura do corpo do e-mail acaba sendo trabalhoso escrever o html do corpo do E-mail dentro da programação.

Pensando nisso resolvi criar uma classe para enviar esses tipos de E-mails usando um modelo de arquivo html externo, ou seja, sem precisarmos escrever e formata-lo dentro do escopo da programação,

Teremos 4 arquivos.
- **cls_email.asp** (nossa classe que recebe as propriedades do e-mail).
- **envia_email.asp** (arquivo para enviar a mensagem).
- **form.html** (formulário para captar as informações a serem transmitidas).
- **html_contato.html** (modelo html simples que será corpo da mensagem).

Primeiro passo será criar um arquivo html modelo com as informações do corpo da mensagem, neste exemplo irá utilizar os dados: Nome, Telefone e Mensagem, que será as variáveis que serão substituídas pelos valores vindos do formulário.

`html_contato.html`

```html
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>HTML Contato</title>
      <style type="text/css" media="screen">
      body { font:11px "trebuchet MS", Arial, helveitica, sans-serif; }
      h2 { font:bold 18px "trebuchet MS", Arial, helveitica, sans-serif }
      </style>
   </head>
   <body>
      <div id="conteudo">
          <h2>Classe para enviar de Email usando CDOSYS FORMATO HTML</h2>
             <p>Neste arquivo crie quantas vari&aacute;veis que voc&ecirc; achar necess&aacute;rio. dentro das chaves </p>
             <p>Lembrando que, ao criar as nomenclaturas neste html ref. as vari&aacute;veis n&atilde;o esque&ccedil;a </p>
         <p>
              Nome: <br />
              Tel:  <br />
              Mensagem <br />
             </p>
      </div>
   </body>
</html>
```

Segundo passo será criar o formulário abaixo para envio das informações.

`form.html`

```html
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>Formulario Envio de E-mail.</title>
   </head>
    <body>
      <div id="principal">
        <form id="frmMail" action="envia_email.asp" method="post">
        <fieldset>
          <legend>Formulario de Contato</legend>
          <label>Nome:  <input type="text" name="NOME" id="NOME"  /></label>
          <label>E-mail: <input type="text" name="EMAIL" id="EMAIL" /></label>
          <label>Telefone: <input type="text" name="TEL" id="TEL" /></label>
          <label>Mensagem: <textarea name="MENSAGEM" id="MENSAGEM" cols="" rows=""></textarea></label>
          <label><input type="submit" id="bto" value=" ENVIAR "  /></label>
        </fieldset>
        </form>
      </div>
    </body>
</html>
```

Terceiro passo será criar o arquivo `cls_email.asp` e configurar o servidor smtp de saída de e-mails.

`cls_email.asp`

```VB.NET
<%
Class clsEmail
  ' DEFINIÇÃO DAS VARIAVES DO OBJ EMAIL
  Private sDE  ' REMETENTE
  Private sPARA ' DESTINATARIO
  Private sASSUNTO' ASSUNTO DA MENSAGEM
  Private sCORPO ' CORPO DA MENSAGEM
  Private oConfig ' oConfig SERÁ OS ELEMENTOS QUE IREMOS ADICIONAR A CADA FORMULARIO
  Public Sub ClassInitialize()
     Set oConfig = Server.CreateObject("Scripting.Dictionary")
  End Sub
  Public Sub ClassTerminate()
     oConfig.RemoveAll
     Set oConfig = Nothing
  End Sub
  Public Property Let DE(valueDE)
  sDE = valueDE
  End Property
  Public Property Let PARA(valuePARA)
  sPARA = valuePARA
  End Property
  Public Property Let ASSUNTO(valueASSUNTO)
  sASSUNTO = valueASSUNTO
  End Property
  Public Property Let CORPO(valueCORPO)
  sCORPO = valueCORPO
  End Property
  Public Property Let Config(sKey, sValue)
  oConfig.Add sKey, sValue
  End Property
  ' ESTA SUB ROTINA UTILIZAMOS PARA TESTAR O CORPO DO EMAIL.
  ' ASSIM NÃO PRECIMOS FICAR ENCHENDO NOSSA CAIXA DE MSG COM MENSAGENS DE TESTE
  ' OU ATÉ MESMO TESTAR OFF-LINE.
  Public Sub testa()
    Response.Write CorpoEmail
  End Sub
  ' NESTA FUNÇÃO FAZEMOS A LEITURA DA ESTRUTURA DO AQUIVO HTML E SUBSTITUIMOS AS VARIAVEIS.
  Public Function CorpoEmail()
    Dim oFSO, oFile, sHtml
    Set oFSO  = Server.CreateObject("Scripting.FileSystemObject")
    Set oFile  = oFSO.OpenTextFile(Server.MapPath(sCORPO),1,False)
     sHtml  = oFile.ReadAll
    Set oFSO  = Nothing
    Set oFile  = Nothing
    Dim oRE, sKey
    ' EXPRESSÃO REGULAR PARA SUBSTITUIR DAS VARIAVEIS
    Set oRE = New RegExp
    oRE.IgnoreCase  = TRUE
    oRE.Global  = TRUE
    ' SUBSTITUIMOS TODAS AS CHAVES CRIADAS NO ARQUIVO HTML
     For Each sKey In oConfig
         oRE.Pattern = sKey 
         oRE.Replace(sHtml,oConfig(sKey))
     Next
    CorpoEmail = sHtml
  End Function

  'ENVAR EMAIL 
  Public Sub SendMail()
    Dim objMail, objMailConfig
    Set objMail   = Server.CreateObject("CDO.Message")
    Set objMailConfig = Server.CreateObject("CDO.Configuration")
     objMailConfig.Fields("http://schemas.microsoft.com/cdo/configuration/smtpserver")  = "mail.seudominio.com.br"
     objMailConfig.Fields("http://schemas.microsoft.com/cdo/configuration/sendusing") = 2
     objMailConfig.Fields.Update
      Set objMail.Configuration  = objMailConfig
       objMail.From   = sDE
       objMail.TO    = sPARA
       objMail.Subject   = sASSUNTO
       objMail.HTMLBody   = CorpoEmail
       objMail.Send()
    Set objMail = Nothing
  End Sub
End Class
%>
```

Logo após criamos o arquivo para enviar a mensagem.

`envia_email.asp`

```VB.NET
<%@LANGUAGE="VBSCRIPT" CODEPAGE="65001"%>
<!--#include file="clsemail.asp" -->
<%
Response.Expires = 0
Response.ExpiresAbsolute = now()
'CRIANDO A INSTANCIA DO OBJ EMAIL
Dim oMail
Set oMail = New clsEmail
' ATRIBUIMOS A PROPRIEDADE REMETENTE 
oMail.DE     = LCase(Request.Form("EMAIL"))
' ATRIBUIMOS A PROPRIEDADE DESTINATARIO
oMail.PARA    = "seuemail@seudominio.com.br"
' ATRIBUIMOS A PROPRIEDADE ASSUNTO
oMail.ASSUNTO   = "CONTATO"
' ATRIBUIMOS A PROPRIEDADE CORPO DO EMAIL (INDICAMOS O CAMINHO RELATIVO DO ARQUIVO HTML )
oMail.CORPO    = "htmlcontato.html"
' CRIAMOS O ARRAY ONDE ATRIBUIMOS AS VARIAVEIS DE SUBSTITUIÇÂO DO CORPO DO EMAIL.
oMail.Config("NOME")  = Trim(Request.Form("NOME"))
oMail.Config("TEL")  = Trim(Request.Form("TEL"))
oMail.Config("MSG")  = Server.HTMLEncode(Request.Form("MENSAGEM"))
' ENVIAMOS O E-MAIL.
oMail.SendMail()
' CHAMAMOS A FUNÇÃO TESTA PARA SABER ESTAMOS ENVIANDO CORRETAMENTE A MENSAGEM.
oMail.testa
Set oMail = Nothing
With Response
.Write "<h5>Seu Email foi enviado com sucesso!</h5>"
End With
%>
```

Definimos a classe do objeto e-mail e atribuímos seus valores padrões o Remetente, Destinatário, Assunto e o Corpo (quer será o caminho relativo do arquivo modelo html_contato.html)
Adicionamos o array com as variáveis de substituição.

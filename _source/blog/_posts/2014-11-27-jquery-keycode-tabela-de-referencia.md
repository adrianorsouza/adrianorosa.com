---
title: jQuery KeyCode Tabela de referência
category_label: jQuery
category: jquery
includejs: keycode
---

Capturar a tecla pressionada no teclado do usuário usando as propriedades do Javascript **charCode** / **keyCode** / **which** pode parecer um pouco complexo, e realmente é, por isso é preciso entender como funciona esses eventos. A Captura desses tipos de eventos pode ser útil para validar algum de tipo de entrada em formulários ou em algum outro tipo de evento no browser. Para lidar com esse tipo de evento a biblioteca [jQuery] pode ser uma boa opção, pois alguns browser armazenam esses eventos de forma diferente, principalmente IE. Para capturar o `keyCode` do usuário de forma mais precisa o evento `keypress()` é a melhor opção.

### Diferenças entre `keyCode` e `charCode`
**keyCode** (Keyboard Codes): Representa o número da tecla que o usuário pressiona no teclado.  
**charCode:** (Character Codes): Representa um número de cada caractere na tabela [*Unicode*][unicode].

Para entender e ter sempre a mão o número de cada `keyCode` representado no teclado criei uma tabela para ter como referência, e para testar os eventos `keydown()`, `keypress()` e `keyup()` basta digitar a tecla nos campos de texto a seguir.

### Javascript KeyCode tabela de referencia.

<input type="text" name="keypress" placeholder="keypress()">
<input type="text" name="keydown" placeholder="keydown()">
<input type="text" name="keyup" placeholder="keyup()"> <br>
<strong><small class="feedback">Para testar digite algo nos campos de texto acima</small></strong>
<table id="CharCodeList" class="table table-responsive table-striped table-bordered ">
  <tbody></tbody>
</table>

#### Referência:
http://unixpapa.com/js/key.html  
http://en.wikipedia.org/wiki/UTF-8

[unicode]: http://www.utf8-chartable.de/unicode-utf8-table.pl?utf8=oct&unicodeinhtml=dec&htmlent=1
[jQuery]: http://api.jquery.com/category/events/keyboard-events/

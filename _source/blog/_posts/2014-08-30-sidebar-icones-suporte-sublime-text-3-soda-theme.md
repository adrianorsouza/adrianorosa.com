---
title: Sidebar Icons Suporte Sublime Text 3 para o Soda Theme
category_label: Sublime Text
category: sublime-text
---

Ontem *29-08* um novo release do [Sublime Text 3](http://www.sublimetext.com/3) build 3065 veio a tona com alguns novos recursos e muitas melhorias, entre elas uma nova feature `sidebar icons` foi adicionada, que deixa o sidebar mais encorpado exibindo ícones para pastas e diferentes tipos de arquivos.

<img src="/images/2014/08/sublime-1.png" alt="" class="img-roundeds img-thumbnail" width="200"> 

O problema é que o [Soda Theme](https://github.com/buymeasoda/soda-theme) até o momento que escrevo este artigo não é totalmente compatível com este novo recurso do ST3 e os novos ícones não aparecem para o `Light Theme` e `Dark Theme` como deviam, além de ficar um espaçamento enorme à esquerda do nome do arquivo quando exibido em subtrees, exemplo na imagem abaixo.

<img src="/images/2014/08/sublime-0.png" alt="" class="img-rounded img-thumbnail" width="200">

### Solução
A Solução é simples, o Sublime possui uma excelente maneira de customizar themes, então para exibir corretamente sidebar icons de acordo com o tipo de arquivo usando o `Soda Theme` basta adicionar os ícones:  

<img src="https://raw.githubusercontent.com/adrianorsouza/soda-theme/master/icons/file_type_default.png" alt="">`file_type_default.png`  

<img src="https://raw.githubusercontent.com/adrianorsouza/soda-theme/master/icons/file_type_source.png" alt="">`file_type_source.png`  

<img src="https://raw.githubusercontent.com/adrianorsouza/soda-theme/master/icons/file_type_text.png" alt="">`file_type_text.png`  

<img src="https://raw.githubusercontent.com/adrianorsouza/soda-theme/master/icons/file_type_markup.png" alt="">`file_type_markup.png`  

<img src="https://raw.githubusercontent.com/adrianorsouza/soda-theme/master/icons/file_type_image.png" alt="">`file_type_image.png`  

<img src="https://raw.githubusercontent.com/adrianorsouza/soda-theme/master/icons/file_type_binary.png" alt="">`file_type_binary.png`  


dentro de uma pasta `icons` e colocar essa pasta no mesmo diretório do arquivo `.sublime-theme`.

Também é necessário editar esses arquivos: `Soda Dark 3.sublime-theme` e `Soda Light 3.sublime-theme` da seguinte maneira:


	// Sidebar folder closed
    {
        "class": "icon_folder",
        "layer0.texture": "Theme - Soda/icons/folder.png",
        "layer0.opacity": 1.0,
        "content_margin": [8, 8]
    },
    // Sidebar folder open
    {
        "class": "icon_folder",
        "parents": [{"class": "tree_row", "attributes": ["expanded"]}],
        "layer0.texture": "Theme - Soda/icons/folder_open.png"
    },

E finalmente o `Soda Theme` exibe sidebar icons corretamente:

<img src="/images/2014/08/sublime-2.png" alt="" class="img-rounded img-thumbnail img-left pull-left" width="200">
<img src="/images/2014/08/sublime-3.png" alt="" class="img-rounded img-thumbnail img-left pull-left" width="200">

<div class="clearfix"></div>

Os ícones e essas alterações estão disponíveis neste [commit](https://github.com/adrianorsouza/soda-theme/commit/5430e6a5212a0e11dead6fd841f9db87b0f5848f) no github basta clonar ou fazer o zip download.



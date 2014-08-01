---
title: Categoria Wordpress
description: Artigos relacionados a linguaguem de programacao wordpress
permalink: /blog/category/wordpress/
slug: wordpress
---

{% for post in site.categories.wordpress %}
<article class="post">
{% include post-header.html %}
</article>
{% endfor %}

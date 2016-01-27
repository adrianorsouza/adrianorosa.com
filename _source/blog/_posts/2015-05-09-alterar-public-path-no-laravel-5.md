---
title: Alterar public path no Laravel 5
category_label: Laravel
category: laravel
---

No Laravel 5 me deparei com um problema comum que vem sendo [discutido][3] largamente por outros desenvolvedores, acontece que em versões anteriores do Laravel existia um arquivo `bootstrap/paths.php` muito util quando queríamos alterar os nomes de alguns dos diretórios padrão usado pelo framework, como por exemplo, a pasta webroot `public` que as vezes precisa se chamar `public_html`, `www` ou `web` dependendo do tipo de configuração do seu webserver ou do serviço de hospedagem que utiliza.

Pois bem, esse arquivo `bootstrap/paths.php` foi removido no Laravel v5.0, e agora não é mais possível definir esse tipo de configuração no framework por padrão, ou seja, se quiser alterar um desses diretórios terá que criar alguns hacks ou da maneira mais fácil que é extender a classe `Illuminate\Foundation\Application` e redefinir o método `publicPath()` ou qualquer outro path que deseja modificar ex. para o diretório `storage` deverá redefinir o método `storagePath()`, após criar a classe dentro do namespace do seu projeto também é necessário alterar o arquivo `bootstrap/app.php` onde a classe é instanciada sendo assim essa alteração será refletida em toda aplicação.


Passo 1: Estender a classe Application:

{% highlight php startinline %}
namespace MyApp

class Application extends \Illuminate\Foundation\Application {

	public function publicPath()
	{
		return $this->basePath.DIRECTORY_SEPARATOR.'public_html';
	}
}
{% endhighlight %}

Passo 2: Atualizar o arquivo `bootstrap/app.php`:

{% highlight php startinline %}
$app = new MyApp\Application(
	realpath(__DIR__.'/../')
);
{% endhighlight %}

Existe também outras maneiras de alterar os paths, se não quiser extender a class `Application`, exemplo redefinir helper function `public_path()` no seu index.php, como no exemplo logo abaixo retirado do [fórum laracasts][5], mas não recomendo esse outro tipo de hack:

{% highlight php startinline %}
function public_path($path = '')
{
	return realpath(__DIR__);
}
{% endhighlight %}

[1]: https://mattstauffer.co/blog/extending-laravels-application

[2]: http://laravel.com/docs/5.0/extending

[3]: https://github.com/laravel/framework/issues/7108

[4]: http://laravel.io/forum/01-21-2015-laravel-5-modify-paths

[5]: https://laracasts.com/discuss/channels/general-discussion/where-do-you-set-public-directory-laravel-5

[6]: http://stackoverflow.com/questions/28433715/specify-a-different-public-path

[7]: http://laravel.io/bin/LkBjO

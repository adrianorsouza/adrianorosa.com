---
title: Upgrade Ruby no Mac OSX via Homebrew
category: ruby
---

No ambiente de desenvolvimento Ruby é comum ter alguns projetos rodando em diferentes versões do Ruby, quando necessário é preciso instalar mais de uma versão do Ruby para determinado projeto rodar no seu sistema. Isso é possível e também existe algumas ferramentas chamadas de *version manager* para fazer isso para você e configurar seu ambiente em poucos cliques. 

O *version manager* bem popular é o [RVM](http://rvm.io/) e também existe o [RBENV](https://github.com/sstephenson/rbenv), mas neste caso para instalar o Ruby irei utilizar o Homebrew por dois motivos:  
1. Homebrew já está instalado no meu sistema então não requer nada mais que alguns comandos.  
2. Na tentativa de instalar via RVM ocorreram alguns problemas de dependências não achei muita vantagem neste momento.

O [Homebrew](http://brew.sh/) um *package manager* para OSX para quem já esta acostumado com ambiente Linux Homebrew é equivalente ao `apt-get`, `dpk` ou `yum`. 

Para instalar uma nova versão do Ruby no OSX basta executar o seguinte comando no terminal.
 
	brew install ruby

Isso vai instalar a versão mais recente do Ruby no seu sistema, sem afetar a versão que já vem instalada no seu Mac.

Com a versão mais recente do Ruby no seu sistema você pode priorizar qual delas deseja utilizar dizendo ao seu search PATH para localizar esta versão da seguinte maneira:

	echo 'export PATH=/usr/local/opt/ruby/bin:$PATH' >> ~/.bash_profile
	
O comando acima adiciona o local de instalação dos arquivos binários da nova instalação do Ruby.

Agora é necessário atualizar o shell para carregar a nova versão:

	source ~/.bash_profile
	
Após este processo você checar atual versão do Ruby no seu sistema:

	ruby --version
	
Deverá exibir a atual versão:

	ruby 2.1.2p95



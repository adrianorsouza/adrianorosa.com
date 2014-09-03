---
title: Como configurar um servidor web Linux Ubuntu
category_label: Linux
category: linux
---

Este artigo pode ser considerado a continuação de [como criar uma instancia na Amazon AWS][post.create.instance] escrito recentemente onde falo sobre a instalação do Ubuntu Server na nuvem. Porém os métodos descritos neste post podem ser aplicados em qualquer instalação do Linux Ubuntu Server versão a partir da 12.10 até a mais recente. Foram longas horas de pesquisas e testes para chegar há um conceito básico de configuração e dos principais requisitos de segurança para um servidor web, este guia foi elaborado para efeito de estudos e testes de novas aplicações e pode ser aplicado no mundo real. Por favor, sinta-se livre em colaborar com este material se de alguma forma possua alguma informação relevante que possa ser atribuída à este artigo.

Índice
-----------

<!-- MarkdownTOC depth=3 autolink=true bracket=round -->

- [1. Introdução](#1.-introdução)
- [2. Requisitos](#2.-requisitos)
- [3. Atualizar o sistema](#3.-atualizar-o-sistema)
  - [3.1 Executar a atualização do sistema](#3.1-executar-a-atualização-do-sistema)
  - [3.2 Atualizar Timezone e Locale](#3.2-atualizar-timezone-e-locale)
- [4. Configurações de Segurança](#4.-configurações-de-segurança)
  - [4.1 Firewall - UFW](#4.1-firewall---ufw)
  - [4.2 Habilitar Logs do Firewall](#4.2-habilitar-logs-do-firewall)
  - [4.3 Restringir configurações de rede](#4.3-restringir-configurações-de-rede)
  - [4.4 Prevenir IP Spoofing](#4.4-prevenir-ip-spoofing)
  - [4.5 Secure shared memory](#4.5-secure-shared-memory)
  - [4.6 Prevenir Fork Bomb limitando user process](#4.6-prevenir-fork-bomb-limitando-user-process)
  - [4.7 Configurações de Segurança do Apache](#4.7-configurações-de-segurança-do-apache)
  - [4.8 Configurações de Segurança do Nginx](#4.8-configurações-de-segurança-do-nginx)
- [5. SSH Security](#5.-ssh-security)
  - [5.1 Desabilitar SSH Root Login](#5.1-desabilitar-ssh-root-login)
  - [5.2 Desabilitar Port Forwarding](#5.2-desabilitar-port-forwarding)
  - [5.3 Definir usuários que podem conectar via SSH](#5.3-definir-usuários-que-podem-conectar-via-ssh)
  - [5.4 Use SFTP invés de FTP](#5.4-use-sftp-invés-de-ftp)
  - [5.5 SSH Welcome Banner](#5.5-ssh-welcome-banner)
- [6. Monitorar o Sistema e Alertas](#6.-monitorar-o-sistema-e-alertas)
  - [6.1 Configurar o Monit](#6.1-configurar-o-monit)
- [7. Instalar HTOP](#7.-instalar-htop)

<!-- /MarkdownTOC -->


1. Introdução
---------------
[introdução]: #introdução "Introdução"

Este artigo é um guia baseado em várias horas de pesquisa em fóruns, sites e na própria documentação do [Linux Ubuntu][docs.ubuntu]. Em forma de agradecimento para aqueles que contribuem para comunidade open source no fim desta página deixo uma referência de seus respectivos links para que também possa ser útil para você que deseja se aprofundar nesse assunto.

2. Requisitos
---------------

- [Linux Ubuntu Server 13.10 LTS][post.create.instance] ou posterior instalado.
- Opcional [Nginx (LEMP stack)][como instalar e configurar o Nginx] instalado
- Opcional [Apache (LAMP stack)][como instalar e configurar o Apache] instalado ou ambos LEMP e LAMP stack.


3. Atualizar o sistema
----------------
Primeira coisa após uma recente instalação do Ubuntu é atualizar sua lista de pacotes e pacotes já instalados, mesmo sendo uma nova instalação pode conter alguns programas desatualizadas ou algumas correções de bug não incluída na compilação do sistema.

### 3.1 Executar a atualização do sistema

	sudo apt-get update
	sudo apt-get upgrade
	sudo apt-get dist-upgrade

Caso deseja fazer o upgrade do sistema por ex: da versão 13.10 LTS para 14.10 LTS:

	sudo do-release-upgrade	
	
### 3.2 Atualizar Timezone e Locale
Quando instalamos um novo servidor web é fundamental verificar se está sincronizado com a data e fuso horário local ou definido pela sua aplicação.

O Timezone define seu fuso horário, Locale define as traduções de strings e formatos data, numérico, moeda e etc. de acordo com sua região.

Em caso de instancias criada na Amazon AWS EC2 o Timezone do servidor não estará definido, é necessário configurar o Timezone e locale conforme os seguintes passos:

Alterar o Timezone e Locale:

	sudo dpkg-reconfigure tzdata 

Para verificar o atual locale execute:

	locale
	
Para visualizar o locale definido no sistema:

	locale -a
	
Caso não tenha o `pt_BR.UTF8` definido no locale é possível instalar o pacote:

	sudo locale-gen pt_BR.UTF-8

Se sua configuração não foi feita corretamente e esta recebendo a seguinte mensagem de erro: 

<pre><span class="text-danger">FIX ERROR: Sorry, command-not-found has crashed! Please file a bug report at: http://askubuntu.com/questions/205378/unsupported-locale-setting-fault-by-command-not-found</span></pre>

Solução edite o arquivo de configuração `/var/lib/locales/supported.d/local`:

	sudo nano /var/lib/locales/supported.d/local

E adicione as seguintes linhas:

	pt_BR.UTF-8 UTF-8
	en_US.UTF-8 UTF-8

Ou abra o arquivo: `/etc/default/locale` e defina manualmente os parâmetros que causam erros:

	LANG="pt_BR.UTF-8"
	LANGUAGE="pt_BR.UTF-8"
	LC_CTYPE="pt_BR.UTF-8"
	LC_ALL="pt_BR.UTF-8"

Se o problema persistir execute os seguintes comandos:
	
	export LANGUAGE=pt_BR.UTF-8
	export LANG=pt_BR.UTF-8
	export LC_ALL=pt_BR.UTF-8
	export LC_CTYPE=pt_BR.UTF-8
	sudo locale-gen pt_BR.UTF-8

Por Fim Reconfigure o Locale:

	sudo dpkg-reconfigure locales

4. Configurações de Segurança
---------------------------

### 4.1 Firewall - UFW
Se seu servidor está na Amazon AWS ele está protegido por um [Network Firewall], caso tenha definido um **Security Group**, porém podemos adicionar mais uma camada de segurança usando o UFW (*Uncomplicated Firewall*) um [Host-based Firewall] que já vem instalado no Ubuntu, mas desativado.

Antes de habilitar UFW Firewall é necessário adicionar as regras de entrada para evitar que você fique trancado do lado de fora e não tenha qualquer tipo acesso ao servidor.

As principais portas de acesso que precisam ser adicionadas são TCP 22 `SSH` e 80 `HTTP`. Para liberar essas portas execute o seguinte comando:

	sudo ufw allow 22
	sudo ufw allow 80

Para permitir acesso ao servidor de um especifico endereço IP:

	sudo ufw allow proto tcp from 1234.456.789.0 to any port 22	
Então poderá ativar o UFW Firewall com o comando:

	sudo ufw enable
	
Para verificar o status do UFW use:

	sudo ufw status verbose

Para deletar uma regra no UFW:

	sudo ufw delete deny 80/tcp	

### 4.2 Habilitar Logs do Firewall
Se você estiver usando o ufw, você pode habilitar logs com o seguinte comando:

	sudo ufw logging on

### 4.3 Restringir configurações de rede
O Kernel do Linux permite alterar vários parâmetros no system runtime, o arquivo `/etc/sysctl.conf` pode ser modificado para deixar ainda mais restrito suas configurações de rede como também prevenir ataques como **[Syn Flood][SYN_flood]**. Não vou detalhar todos os itens aqui, mas o arquivo é bem comentado, eu recomendo que antes de editar leia seu manual.

Agora, abra o arquivo `/etc/sysctl.conf` 

	sudo nano /etc/sysctl.conf

E altere conteúdo desse arquivo para o seguinte:

```
net.ipv4.conf.default.rp_filter = 1
net.ipv4.conf.all.rp_filter = 1
net.ipv4.icmp_echo_ignore_broadcasts = 1
net.ipv4.tcp_syncookies = 1
net.ipv4.tcp_max_syn_backlog = 2048
net.ipv4.tcp_synack_retries = 2
net.ipv4.tcp_syn_retries = 5
net.ipv4.ip_forward = 0
net.ipv4.icmp_echo_ignore_all = 1
net.ipv4.conf.all.accept_redirects = 0
net.ipv6.conf.all.accept_redirects = 0
net.ipv4.conf.default.accept_redirects = 0
net.ipv6.conf.default.accept_redirects = 0
net.ipv4.conf.all.send_redirects = 0
net.ipv4.conf.default.send_redirects = 0
net.ipv4.conf.all.accept_source_route = 0
net.ipv6.conf.all.accept_source_route = 0
net.ipv4.conf.default.accept_source_route = 0
net.ipv6.conf.default.accept_source_route = 0
net.ipv4.conf.all.log_martians = 1
net.ipv4.icmp_ignore_bogus_error_responses = 1
net.ipv6.conf.default.router_solicitations = 0
net.ipv6.conf.default.accept_ra_rtr_pref = 0
net.ipv6.conf.default.accept_ra_pinfo = 0
net.ipv6.conf.default.accept_ra_defrtr = 0
```

após alterar o arquivo faça reload:

	sudo sysctl -p

### 4.4 Prevenir IP Spoofing
Edite o arquivo `/etc/host.conf`

	sudo nano /etc/host.conf
	
Adicione as seguintes linhas:

	order bind,hosts
	nospoof on

### 4.5 Secure shared memory
Por padrão, `/run/shm` é montado com leitura/gravação, com permissão para executar programas. Nos últimos anos, muitas listas de discussão de segurança identificaram diversos exploits onde `/run/shm` é usado em um ataque contra a serviços, como o httpd. [Para mais detalhes][Shared_Memory].

Edite o arquivo `/etc/fstab` e adicione a seguinte linha:

	none	/run/shm	tmpfs	rw,noexec,nosuid,nodev	0 0

### 4.6 Prevenir Fork Bomb limitando user process
Edit o arquivo: `/etc/security/limits.conf`
	
	sudo nano /etc/security/limits.conf

E adicione a seguinte linha no fim do arquivo:

	*                hard    nproc           800

### 4.7 Configurações de Segurança do Apache
Se possui o Apache (LAMP stack) instalado no seu servidor siga as instruções do artigo [como instalar e configurar o Apache] para adicionar uma camada extra de segurança no seu servidor web.

### 4.8 Configurações de Segurança do Nginx
Se possui o Nginx (LEMP stack) instalado no seu servidor siga as instruções do artigo [como instalar e configurar o Nginx] para adicionar uma camada extra de segurança no seu servidor web.

5. SSH Security
---------------

SSH é um meio seguro para conexão com seu servidor remoto, embora seja muito seguro alguns tweaks na configuração do `sshd` pode tornar ainda mais restrito, para isso é necessário abrir o arquivo `/etc/ssh/sshd_config`. E neste arquivo vamos alterar alguns parâmetros e se esses parâmetros não existirem iremos adicioná-lo.

### 5.1 Desabilitar SSH Root Login
Geralmente o Ubuntu não permite acesso direto com o usuário `root`, mas caso tenha definido uma senha para usuário `root` isso pode ser um potencial risco de segurança. Para desabilitar login com `root` abra o o arquivo `/etc/ssh/sshd_config`:

	sudo nano /etc/ssh/sshd_config
	
Aproveite e desabilite também **[Password Authentication]** e **[LoginGraceTime]** encontre os parâmetros a seguir e defina conforme abaixo:

```
LoginGraceTime 20
PasswordAuthentication no
PermitRootLogin no
```

### 5.2 Desabilitar Port Forwarding
Se os parâmetros abaixo não existirem adicione no fim do arquivo para desabilitar SSH Port Forwarding:

	AllowTcpForwarding no
	X11Forwarding no

### 5.3 Definir usuários que podem conectar via SSH
É possível definir usuários ou grupos específicos que podem ter ou não acesso via SSH.  
Para permitir ou bloquear usuários SSH:

	AllowUsers username
	DenyUsers username

Para permitir ou bloquear groups SSH:

	AllowGroups groupname
	DenyGroups groupname

### 5.4 Use SFTP invés de FTP
Suponha que você queira dar alguns usuários ou grupos a capacidade de upload e download de arquivos de seus diretórios home (ou ~/web), mas não uma conta shell completo com permissão de conexão SSH. No Ubuntu isso é simples de conseguir com componente SFTP do OpenSSH.

Primeiro localize a seguinte linha no arquivo `/etc/ssh/sshd_config`:
	
	Subsystem sftp /usr/lib/openssh/sftp-server

E altera para:

	Subsystem sftp internal-sftp
	
Agora, adicione as linhas como esta abaixo no fim do arquivo `/etc/ssh/sshd_config`.

```
Match User username
  ChrootDirectory %h
  X11Forwarding no
  AllowAgentForwarding no
  AllowTcpForwarding no
  ForceCommand internal-sftp
```	

Para definir essa regra para mais de um usuário, use grupos e altere a linha `Match User username` para o nome do grupo que criou para usuários `SFTP` como abaixo:

	Match Group groupname

Também, defina o shell para `/usr/bin/false` para previnir normal ssh login:
	
	sudo usermod -s /usr/bin/false username

<p class="text-warning">
<strong>Importante:</strong> A diretório definido no parâmetro <code>ChrootDirectory</code> deve pertencer ao usuário <code>root</code> se este diretório é a própria pasta home do usuário, então altere as permissões:</p>

	sudo chown root:root /home/username

### 5.5 SSH Welcome Banner
Se desejar é possível adicionar um banner de boas vindas para ser exibido quando usuários SSH se conectarem, para isso no arquivo `/etc/ssh/sshd_config` remova `#` na linha `#Banner /etc/issue.net`.

Logo após, edite o arquivo `/etc/issue.net` para definir o banner que será exibido quando usuários se conectarem via SSH.

Após todas as mudanças no arquivo `/etc/ssh/sshd_config` lembre-se de reiniciar o ssh daemon com o seguinte comando:

	sudo service ssh restart


6. Monitorar o Sistema e Alertas
-------------------------------------------

Mesmo as melhores configurações de servidores podem ter problemas. Para monitorar os recursos do seu servidor Linux eu recomendo instalar e configurar o [Monit]. Monit permite monitorar eventos e processos, muito útil para checar sobrecarga ou paralisação de algum recurso.

Para instalar o Monit:

	sudo apt-get install monit

Para habilitar Monit on startup edite o arquivo `/etc/default/monit`: 

	sudo nano /etc/default/monit

E altere o parâmetro `START` para `yes` ou `1` da seguinte maneira: 

	# Set START to yes to start the monit
	START=yes

### 6.1 Configurar o Monit
O arquivo de configuração do Monit fica localizado em `/etc/monit/monitrc`. Ao abrir esse arquivo pode parecer inicialmente bem complexo para entender seus parâmetros, mas o que precisa ser feito e adicionar entradas dos processos que deseja monitorar por ex: apache, mysql, ssh e definir seus limites e alertas caso tenha alguma falha e finalmente definir um endereço de email para onde será enviado alertas em caso de falha ou overload. Bom eu recomendo que leia a [documentação][docs.monit] caso tenha alguma dúvida.

	sudo nano /etc/monit/monitrc
	
Depois de configurar o Monit pode testar as configurações: 

	sudo monit -t
	
Após configurar o Monit para habilitar/desabilitar use os seguintes comandos:

Habilitar:

	sudo /etc/init.d/monit start

Desabilitar:
	
	sudo /etc/init.d/monit stop


7. Instalar HTOP
---------------

[HTOP][htop] é um programa similar ao comando `top` mas de forma interativa você pode visualizar processos no Linux. Para instalar execute o comando:
	
	sudo apt-get install htop

----

#### Referência

https://help.ubuntu.com/community/StricterDefaults  
http://askubuntu.com/questions/205378/unsupported-locale-setting-fault-by-command-not-found  
https://help.ubuntu.com/community/SSH/OpenSSH/  
https://help.ubuntu.com/community/UFW  
http://hisham.hm/htop/  
http://mmonit.com/monit/#documentation  
https://www.thefanclub.co.za/how-to/how-secure-ubuntu-1204-lts-server-part-1-basics  
http://blog.chriskankiewicz.com/setting-up-an-ubuntu-web-server.html  

[docs.ubuntu]: https://help.ubuntu.com/ "Documentação Ubuntu"

[post.create.instance]: /blog/aws/criar-instancia-na-amazon-aws.html "Como Criar uma instancia na Amazon AWS"

[locale.error]: http://askubuntu.com/questions/205378/unsupported-locale-setting-fault-by-command-not-found

[ufw]: https://help.ubuntu.com/community/UFW "Firewall UFW"

[Shared_Memory]: https://help.ubuntu.com/community/StricterDefaults#Shared_Memory

[sshd]: https://help.ubuntu.com/community/StricterDefaults#SSH_Settings

[sshd_config]: https://help.ubuntu.com/community/SSH/OpenSSH/Configuring

[Disable_Forwarding]: https://help.ubuntu.com/community/SSH/OpenSSH/Configuring#Disable_Forwarding

[Password Authentication]: https://help.ubuntu.com/community/SSH/OpenSSH/Configuring#Disable_Password_Authentication

[LoginGraceTime]: https://help.ubuntu.com/community/StricterDefaults#SSH_Login_Grace_Time

[htop]: http://hisham.hm/htop/

[monit]: http://mmonit.com/monit/
[docs.monit]: http://mmonit.com/monit/#documentation

[Network Firewall]:http://en.wikipedia.org/wiki/Application_firewall#Network-based_application_firewalls

[Host-based Firewall]:http://en.wikipedia.org/wiki/Application_firewall#Host-based_application_firewalls

[SYN_flood]: http://en.wikipedia.org/wiki/SYN_flood

[como instalar e configurar o Apache]: #todo
[como instalar e configurar o Nginx]: /blog/nginx/instalar-versao-recente-nginx-no-linux-ubuntu.html

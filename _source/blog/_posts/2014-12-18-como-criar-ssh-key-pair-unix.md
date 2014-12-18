---
title: Criar SSH Keys em sistemas UNIX
category_label: Segurança
category: seguranca
---

***Key Pair***, ou [**SSH Keys**][5] ou ainda **Chave Pública** consiste em duas chaves uma pública que deve ser instalada no servidor remoto, e a outra chave privada que fica salva no seu computador, as duas juntas permitem que você conecte de forma segura a um servidor remoto usando o protocolo SSH ou SFTP.

Para criar sua SSH key em ambientes Unix siga os passos logo abaixo, se você é usuário Windows visite o post [como criar ssh key pair no windows][windows].

## Passo 1: Verifique seu já possui SSH Key
Veja se já existe SSH keys no seu computador, em ambientes Unix pode ser encontrado em um desses diretórios `~/.ssh/identity`, `~/.ssh/id_ecdsa`, `~/.ssh/id_dsa` ou `~/.ssh/id_rsa`. Para verificar digite no terminal:

	ls -al ~/.ssh

> Dica: se você tiver uma nova instalação Mac OSX ou Linux, você não terá o diretório `~/.ssh` criado ainda, esse diretório onde armazena suas chaves públicas e privadas será criado automaticamente no passo seguinte.

## Passo 2: Criar uma nova SSH Key
Se ainda não tem sua SSH key deverá gerar uma usando o comando `ssh-keygen`:

	ssh-keygen -t rsa -b 4096 -C "seuemail@example.com"

Explicando o comando acima:  

- Parâmetro `-t` específica o tipo de chave nesse caso usamos o algoritmo `rsa` para protocolo versão 2 outros possíveis valores são `rsa1`, `dsa` e `ecdsa`.  

- Parâmetro `-C` adiciona um comentário à chave pública, é apenas uma maneira fácil de identificar a finalidade desta chave, o valor pode ser `user@host`. 

- Parâmetro `-b` informa *key size* ou total de bits. O padrão é `2048`, geralmente esse valor já é o suficiente para sua chave se quiser mais segurança use o valor máximo `4096`.

> ATENÇÃO: Para chaves **RSA** o valor mínimo é de `768` bits, mas não crie sua chave com menos de `2048` bits pois estudos apontam que é possível [craquear][rsacracked] esses tipos de chaves.
 
### Defina Passphrase
O Passphrase é como uma senha e precisa ser digitado to vez que usar a chave para se conectar SSH, se você esquecer o Passphrase ou outra pessoa ter acesso à sua Private Key ficará impossibilitado de conectar ao servidor remoto. Caso não queira definir um Passphrase apenas deixe em branco, pressione enter duas vezes para finalizar.
	
## Passo 4: Copie sua chave pública para o servidor
Após executar o comando para gerar SSH keys dois arquivos serão salvos no diretório `~/ssh` por padrão os arquivos são `id_rsa` e `id_rsa.pub`, mas é possível criá-los com nomes e diretórios diferentes se for necessário. 

A chave pública, cujo a extensão é `.pub`, deve ser copiado para o servidor e o seu conteúdo é algo como:

	ssh-rsa AAAAB3NzaC1yc2EAAAADAQA .....
	
A chave privada tem o seguinte conteúdo:

	-----BEGIN RSA PRIVATE KEY-----
	MIIJKQIBAAKCAgEAzpHUcAE5w5e49Qw2S4cp17OmsIc1D8yfNR7y/ZLsf0382zsw
	.....

e deve ter apenas permissão de leitura pelo seu usuário, altere usando o comando:

	chmod 400 ~/.ssh/id_rsa

Com sua SSH key pair criada, é hora de copiar a chave pública para o servidor, se você não é o administrador do servidor remoto deverá enviar o conteúdo da chave pública para o responsável fazer isso.

No servidor remoto, usando um editor de sua preferencia adicione a chave pública ao arquivo ` ~/.ssh/authorized_keys`, ou simplesmente utilize o comando:

	cat ~/.ssh/id_rsa.pub | ssh user@123.45.56.78 "mkdir -p ~/.ssh && cat >>  ~/.ssh/authorized_keys"

## Passo 5: Faça um teste com sua SSH Key

	ssh user@123.456.789.0

Se sua SSH key foi salva em um diretório diferente de `~/.ssh` você deverá informar o diretório onde esta a chave para para ter sucesso na conexão com servidor.

	ssh -i /diretorio/subpasta/sua_chave_privada user@123.456.789.0
	
## Passo 6: Bonus, usuários Mac OSX
Se você definiu um Passphrase, pode salvar esse valor na sua Keychain e assim evitar ter que digitar o passphrase sempre que for conectar ao servidor remoto. Para adicionar sua SSH key na Keychain use o comando `ssh-add`:

	ssh-add -K ~/.ssh/id_rsa

Digite sua Passphrase e sua chave será armazena na Keychain.

Para visualizar a lista de chaves adicionadas ao `ssh-agent`:

	ssh-add -l

Esse comando exibe o tamanho de bits de sua chave, footprint e o diretório da chave privada da seguinte forma:

	2048 ee:aa:11:11:bb:cc:gg:11:Ca:Fe:00:E1:49:30:8C:01 ~/.ssh/id_rsa (RSA)

### Mais informações sobre SSH Keys:
[https://help.ubuntu.com/community/SSH/OpenSSH/Keys][4]

[1]: https://help.github.com/articles/generating-ssh-keys/
[2]: https://help.github.com/articles/working-with-ssh-key-passphrases/
[3]: https://www.digitalocean.com/community/tutorials/how-to-set-up-ssh-keys--2
[4]: https://help.ubuntu.com/community/SSH/OpenSSH/Keys
[5]: /blog/seguranca/ssh-keys.html
[rsacracked]: http://www.techworld.com/news/security/rsa-1024-bit-private-key-encryption-cracked-3214360/

[windows]: /blog/seguranca/como-criar-ssh-key-pair-windows.html
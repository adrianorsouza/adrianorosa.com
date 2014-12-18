---
title: Criar SSH Key pair no Windows
category_label: Segurança
category: seguranca
---

A maneira mais simples de criar SSH key no Windows é utilizando o PuTTY. O PuTTY é um programa de código aberto que emula um Terminal, semelhante ao Terminal do Linux, para estabelecer uma conexão SSH. PuTTY oferece o utilitário PuTTYGen no qual você deverá usar para criar sua chave.

Em ambientes Unix veja o post [Como criar SSH Key pair em sistemas UNIX][1].

***Key Pair***, ou [**SSH Keys**][2] ou ainda **Chave Pública** consiste em duas chaves uma pública que deve ser instalada no servidor remoto, e a outra chave privada que fica salva no seu computador, as duas juntas permitem que você conecte de forma segura a um servidor remoto usando o protocolo SSH ou SFTP.

## Passo 1: Download PuTTYgen
Vá até o site [PuTTY][gen] faça o download do utilitário `PuTTYGen`.

## Passo 2: Criar SSH Key
- Execute o `puttygen.exe`, e log verá a janela `PuTTY Key Generator` <small>imagem 2.1</small>.

- Na parte inferior da janela, no item *Parameters* por padrão já estará selecionado o algoritmo `SSH-2 RSA` e definido o número de bits `2048`, esses parâmetros são o recomendado para gerar SSH Key.

- Para criar SSH Key clique no botão `Generate`. E tenha certeza de movimentar o ponteiro do mouse logo a abaixo a barra de progresso até que seja finalizada.

<div class="img-wrap text-center">
<img src="/images/2014/12/putty-1.jpg" alt="Tela PuTTY Key Generator" title="Tela PuTTY Key Generator" class="img-thumbnail" width="400">
<span>imagem 2.1 Tela PuTTY Key Generator</span>
</div>

## Passo 3: Complete as informações e salve sua SSH Key

Na próxima tela, <small>imagem 3.1</small> siga os passos:

1. Adicione um comentário para identificar sua Public Key ex. usuario@host.
 
2. Crie um Passphrase (recomendado). O Passphrase é como uma senha que precisa ser digitado cada vez que iniciar uma sessão SSH ou SFTP, o que torna sua SSH Key ainda mais segura.

3. Copie a Public Key para o servidor e salve em `authorized_keys`
	> NOTA: Para adicionar a sua chave pública na lista de chaves autorizadas é preciso ter acesso administrativo ao servidor no qual deseja estabelecer uma conexão SSH. Se você é usuário de um sistema onde esse acesso é restrito você deverá revelar sua chave pública para o responsável configurar e instalar para você. Lembre-se somente a chave pública deve ser revelada para terceiros.

4. Por fim, salve sua Private Key em algum local seguro no seu computador.

<div class="img-wrap text-center">
<img src="/images/2014/12/putty-2.jpg" alt="Gerando SSH keys com PuTTY" title="Gerando SSH keys com PuTTY" class="img-thumbnail" width="400">
<span>imagem 3.1 Gerando SSH keys com PuTTY</span>
</div>

> NOTA: PuTTY e OpenSSH usam diferentes formatos para Public Key. Sua Public Key deve começar com a linha: `ssh-rsa AAAAAAAB3.....` se esse valor for algo como: `---- BEGIN SSH2 PUBLIC KEY ----` .... esta errado.


Veja mais detalhes na [documentação do PuTTY][docs].

[1]: /blog/seguranca/como-criar-ssh-key-pair-unix.html
[2]: /blog/seguranca/ssh-keys.html

[docs]: http://www.chiark.greenend.org.uk/~sgtatham/putty/docs.html
[putty]: [http://www.chiark.greenend.org.uk/~sgtatham/putty/]
[gen]: http://www.chiark.greenend.org.uk/~sgtatham/putty/download.html

[ref_2]: http://katsande.com/using-puttygen-to-generate-ssh-private-public-keys
[ref_3]: http://wiki.joyent.com/wiki/display/jpc2/Manually+Generating+Your+SSH+Key+in+Windows

---
title: FTP não é seguro saiba por que
category_label: Segurança
category: seguranca
---

**FTP** ([File Transfer Protocol][ftp]) é um protocolo bem conhecido e vastamente usado para transferência de arquivos entre dois computadores, seu uso é feito principalmente em hospedagens de site para transferência de arquivos. Porém o uso de FTP não é seguro por diversas razões saiba por que.

### Como funciona o FTP

O design do FTP permite conectar a outro computador remoto com autenticação de usuário e senha, ou também de forma anônima se o servidor for configurado para esse fim. Quando você abre uma nova conexão FTP usando um software client-server, como por exemplo o FileZilla, você digita seu login e senha e o endereço IP ou domínio do servidor, e seu software client-server envia através de comando suas credencias em texto plano para o servidor remoto que verifica se os dados de login estão corretos, e então cria-se uma sessão FTP entre os dois computadores, a partir daí é possível transferir arquivos para o servidor remoto e vice-versa.

### E por que FTP é inseguro?

O uso do FTP é um grande risco de segurança porque a transferência de arquivos, tanto *upload* quanto *download*, são feitos sem encriptação, ou seja, a transmissão dos dados é feita pela rede em formato de texto plano o que significa que se alguém interceptar o pacote TCP durante a transmissão, método também conhecido como [Sniffing][Sniffer], dará ao hacker acesso à todas informações de seus arquivos e credencias colocando em risco seu servidor e computador local.

Além disso, existem [outras vulnerabilidades][ftpsecurity] bem conhecidas deste protocolo.

### Qual a solução seu eu uso FTP

Se você ou sua empresa se preocupa com a segurança de suas informações a solução é bem simples **NÃO USE FTP**, existem outros métodos conhecidos e de fácil implementação  como por exemplo o WinSCP e SFTP.

### Use SFTP invés do FTP

[SFTP][wiki_sftp] (Secure Shell File Transfer Protocol) é um protocolo parte do pacote OpenSSH. Embora a semelhança com nome FTP o SFTP tem seu design e recursos diferentes. Uma de suas vantagens é o método de autenticação baseado em SSH Key-pair, ou seja, não depende de usuário e senha e sua transmissão de dados é feita através de tunelamento SSH encriptada do inicio ao fim da conexão, o que significa que mesmo que alguém intercepte seus pacotes TCP estará impossibilitado de decifrar os dados transmitidos.

### Como usar o SFTP 

O SFTP é baseado em SSH Key pair, então basicamente o que precisa:

1.  Criar sua SSH Keys, mas isso pode ser diferente entre sistemas [Windows][gen_ssh_win] e [Unix][gen_ssh_unix].

2.  Configurar seu servidor com SFTP ou solicitar junto ao seu provedor de hospedagem de sites se oferece este recurso, mas dificilmente você encontrará esse recurso em hospedagem de site compartilhada. O ideal é que tenha um VPS gerenciável onde possa ter total acesso à configuração, sendo assim será possível [configurar SFTP facilmente][setup_sftp].

3.  Utilizar um SFTP client, diversos softwares FTP client também oferece suporte para SFTP é o caso do [FileZilla][filezilla], veja [como configurar uma conexão com SFTP com FileZilla][setup_filezilla].

### Conclusão
FTP é inseguro é deve ser evitado sempre que possível, o uso de alternativas seguras como SFTP, SCP e WinSCP deve ser usado em seu lugar. Se seu provedor de hospedagem não oferece este recurso procure alternativas como VPS ou migre para empresas que possibilitam essa configuração.

[setup_filezilla]: /blog/seguranca/acesse-arquivos-remoto-com-sftp-e-filezilla.html
[setup_sftp]: /blog/linux/configurar-webserver-linux-ubuntu-php-e-apache-nginx.html#5.4-use-sftp-inv%C3%A9s-de-ftp
[gen_ssh_unix]: /blog/seguranca/como-criar-ssh-key-pair-unix.html
[gen_ssh_win]: /blog/seguranca/como-criar-ssh-key-pair-windows.html
[wiki_ssh]: http://en.wikipedia.org/wiki/Secure_Shell
[wiki_sftp]: http://en.wikipedia.org/wiki/SSH_File_Transfer_Protocol
[Sniffer]: http://en.wikipedia.org/wiki/Packet_analyzer
[ftpsecurity]: http://en.wikipedia.org/wiki/File_Transfer_Protocol#Security
[ftp]: http://en.wikipedia.org/wiki/File_Transfer_Protocol
[filezilla]: https://filezilla-project.org/
[1]: http://www.darkreading.com/risk/compliance/ftp-ubiquitous-and-dangerously-noncompliant/d/d-id/1137390?
[2]: http://engineering.deccanhosts.com/2013/02/why-is-ftp-insecure.html
[3]: http://www.raditha.com/php/ftp/security.php
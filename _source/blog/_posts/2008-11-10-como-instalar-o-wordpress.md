---
title: Como instalar o WordPress
category_label: Wordpress
category: wordpress
date: 2008-11-10 10:27:20
wp_id: 50
wp_slug: como-instalar-o-wordpress
---

Irei neste artigo explicar como instalar o gerenciador de Blog’s chamado WordPress e em alguns passos você terá seu próprio Blog com sistema de gerenciamento de conteúdo configurado pronto para publicar informações na web.

Embora este artigo não tenha sido publicado através da plataforma WordPress, pois roda sobre uma plataforma de minha autoria, irei exemplificar como instalar, configurar, e publicar artigos em Blog na plataforma WordPress, Mas antes vou falar um pouco sobre o que é WordPress.

Bom a menos que você esteja vivendo escondido numa caverna nos últimos anos já deve ter ouvido falar do WordPress! O WordPress é um sistema de gerencimento de conteúdo web, um ótimo (CMS) para criação de Blog’s, utilizado por milhares de usuários e empresas em todo o mundo. O WordPress foi escrito em PHP por Matt Mullenweg um evangelista do open source.

Wordpress é open source, ou seja, é destituído gratuitamente sobre a licença GPL(GENERAL PUBLIC LICENSE) onde você poderá copiar e alterar o código do sistema mas mantendo os créditos do autor.

Caso ainda tenha dúvida sobre o que é o WordPress visite as referencias abaixo para prosseguir neste artigo:  
<a href="http://pt.wikipedia.org/wiki/WordPress">http://pt.wikipedia.org/wiki/WordPress</a>

### Instalação do Wordpress.

**Requisitos:**
Para configurar um Blog WordPress é preciso que tenha um serviço de hospedagem de sites e um banco de dados que forneça os seguintes requisitos:
- Versão do PHP 4.3 ou superior.
- Versão do MySQL 4.0 ou superior.

Vale lembrar que para instalar, configurar o WordPress e ter seu próprio blog e não é necessário conhecimentos em programação web muito menos banco de dados.

Passando pelos requisitos mínimos é preciso fazer o download do WordPress, recentemente foi lançada uma versão em português onde é possível efetuar download no link: <a href="http://br.wordpress.org/wordpress-2.6-pt_BR.zip">http://br.wordpress.org/wordpress-2.6-pt_BR.zip</a> "nesta demonstração estaremos usando esta versão, no entanto recomendo a utilizar sempre a última versão disponível <a href="http://wordpress.org/latest.zip">http://wordpress.org/latest.zip</a> (em inglês)".

1. – Após o donwload descompacte os arquivos em uma pasta,
2. – Acesse seu FTP e faça o upload de todos os arquivos para a raiz ou sub-pasta do seu site,
3. – Ao concluir o upload, abra seu navegador web e acesse o endereço do seu site onde esta os arquivos (caso você copiou este arquivos em uma sub-pasta acesse www.seusite.com.br/subpasta/index.php ou senão www.seusite.com.br/index.php).
4. – deverá aparecer a seguinte mensagem assim como na imagem abaixo.

<img class="img-thumbnail" title="wp1" src="/images/2011/04/wp1.jpg" alt="wp1" width="480" height="89" />

**Nota:** Em alguns tutorias na web informam que é preciso criar um arquivo de configuração chamado wp-config-.php e editar no bloco de notas algumas linhas de códigos em PHP, neste caso não precisamos de criar este arquivo pois será está configuração será feita através da interface web automaticamente. Portanto não se preocupe, pois você não terá que escrever nenhuma linha de código.

Agora iremos gerar um arquivo de configuração o famoso wp-config.php que fará a conexão com seu banco de dados MySQL, clique em <em>Criar um Arquivo de Configuração</em>, na próxima tela você terá as seguintes opções como na imagem abaixo:

<img class="img-thumbnail" title="wp2" src="/images/2011/04/wp2.jpg" alt="wp2" width="480" height="300" />

6. - Clique em <em>Vamos começar!</em> e preencha corretamente os campos, veja a imagem abaixo:

<img class="img-thumbnail" title="wp3" src="/images/2011/04/wp3.jpg" alt="Imagem 1.3" width="480" height="389" />

**Os itens de configuração são:**
1. Nome do Banco de Dados,
2. Usuário do Banco de Dados,
3. Senha do Banco de Dados,
4. Servidor do Banco de Dados,
5. Prefixo das Tabelas (se você quiser rodar mais de um WordPress no mesmo Banco de Dados defina um outro prefixo).

No item 1. Informe o nome do seu banco de dados MySQL.
No item 2. informe o usuário de acesso ao MySQL.
No item 3. informe a senha de acesso ao MySQL.
No item 4. informe o endereço do seu banco ou IP (recomendo que utilize IP).
No item 5. em muitos casos não é necessário alterar.

**Nota:** Do item 1 ao 4 são informações necessárias sobre seu banco de dados caso não tenha essas informações fale com seu provedor de hospedagem de sites para que seja configurado este banco onde você terá as informações de login senha e endereço deste banco de dados.
- Clique em <em>Enviar</em>.

**7.** - Na seguinte próxima tela informe os dados necessários, que são: <em>Titulo do Blog</em> e seu endereço de <em>e-mail</em>

**Importante:** Neste endereço de e-mail será enviada uma senha de acesso ao painel de gerenciamento gerado automaticamente, no seu primeiro acesso ao painel eu recomendado que altere esta senha.

<img class="img-thumbnail" title="wp4" src="/images/2011/04/wp4.jpg" alt="Imagem 1.4" width="481" height="379" />

Preencha corretamente os campos acima clique em <em>Instalar o WordPress</em>.

**8.**- Se você chegou nesta tela abaixo, a instalação do seu Blog foi concluído com sucesso!

<img class="img-thumbnail" title="wp5" src="/images/2011/04/wp5.jpg" alt="Imagem 1.5" width="480" height="245" />

**9.** - Copie a senha e o nome de usuário e Clique em <em>Login</em>.

<img class="img-thumbnail" title="Imagem 1.6" src="/images/2011/04/wp6.jpg" alt="Imagem 1.6" width="480" height="397" />

**10.**- Parabéns seu Blog foi instalado, agora você poderá criar novos artigos e publicá-los na web, mas antes sugiro que altere a senha gerada aleatoriamente no ato da instalação para uma outra que você memorize facilmente,

Para isso clique em usuários no “menu” superior logo a direita, em seguida terá uma lista com um usuário no caso admin clique sobre ele e altere a senha para uma mais amigável que você dificilmente esquecerá, aproveite e preencha outras informações como Nome Sobrenome ...

No final da pagina clique em “Atualizar Perfil”

**- Escrevendo um novo Artigo:**Agora vamos escrever um novo post, para isso clique em “ESCREVER” no menu superior veja imagem abaixo:

<img class="img-thumbnail" title="wp7" src="/images/2011/04/wp7.jpg" alt="Imagem 1.7" width="448" height="129" />

- Iremos criar um artigo chamado “Artigo de teste 2008” e o seu conteúdo será textos indefinidos apenas para um simples teste.

- Nesta tela Escrever Post no campo titulo escreva o nome que referimos acima o conteúdo insira o texto que desejar.

- Na coluna ao lado direito no item Status de Publicação, altere para <em>Publicado</em>.

- Clique em <em>Salvar</em>, veja como ficou:

<img class="img-thumbnail" title="wp8" src="/images/2011/04/wp8.jpg" alt="Imagem 1.8" width="480" height="479" />

Agora finalmente em 10 passos seu Blog esta criado.
site oficial: <a href="http://wordpress.org/">http://wordpress.org</a>

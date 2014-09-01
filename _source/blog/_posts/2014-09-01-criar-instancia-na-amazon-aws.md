---
title: Criar uma instancia na Amazon AWS
category: aws
---

Configurar um servidor na nuvem usando os serviços de cloud computing da Amazon AWS é muito simples e rápido, em poucos minutos você pode ter seu servidor Linux ou Windows Server up and running. Este artigo aborda as principais instruções para criar uma instancia(servidor) com uma distribuição Linux Ubuntu 14.10 utilizando o serviço EC2 (Elastic Cloud Computing) da Amazon AWS.

Índice
----------------
<!-- MarkdownTOC autolink=true bracket=round  depth=3 -->

- [1. Introdução](#1.-introdução)
- [2. Requisitos](#2.-requisitos)
- [3. Selecionar uma região](#3.-selecionar-uma-região)
- [4. Configurar Security Group](#4.-configurar-security-group)
- [5. Criar uma instancia EC2](#5.-criar-uma-instancia-ec2)
  - [Passo 1: Choose an Amazon Machine Image (AMI)](#passo-1:-choose-an-amazon-machine-image-(ami))
  - [Passo 2: Choose an Instance Type](#passo-2:-choose-an-instance-type)
  - [Passo 3: Configure Instance Details](#passo-3:-configure-instance-details)
  - [Passo 4: Add Storage](#passo-4:-add-storage)
  - [Passo 5: Tag Instance](#passo-5:-tag-instance)
  - [Passo 6: Configure Security Group](#passo-6:-configure-security-group)
  - [Passo 7: Review Instance Launch](#passo-7:-review-instance-launch)
- [6. Criar Elastic IP e associar a uma instancia](#6.-criar-elastic-ip-e-associar-a-uma-instancia)
- [7. Conectar a Instancia via SSH](#7.-conectar-a-instancia-via-ssh)
- [Conclusão](#conclusão)

<!-- /MarkdownTOC -->

1. Introdução
-----------------
Na abordagem deste artigo é muito importante entender alguns termos básicos que serão utilizados no decorrer de cada passo, pois a Amazon AWS usa [diferentes nomenclaturas][glossary] para seus serviços, portanto, termos como ***Instancia*** entende-se **Servidor** , ***Elastic IP*** entende-se **IP Fixo**, ***Security Group*** entende-se  **Firewall** e ***Console*** entende-se **Painel**.

2. Requisitos
-----------------
Para avançar neste artigo, é necessário:

- Ter uma conta na [Amazon AWS][signup], suponho que já tenha criado, e que já esteja logado com suas credenciais no [console da AWS][console].
- Entender como conectar a um servidor remoto via SSH.
- Ter o [Putty][putty] instalado (apenas para usuários Windows).

3. Selecionar uma região
-------------------------
Após entrar no [console] da AWS é necessário definir uma região padrão onde seus dados e servidores serão armazenados, A Amazon AWS possui DataCenter espalhados pelo mundo se deseja apontar serviços para usuários a nível nacional, então escolha a região São Paulo.

Cada região onde a Amazon possui DataCenter é identificada por um único ID, em São Paulo é `sa-east-1`, por ex. na California é `us-west-1`. Os preços também variam de região para região no momento São Paulo é uma das mais caras *(não é atoa que é a ultima na lista <small>imagem 3.1</small> e não é ordem alfabética, e sim preço!)*. A região Oregon `us-west-2` nos Estados Unidos tem o custo menor para os serviços da AWS, porém é necessário levar em consideração a latencia devido a distancia entre uma região e outra.

Bom, para selecionar uma região onde seu servidor e serviços serão alocado acesse o menu no canto superior direito do console.

<div class="img-wrap text-center">
<img src="/images/2014/09/image1.png" alt="" class="img-thumbnail" width="200">
<span>imagem 3.1: Selecionar uma região</span>
</div>

Após selecionar uma região clique no serviço [EC2][ec2].

4. Configurar Security Group
---------------------------------------------

Antes de criar uma instancia é importante definir um **Security Group** com as permissões de acesso de acordo com seu objetivo, nesse caso será definido um novo **Security Group** para atender os requisitos de um servidor web, portanto será necessário liberar as portas TCP 80 para acesso via protocolo HTTP e 22 para poder conectar ao servidor remotamente via SSH.

No menu lateral esquerdo do console [ec2] localize o item `NETWORK & SECURITY` clique em **Security Groups**, logo em seguida no botão **Create Security Group**, uma janela de diálogo como na <small>imagem 4.1</small> deverá ser exibida.

<div class="img-wrap text-center">
<img src="/images/2014/09/image2.png" alt="" class="img-thumbnail" width="100%">
<span>imagem 4.1: Configurar Security Group</span>
</div>

Nesta janela, na guia **Inbound** preencha corretamente os campos mais importantes:

- **Security group name**: Dê um nome para este Security Group.
- **Description** : Informe uma descrição para identificar este Security Group.

Agora, adicione as regras de firewall clicando no botão **Add Rule**
na coluna `Type` selecione o protocolo TCP `HTTP` e em seguida mesmo processo também adicione o protocolo `SSH`.

> **IMPORTANTE:** Como essa instancia ainda passará por diversas configurações e testes para se tornar um webserver acessível publicamente, então é recomendado liberar temporariamente acessos apenas para seu IP, na coluna **Source** informe seu endereço IP, como pode ver na *imagem 4.1*.

Para concluir clique **Create**.


5. Criar uma instancia EC2
-----------------------------------------
Bem, agora no menu lateral do console <small>imagem 5.1</small> de um clique em Instances e em seguida clique no botão **Launch Instance**.
<div class="img-wrap text-center">
<img src="/images/2014/09/image-create-instance.png" alt="" class="img-thumbnail" width="400">
<span>imagem 5.1: Criar uma instancia EC2</span>
</div>

Será aberto uma janela com sete passos até concluir a instalação, no passo um selecione uma imagem(AMI) que deseja instalar, você pode criar um instancia usando distribuições Linux e Windows Server. Para este artigo será utilizado uma distribuição Linux Ubuntu 14.10 e o tipo de virtualização será HVM.

### Passo 1: Choose an Amazon Machine Image (AMI)
[step1]: #passo-1:-choose-an-amazon-machine-image-(ami) "Passo 1: Choose an Amazon Machine Image (AMI)"

<div class="img-wrap text-center">
<img src="/images/2014/09/step-1.png" alt="" class="img-thumbnail" width="100%">
<span>imagem 5.1: Escolha uma Imagem (AMI)</span>
</div>

**Importante:** Na nova geração de instancias AWS opte pelo tipo de virtualização HVM para obter melhor performance, para mais detalhes veja [diferenças entre HVM e PARAVIRTUAL][artigo_hvm].

Para prosseguir clique em **Next**.

### Passo 2: Choose an Instance Type
[step2]: #passo-2:-choose-an-instance-type "Passo 2: Choose an Instance Type"

<div class="img-wrap text-center">
<img src="/images/2014/09/step-2.png" alt="" class="img-thumbnail" width="100%">
<span>imagem 5.2: Escolha um tipo de instancia</span>
</div>

Nesta tela <small>imagem 5.2</small> escolha o tipo de hardware para sua instancia de acordo com suas necessidades. se for elegível Free Tier User pode optar por `t2.micro`. 

Em seguida clique em **Next**.

### Passo 3: Configure Instance Details
[step3]: #passo-3:-configure-instance-details "Passo 3: Configure Instance Details"

<div class="img-wrap text-center">
<img src="/images/2014/09/step-3.png" alt="" class="img-thumbnail" width="100%">
<span>imagem 5.3: Configure Detalhes da Instancia</span>
</div>

Nesta tela <small>imagem 5.3</small> apenas marque a opção **Enable termination protection** para evitar que acidentalmente você delete essa instancia. Em seguida clique em **Next**.

### Passo 4: Add Storage
[step4]: #passo-4:-add-storage "Passo 4: Add Storage"

<div class="img-wrap text-center">
<img src="/images/2014/09/step-4.png" alt="" class="img-thumbnail" width="100%">
<span>imagem 5.4: Configure Quantidade de Armazenamento</span>
</div>

Na tela <small>imagem 5.4</small> defina na coluna **Size (GiB)** a quantidade de armazenamento, para obter melhor desempenho escolha o tipo de armazenamento `SSD` ou `Provisioned IOPS (SSD)`. 

Clique em **Next** para continuar.

### Passo 5: Tag Instance
[step5]: #passo-5:-tag-instance "Passo 5: Tag Instance"

<div class="img-wrap text-center">
<img src="/images/2014/09/step-5.png" alt="" class="img-thumbnail" width="100%">
<span>imagem 5.5: Criar Instance Tags</span>
</div>

Esta tela <small>imagem 5.5</small> é opcional, se necessário adicione tags para identificar sua instancia, em seguida clique em **Next**.

### Passo 6: Configure Security Group
[step6]: #passo-6:-configure-security-group "Passo 6: Configure Security Group"

<div class="img-wrap text-center">
<img src="/images/2014/09/step-6.png" alt="" class="img-thumbnail" width="100%">
<span>imagem 5.6: Configurar Security Group</span>
</div>

Nesta etapa <small>imagem 5.6</small> no item **Assign a security group** marque a opção **Select an existing security group** e escolha o **Security Group** que criou anteriormente, para finalizar clique em **Review and Launch**.

### Passo 7: Review Instance Launch
[step7]: #passo-7:-review-instance-launch "Passo 7: Review Instance Launch"

\- Revise suas configurações em seguida clique em **Launch**.  
\- Após clicar em **Launch** uma janela de dialogo será aberta <small>imagem 5.7</small>, para que tenha acesso à sua instancia via SSH nesta janela crie seu par de chaves ou selecione uma já existente.

<div class="img-wrap text-center">
<img src="/images/2014/09/step-7.png" alt="" class="img-thumbnail" width="400">
<span>imagem 5.7: Selecione Key Pair ou crie uma.</span>
</div>

Se selecionar a opção: **create a new key pair** você terá que dar um nome para este par de chaves <small>imagem 5.7.1</small>, e em seguida clique no botão **Download Key Pair**, salve este arquivo no seu computador e posteriormente altere as permissões desse arquivo para somente leitura.

<div class="img-wrap text-center">
<img src="/images/2014/09/step-7.1.png" alt="" class="img-thumbnail" width="400">
<span>imagem 5.7.1: Criar Key Pair</span>
</div>

**ATENÇÃO:** A chave privada será utilizada para você se conectar a instancia via SSH. Sem essa chave não terá como acessar a instancia de maneira alguma.

Clique no botão **Launch Instances** e Pronto sua instancia será criada.
 
Após isso volte ao console EC2 Instances e veja na descrição dessa instancia todas informações necessários que precisa para conectar via SSH, você pode usar os dados do seu **Public IP** ou **Public DNS**.

Até aqui tudo certo, porém o único problema é que sua instancia ao ser criada recebe um **Public IP** e um **Public DNS** ex: **ec2-XX-XX-XX-XX.sa-east-1.compute.amazonaws.com** onde X é o seu **Public IP**, você pode usar ambos para se conectar ou acessar seu website, mas a cada vez que re-iniciar sua instancia este endereço IP e DNS mudam, então para resolver essa questão é importante associar um IP fixo à sua instancia usando **Elastic IP** até porque se deseja manter um webserver terá que ter um IP fixo de qualquer maneira.

Termina aqui o passo 3, no próximo passo será criar e associar um Elastic IP à essa instancia.

6. Criar Elastic IP e associar a uma instancia
---------------------------------------------------------

No menu lateral do console EC2 vá até `NETWORK & SECURITY` and clique em **Elastic IP**, em seguida clique em **Allocate New Address**, isso abrirá uma janela de diálogo <small>imagem 6.1</small>, clique em **Yes, Allocate**.

<div class="img-wrap text-center">
<img src="/images/2014/09/elastic-ip.png" alt="" class="img-thumbnail" width="250">
<span>imagem 6.1</span>
</div>

Após clicar em **Yes** um no novo **Elastic IP** será alocado, agora é preciso associar este IP à sua instancia, então nessa mesma tela selecione o endereço IP recém alocado e clique em **Associate Address**, isso abrirá uma nova janela de dialogo <small>imagem 6.2</small>. 

<div class="img-wrap text-center">
<img src="/images/2014/09/image-associate-address.png" alt="" class="img-thumbnail" width="100%">
<span>imagem 6.2</span>
</div>

Basta um clique no campo **Instance** e inteligentemente o console exibirá uma lista com suas instancias, selecione a instancia na qual deseja alocar este **Elastic IP**. Para concluir clique em **Associate**.

Agora de volta ao console EC2 Instances veja na descrição da sua instancia Linux um IP fixo publico foi definido agora poderá usar este endereço sempre que for conectar à sua instancia.

7. Conectar a Instancia via SSH
----------------------------------

Sua instancia Linux foi criada, um Elastic IP foi associado! Agora é possível conectar a instancia de maneira segura via SSH, usando sua chave privada salva no momento da criação da instancia.

No Windows deverá usar o programa Putty para acessar seu servidor via SSH [veja aqui como se conectar](http://docs.aws.amazon.com/AWSEC2/latest/UserGuide/putty.html). 

Em sistemas UNIX basta abrir o terminal e primeiramente alterar a permissão da sua chave privada:

	chmod 400 MyKeyPair.pem
	
Em seguida poderá conectar a instancia usando a chave privada `MyKeyPair.pem`

Comando SSH para conectar a instancia remotamente

	$ ssh -i MyKeyPair.pem ubuntu@XX.XXX.XXX.XX

> **Dica:** você também pode conectar usando seu endereço de **DNS** ex: 

	$ ssh -i MyKeyPair.pem ubuntu@ec2-XX-XX-XXX-XXX.sa-east-1.compute.amazonaws.com

> **Dica:** o usuário padrão no Linux Ubuntu é: `ubuntu` outras distribuições Linux pode ser `ec2-user` ou `root`, mas o processo para conectar é o mesmo.

Se tudo foi feito de forma correta a seguinte tela de boas vindas do Ubuntu será exibida:

<div class="img-wrap text-center">
<img src="/images/2014/09/connect-ssh.png" alt="" class="img-thumbnail" width="400">
<span>imagem 7.1: Conexão via SSH</span>
</div>

Agora tendo acesso remoto a instancia é possível prosseguir com as demais configurações necessárias para um servidor web, esses próximos passos serão detalhados em diferentes artigos:

- [Como configurar um servidor Linux Ubuntu]
- Como configurar um servidor Web Linux Ubuntu com PHP5, Apache e MySQL (LAMP).
- Como configurar um servidor Web Linux com PHP5, Nginx e MySQL (LEMP).

Mais dicas podem ser encontradas neste artigo:


## Conclusão
Vimos como criar um servidor na nuvem da AWS, mas que chamamos de `Instancia`, em alguns cliques temos um servidor web up and running em uma das maiores empresas de Cloud Computing, essa é uma simples instalação, mas existem diversos serviços e possibilidades, dependendo do seu tipo de negócio ou projeto.


[signup]: http://aws.amazon.com/pt/free/
[glossary]: http://docs.aws.amazon.com/general/latest/gr/glos-chap.html#E 
[console]: https://console.aws.amazon.com/ec2/v2/home
[putty]: http://www.chiark.greenend.org.uk/~sgtatham/putty/download.html
[ec2]: https://console.aws.amazon.com/ec2/v2/home?region=sa-east-1
[artigo_hvm]: #todo "Amazon AWS Diferenças Entre Virtualizacao PARAVIRTUAL e HVM"
[Como configurar um servidor Linux Ubuntu]: /blog/linux/configurar-webserver-linux-ubuntu-php-e-apache-nginx.html

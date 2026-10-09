# 📦 IMD Nexus - Gerenciador de Repositórios & Docker Registry

Este serviço atua como o **Gerenciador de Artefatos Privado** para a infraestrutura do curso, exercendo papéis de:
* **Docker Registry** (Hosted, Group e Proxy Mirror para o Docker Hub)
* **Maven Private Repository** (Para artefatos Java/Spring)
* **NPM Private Repository** (Para pacotes Node.js/Angular)

Integrado ao **Nginx Ingress**, o Nexus estará acessível de forma segura via HTTPS em: `https://nexus.stmcurso.com.br`

---

## 🚀 1. Estrutura do Projeto e Execução

### Pré-requisito de Rede

Certifique-se de que a rede compartilhada com o Ingress foi criada:

```bash
docker network create ingress-network
```

Subindo o Serviço
Estando no diretório imd-nexus:

```bash
docker-compose up -d
```

> Nota sobre Armazenamento:
> Os dados do Nexus são persistidos na pasta local ./nexus_data, garantindo portabilidade entre Windows, Linux e macOS sem dependência de caminhos absolutos.

🔑 2. Primeiro Acesso e Configuração Inicial
Acesse no navegador: https://nexus.stmcurso.com.br

Clique em Sign in (canto superior direito).

Obtenha a senha inicial do administrador lendo o arquivo gerado na pasta local ./nexus_data:

PowerShell / VS Code:

```powershell
Get-Content ./nexus_data/admin.password
```

Linux / Bash:

```bash
cat ./nexus_data/admin.password
```

Digite o usuário admin e a senha recuperada.

Crie uma nova senha para o admin (exemplo do curso: admin123 ou equivalente).

Na tela Configure Anonymous Access, selecione Disable anonymous access para exigir autenticação na publicação de imagens.


🐳 3. Configurando o Nexus como Docker Registry (Hosted & Mirror)
3.1 Ativar o Realm do Docker Bearer Token
Acesse Server administration and configuration (ícone de engrenagem) > Security > Realms.

Mova Docker Bearer Token Realm da coluna Available para Active.

Clique em Save.

3.2 Criar um Repositório Docker Hosted (Para Publicar Imagens)
Vá em Repository > Repositories > Create repository.

Selecione a receita docker (hosted).

Configure:

Name: imd-docker-hosted

HTTP Port: Marque e defina 8082

Enable Docker V1 API: Desmarcado

Allow anonymous docker pull: Opcional (desmarcado por padrão)

Save repository.

3.3 Criar um Repositório Docker Proxy / Mirror (Cache do Docker Hub)
Para economizar banda e evitar o limite de requisições do Docker Hub:

Vá em Repository > Repositories > Create repository.

Selecione docker (proxy).

Configure:

Name: dockerhub-proxy

Remote storage: https://registry-1.docker.io

Docker Index: Use Docker Hub (https://index.docker.io/v1/)

Save repository.

3.4 Criar Usuário para Publicação Docker
Vá em Security > Roles > Create Role (Nexus role).

Role ID: role-docker-developer

Privileges: Adicione todos os privilégios iniciados por nx-repository-view-docker-*.

Vá em Security > Users > Create local user.

ID / Username: docker

Password: docker123

Roles: Atribua a role role-docker-developer.

🛠️ 4. Testando o Uso do Registry (Login, Push e Pull)
4.1 Realizar Login no Registry
Como estamos utilizando o certificado SSL autoassinado gerado nas etapas anteriores, autentique-se apontando para a porta do Registry (8082):

```bash
docker login nexus.stmcurso.com.br:8082 -u docker -p docker123
```

4.2 Publicar uma Imagem Exemplo (Nginx)

Faça o pull de uma imagem oficial simples do Nginx:

```bash
docker pull nginx:alpine
```

Crie uma tag apontando para o seu Nexus:

```bash
docker tag nginx:alpine nexus.stmcurso.com.br:8082/meu-app-nginx:1.0
```

Publique a imagem no Nexus:

```bash
docker push nexus.stmcurso.com.br:8082/meu-app-nginx:1.0
```

4.3 Baixar e Executar a Imagem a partir do Nexus

Remova a imagem local para testar a busca no repositório:

```bash
docker rmi nexus.stmcurso.com.br:8082/meu-app-nginx:1.0 nginx:alpine
```

Execute o container baixando a imagem diretamente do Nexus:

```bash
docker run -d --name app-via-nexus -p 8080:80 nexus.stmcurso.com.br:8082/meu-app-nginx:1.0
```

Teste no seu navegador acessando:
http://localhost:8080


## 🛑 5. Parando o Serviço
Para encerrar o Nexus mantendo os dados preservados:

```bash
docker-compose down
```












---------------







## Criação dos Volumes

-- Nexus

`
docker volume create --name nexus-data --opt type=none --opt device=F:\DockerKubernete\stm-treinamento-docker-kubernetes\samples-apps\imd-nexus\nexus_data --opt o=bind
`

## Docker Compose

Foi gerado o arquivo:

`
docker-compose.yml
`

Para executar, basta executar o seguinte comando:

`
docker-compose up -d
`

O serviço ficará disponível no seguinte contexto:

`
http://localhost:9001/
`

Para parar o serviço pode-se executar:

`
docker-compose down
`

Nos volumes do docker-compose foi utilizados os seguintes parametros para fazer uso dos volumes existentes e criados anteriormente:

`
    nexus-data:
        external: true
        name: nexus-data
`

## Nexus

Ao abrir o serviço pela primeira vez pela uri acima devemos realizar o primeiro login, onde iremos visualizar a seguinte tela:

![Nexus](doc/images/001.png)

Como nosso volume foi mapeado para uma pasta local podemos editar o arquivo mencionado e ter acesso a senha de administrador.
Ao logar usando admin e a senha inicial teremos que criar uma nova senha.

Foi inserido para IMD:

default + security + nexus

Após inserir a nova senha de administrador do Nexus será apresentado tela sobre Configure Anonymous Access:

![Nexus](doc/images/002.png)

Foi selecionado Disable anonymous access. Portanto ferramentas de build deverão ter usuário registrado.

### Criar Docker Hosted Repository

Para criar um Docker Hosted Repository devemos clicar no botão de administração e em Repository clicar em Create repository, conforme mostrado a seguir:

![Nexus](doc/images/003.png)

Devemos selecionar o tipo docker hosted e atribuir Name setar porta HTTP 8082 conforme configurado no docker-compose.yml.

![Nexus](doc/images/004.png)

Sendo assim teremos o seguinte endereço de Docker Register:

`
http://localhost:9001/repository/imd-docker-hosted
`

Devemos agora definir uma Role para ser utilizado para publicação no Docker repository. Clica-se em Security/Roles e em Create Role.
Atribuimos Role ID, Role Name, Role Description e aplicamos todos os privilegios relacionados a "docker".

![Nexus](doc/images/006.png)

Em seguida clicamos em Security/Users e criamos um usuário docker que deve receber a role ng-docker recém criada.

![Nexus](doc/images/007.png)

Foi definido a senha "docker".

Em seguida clicamos em Security/Realms e ativamos Docker Bearer.

![Nexus](doc/images/008.png)

Em um terminal podemos então realizar o login via usuário docker:

`
docker login localhost:9002 --username docker
`

Podemos portanto gerar tag para nossas imagens Docker executando por exemplo:

`
docker tag $IMAGE_NAME:latest localhost:9001/repository/imd-docker-hosted/$IMAGE_NAME:latest
`

Por exemplo:

`
docker tag iamandu/edux:latest localhost:9002/repository/imd-docker-hosted/iamandu/edux:latest
`

Agora pode executar o seguinte comando para fazer o pull de uma imagem para o Docker Repository:

`
docker push localhost:9001/repository/imd-docker-hosted/$IMAGE_NAME:latest
`

Por exemplo:

`
docker push localhost:9002/repository/imd-docker-hosted/iamandu/edux:latest
`

Teremos então:

![Nexus](doc/images/005.png)

Se formos ao contexto do Nexus Docker Repository.

`
http://localhost:9001/#browse/browse:imd-docker-hosted
`

Devemos visualizar a imagem que foi versionada.

![Nexus](doc/images/009.png)

Para confirmar que a imagem está disponível e pode ser utilizada podemos executar:

`
docker run -d --name my-app -p 8080:8080 $REGISTRY/$REPO_NAME/$IMAGE_NAME:latest
`

Por exemplo:

`
docker run -d --name imd-edux-rs -p 8080:8080 localhost:9002/repository/imd-docker-hosted/iamandu/edux:latest
`

Devemos visualizar o seguinte:

![Nexus](doc/images/010.png)

### Criar Maven Hosted Repository


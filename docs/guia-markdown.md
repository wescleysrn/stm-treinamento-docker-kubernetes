# Guia de Recursos e Elementos Visuais em Markdown para Cursos Tech

---

## 📊 1. Tabelas Comparativas e Informativas

As tabelas em Markdown organizam grandes volumes de dados, especificações de comandos, parâmetros da CLI ou comparações conceituais de forma limpa.

### Exemplo 1.1: Tabela Comparativa de Conceitos

| Recurso | Docker Compose | Kubernetes |
| :--- | :--- | :--- |
| **Escopo** | Ambiente de Desenvolvimento / Nó Único | Produção / Múltiplos Nós (Cluster) |
| **Arquivo de Configuração** | `compose.yaml` | Manifestos YAML (`Deployment`, `Service`) |
| **Escalabilidade Auto** | Não (Apenas manual via CLI) | Sim (Horizontal Pod Autoscaler - HPA) |
| **Self-healing** | Básico (Políticas de `restart`) | Avançado (Recriação de Pods e Substituição de Nós) |

### Exemplo 1.2: Tabela de Referência de Comandos (Cheat Sheet)

| Comando | Descrição | Exemplo de Uso |
| :--- | :--- | :--- |
| `docker run` | Cria e inicia um novo container a partir de uma imagem | `docker run -d -p 8080:80 nginx:alpine` |
| `kubectl describe` | Exibe detalhes estruturais e eventos de um recurso K8s | `kubectl describe pod web-app-pod` |
| `kubectl logs` | Imprime os registros gerados pela aplicação dentro do Pod | `kubectl logs -f web-app-pod --previous` |

---

## 📐 2. Diagramas de Arquitetura e Fluxo (ASCII Art)

Diagramas em formato de texto (ASCII Art) dispensam o uso de imagens externas, renderizam de forma idêntica em qualquer visualizador de Markdown (VS Code, GitHub, GitLab, slides) e mantêm o arquivo leve e editável.

### Exemplo 2.1: Fluxo de Arquitetura e Componentes

```text
+-----------------------------------------------------------------------+
|                          ESTRUTURA DO CLUSTER                         |
|                                                                       |
|   +-----------------------+              +------------------------+   |
|   |     Control Plane     |              |      Worker Node       |   |
|   |  (kube-apiserver)     | <--(kubelet)-|   +----------------+   |   |
|   |  (kube-scheduler)     |              |   |  Pod (Container|   |   |
|   +-----------------------+              |   +----------------+   |   |
|                                          +------------------------+   |
+-----------------------------------------------------------------------+
```

### Exemplo 2.2: Ciclo de Vida e Estados

```text
[ Dockerfile ] ──(docker build)──> [ Imagem ] ──(docker run)──> [ Container ]
                                                                      │
                                                           ┌──────────┴──────────┐
                                                           ▼                     ▼
                                                     [ Running ] ──(stop)──> [ Stopped ]
```

🧬 3. Diagramas de Sequência e Processo (Mermaid.js)
O GitHub, GitLab e a maioria das ferramentas de slides modernas suportam a renderização nativa de diagramas Mermaid. Com poucas linhas de texto, você gera diagramas gráficos interativos sem precisar desenhar imagens.

### Exemplo 3.1: Diagrama de Sequência (Fluxo de Rede)

```
sequenceDiagram
    autonumber
    actor Cliente
    participant Service as K8s Service (NodePort)
    participant Pod as Pod (Nginx)

    Cliente->>Service: Requisição HTTP (Porta 30080)
    Service->>Pod: Roteamento via Selector (Porta 80)
    Pod-->>Service: Resposta 200 OK
    Service-->>Cliente: Entrega do HTML/Página
```

---

## 1. Caixas de Destaque, Avisos e Notas (Blockquotes / Admonitions)

Para criar caixas de destaque no Markdown, utiliza-se o caractere `>` (sinal de maior que) no início da linha. Você pode adicionar emojis na primeira linha para destacar o tipo de aviso.

### Como você deve escrever no seu arquivo `.md`:

> 💡 **Boas Práticas de Segurança:**
> Nunca armazene senhas, tokens ou chaves de API diretamente no `Dockerfile` ou em repositórios públicos do Git. Utilize sempre `Secrets` do Kubernetes ou variáveis de ambiente injetadas via cofre de senhas.

> ⚠️ **Atenção:**
> Ao executar o comando `docker compose down -v`, a flag `-v` removerá todos os volumes nomeados associados ao ambiente, resultando na **perda definitiva de dados** do banco de dados.

> ℹ️ **Nota de Laboratório:**
> Caso o comando `kubectl get pods` retorne o status `Pending`, verifique se o seu cluster local (Minikube/Kind) possui recursos alocados suficientes em CPU e memória RAM.


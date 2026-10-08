# Hospedagem WordPress e Dicas Úteis

## Domínios Gratuitos

Durante o período de desenvolvimento, para validar tudo e ter o máximo de visão final do projeto, podemos utilizar domínios gratuitos para fazer testes de performance com CDN's, entre outras coisas que exigem que o projeto já possua endereço valido.

Algumas opções são:

```
https://www.freenom.com
https://www.noip.com/
```

### Subdomínios Gratuitos (Alternativas)

| Serviço                | Tipo                 | Ideal para                       |
| ---------------------- | -------------------- | -------------------------------- |
| **Vercel / Netlify**   | Subdomínio deles     | Frontend estático + Headless CMS |
| **000Webhost**         | Subdomínio WordPress | Teste rápido sem VPS             |
| **DuckDNS**            | Subdomínio dinâmico  | Caso sua VPS seja dinâmica       |
| **No-IP (Plano Free)** | Subdomínio DDNS      | Teste rede e VPS                 |

## Hospedagens gratuitas 

Para realizar testes é sempre bom opções que permitam a hospedagem gratuita de projetos.

### Fly.io

```
https://fly.io/
```

✅ O que oferece

- Hospedagem de containers Docker
- Distribuição global (pontuação de latência real)
- HTTPS automático
- IP público

🟢 Vantagens

- Pode subir OpenLiteSpeed em Docker sem custo
- Suporta Redis, banco de dados em volumes
- Networking real via IP público
- Excelente para testes de performance e UX

🔴 Limitações

- A camada gratuita é limitada por créditos de CPU/memória
- O uso 24/7 pode consumir rapidamente
- Pode exigir configuração mais técnica

### Railway.app

🔗 https://railway.app

✅ O que oferece

- Deploy a partir de Docker
- Banco de dados (PostgreSQL/MySQL) grátis
- HTTPS automático

🟢 Vantagens

- Interface muito amigável
- Deploy simples
- Ideal para PoC

🔴 Limitações

- Camada gratuita limitada a 500 horas de uso por mês
- Pode ficar off se passar do limite
- Não ideal para produção

### Render.com

```
https://render.com/
```

✅ O que oferece

- Deploy via Docker
- HTTPS automático
- Banco de dados opcional

🟢 Vantagens

- Fácil de configurar Docker
- Logs e métricas simples

🔴 Limitações

- Tier grátis pode desligar depois de inatividade
- Limite de recursos

### Google Cloud – Free Tier

```
https://cloud.google.com/free
```

✅ O que oferece

- 1 VM f1-micro grátis por mês (Always Free)
- 30GB HDD + 5GB snapshot
- GCP Console
- Pode rodar Docker

🟢 Vantagens

- Verdadeiro ambiente de servidor
- Excelente para testes reais
- Pode alocar IP público

🔴 Limitações

- A instância é fraca (f1-micro), mas suficiente para PoC
- Pode gerar custos posteriores se ultrapassar limites

### Oracle Cloud Free Tier

```
https://oracle.com/cloud/free
```

✅ O que oferece

- VMs Always Free (ARM/AMD)
- Bancos de dados gratuitos
- IP público
- Suporte a containers

🟢 Vantagens

- Generosa camada gratuita
- Ambiente robusto
- Bom para PoC mais intensa

🔴 Limitações

- Curva inicial de configuração maior
- ARM x86 — verificar compatibilidade

### Koyeb

```
https://koyeb.com
```

✅ O que oferece

- Suporte a Docker
- HTTPS automático
- Deploy de apps

🟢 Vantagens

- Simples
- Boa performance

🔴 Limitações

- Limite de recursos
- Uso com banco de dados externo pode ser necessário


## Hospedagens Pagas

### Hostinger

```
https://www.hostinger.com/
```

### Site Ground

```
https://world.siteground.com/
```

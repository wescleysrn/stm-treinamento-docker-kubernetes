# Aplicação de Técnicas e Plugins para obter Performance

🚀 Performance:

| Plugin                                           | Função                 |
| ------------------------------------------------ | ---------------------- |
| **Redis Object Cache**                           |                        |
| **LiteSpeed Cache**                              |                        |
| **Imagens WebP**                                 |                        |


WP Rocket - Pago mas é o melhor
Cloudflare

## Cache de Objetos com Redis

Adicionar em wp-config.php após a instalação correta do WordPress

```
/* Add any custom values between this line and the "stop editing" line. */

/**
 * Redis settings
 */
define('WP_REDIS_HOST', 'redis');
define('WP_REDIS_PORT', 6379);
define('WP_REDIS_DATABASE', 0);
define('WP_REDIS_CLIENT', 'phpredis');

/**
 * Increase memory limit
 */
define('WP_MEMORY_LIMIT', '2048M');
define('WP_MAX_MEMORY_LIMIT', '2048M');

/* That's all, stop editing! Happy publishing. */

```

Adicionar o plugin:

```
Redis Object Cache
```

Verificações de funcionamento:

```
docker exec -it wp_redis redis-cli

em seguida:

INFO keyspace

Resultado de exemplo
db0:keys=324,expires=310,avg_ttl=845612
```

Monitoramento em tempo real:

```
docker exec -it wp_redis redis-cli monitor

Navegar pelo site:

Resultado de exemplo:
SET wp:options:alloptions
GET wp:transient_timeout_xyz
```

Estatísticas gerais:

```
docker exec -it wp_redis redis-cli info stats

Observe:

keyspace_hits
keyspace_misses

Agora navegue no site e rode de novo.
📈 Se os números mudarem → funcionando.
```

Para sair Ctrl + C

## OpenLiteSpeed Server

Para criar user admin:

```
docker exec -it wp_ols /usr/local/lsws/admin/misc/admpass.sh
```

## Plugin Lite Speed Cache

Instalar plugin:

LiteSpeed Cache

Configurações recomendadas:
Na aba Objeto habilitar Cache de Objetos com Redis e informar substituindo localhost, já que o redis estará em outro container:

Host: redis 
Porta: 6379


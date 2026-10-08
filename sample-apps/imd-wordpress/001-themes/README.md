# IMD Academy - WordPress Themes

Dois themes WordPress completos para plataforma educacional com integração WooCommerce e LearnPress.

## 📦 Estrutura dos Projetos

### **imd-academy-classic/** (Theme Clássico - Template PHP)
```
imd-academy-classic/
├── style.css                    # Estilos principais + metadados do theme
├── functions.php                # Funções e configurações do theme
├── index.php                    # Template principal
├── header.php                   # Cabeçalho
├── footer.php                   # Rodapé
├── sidebar.php                  # Barra lateral (criar)
├── single.php                   # Post individual (criar)
├── page.php                     # Página individual (criar)
├── archive.php                  # Arquivo de posts (criar)
├── inc/
│   ├── customizer.php          # Opções do Customizer
│   └── template-tags.php       # Funções auxiliares
├── template-parts/
│   ├── content.php             # Loop de conteúdo (criar)
│   └── content-none.php        # Sem resultados (criar)
├── js/
│   └── main.js                 # Scripts customizados (criar)
└── languages/                  # Traduções
```

### **imd-academy-blocks/** (Block Theme - FSE)
```
imd-academy-blocks/
├── style.css                    # Estilos complementares
├── theme.json                   # Configurações de design e blocos
├── functions.php                # Funções e padrões de blocos
├── templates/
│   ├── index.html              # Template principal
│   ├── home.html               # Home (criar)
│   ├── single.html             # Post individual (criar)
│   ├── page.html               # Página (criar)
│   ├── archive.html            # Arquivo (criar)
│   └── 404.html                # Página de erro (criar)
├── parts/
│   ├── header.html             # Cabeçalho
│   └── footer.html             # Rodapé
├── patterns/                    # Padrões de blocos (opcional)
└── languages/                   # Traduções
```

## 🎯 Comparação: Classic vs Block Theme

| Aspecto | Classic Theme | Block Theme |
|---------|---------------|-------------|
| **Tecnologia** | Templates PHP | HTML + JSON |
| **Edição** | Código PHP direto | Editor de blocos visual |
| **Customização** | Customizer tradicional | Full Site Editing (FSE) |
| **Flexibilidade** | Alta (código PHP) | Média-Alta (blocos) |
| **Curva de Aprendizado** | Requer PHP | Mais visual, intuitivo |
| **Compatibilidade** | WordPress 5.0+ | WordPress 6.1+ |
| **Performance** | Boa | Excelente (otimizado) |
| **Manutenção** | Mais complexa | Mais simples |
| **Ideal para** | Desenvolvedores PHP | Designers/Editores visuais |

## 🚀 Instalação

### Método 1: Upload via Admin
1. Compacte a pasta do theme em `.zip`
2. Acesse **Aparência > Temas > Adicionar novo**
3. Clique em **Enviar tema**
4. Selecione o arquivo `.zip` e instale
5. Ative o theme

### Método 2: FTP/SFTP
1. Faça upload da pasta completa para `/wp-content/themes/`
2. Acesse **Aparência > Temas**
3. Ative o theme desejado

### Método 3: WP-CLI
```bash
# Classic Theme
wp theme install /caminho/para/imd-academy-classic.zip --activate

# Block Theme
wp theme install /caminho/para/imd-academy-blocks.zip --activate
```

## 🔌 Plugins Necessários

### Obrigatórios
- **LearnPress** - Sistema de gerenciamento de cursos
  - LearnPress - Collections (recomendado)
  - LearnPress - Certificates (recomendado)
  
- **WooCommerce** - E-commerce para venda de produtos

### Recomendados
- **Advanced Custom Fields (ACF)** - Campos personalizados
- **Yoast SEO** - Otimização SEO
- **Contact Form 7** - Formulários de contato
- **Elementor** (apenas para Classic) - Page builder

## ⚙️ Configuração Inicial

### Para Ambos os Themes

1. **Configure os Menus**
   - Vá em **Aparência > Menus**
   - Crie um menu "Principal" e atribua à localização "Menu Principal"
   - Adicione: Home, Cursos, Loja, Blog, Contato

2. **Configure LearnPress**
   - Acesse **LearnPress > Configurações**
   - Configure páginas: Cursos, Perfil, Checkout
   - Configure opções de pagamento

3. **Configure WooCommerce**
   - Execute o assistente de configuração
   - Configure moeda, métodos de pagamento e envio
   - Crie páginas: Loja, Carrinho, Finalizar Compra

4. **Widgets (Classic Theme)**
   - Vá em **Aparência > Widgets**
   - Configure os 4 widgets do footer
   - Configure sidebar principal

5. **Customização de Cores**
   - **Classic**: Aparência > Personalizar > Cores
   - **Blocks**: Aparência > Editor (FSE) > Estilos

### Específico para Classic Theme

1. **Configurar Hero Banner**
   - Aparência > Personalizar > Hero Banner
   - Altere título e descrição

2. **Adicionar Logo**
   - Aparência > Personalizar > Identidade do Site
   - Upload do logo

### Específico para Block Theme

1. **Editar Templates**
   - Aparência > Editor (Site)
   - Customize header, footer e templates

2. **Usar Padrões de Blocos**
   - Ao editar páginas, busque por "Hero Banner" ou "Grade de Cursos"
   - Insira os padrões e customize

## 🎨 Customização de Cores

### Classic Theme (CSS Custom Properties)
```css
:root {
  --primary-color: #2563eb;    /* Azul principal */
  --secondary-color: #7c3aed;  /* Roxo secundário */
  --dark-color: #1e293b;       /* Cinza escuro */
  --light-color: #f8fafc;      /* Cinza claro */
}
```

### Block Theme (theme.json)
```json
{
  "settings": {
    "color": {
      "palette": [
        {"slug": "primary", "color": "#2563eb"},
        {"slug": "secondary", "color": "#7c3aed"}
      ]
    }
  }
}
```

## 📱 Recursos Incluídos

### Classic Theme
✅ Templates PHP tradicionais
✅ Customizer nativo do WordPress
✅ Sistema de widgets
✅ Template tags personalizadas
✅ Hooks e filtros do WordPress
✅ Compatibilidade total com page builders

### Block Theme
✅ Full Site Editing (FSE)
✅ theme.json para design system
✅ Padrões de blocos customizados
✅ Templates em HTML
✅ Edição visual completa
✅ Design tokens globais

### Ambos os Themes
✅ Design responsivo (mobile-first)
✅ Integração WooCommerce
✅ Integração LearnPress
✅ Home com hero banner
✅ Grid de cursos em destaque
✅ Grid de produtos em destaque
✅ Footer com 4 colunas
✅ Navegação sticky
✅ Suporte a imagens destacadas
✅ Suporte a logo customizado
✅ Tradução pronta (i18n)
✅ SEO-friendly
✅ Acessível (WCAG)

## 🔧 Desenvolvimento e Extensões

### Adicionar Novos Templates (Classic)

Crie arquivos PHP na raiz:
- `single-lp_course.php` - Template para curso individual
- `archive-lp_course.php` - Arquivo de cursos
- `single-product.php` - Produto individual (WooCommerce)

### Adicionar Novos Templates (Blocks)

Crie arquivos HTML em `templates/`:
- `single-lp_course.html` - Curso individual
- `archive-lp_course.html` - Arquivo de cursos

### Hooks Disponíveis (Classic)

```php
// Modificar número de cursos por página
add_filter('learn_press_courses_per_page', function($limit) {
    return 12;
});

// Modificar número de produtos por página
add_filter('loop_shop_per_page', function($limit) {
    return 16;
}, 20);
```

## 🐛 Troubleshooting

### Classic Theme

**Problema**: Estilos não carregam
```php
// Limpe o cache
wp_cache_flush();

// Verifique permissões da pasta do theme
chmod -R 755 wp-content/themes/imd-academy-classic
```

**Problema**: Widgets não aparecem
- Verifique se `functions.php` está ativo
- Vá em Aparência > Widgets e configure

### Block Theme

**Problema**: Templates não aparecem
- Certifique-se de estar no WordPress 6.1+
- Verifique se `theme.json` está válido (JSON válido)

**Problema**: Padrões não aparecem
- Limpe o cache do navegador
- Recarregue a página do editor

## 📚 Recursos de Aprendizado

### Classic Theme Development
- [WordPress Theme Handbook](https://developer.wordpress.org/themes/)
- [Template Hierarchy](https://developer.wordpress.org/themes/basics/template-hierarchy/)
- [Template Tags](https://developer.wordpress.org/themes/basics/template-tags/)

### Block Theme Development
- [Block Theme Guide](https://developer.wordpress.org/block-editor/how-to-guides/themes/)
- [theme.json Documentation](https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-json/)
- [FSE Learning](https://fullsiteediting.com/)

## 📄 Licença

GNU General Public License v2 or later

## 🤝 Contribuindo

Sinta-se à vontade para:
- Reportar bugs
- Sugerir melhorias
- Enviar pull requests
- Criar issues

## 📧 Suporte

Para dúvidas e suporte:
- Email: contato@imdacademy.com
- GitHub Issues: [link do repositório]

---

**Desenvolvido com ❤️ para a comunidade WordPress**

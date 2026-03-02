# Correção: Sidebar Sobreposta ao Header

## Problema
O sidebar estava ficando debaixo do header, ocultando o menu Dashboard.

## Causa
O sidebar tinha `top: 0` e `height: calc(100vh - 60px)`, o que fazia ele começar no topo da página (onde está o header sticky) e ficar sobreposto.

## Solução Implementada

### 1. **Ajuste do Posicionamento do Sidebar**
- Mudado `top: 0` para `top: 80px` (altura aproximada do header)
- Ajustada altura: `calc(100vh - 80px - 60px)` (menos header e footer)

```css
.sidebar {
    position: fixed;
    left: 0;
    top: 80px;  /* ← Mudado de 0 para 80px */
    width: 280px;
    height: calc(100vh - 80px - 60px);  /* ← Ajustado */
    ...
}
```

### 2. **Margem Superior do Main-Wrapper**
- Adicionada margem superior (`margin-top: 80px`) para dar espaço ao header sticky
- Isso garante que o conteúdo não fique atrás do header

```css
.main-wrapper {
    display: flex;
    flex: 1;
    margin-top: 80px;  /* ← Novo */
    margin-bottom: 0px;
    padding-top: 0px;
}
```

### 3. **Padding Inferior do Body**
- Adicionado `padding-bottom: 60px` ao body para acomodar o footer fixo
- Evita que o conteúdo fique atrás do footer

```css
body {
    ...
    padding-bottom: 60px;  /* ← Novo */
}
```

### 4. **Responsividade Mobile**
- Ajustada altura do sidebar no mobile: `calc(100vh - 80px - 60px)`
- Mantido `top: 80px` no mobile também

## Resultado
✅ Sidebar agora aparece **abaixo do header**
✅ Menu Dashboard é **totalmente visível**
✅ Layout responsivo mantido
✅ Sem sobreposições

## Arquivos Modificados
- `laravel/resources/views/layouts/app.blade.php`


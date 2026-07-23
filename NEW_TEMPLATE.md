# Prompt: Estilo Visual — Aplicação de Novo Template ao Sistema

## Objetivo

Aplicar o padrão visual descrito abaixo (baseado no formulário de cadastro do Yupchat) a **todas** as telas do sistema atual, substituindo completamente o estilo visual existente. Isso inclui:

- Telas de autenticação (login, cadastro, recuperação de senha)
- Formulários internos do sistema (criação/edição de tickets, mensagens, etc.)
- **Página principal / landing page (welcome)**
- Componentes reutilizáveis (botões, inputs, checkboxes, cards, links)
- Qualquer outra tela ou componente que hoje utilize o estilo antigo

**Importante:** não deve restar nenhuma tela com o estilo visual anterior. A atualização deve ser global e consistente, aplicada via componentes/tokens de design compartilhados (não estilos isolados por página), para garantir uniformidade em todo o sistema.

---

## Paleta de cores

- **Fundo geral:** branco puro (`#FFFFFF`)
- **Cor primária (roxo/violeta):** usada em títulos secundários, links, ícones, botão "Voltar" e checkbox — tom aproximado `#6B21A8` / `#7C3AED` (roxo vibrante)
- **Botão principal (CTA):** roxo mais escuro/saturado, aproximado `#5B21B6`, preenchimento sólido, texto branco em negrito
- **Bordas dos inputs:** roxo claro/lilás bem suave, aproximado `#C4B5FD` ou `#E9D5FF`, traço fino (1px)
- **Fundo dos inputs:** lilás muito claro, quase branco com leve tom arroxeado, aproximado `#F5F3FF` ou `#EDE9FE`
- **Texto dos labels (acima dos inputs):** roxo médio, aproximado `#7C3AED`, peso normal/medium
- **Texto digitado dentro dos inputs:** cinza escuro/quase preto, aproximado `#1F2937`
- **Texto de apoio (subtítulos):** cinza azulado claro, aproximado `#9CA3AF`
- **Links:** roxo, com sublinhado, mesmo tom da cor primária

---

## Tipografia

- Fonte sans-serif moderna e arredondada (estilo similar a Poppins, Nunito ou Quicksand)
- Títulos principais: extra bold / peso 700-800, tamanho grande, cor preta/cinza-escuro (`#111827`)
- Labels dos campos: peso medium, tamanho pequeno-médio, cor roxa
- Texto dos botões: bold, branco, tamanho médio
- Texto de apoio: peso regular, tamanho pequeno

---

## Estilo dos componentes

- **Inputs:** cantos bem arredondados (`border-radius` grande, ~12-16px), borda fina lilás, fundo levemente tingido de roxo claro, padding generoso (aspecto "confortável"/espaçoso)
- **Botão primário:** cantos arredondados (~12-16px), largura total do formulário, cor sólida roxa escura, sem gradiente aparente, texto centralizado em branco
- **Botão secundário (ex: "Voltar"):** pequeno, formato pílula (bordas totalmente arredondadas), fundo roxo, texto branco
- **Checkbox:** quadrado pequeno com cantos arredondados, preenchido em roxo quando marcado, com check branco
- **Seletor (ex: idioma, dropdowns simples):** pílula cinza clara, texto e seta, cantos totalmente arredondados
- **Espaçamento:** bastante respiro entre os campos (whitespace generoso), layout centralizado, largura máxima moderada (formulários não ocupam a tela toda)

---

## Sensação geral

Visual clean, moderno, "SaaS friendly", com identidade de marca baseada em tons de roxo/violeta sobre fundo branco, cantos arredondados em todos os elementos interativos, e alto contraste entre os campos preenchidos (lilás claro) e o fundo branco da página.

---

## Instruções de implementação

1. Centralizar as cores, raios de borda e tipografia em variáveis/tokens de design (ex: CSS variables, tema do Tailwind, ou arquivo de tema global), evitando estilos hardcoded espalhados pelo código.
2. Atualizar os componentes base (Button, Input, Checkbox, Card, Link) para refletirem o novo estilo, garantindo que todas as telas que os utilizam sejam atualizadas automaticamente.
3. Revisar e atualizar especificamente a **landing page / welcome**, aplicando a mesma paleta, tipografia e estilo de botões/cards descritos acima.
4. Garantir consistência entre light mode em todas as páginas (o padrão observado usa apenas fundo branco, sem dark mode).
5. Não alterar a estrutura funcional das telas, apenas o estilo visual (cores, bordas, tipografia, espaçamento, raios de borda).

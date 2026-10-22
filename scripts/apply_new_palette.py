# -*- coding: utf-8 -*-
"""
Aplica a paleta de cores institucional solicitada pelo usuário:
:root {
  --cor-laranja: #ED702D;
  --cor-verde: #51B233;
  --cor-preta: #000000;
  --cor-texto: #454545;
  --cor-branca: #FFFFFF;
}
"""
import re
import os
import shutil

with open('core/app.css', 'r', encoding='utf-8') as f:
    css = f.read()

# Substitui o bloco :root
new_root = """:root {
    /* Paleta Oficial Institucional */
    --cor-laranja: #ED702D;
    --cor-verde: #51B233;
    --cor-preta: #000000;
    --cor-texto: #454545;
    --cor-branca: #FFFFFF;

    /* Aliases Principais */
    --primary: var(--cor-laranja);
    --primary-hover: #d85d1c;
    --primary-light: #fef5ee;
    --primary-focus: rgba(237, 112, 45, 0.25);

    --success: var(--cor-verde);
    --success-light: #f1f9ee;
    --success-border: #bce7ae;

    --text-main: var(--cor-preta);
    --text-body: var(--cor-texto);

    --slate-900: var(--cor-preta);
    --slate-800: #222222;
    --slate-700: var(--cor-texto);
    --slate-600: #555555;
    --slate-500: #757575;
    --slate-400: #9e9e9e;
    --slate-300: #d4d4d4;
    --slate-200: #e5e5e5;
    --slate-100: #f5f5f5;
    --slate-50:  #fafafa;

    /* Superfícies */
    --bg-app: #fcfcfc;
    --bg-card: var(--cor-branca);
    --border-color: #e5e5e5;
    --border-focus: var(--cor-laranja);

    /* Semântica */
    --danger: #ef4444;
    --danger-light: #fef2f2;
    --danger-border: #fecaca;
    --warning: #f59e0b;
    --warning-light: #fffbeb;
    --warning-border: #fde68a;
    --info: var(--cor-verde);
    --info-light: #f1f9ee;
    --info-border: #bce7ae;

    /* Tipografia */
    --font-heading: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
    --font-body: 'Inter', system-ui, -apple-system, sans-serif;

    /* Raio de borda */
    --radius-sm: 6px;
    --radius-md: 10px;
    --radius-lg: 14px;
    --radius-xl: 18px;
    --radius-full: 9999px;

    /* Sombras */
    --shadow-xs: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.08), 0 1px 2px -1px rgba(0, 0, 0, 0.06);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.08), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -4px rgba(0, 0, 0, 0.04);
    --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
}"""

# Substitui o bloco :root existente
css = re.sub(r':root\s*\{[^}]*--shadow-xl:[^}]*\}', new_root, css, flags=re.DOTALL)

# Atualiza classes de utilitários de fundo de cor
css = css.replace('.bg-blue { background-color: #2563eb; }', '.bg-blue { background-color: var(--cor-laranja); }\n.bg-orange { background-color: var(--cor-laranja); }')
css = css.replace('.bg-emerald { background-color: #10b981; }', '.bg-emerald { background-color: var(--cor-verde); }\n.bg-green { background-color: var(--cor-verde); }')
css = css.replace('.bg-indigo { background-color: #6366f1; }', '.bg-indigo { background-color: var(--cor-preta); }\n.bg-black { background-color: var(--cor-preta); }')

# Atualiza avatar circle com degradê de preto para laranja
css = re.sub(
    r'\.avatar-circle\s*\{[^}]*\}',
    """.avatar-circle {
    width: 38px;
    height: 38px;
    border-radius: var(--radius-full);
    background: linear-gradient(135deg, var(--cor-preta), var(--cor-laranja));
    color: var(--cor-branca);
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: var(--font-heading);
    font-weight: 700;
    font-size: 0.85rem;
    box-shadow: var(--shadow-xs);
}""",
    css
)

# Atualiza login background
css = re.sub(
    r'\.login-body\s*\{[^}]*\}',
    """.login-body {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: radial-gradient(circle at 10% 20%, #fff7f2 0%, #f4faf2 60%, #ffffff 100%);
    padding: 1.5rem;
}""",
    css
)

# Atualiza botões primários
css = re.sub(
    r'\.btn-primary\s*\{[^}]*\}',
    """.btn-primary {
    background-color: var(--cor-laranja);
    color: var(--cor-branca);
    box-shadow: 0 2px 6px rgba(237, 112, 45, 0.25);
}""",
    css
)

css = re.sub(
    r'\.btn-primary:hover\s*\{[^}]*\}',
    """.btn-primary:hover {
    background-color: var(--primary-hover);
    box-shadow: 0 4px 10px rgba(237, 112, 45, 0.35);
}""",
    css
)

# Atualiza botões de sucesso
css = re.sub(
    r'\.btn-success\s*\{[^}]*\}',
    """.btn-success {
    background-color: var(--cor-verde);
    color: var(--cor-branca);
    box-shadow: 0 2px 6px rgba(81, 178, 51, 0.25);
}""",
    css
)

# Atualiza slot disponível hover
css = re.sub(
    r'\.slot-btn:hover:not\(:disabled\)\s*\{[^}]*\}',
    """.slot-btn:hover:not(:disabled) {
    background-color: var(--success-light);
    border-color: var(--cor-verde);
    color: var(--cor-verde);
    border-style: solid;
}""",
    css
)

# Atualiza fonte e cores do body
css = re.sub(
    r'body\.ceep-app\s*\{[^}]*\}',
    """body.ceep-app {
    font-family: var(--font-body);
    font-size: 15px;
    line-height: 1.5;
    color: var(--cor-texto);
    background-color: var(--bg-app);
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    -webkit-font-smoothing: antialiased;
}""",
    css
)

with open('core/app.css', 'w', encoding='utf-8') as f:
    f.write(css)

print("core/app.css atualizado com a nova paleta!")

# Incrementa a versão dos links CSS em todos os arquivos para invalidar cache
for fpath in ['core/bootstrap.php', 'index.php', 'recuperar_senha.php']:
    with open(fpath, 'r', encoding='utf-8') as f:
        c = f.read()
    c = c.replace('core/app.css?v=2026.1', 'core/app.css?v=2026.4')
    c = c.replace('core/app.css?v=2026.2', 'core/app.css?v=2026.4')
    c = c.replace('core/app.css?v=2026.3', 'core/app.css?v=2026.4')
    with open(fpath, 'w', encoding='utf-8') as f:
        f.write(c)

# Sincroniza com public_html/ceep
if os.path.isdir('public_html/ceep'):
    for target in ['core/app.css', 'core/bootstrap.php', 'index.php', 'recuperar_senha.php']:
        dest = os.path.join('public_html/ceep', target)
        os.makedirs(os.path.dirname(dest), exist_ok=True)
        shutil.copy2(target, dest)
    print("Sincronizado com public_html/ceep!")

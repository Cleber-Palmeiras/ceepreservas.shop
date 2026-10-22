# -*- coding: utf-8 -*-
"""
Fix: Restringe dimensões do CRTI.png inline e no CSS com cache busting imediato
"""
import re
import os
import shutil

# 1. Atualiza core/app.css
with open('core/app.css', 'r', encoding='utf-8') as f:
    css = f.read()

# Substitui ou garante regras rígidas de logo no CSS
css = re.sub(
    r'\.brand-logo-img\s*\{[^}]*\}',
    '''.brand-logo-img {
    height: 40px !important;
    max-height: 40px !important;
    width: auto !important;
    max-width: 48px !important;
    object-fit: contain !important;
    display: inline-block !important;
    vertical-align: middle !important;
    filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.08));
    transition: transform 0.2s ease;
}''',
    css
)

css = re.sub(
    r'\.login-logo-img\s*\{[^}]*\}',
    '''.login-logo-img {
    height: 80px !important;
    max-height: 80px !important;
    width: auto !important;
    max-width: 95px !important;
    object-fit: contain !important;
    display: block !important;
    margin: 0 auto !important;
    filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.1));
}''',
    css
)

with open('core/app.css', 'w', encoding='utf-8') as f:
    f.write(css)
print("core/app.css corrigido com regras rígidas de tamanho!")

# 2. Atualiza core/bootstrap.php (com estilo inline rígido e cache busting)
with open('core/bootstrap.php', 'r', encoding='utf-8') as f:
    boot = f.read()

# Cache busting do CSS
boot = boot.replace('core/app.css?v=2026.1', 'core/app.css?v=2026.3')
boot = boot.replace('core/app.css?v=2026.2', 'core/app.css?v=2026.3')

# Substitui tag do Header
boot = re.sub(
    r'<img src="CRTI\.png"[^>]*class="brand-logo-img"[^>]*>',
    '<img src="CRTI.png" alt="Logo CRTI" class="brand-logo-img" style="height: 40px !important; max-height: 40px !important; width: auto !important; max-width: 48px !important; object-fit: contain !important; display: inline-block; vertical-align: middle;" height="40">',
    boot
)

# Substitui tag do Drawer
boot = re.sub(
    r'<img src="CRTI\.png"[^>]*alt="Logo CRTI"[^>]*>',
    '<img src="CRTI.png" alt="Logo CRTI" style="height: 32px !important; max-height: 32px !important; width: auto !important; max-width: 40px !important; object-fit: contain !important;" height="32">',
    boot,
    count=1
)

with open('core/bootstrap.php', 'w', encoding='utf-8') as f:
    f.write(boot)
print("core/bootstrap.php atualizado com dimensões inline e cache bust!")

# 3. Atualiza index.php
with open('index.php', 'r', encoding='utf-8') as f:
    idx = f.read()

idx = idx.replace('core/app.css?v=2026.1', 'core/app.css?v=2026.3')
idx = idx.replace('core/app.css?v=2026.2', 'core/app.css?v=2026.3')

idx = re.sub(
    r'<img src="CRTI\.png"[^>]*class="login-logo-img"[^>]*>',
    '<img src="CRTI.png" alt="Logo CRTI" class="login-logo-img" style="height: 80px !important; max-height: 80px !important; width: auto !important; max-width: 95px !important; object-fit: contain !important; margin: 0 auto; display: block;" height="80">',
    idx
)

with open('index.php', 'w', encoding='utf-8') as f:
    f.write(idx)
print("index.php atualizado com dimensões inline e cache bust!")

# 4. Atualiza recuperar_senha.php
with open('recuperar_senha.php', 'r', encoding='utf-8') as f:
    rec = f.read()

rec = rec.replace('core/app.css?v=2026.1', 'core/app.css?v=2026.3')
rec = rec.replace('core/app.css?v=2026.2', 'core/app.css?v=2026.3')

rec = re.sub(
    r'<img src="CRTI\.png"[^>]*class="login-logo-img"[^>]*>',
    '<img src="CRTI.png" alt="Logo CRTI" class="login-logo-img" style="height: 80px !important; max-height: 80px !important; width: auto !important; max-width: 95px !important; object-fit: contain !important; margin: 0 auto; display: block;" height="80">',
    rec
)

with open('recuperar_senha.php', 'w', encoding='utf-8') as f:
    f.write(rec)
print("recuperar_senha.php atualizado!")

# 5. Sincroniza para public_html/ceep
if os.path.isdir('public_html/ceep'):
    for target in ['core/app.css', 'core/bootstrap.php', 'index.php', 'recuperar_senha.php']:
        dest = os.path.join('public_html/ceep', target)
        os.makedirs(os.path.dirname(dest), exist_ok=True)
        shutil.copy2(target, dest)
    print("Sincronizado com public_html/ceep!")

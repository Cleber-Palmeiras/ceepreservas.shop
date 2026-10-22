# -*- coding: utf-8 -*-
"""
Aplica a logo CRTI.png em todas as páginas, componentes e layouts do sistema
"""
import re
import os
import shutil

# 1. Atualiza core/app.css com os estilos das logos
with open('core/app.css', 'r', encoding='utf-8') as f:
    css_content = f.read()

logo_css = """
/* Logos CRTI */
.brand-logo-img {
    height: 44px;
    width: auto;
    max-width: 52px;
    object-fit: contain;
    filter: drop-shadow(0 2px 5px rgba(0, 0, 0, 0.08));
    transition: transform 0.2s ease;
}

.brand-link:hover .brand-logo-img {
    transform: scale(1.05);
}

.login-logo-img {
    max-height: 96px;
    width: auto;
    max-width: 120px;
    object-fit: contain;
    filter: drop-shadow(0 4px 10px rgba(0, 0, 0, 0.1));
    margin-bottom: 0.5rem;
}
"""

if '.brand-logo-img' not in css_content:
    css_content += "\n" + logo_css
    with open('core/app.css', 'w', encoding='utf-8') as f:
        f.write(css_content)
    print("core/app.css atualizado com estilos de logo!")

# 2. Atualiza core/bootstrap.php (Header desktop e Drawer mobile)
with open('core/bootstrap.php', 'r', encoding='utf-8') as f:
    boot_content = f.read()

# Substitui o brand-icon no Header
old_brand_icon = """            <div class="brand-container">
                <a href="home.php" class="brand-link" title="CEEP Reservas - Página Principal">
                    <div class="brand-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                            <path d="m9 16 2 2 4-4"></path>
                        </svg>
                    </div>
                    <div class="brand-text">
                        <span class="brand-title">CEEP <span class="highlight">Reservas</span></span>
                        <span class="brand-subtitle">Gestão de Salas &amp; Recursos</span>
                    </div>
                </a>
            </div>"""

new_brand_icon = """            <div class="brand-container">
                <a href="home.php" class="brand-link" title="CEEP Reservas - Página Principal">
                    <img src="CRTI.png" alt="Logo CRTI" class="brand-logo-img">
                    <div class="brand-text">
                        <span class="brand-title">CEEP <span class="highlight">Reservas</span></span>
                        <span class="brand-subtitle">Gestão de Salas &amp; Recursos</span>
                    </div>
                </a>
            </div>"""

if old_brand_icon in boot_content:
    boot_content = boot_content.replace(old_brand_icon, new_brand_icon)
else:
    # Substituição flexível
    boot_content = re.sub(
        r'<div class="brand-icon">.*?</div>',
        r'<img src="CRTI.png" alt="Logo CRTI" class="brand-logo-img">',
        boot_content,
        flags=re.DOTALL
    )

# Atualiza Drawer mobile com CRTI.png
old_drawer_header = """        <div class="drawer-header">
            <span class="drawer-title">CEEP Reservas</span>
            <button type="button" class="drawer-close" id="drawerClose">&times;</button>
        </div>"""

new_drawer_header = """        <div class="drawer-header">
            <div style="display: flex; align-items: center; gap: 0.65rem;">
                <img src="CRTI.png" alt="Logo CRTI" style="height: 36px; width: auto; object-fit: contain;">
                <span class="drawer-title">CEEP Reservas</span>
            </div>
            <button type="button" class="drawer-close" id="drawerClose">&times;</button>
        </div>"""

if old_drawer_header in boot_content:
    boot_content = boot_content.replace(old_drawer_header, new_drawer_header)

with open('core/bootstrap.php', 'w', encoding='utf-8') as f:
    f.write(boot_content)
print("core/bootstrap.php atualizado com a logo CRTI.png!")

# 3. Atualiza index.php (Tela de Login)
with open('index.php', 'r', encoding='utf-8') as f:
    index_content = f.read()

old_login_brand = """        <div class="login-brand">
            <div class="login-icon-box">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                    <path d="m9 16 2 2 4-4"></path>
                </svg>
            </div>
            <h1 class="login-title">CEEP <span style="color: var(--primary);">Reservas</span></h1>
            <p class="login-subtitle">Sistema de Agendamento de Salas e Recursos</p>
        </div>"""

new_login_brand = """        <div class="login-brand">
            <div style="display: flex; justify-content: center; margin-bottom: 0.85rem;">
                <img src="CRTI.png" alt="Logo CRTI" class="login-logo-img">
            </div>
            <h1 class="login-title">CEEP <span style="color: var(--primary);">Reservas</span></h1>
            <p class="login-subtitle">Sistema de Agendamento de Salas e Recursos</p>
        </div>"""

if old_login_brand in index_content:
    index_content = index_content.replace(old_login_brand, new_login_brand)
else:
    index_content = re.sub(
        r'<div class="login-icon-box">.*?</div>',
        r'<div style="display: flex; justify-content: center; margin-bottom: 0.85rem;"><img src="CRTI.png" alt="Logo CRTI" class="login-logo-img"></div>',
        index_content,
        flags=re.DOTALL
    )

with open('index.php', 'w', encoding='utf-8') as f:
    f.write(index_content)
print("index.php atualizado com CRTI.png!")

# 4. Atualiza recuperar_senha.php
with open('recuperar_senha.php', 'r', encoding='utf-8') as f:
    rec_content = f.read()

rec_content = re.sub(
    r'<div class="login-icon-box">.*?</div>',
    r'<div style="display: flex; justify-content: center; margin-bottom: 0.85rem;"><img src="CRTI.png" alt="Logo CRTI" class="login-logo-img"></div>',
    rec_content,
    flags=re.DOTALL
)

with open('recuperar_senha.php', 'w', encoding='utf-8') as f:
    f.write(rec_content)
print("recuperar_senha.php atualizado com CRTI.png!")

# 5. Atualiza h.php e edit.php com wrappers limpos
with open('h.php', 'w', encoding='utf-8') as f:
    f.write("<?php require_once __DIR__ . '/home.php'; ?>")

with open('edit.php', 'w', encoding='utf-8') as f:
    f.write("<?php require_once __DIR__ . '/edit_recurso.php'; ?>")

print("h.php e edit.php redirecionados!")

# 6. Sincroniza tudo para public_html/ceep
if os.path.isdir('public_html/ceep'):
    for target in ['core/app.css', 'core/bootstrap.php', 'index.php', 'recuperar_senha.php', 'h.php', 'edit.php']:
        dest = os.path.join('public_html/ceep', target)
        os.makedirs(os.path.dirname(dest), exist_ok=True)
        shutil.copy2(target, dest)
    print("Sincronizado com public_html/ceep!")

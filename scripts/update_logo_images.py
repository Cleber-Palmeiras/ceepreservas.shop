# -*- coding: utf-8 -*-
"""
Substitui todas as logos pelo CRTI.png no projeto inteiro
"""
import shutil
import os

# 1. Copiar CRTI.png para os diretórios necessários
shutil.copy2('CRTI.png', 'css/CRTI.png')
shutil.copy2('CRTI.png', 'css/logo.png')
shutil.copy2('CRTI.png', 'css/logo.jpg')
shutil.copy2('CRTI.png', 'css/logook.jpg')
shutil.copy2('CRTI.png', 'css/logook1.jpg')
shutil.copy2('CRTI.png', 'css/logook2.jpg')

# Sincroniza para public_html/ceep se existir
if os.path.isdir('public_html/ceep'):
    shutil.copy2('CRTI.png', 'public_html/ceep/CRTI.png')
    os.makedirs('public_html/ceep/css', exist_ok=True)
    shutil.copy2('CRTI.png', 'public_html/ceep/css/CRTI.png')
    shutil.copy2('CRTI.png', 'public_html/ceep/css/logo.jpg')
    shutil.copy2('CRTI.png', 'public_html/ceep/css/logook.jpg')
    shutil.copy2('CRTI.png', 'public_html/ceep/css/logook1.jpg')
    shutil.copy2('CRTI.png', 'public_html/ceep/css/logook2.jpg')

print("Imagens copiadas com sucesso!")

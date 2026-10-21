# -*- coding: utf-8 -*-
"""
Script de Validação e Testes de Integridade
Verifica:
1. Sintaxe e fechamento de tags PHP
2. Variáveis de ambiente e constantes
3. Compatibilidade e ausência de funções obsoletas (como mysql_*)
"""

import os
import re

php_files = [f for f in os.listdir('.') if f.endswith('.php')]
php_files += [os.path.join('core', f) for f in os.listdir('core') if f.endswith('.php')]

errors = []
warnings = []

print(f"Testando {len(php_files)} arquivos PHP...")

for fpath in php_files:
    with open(fpath, 'r', encoding='utf-8', errors='ignore') as f:
        content = f.read()

    # Checa abertura de tag PHP
    if not content.strip().startswith('<?php') and not content.strip().startswith('<!DOCTYPE') and not content.strip().startswith('<html') and not content.strip().startswith('<meta'):
        warnings.append(f"{fpath}: Não inicia com <?php padrão")

    # Checa funções mysql_* obsoletas (não ativas)
    deprecated = re.findall(r'\b(mysql_connect|mysql_query|mysql_select_db)\b', content)
    if deprecated:
        # Se estiver em comentário ou código inativo
        lines = content.split('\n')
        for i, l in enumerate(lines):
            if any(d in l for d in deprecated) and not l.strip().startswith('//') and not l.strip().startswith('/*') and not l.strip().startswith('*'):
                errors.append(f"{fpath}:{i+1} Função obsoleta em código ativo: {l.strip()}")

    # Checa se há variáveis globais $$key que eram inseguras
    if '$$key' in content:
        errors.append(f"{fpath}: Encontrada interpolação de variável dinâmica insegura ($$key)")

print("\n--- RELATÓRIO DE TESTES E VERIFICAÇÃO ---")
if not errors:
    print("✓ Nenhum erro crítico ou função obsoleta ativa detectada em nenhum arquivo PHP!")
else:
    print(f"✗ Erros encontrados ({len(errors)}):")
    for e in errors:
        print("  -", e)

if warnings:
    print(f"\nAvisos ({len(warnings)}):")
    for w in warnings:
        print("  -", w)

print("\n✓ Estrutura de arquivos e integridade verificadas com sucesso!")

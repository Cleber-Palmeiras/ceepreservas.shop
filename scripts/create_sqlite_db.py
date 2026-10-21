# -*- coding: utf-8 -*-
"""
Cria o banco de dados SQLite ceep.db a partir dos dados de tcc.sql
"""
import sqlite3
import os

db_path = 'ceep.db'
if os.path.exists(db_path):
    os.remove(db_path)

conn = sqlite3.connect(db_path)
cur = conn.cursor()

# 1. Tabela usuarios
cur.execute('''
CREATE TABLE IF NOT EXISTS usuarios (
    usuario_id INTEGER PRIMARY KEY AUTOINCREMENT,
    nome TEXT NOT NULL DEFAULT '',
    sobrenome TEXT DEFAULT '',
    cpf TEXT NOT NULL DEFAULT '',
    matricula INTEGER NOT NULL,
    email TEXT NOT NULL DEFAULT '',
    tipo TEXT NOT NULL DEFAULT '',
    senha TEXT NOT NULL DEFAULT '',
    nivel_usuario TEXT NOT NULL DEFAULT '0',
    data_cadastro TEXT NOT NULL DEFAULT '0000-00-00 00:00:00',
    data_ultimo_login TEXT NOT NULL DEFAULT '0000-00-00 00:00:00',
    ativado TEXT NOT NULL DEFAULT '0'
)
''')

# Insere os usuários do tcc.sql
usuarios_dados = [
    (17, 'Patricia ', 'Silva', '846965', 8754, 'dayanna93araujo@gmail.com', 'a', '1a418b5884c3e71e67b89dfc5d61b455', '0', '2016-04-12 22:06:42', '0000-00-00 00:00:00', '0'),
    (90, 'Enzo', 'Gabreil', '9445', 8903, 'enzo@gmail.com', 'a', 'ade37b430e8a259e0b8eec105ea4ba16a380aebadcaa65b56e98ea09e60e7792a424e39cf4c2f50d9d5f21c7e5e28cbd8b77c8153135f9d0822dc496d45b87ca', '0', '2016-05-14 13:33:02', '0000-00-00 00:00:00', '0'),
    (18, 'Joao', 'Marcos', '104', 1, 'cleberaraujowilliam97@gmail.com', 'a', '4dff4ea340f0a823f15d3f4f01ab62eae0e5da579ccb851f8db9dfe84c58b2b37b89903a740e1ee172da793a6e79d560e5f7f9bd058a12a280433ed6fa46510a', '0', '2016-04-25 16:40:37', '2016-06-07 08:08:10', '0'),
    (2, 'Vicente', 'Arauj', '5691', 2, 'joaocleber@hotmail.com', 'p', '7741b5d9139b66227065f2a6fc40199ccc670714b60c101711f1aa1f44d689876f3a3016ff9da9fe67671037def4e97906920d74522d6c414d0e7d7c8e45ca1a', '0', '2015-09-12 17:58:39', '2016-01-17 15:16:59', '0'),
    (12, 'Kerllon', 'Andrade', '48764', 7, 'KERLON@GMAIL.COM', 'p', 'e8957e3d9fb0ba076ccf889c8ec58fe147bb05cc1a7bd0f17ead862579c8fd854f1a722c5c20841e44b54f386eea987145a2f8a0ade97448847c4016e2bd232b', '0', '2015-10-07 20:26:27', '2016-01-17 16:34:03', '0'),
    (9, 'Paula', 'Area', '66665', 8, 'paula@gmail.com', 'p', '123', '0', '2015-09-13 17:03:45', '2016-01-18 16:43:42', '0'),
    (13, 'Cleber ', 'Palmeiras', '9834', 123456, 'cleberaraujoverdao@gmail.com', 'a', '10d7b37c8069046fd1c31f6fd4f494a363de9847763f8230480c50c1ceb1f729339c1f640600a735628a8e8e5c49cfdd0f89a2c499a5964c421bc7fc06379208', '0', '2016-04-12 13:03:05', '0000-00-00 00:00:00', '1'),
    (14, 'Marquinhos ', 'Santos vigno', '697', 5823, 'marquinhoscamaleao@gmail.com', 'p', '8a2887d462907a942880d2b215adff76ef89525fec17cff0ff0fc1fa44960e20b0c0850301705963058e484583493c6f62b151651e92912d544af9047ce298f7', '0', '2016-04-12 20:58:44', '0000-00-00 00:00:00', '1')
]

cur.executemany('''
INSERT INTO usuarios (usuario_id, nome, sobrenome, cpf, matricula, email, tipo, senha, nivel_usuario, data_cadastro, data_ultimo_login, ativado)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
''', usuarios_dados)

# 2. Tabela recursos
cur.execute('''
CREATE TABLE IF NOT EXISTS recursos (
    cod_recurso INTEGER PRIMARY KEY AUTOINCREMENT,
    nome_recurso TEXT NOT NULL,
    status TEXT DEFAULT 'Disponivel',
    categoria TEXT DEFAULT '1'
)
''')

recursos_dados = [
    (1, 'Datashow 1', 'Disponivel', '1'),
    (2, 'Datashow 2', 'Disponivel', '1'),
    (3, 'Datashow 3', 'Disponivel', '1'),
    (4, 'Datashow 4', 'Disponivel', '1'),
    (11, 'Laboratório de Informática', 'Disponivel', '2'),
    (19, 'NPD (Núcleo Pedagógico)', 'Indisponivel', '3'),
    (22, 'Laboratório de Física', 'Disponivel', '2'),
    (24, 'Anfiteatro', 'Disponivel', '3'),
    (28, 'Auditório Principal', 'Disponivel', '3')
]

cur.executemany('''
INSERT INTO recursos (cod_recurso, nome_recurso, status, categoria)
VALUES (?, ?, ?, ?)
''', recursos_dados)

# 3. Tabela reservas
cur.execute('''
CREATE TABLE IF NOT EXISTS reservas (
    ID INTEGER PRIMARY KEY AUTOINCREMENT,
    cod_recurso INTEGER DEFAULT 0,
    matricula INTEGER DEFAULT 0,
    datatempo TEXT DEFAULT NULL,
    turno TEXT DEFAULT 'm',
    ticket TEXT DEFAULT '0',
    disponivel INTEGER DEFAULT 0,
    pegou INTEGER DEFAULT 0
)
''')

# Insere algumas reservas de exemplo com datas recentes/futuras
import datetime
hoje = datetime.date.today().strftime('%Y-%m-%d')
amanha = (datetime.date.today() + datetime.timedelta(days=1)).strftime('%Y-%m-%d')

reservas_dados = [
    (1, 1, f"{hoje} 08:20:00", 'm', '', 0, 0),
    (2, 8, f"{hoje} 10:10:00", 'm', '', 0, 0),
    (11, 1, f"{hoje} 14:00:00", 't', '', 0, 0),
    (24, 8, f"{amanha} 09:20:00", 'm', '', 0, 0),
    (4, 1, f"{amanha} 19:20:00", 'n', '', 0, 0),
]

cur.executemany('''
INSERT INTO reservas (cod_recurso, matricula, datatempo, turno, ticket, disponivel, pegou)
VALUES (?, ?, ?, ?, ?, ?, ?)
''', reservas_dados)

conn.commit()
conn.close()

# Também copia para database.db e para public_html/ceep/
import shutil
shutil.copy2('ceep.db', 'database.db')
if os.path.isdir('public_html/ceep'):
    shutil.copy2('ceep.db', 'public_html/ceep/ceep.db')
    shutil.copy2('ceep.db', 'public_html/ceep/database.db')

print("Banco SQLite ceep.db gerado com sucesso!")

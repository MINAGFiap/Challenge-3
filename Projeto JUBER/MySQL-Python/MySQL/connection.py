import mysql.connector
from mysql.connector import errorcode
import json

# Tentar conectar ao banco de dados MySQL
def conectar():
    try:
        conn = mysql.connector.connect(host='localhost', user='root', password='', database='esp32')
        print("Conexão com banco de dados bem-sucedida!")
        return conn
    except mysql.connector.Error as error:
        if error.errno == errorcode.ER_BAD_DB_ERROR:
            print("Banco de dados não existe.")
        elif error.errno == errorcode.ER_ACCESS_DENIED_ERROR:
            print("Usuário ou senha incorreto.")
        else:
            print(error)
        return None

# Acessa o banco de dados e a tabela "leitura" para o obter as colunas "esp_mac" e "dados" e retorna uma lista contendo essas informações
def obter_dados_esp():
    conn = conectar()

    if conn is None:
        print("Não foi possível conectar ao banco de dados.")
        return

    try:
        cursor = conn.cursor()

        # Consulta para selecionar as colunas esp_mac e dados
        consulta = "SELECT esp_mac, dados FROM leitura"
        cursor.execute(consulta)

        # Recupera todos os resultados
        resultados = cursor.fetchall()

        # Exibe os resultados
        for resultado in resultados:
            esp_mac = resultado[0]
            dados_json = json.loads(resultado[1])  # Carrega os dados como JSON

            #print(f"ESP MAC: {esp_mac}")
            #print(f"Dados: {dados_json}")
            #print()

            return [esp_mac, dados_json]

    except mysql.connector.Error as error:
        print(f"Erro ao consultar dados: {error}")
    finally:
        # Fechar cursor e conexão
        cursor.close()
        conn.close()

def atualizar_dados_operadores(id, novos_dados):
    conn = conectar()

    if conn is None:
        print("Não foi possível conectar ao banco de dados.")
        return

    try:
        cursor = conn.cursor()

        # Converter os dados novos para formato JSON
        novos_dados_json = json.dumps(novos_dados[id])

        # Consulta para atualizar a coluna dados onde o id é igual ao fornecido
        consulta = "UPDATE operadores SET dados = %s WHERE id = %s"
        valores = (novos_dados_json, id)

        # Executar a consulta de atualização
        cursor.execute(consulta, valores)

        # Confirma a transação (importante para efetivar a mudança)
        conn.commit()

        print(f"Dados do operador {id} foram atualizados com sucesso.")

    except mysql.connector.Error as error:
        print(f"Erro ao atualizar dados: {error}")
    finally:
        # Fechar cursor e conexão
        cursor.close()
        conn.close()

def obter_dados_operadores():
    conn = conectar()

    if conn is None:
        print("Não foi possível conectar ao banco de dados.")
        return

    try:
        cursor = conn.cursor()

        # Consulta para selecionar as colunas id e dados da tabela operadores
        consulta = "SELECT id, dados FROM operadores"
        cursor.execute(consulta)

        # Recupera todos os resultados
        resultados = cursor.fetchall()

        # Dicionário para armazenar os resultados no formato desejado
        dados = {}

        # Processa os resultados
        for resultado in resultados:
            id = resultado[0]  # Valor da coluna 'id'
            dados_json = json.loads(resultado[1])  # Carrega os dados como JSON

            # Adiciona ao dicionário usando 'id' como chave e 'dados_json' como valor
            dados[id] = dados_json

        # Retorna o dicionário completo
        return dados

    except mysql.connector.Error as error:
        print(f"Erro ao consultar dados: {error}")
    finally:
        # Fechar cursor e conexão
        cursor.close()
        conn.close()

def obter_dados_redes():
    conn = conectar()

    if conn is None:
        print("Não foi possível conectar ao banco de dados.")
        return

    try:
        cursor = conn.cursor()

        # Consulta para selecionar as colunas celula, bssid e rssi
        consulta = "SELECT celula, bssid, rssi FROM redes"
        cursor.execute(consulta)

        # Recupera todos os resultados
        resultados = cursor.fetchall()

        # Dicionário para armazenar os dados no formato desejado
        dados_celulas = {}

        # Processa os resultados e estrutura os dados
        for resultado in resultados:
            celula = resultado[0]  # Valor da coluna 'celula'
            bssid = resultado[1]   # Valor da coluna 'bssid'
            rssi = resultado[2]    # Valor da coluna 'rssi'

            # Cria uma chave para a celula (por exemplo, "(1, 1)")
            chave_celula = str(celula)

            # Se a célula ainda não existe no dicionário, cria uma entrada para ela
            if chave_celula not in dados_celulas:
                dados_celulas[chave_celula] = {}

            # Se o BSSID já existe, adicione o novo valor de RSSI na mesma chave BSSID
            if bssid not in dados_celulas[chave_celula]:
                dados_celulas[chave_celula][bssid] = []

            # Adiciona o valor RSSI à lista de valores para o BSSID correspondente
            dados_celulas[chave_celula][bssid].append(rssi)

        # Retorna o dicionário completo
        return dados_celulas

    except mysql.connector.Error as error:
        print(f"Erro ao consultar dados: {error}")
    finally:
        # Fechar cursor e conexão
        cursor.close()
        conn.close()

# Função para inserir ou atualizar os dados da posição do ESP32 no banco de dados
def atualizar_posicao_esp(celula):
    conn = conectar()

    if conn is None:
        print("Não foi possível conectar ao banco de dados.")
        return

    try:
        cursor = conn.cursor()

        # Dados da célula (macAddress, x, y)
        macAddress = celula[0]
        x = celula[1]
        y = celula[2]

        # Consulta SQL para inserir ou atualizar os dados
        consulta = """
        INSERT INTO posicao (macAddress, x, y)
        VALUES (%s, %s, %s)
        ON DUPLICATE KEY UPDATE x = VALUES(x), y = VALUES(y);
        """

        # Executa a consulta
        cursor.execute(consulta, (macAddress, x, y))

        # Confirma a transação
        conn.commit()

        print(f"Dados do macAddress {macAddress} foram inseridos/atualizados com sucesso.")

    except mysql.connector.Error as error:
        print(f"Erro ao inserir ou atualizar dados: {error}")
    finally:
        # Fechar cursor e conexão
        cursor.close()
        conn.close()

def obter_dados_corridas():
    # Conectar ao banco de dados
    conn = conectar()

    if conn is None:
        return
    
    try:
        cursor = conn.cursor()
        
       # Executa a consulta SQL para obter os dados da tabela 'corridas'
        cursor.execute("SELECT id, x, y, status FROM corridas")

        # Obtém todos os resultados da consulta
        resultados = cursor.fetchall()

        # Fecha o cursor e a conexão
        cursor.close()
        conn.close()

        # Processa os dados
        for corrida in resultados:
            id_corrida = corrida[0]
            x = corrida[1]
            y = corrida[2]
            status = corrida[3]

            # Exemplo de tratamento dos dados (aqui você pode processá-los conforme sua necessidade)
            return [id_corrida, x, y, status]

    except mysql.connector.Error as err:
        print(f"Erro ao buscar dados: {err}")
    finally:
        # Fechar cursor e conexão
        cursor.close()
        conn.close()

def atualizar_dados_percurso(id_viagem, percurso):
    conn = conectar()

    if conn is None:
        print("Não foi possível conectar ao banco de dados.")
        return

    try:
        cursor = conn.cursor()

        # Consulta SQL para inserir ou atualizar os dados
        consulta = """
        INSERT INTO percurso (id, caminho)
        VALUES (%s, %s)
        ON DUPLICATE KEY UPDATE caminho = VALUES(caminho);
        """

        # Converte a lista de percurso para JSON
        caminho_json = json.dumps(percurso)

        # Executa a consulta
        cursor.execute(consulta, (id_viagem, caminho_json))

        # Confirma a transação
        conn.commit()

        print(f"Dados da viagem {id_viagem} foram inseridos/atualizados com sucesso.")

    except mysql.connector.Error as error:
        print(f"Erro ao inserir ou atualizar dados: {error}")
    finally:
        # Fechar cursor e conexão
        cursor.close()
        conn.close()
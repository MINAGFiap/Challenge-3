import numpy as np
from queue import PriorityQueue

'''
acessar o dicionário locais -> pegar todos os pontos da matriz (x, y) dentro da matriz de roteadores -> 
verificar o endereço (bssid) -> acessar lista de intensidades -> 
comparar os valores com uma margem de erro (5 a 10 dB) -> retornar a posição aproximada
'''

def calcular_media(dados_esp_filtrado, dados_redes):
    media = {}

    # Pega o bssid e valor dos dados filtrados recebidos pelo ESP32
    for entrada in dados_esp_filtrado:
        for bssid, rssi_valor in entrada.items():
            # Pega células e o bssid do banco de dados
            for celula, bssids_banco in dados_redes.items():
                # Verificar se o BSSID está presente no banco de dados para a célula
                if bssid in bssids_banco:
                    # Pega a lista de RSSIs do banco de dados
                    lista_rssi = bssids_banco[bssid]

                    # Calcula a média dos valores de RSSI
                    media_rssi = np.mean(lista_rssi)

                    # Se a célula não estiver no dicionário de média, cria a entrada
                    if celula not in media:
                        media[celula] = {}

                    # Armazena a média no dicionário
                    media[celula][bssid] = int(round(media_rssi))

    return media

def selecionar_maiores(dados_esp):

    dados = dados_esp[1]

    # Fornece todos os endereços MAC dos roteadores
    id_roteador = list(dados.keys())
    #print(id_roteador)

    # Fornece as intensidades (RSSI) coletados e torna em ordem decrescente
    valores = list(dados.values())
    valores.sort(reverse=True)
    #print('Valores: ',valores)

    # Organizar a lista contendo o dicionário em ordem decrescente
    ordem = []

    # Cria um conjunto para determinar os itens já usados, caso contrário haja alguns roteadores com mesma intensidade irá repetir o nome do primeiro que aparecer
    # Um conjunto (set()) "exclui" itens repetidos, por exemplo, lista = [1, 1, 2, 2, 2, 3, 4, 5, 5]    conjunto = set(lista)   retorna     [1, 2, 3, 4, 5]
    indices_usados = set()
    
    # Cria um loop para passar por todos os itens dos valores
    for e in range(4):
         # Cria um loop para passar por todos os itens dos dados comparando o valor da lista valores com valor dentro dicionário naquela posição
        for i in range(len(id_roteador)):
            # Pega a chave que identifica o roteador
            id = id_roteador[i]

            # Compara se o valor dentro do dicionário é igual ao valor da lista valores
            if dados[id] == valores[e] and i not in indices_usados:
                # Caso seja igual, adiciona o dicionário na posição i para uma nova lista que irá armazenar os 4 menores valores
                ordem.append({id: dados[id]})
                indices_usados.add(i)   # Adiciona o índice ao conjunto para evitar duplicação
    #print('Ordem: ', ordem)

    return ordem

def posicionamento_esp(dados_esp, dados_redes):

    id = dados_esp[0]

    selecionados = selecionar_maiores(dados_esp)

    media = calcular_media(selecionados, dados_redes)

    # Transformar a lista de 'selecionados' em um dicionário para facilitar a comparação
    dados_dicionario = {list(item.keys())[0]: list(item.values())[0] for item in selecionados}

    celula_final = None
    menor_diferenca_total = float('inf')  # Inicialmente, definir como infinito

    # Iterar sobre as células em 'media'
    for celula, bssids_media in media.items():
        diferenca_total = 0
        bssid_encontrados = 0

        # Comparar cada BSSID e seu RSSI com os valores em 'dados'
        for bssid, rssi_media in bssids_media.items():
            if bssid in dados_dicionario:
                rssi_dados = dados_dicionario[bssid]
                # Calcular a diferença absoluta entre o RSSI da média e o RSSI nos dados
                diferenca = abs(rssi_media - rssi_dados)
                diferenca_total += diferenca
                bssid_encontrados += 1

        # Verificar se essa célula tem uma menor diferença total
        if bssid_encontrados > 0 and diferenca_total < menor_diferenca_total:
            menor_diferenca_total = diferenca_total
            celula_final = celula

    # Remover os parênteses e dividir a string pelo separador ','
    celula_final = celula_final.strip('()')  # Remove os parênteses
    x, y = celula_final.split(',')  # Divide a string pela vírgula

    # Converter os valores de x e y para inteiros, se necessário
    x = int(x.strip())  # Remove possíveis espaços em branco e converte para inteiro
    y = int(y.strip())  # Remove possíveis espaços em branco e converte para inteiro

    return [id, x, y] 

# Determinar quais operadores estão livres e qual está mais perto do carrinho
def selecionar_operador(dados_operadores, x_desejado, y_desejado):

    # Converte os ids dos operadores que está em um dicionário para um lista
    # keys() obtem os 'nomes' chaves, por exemplo, op = {'op01': {'situação': 'ocupado'}, 'op02': {'situação': 'ocupado'}} retorna dict_keys['op01', 'op02']
    # list() converte o dict_keys em uma lista dict_keys['op01', 'op02'] ---->>> lista = ['op01', 'op02']
    operadores = list(dados_operadores.keys())

    operadores_livres = []
    distancia = []
    valores = []

    # Determinar quais operadores estão com a situação igual a livre
    for i in range(len(dados_operadores)):
        id = operadores[i]

        if dados_operadores[id]['situacao'] == 'livre':
            operadores_livres.append(id)
            
    # Determinar qual operador está com posição x e y (início) até a posição x e y desejada (destino)
    for i in range(len(operadores_livres)):
        id = operadores_livres[i]
        passos_x = 0
        passos_y = 0

        # Cálculo da distância em X
        # Quantos passos foram necessários até a posição de x fosse igual a posição x do destino
        if dados_operadores[id]['x'] < x_desejado:
            x = dados_operadores[id]['x']
            while x != x_desejado:
                x += 1
                passos_x += 1

        elif dados_operadores[id]['x'] > x_desejado:
            x = dados_operadores[id]['x']
            while x != x_desejado:
                x -= 1
                passos_x += 1

        # Cálculo da distância em Y
        # Quantos passos foram necessários até a posição de y fosse igual a posição y do destino
        if dados_operadores[id]['y'] < y_desejado:
            y = dados_operadores[id]['y']
            while y != x_desejado:
                y += 1
                passos_y += 1

        elif dados_operadores[id]['y'] > y_desejado:
            y = dados_operadores[id]['y']
            while y != y_desejado:
                y -= 1
                passos_y += 1

        # Quantidade de passos necessários dados da posição atual até o destino
        passos = passos_x + passos_y

        # Armazena a distância calculada para cada operador livre
        distancia.append({id: {'distancia': passos}})

    # Ordena em ordem crescente as distâncias para que operador "ande" até o carrinho 
    for i in range(len(operadores_livres)):
        valor = list(distancia[i].values())
        valores.append(valor[0]['distancia'])
        valores.sort()

    # Determina o ID do operador que está mais perto do carrinho 
    for i in range(len(operadores_livres)):
            id = operadores_livres[i]
            dic = distancia[i]
        
            if dic[id]['distancia'] == valores[0]:
                id_escolhido = id    

    # Altera os dados do operador para ocupado e a posição atual para igual a posição da entrega
    for i in range(len(dados_operadores)):
        id = operadores[i]
        if id == id_escolhido:
            dados = list(dados_operadores.values())[i]
            #print(dados)
            dados['situacao'] = 'ocupado'
            dados['x'] = x_desejado
            dados['y'] = y_desejado
            #print(dados)
            
            novos_dados = {id_escolhido: dados}

    #print()
    #print('Operadores livres: ', operadores_livres)
    #print('Passos gastos: ', valores)
    #print('Distâncias: ', distancia)
    #print('Operador escolhido: ', id_escolhido)
    print()
    
    return [id_escolhido, novos_dados]

# Determinar se algum operador livre para fazer a entrega
def disponibilidade(dados_operadores):
    operadores = list(dados_operadores.keys())

    for i in range(len(dados_operadores)):
        id = operadores[i]

        if dados_operadores[id]['situacao'] == 'livre':
            return True

def organizar_viagem(dados_viagens, dados_operadores):
    pass

#Algoritmo A*

# Define uma estimativa da distância restante até o objetivo
def est_restante(celula, destino):
    x = celula[0]
    y = celula[1]
    x2 = destino[0]
    y2 = destino[1]
    return abs(x - x2) + abs(y - y2)

#def aestrela(grade/matriz, celula_inicial, destino)
def percurso(inicial, destino):
    # Criar a grade sobre a planta da fábrica
    # Para realizar o cálculo de retorno para o ponto inicial, deverá inverter a biblioteca do caminho, pois nesse exemplo se move de (10, 10) para (1, 1)
    
    grade = [
        (1, 1), (2, 1), (3, 1), (4, 1), (5, 1), (6, 1), (7, 1), (8, 1), (9, 1), 
        (1, 2), (2, 2), (3, 2), (4, 2), (5, 2), (6, 2), (7, 2), (8, 2), (9, 2), 
        (1, 3), (2, 3), (3, 3), (4, 3), (5, 3), (6, 3), (7, 3), (8, 3), (9, 3), 
        (1, 4), (2, 4), (3, 4), (4, 4), (5, 4), (6, 4), (7, 4), (8, 4), (9, 4)]
    
    obstaculos = {
        (1, 1): {'E': 0, 'W': 0, 'N': 0, 'S': 1}, (2, 1): {'E': 0, 'W': 0, 'N': 1, 'S': 1}, (3, 1): {'E': 0, 'W': 0, 'N': 1, 'S': 1}, (4, 1): {'E': 0, 'W': 0, 'N': 1, 'S': 1}, (5, 1): {'E': 0, 'W': 0, 'N': 1, 'S': 1}, (6, 1): {'E': 0, 'W': 0, 'N': 1, 'S': 1}, (7, 1): {'E': 0, 'W': 0, 'N': 1, 'S': 1}, (8, 1): {'E': 1, 'W': 0, 'N': 0, 'S': 0}, (9, 1): {'E': 0, 'W': 0, 'N': 0, 'S': 0}, 
        (1, 2): {'E': 0, 'W': 1, 'N': 0, 'S': 0}, (2, 2): {'E': 0, 'W': 0, 'N': 0, 'S': 0}, (3, 2): {'E': 0, 'W': 0, 'N': 0, 'S': 0}, (4, 2): {'E': 0, 'W': 0, 'N': 0, 'S': 0}, (5, 2): {'E': 0, 'W': 0, 'N': 0, 'S': 0}, (6, 2): {'E': 0, 'W': 0, 'N': 0, 'S': 0}, (7, 2): {'E': 0, 'W': 0, 'N': 0, 'S': 0}, (8, 2): {'E': 0, 'W': 0, 'N': 0, 'S': 1}, (9, 2): {'E': 0, 'W': 0, 'N': 0, 'S': 0}, 
        (1, 3): {'E': 0, 'W': 0, 'N': 0, 'S': 0}, (2, 3): {'E': 0, 'W': 0, 'N': 0, 'S': 0}, (3, 3): {'E': 0, 'W': 0, 'N': 0, 'S': 0}, (4, 3): {'E': 0, 'W': 0, 'N': 0, 'S': 0}, (5, 3): {'E': 0, 'W': 0, 'N': 0, 'S': 0}, (6, 3): {'E': 0, 'W': 0, 'N': 0, 'S': 0}, (7, 3): {'E': 0, 'W': 0, 'N': 0, 'S': 0}, (8, 3): {'E': 0, 'W': 0, 'N': 0, 'S': 0}, (9, 3): {'E': 0, 'W': 0, 'N': 0, 'S': 0},
        (1, 4): {'E': 0, 'W': 0, 'N': 0, 'S': 0}, (2, 4): {'E': 0, 'W': 0, 'N': 0, 'S': 0}, (3, 4): {'E': 0, 'W': 0, 'N': 0, 'S': 0}, (4, 4): {'E': 0, 'W': 0, 'N': 0, 'S': 0}, (5, 4): {'E': 0, 'W': 0, 'N': 0, 'S': 0}, (6, 4): {'E': 0, 'W': 0, 'N': 0, 'S': 0}, (7, 4): {'E': 0, 'W': 0, 'N': 0, 'S': 0}, (8, 4): {'E': 0, 'W': 0, 'N': 0, 'S': 0}, (9, 4): {'E': 0, 'W': 0, 'N': 0, 'S': 0},
    }


    celula_inicial = inicial

    # Determina que cada célula da grade possui um peso 'infinito'
    valor_caminho = {celula: float('inf') for celula in grade}
    # Define a quantidade de repetições realizadas, por quantas células passou
    dist_atual = {}

    dist_atual[celula_inicial] = 0
    valor_caminho[celula_inicial] = dist_atual[celula_inicial] + est_restante(celula_inicial, destino)  # Determina o valor inicial
    #print(valor_caminho)

    # O queue (fila) é utilizado para armazenar todos valores das células e determinar os menores, as células com menor custo (menor distância)
    fila = PriorityQueue()
    item = (valor_caminho[celula_inicial], est_restante(celula_inicial, destino), celula_inicial)
    fila.put(item)

    caminho = {}
    # Enquanto existir células dentro da fila
    while not fila.empty():         
        cel = fila.get()[2]

        # Caso a célula atual for igual a célula destino, o loop é fechado
        if cel == destino:          
            break
        
        # Verifica em cada direção (sul, norte, leste e oeste) de uma célula qualquer (cel) não existem paredes bloqueando o percusso
        for dir in "NSEW": 
            if obstaculos[cel][dir] == 1:
                # Posição atual
                linha_cel = cel[0]
                coluna_cel = cel[1]
                # Criar uma biblioteca que contém os obstáculos em cada célula da matriz que sobrepõe o mapa

                # Se tiver uma posição livre no N,S,E ou W, calcula a posição da próxima célula
                if dir == 'N':
                    # Determina qual é a próxima célula na direção norte, subtraindo uma linha
                    prox_cel = (linha_cel - 1, coluna_cel)
                elif dir == 'S':
                    # Determina qual é a próxima célula na direção sul, somando uma linha
                    prox_cel = (linha_cel + 1, coluna_cel)
                elif dir == 'W':
                    # Determina qual é a próxima célula na direção leste, subtraindo uma coluna
                    prox_cel = (linha_cel, coluna_cel - 1)
                elif dir == 'E':
                    # Determina qual é a próxima célula na direção oeste, somando uma coluna
                    prox_cel = (linha_cel, coluna_cel + 1)
                
                # Se a célula seguinte não for bloqueada adiciona mais um passo para a dist_atual e
                novo_dist = dist_atual[cel] + 1
                # determina o novo valor do percusso                                      
                novo_valor = novo_dist + est_restante(prox_cel, cel)

                if novo_valor < valor_caminho[prox_cel]:
                    # O valor_caminho e dist_atual são substituito pelos valores de novo_valor e novo_dist
                    valor_caminho[prox_cel] = novo_valor
                    dist_atual[prox_cel] = novo_dist
                    # Adiciona o novo valor a fila
                    item = (novo_valor, est_restante(prox_cel, destino), prox_cel)
                    fila.put(item)
                    # Adiciona todas as possíveis celulas, ou seja, armazena todas as células pela qual analisou/passou até encontrar o caminho
                    caminho[prox_cel] = cel
                    #print(f"")
                    #print("Caminho: ",caminho)
    #print(valor_caminho)

    # Reconstruir o caminho da célula destino até a célula inicial
    celula_analisada = destino
    percurso = [celula_analisada]  # Inicia com o destino

    # Reconstrói o caminho invertido até a célula inicial
    while celula_analisada != inicial:
        celula_analisada = caminho[celula_analisada]
        percurso.append(celula_analisada)

    percurso.reverse()  # Inverte a lista para ir da célula inicial até a célula destino
    return percurso
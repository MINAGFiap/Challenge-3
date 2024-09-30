from processamento_dados import *
from connection import *

# --->> dados_viagens[id_viagem]['esp'] ===>> "D0:EF:76:46:79:1C" comparar com dados_[id_carrinho]

if __name__ == '__main__':
    dados_esp = obter_dados_esp()
    dados_operadores = obter_dados_operadores()
    dados_redes = obter_dados_redes()
    dados_corridas = obter_dados_corridas()
    print("Dados ESP32: ", dados_esp)
    print()
    print("Dados operadores: ", dados_operadores)
    print()
    print("Dados redes: ", dados_redes)
    print()
    print("Dados corridas: ", dados_corridas)

    operador = selecionar_operador(dados_operadores, 10, 10)
    #print("Operador: ", operador)
    #atualizar_dados_operadores(operador[0], operador[1])

    selecionados = selecionar_maiores(dados_esp)
    print("Selecionados: ", selecionados)
    print()

    media = calcular_media(selecionados, dados_redes)
    print("Média do RSSI redes: ", media)
    print()

    celula = posicionamento_esp(dados_esp, dados_redes)
    print(celula)
    print(f"O carrinho com endereço {celula[0]} está atualmente na célula: ({celula[1]}, {celula[2]})")
    print()

    atualizar_posicao_esp(celula)
    print()

    if dados_corridas != None:
        celula_inicial = (celula[1], celula[2])
        celula_destino = (dados_corridas[1], dados_corridas[2]) 
        caminho = percurso(celula_inicial, celula_destino)
        print("Percurso: ", caminho)
        print()

        atualizar_dados_percurso(dados_corridas[0], caminho)

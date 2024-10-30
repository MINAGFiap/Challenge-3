# Challenge 3 - Indústria 4.0 e Sociedade 5.0 

### -Integrantes-
- André Bartolo Pellegrino dos Santos
  - RM558319
- Giuliano Ferreira Venceslau
  - RM558674
- Iury Cardoso Araujo
  - RM558850
- Maria Luiza Miyazaki Braz de Paula
  - RM555702
- Rafael Augusto Oliveira Silva
  - RM555154

## -Introdução-
### Objetivos do projeto
Nosso projeto tem como objetivo principal buscar uma solução para a má otimização do reabastecimento de peças nos carros-kit da fábrida da John Deere;

[![imagem-2024-09-26-172419359.png](https://i.postimg.cc/8PTXY5BS/imagem-2024-09-26-172419359.png)](https://postimg.cc/qgZGhp5b)
## -Desenvolvimento-
### Arquitetura
#### Esp 32 + “triangulação”
Nosso grupo propõe utilizar um ESP32 conectado em uma rede de roteadores para que seja possível fazer a “triangulação” por meio do indicador de força de sinal recebido, assim localizando o ESP e consequentemente, o carrinho.
#### ALGORITMO “A *”
Também utilizaremos o algoritmo “A*” para otimizar o percurso de cada uma das entregas realizadas dentro da empresa.
### Tecnologias e Algoritmos
- ESP32 - Para localização do Carrinho-kit.
- PHP - Para a construção do Site.
- Python - Para a construção do algoritmo e da lógica interna.
- C++ - Para controle do ESP
#### Demonstração
- Link do vídeo demonstrativo dos algoritmos: [YouTube](https://www.youtube.com/watch?v=Wb3LYma8qg4)
### Modelo de UML

[![imagem-2024-09-26-170408966.png](https://i.postimg.cc/c4WHKmD7/imagem-2024-09-26-170408966.png)](https://postimg.cc/4n8ZqzRn)
## -Resultados-
- Site - Gestor: O site busca enviar os parâmetros dos carrinhos-kit e gerenciar as peças desejadas para o reabastecimento.
- Site - Operador: O site disbonipiliza um mapa que mostra onde está o carrinho-kit a ser reabastecido, também conta com uma seção para analisar quais corridas estão ativas e quais estão finalizadas.
#### Demonstração
- Link do vídeo demonstrativo do site: [YouTube](https://www.youtube.com/watch?v=zbjiQSFvvno) 

## -Teste de desempenho-
### Teste 1 - Precisão de Localização.

#### Definição da Ferramenta de Teste.
Para esse teste, optamos por utilizar as ferramentas 'Arduino IDE' e 'Visual Studio Code'.

#### Discussão dos Resultados.
Os testes indicaram que a precisão da localização depende da estabilidade do sinal. Em pontos mais distantes (30 cm), a precisão foi maior, com um número menor de erros de posição. Já para pontos mais próximos (15 cm), a precisão diminuiu devido à variação do RSSI, resultando em posições incorretas em alguns casos. Isso demonstra que a proximidade dos pontos aumenta a sensibilidade do teste a variações de sinal.

#### Soluções Futuras.
Para aprimorar a precisão, o grupo sugere o uso de algoritmos que filtrem ruídos e oscilações do RSSI, além de realizar mais medições para formar uma base de dados robusta, aumentando a confiabilidade do sistema de localização.

### Teste 2 - Estabilidade de Sinal.

#### Definição da Ferramenta de Teste.
Para esse teste, optamos por utilizar as ferramentas 'Arduino IDE' e 'Visual Studio Code'.

#### Discussão dos Resultados.
Foi observado que o sinal apresentou uma variação de -3 dBm nas primeiras coletas, e essa variação aumentou com o tempo e ao introduzir obstáculos, como colocar objetos sobre o ESP ou fechar a porta do cômodo. Essa instabilidade pode comprometer a precisão da localização, indicando que o ambiente e os obstáculos influenciam significativamente a confiabilidade do sinal.

#### Soluções Futuras.
Para melhorar a estabilidade, o grupo propõe realizar testes adicionais em diferentes ambientes para ajustar o algoritmo de leitura do RSSI. Outra ideia é aplicar técnicas de filtragem para reduzir o impacto das variações de sinal provocadas por obstáculos.



#### Evidências de Testes (O pdf a seguir contém as evidências dos dois testes citados a cima.)
[Evidêcias - PDF](https://drive.google.com/file/d/1xBGWf5DfIQI5K1n9-lcwrJ2KKj0MFCr3/view?usp=sharing)

#include <WiFi.h>
#include <HTTPClient.h>
#include <vector>

String URL = "http://IPdoComputador/MINAG/ESP32_DATABASE/registrarDados.php";

// Nome e senha da rede que vai conectar para fazer o ping e enviar os dados
const char* ssid = "";
const char* password = "";

String comandoRecebido = "";
int repeat = 1;

void setup() {
  Serial.begin(115200);
  
  // Conectar ao Wifi
  conectarWifi();

  Serial.println("");
  Serial.println("Configuração completa."); 
}

void loop() {


  Serial.println("Digite no serial monitor a célula (x,y) que será coletado.");

  if (Serial.available() > 0) {

    comandoRecebido = Serial.readString();
    comandoRecebido.trim();
    
    Serial.print("Célula recebida: ");
    Serial.println(comandoRecebido);

    if (comandoRecebido != "") {
      char pc = comandoRecebido.charAt(0);
      char uc = comandoRecebido.charAt(comandoRecebido.length() - 1);

      if (pc == '(' && uc == ')') {

        if (WiFi.status() != WL_CONNECTED) {
          conectarWifi();
        }

        // Necessário 50 coletadas para o filtro de convolução
        while (repeat <= 5) {
          int n = WiFi.scanNetworks();
          if (n == 0) {
            Serial.println("Nenhuma rede encontrada.");
          } else {
            Serial.print(n);
            Serial.println(" Rede(s) encontrada(s).");

            std::vector<String> redes_nome;
            std::vector<String> redes_mac;
            std::vector<int> redes_int;

            for (int i = 0; i < n; ++i) {
              redes_nome.push_back(WiFi.SSID(i));
              redes_mac.push_back(WiFi.BSSIDstr(i));
              redes_int.push_back(WiFi.RSSI(i));
              delay(50);
            }

            for (int i = 0; i < redes_nome.size(); i++) {
              String dado = "celula="+comandoRecebido+"&bssid="+redes_mac[i]+"&rssi="+ String(redes_int[i]);

              HTTPClient http;
              http.begin(URL);

              // Definindo o tipo de conteúdo como URL-encoded
              http.addHeader("Content-Type", "application/x-www-form-urlencoded");

              int httpCode = http.POST(dado);
              String carrega = http.getString();

              //Serial.print("URL: "); Serial.println(URL);
              Serial.print("DADO: "); Serial.println(dado);
              Serial.print("httpCode: "); Serial.println(httpCode);
              Serial.print("Conexão: "); Serial.println(carrega);
              Serial.println("<<<......................>>>");
              delay(500);
            }   
          }
          
          delay(50);
          Serial.println("Loop: " + String(repeat));
          repeat++;
        }

        repeat = 1;
        comandoRecebido = "";
        delay(2000);
      } else {
        Serial.println("Comando não reconhecido.");
      }
    }
  }

  if (WiFi.status() == WL_CONNECTED) { 
    WiFi.disconnect();
    Serial.print("WiFi desconectado de: ");
    Serial.println(ssid);
  }
}

void conectarWifi() {
  WiFi.begin(ssid, password);

  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.print(".");
  }
  Serial.println("");
  Serial.println("Conectado ao Wi-Fi.");
  Serial.println("");
}

#include <WiFi.h>
#include <HTTPClient.h>
#include <vector>
#include <ArduinoJson.h>

String URL = "http://IPdoComputador/MINAG/ESP32_DATABASE/lerRSSI.php";

// Nome e senha da rede que vai conectar para fazer o ping e enviar os dados
const char* ssid = "redes";
const char* password = "gg";

void setup() {
  Serial.begin(115200);
  
  // Conectar ao Wifi
  conectarWifi();

  Serial.println("");
  Serial.println("Configuração completa."); 
}

void loop() {
  Serial.println("");

  if (WiFi.status() != WL_CONNECTED) {
    conectarWifi();
  }

  int n = WiFi.scanNetworks();
  if (n == 0) {
    Serial.println("Nenhuma rede encontrada.");
  } else {
    Serial.print(n);
    Serial.println(" Rede(s) encontrada(s).");

    // Criando um JSON para armazenar os dados
    StaticJsonDocument<512> doc; // Tamanho do documento pode ser ajustado conforme necessário

    // Adiciona o endereço MAC do ESP32 ao JSON
    String esp_mac = WiFi.macAddress();
    doc["esp_mac"] = esp_mac;

    // Cria um objeto para armazenar as redes detectadas
    JsonObject dados = doc.createNestedObject("dados");

    // Loop para capturar os endereços MAC e RSSI das redes ao redor
    for (int i = 0; i < n; ++i) {
      // Adicionando MAC como chave e RSSI como valor
      dados[WiFi.BSSIDstr(i)] = WiFi.RSSI(i);
      delay(50);
    }

    // Serializando o JSON para enviar como string
    String json;
    serializeJson(doc, json);
    
    // Exibir o JSON gerado no Serial Monitor
    Serial.print("JSON gerado: ");
    Serial.println(json);

    // Enviar os dados via HTTP POST
    HTTPClient http;
    http.begin(URL);

    // Definir o cabeçalho do tipo de conteúdo como JSON
    http.addHeader("Content-Type", "application/json");

    // Enviar o JSON
    int httpCode = http.POST(json); // Enviando o JSON como string
    String carrega = http.getString();

    Serial.print("httpCode: "); Serial.println(httpCode);
    Serial.print("Conexão: "); Serial.println(carrega);
    Serial.println("<<<......................>>>");
    delay(3000);
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

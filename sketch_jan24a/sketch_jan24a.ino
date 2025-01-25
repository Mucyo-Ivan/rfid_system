#include <SPI.h>
#include <MFRC522.h>
#include <WiFi.h>
#include <HTTPClient.h>

const char* ssid = "your_ssid";
const char* password = "your_password";
const char* serverUrl = "http://your_server_ip/rfid_system/index.php";

MFRC522 rfid(SS_PIN, RST_PIN);

void setup() {
    Serial.begin(9600);
    WiFi.begin(ssid, password);
    while (WiFi.status() != WL_CONNECTED) {
        delay(1000);
        Serial.println("Connecting to WiFi...");
    }
    Serial.println("WiFi Connected!");

    SPI.begin();
    rfid.PCD_Init();
}

void loop() {
    if (!rfid.PICC_IsNewCardPresent() || !rfid.PICC_ReadCardSerial())
        return;

    String rfid_code = "";
    for (byte i = 0; i < rfid.uid.size; i++) {
        rfid_code += String(rfid.uid.uidByte[i], HEX);
    }

    HTTPClient http;
    http.begin(serverUrl);
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");

    String requestData = "rfid_code=" + rfid_code;
    int httpResponseCode = http.POST(requestData);

    if (httpResponseCode > 0) {
        String response = http.getString();
        Serial.println(response);
    } else {
        Serial.println("Error sending request.");
    }

    http.end();
}

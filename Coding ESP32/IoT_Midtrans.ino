#include <WiFi.h>
#include <WebServer.h>
#include <Stepper.h>

const char* ssid = "MKZ"; //ganti SSID 
const char* password = "12341234"; //Ganti Password

#define IN1 14
#define IN2 27
#define IN3 26
#define IN4 25

Stepper stepper(2048, IN1, IN3, IN2, IN4);

#define BUZZER 33

WebServer server(80);

String htmlPage = R"=====( 
<!DOCTYPE html>
<html>
<head>
<title>ESP32 IoT Control</title>
<style>
body {font-size:22px; font-family:Arial;}
</style>
</head>
<body>
<h2>Selesai Bosskuh...</h2>
</body>
</html>
)=====";

void beep2() {
  for (int i = 0; i < 2; i++) {
    digitalWrite(BUZZER, HIGH);
    delay(200);
    digitalWrite(BUZZER, LOW);
    delay(200);
  }
}

void stepperRunTwice() {
  stepper.setSpeed(10);
  stepper.step(1024);
  delay(300);
  stepper.step(1024);
}

void handleRoot() {
  beep2();
  stepperRunTwice();
  beep2();

  server.send(200, "text/html", htmlPage);
}

void setup() {
  Serial.begin(115200);

  pinMode(BUZZER, OUTPUT);
  digitalWrite(BUZZER, LOW);

  stepper.setSpeed(10);

  WiFi.begin(ssid, password);
  Serial.println("Menyambungkan WiFi...");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  Serial.println("\nTerhubung!");
  Serial.print("IP ESP32: ");
  Serial.println(WiFi.localIP());

  server.on("/", handleRoot);
  server.begin();
}

void loop() {
  server.handleClient();
}

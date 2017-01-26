@ECHO OFF
pushd .

ECHO Remove previous builds...
del /q *.json

ECHO Downloading latest AvaTax Swagger file...
"c:\program files\curl\bin\curl.exe" https://sandbox-rest.avatax.com/swagger/v2/swagger.json -o Avalara.AvaTax.RestClient.json

popd
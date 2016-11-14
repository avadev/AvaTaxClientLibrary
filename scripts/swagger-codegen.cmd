@ECHO OFF
pushd .

ECHO Remove previous builds...
del /q swagger-codegen-cli.jar
del /q swagger.json

ECHO Downloading latest swagger codegen...
"c:\program files\curl\bin\curl.exe" http://repo1.maven.org/maven2/io/swagger/swagger-codegen-cli/2.2.1/swagger-codegen-cli-2.2.1.jar -o swagger-codegen-cli.jar

ECHO Downloading latest AvaTax Swagger file...
"c:\program files\curl\bin\curl.exe" https://rest.avatax.com/swagger/v2/swagger.json -o Avalara.AvaTax.RestProxy.json

ECHO Generating swagger models...
java -jar swagger-codegen-cli.jar generate -i Avalara.AvaTax.RestProxy.json --lang csharp --output generated --model-package Avalara.AvaTax.RestProxy.Models --api-package Avalara.AvaTax.RestProxy.Api -t templates -c config.json

ECHO Updating code with latest models...
del /f/s/q generated\fetchresult*
copy generated\src\Avalara.AvaTax.RestClient\Model\* ..\clients\dotnet\model

ECHO Regenerate user interface from the swagger JSON
"..\generator\ClientApiGenerator\bin\debug\ClientApiGenerator.exe" .\Avalara.AvaTax.RestProxy.json ..\clients\dotnet\AvaTaxApi.cs

ECHO Cleaning up...
del /f/s/q generated

popd
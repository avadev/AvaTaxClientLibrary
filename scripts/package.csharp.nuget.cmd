@echo off
pushd .
cd ..\clients\dotnet
..\..\..\..\nuget.exe pack Avalara.AvaTax.RestClient.nuspec
popd
pause
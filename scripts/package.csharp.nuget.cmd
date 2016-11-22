@echo off
pushd .
cd ..\clients\dotnet 
dotnet build -c release
..\..\..\..\nuget.exe pack Avalara.AvaTax.RestClient.nuspec
popd
@echo off
pushd .
cd ..\clients\dotnet 
dotnet restore
C:\Windows\Microsoft.NET\Framework64\v4.0.30319\MSBuild.exe Avalara.AvaTax.RestClient.net20.csproj /property:Configuration=Release
C:\Windows\Microsoft.NET\Framework64\v4.0.30319\MSBuild.exe Avalara.AvaTax.RestClient.netstandard.csproj /property:Configuration=Release
..\..\..\..\nuget.exe pack Avalara.AvaTax.RestClient.nuspec
popd
pause
@echo off
pushd .
cd ..\clients\dotnet

REM 
REM No idea why these things won't build with msbuild; have to figure out how to get them buildable from command line at some point.
REM They always fail with a 'newtonsoft missing' error, even though building from visual studio always works.
REM 
REM ECHO ********** Building NET20
REM "c:\windows\microsoft.net\Framework64\v4.0.30319\MSBuild.exe" Avalara.AvaTax.RestClient.net20.csproj /p:Configuration=Release
REM ECHO ********** Building NETSTANDARD
REM "c:\windows\microsoft.net\Framework64\v4.0.30319\MSBuild.exe" Avalara.AvaTax.RestClient.netstandard.csproj /p:Configuration=Release

ECHO ********** Packaging NUGET
..\..\..\..\nuget.exe pack Avalara.AvaTax.RestClient.nuspec
popd
pause
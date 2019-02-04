start "" "%PROGRAMFILES%\Git\bin\sh.exe" -c "sh git-branch.sh NEXT-VERSION"

cd ..\generator\ClientApiGenerator\bin\Debug\
ClientApiGenerator.exe -g C:\git\develop\AvaTaxClientLibrary\scripts\avataxapi.json

cd ..\..\..\..\scripts
start "" "%PROGRAMFILES%\Git\bin\sh.exe" -c "sh git-commit.sh NEXT-VERSION"

curl -H "User-Agent: contygm" -H "Content-Type: application/json" -H "Authorization: token %GIT_TOKEN%" -X POST --data @git.json https://api.github.com/repos/contygm/AvaTax-REST-V2-DotNet-SDK/pulls
curl -H "User-Agent: contygm" -H "Content-Type: application/json" -H "Authorization: token %GIT_TOKEN%" -X POST --data @git.json https://api.github.com/repos/contygm/AvaTax-REST-V2-JS-SDK/pulls
curl -H "User-Agent: contygm" -H "Content-Type: application/json" -H "Authorization: token %GIT_TOKEN%" -X POST --data @git.json https://api.github.com/repos/contygm/AvaTax-REST-V2-PHP-SDK/pulls
curl -H "User-Agent: contygm" -H "Content-Type: application/json" -H "Authorization: token %GIT_TOKEN%" -X POST --data @git.json https://api.github.com/repos/contygm/AvaTax-REST-V2-Python-SDK/pulls
curl -H "User-Agent: contygm" -H "Content-Type: application/json" -H "Authorization: token %GIT_TOKEN%" -X POST --data @git.json https://api.github.com/repos/contygm/AvaTax-REST-V2-Ruby-SDK/pulls

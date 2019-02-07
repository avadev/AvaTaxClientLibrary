# if you want to skip or add SDKs, modify this array
declare -a sdks=("DotNet" "PHP" "JS" "Python" "Ruby")

# get to right directory level from scripts folder
cd ../

# loop through all SDKs
for i in "${sdks[@]}"; do
    cd ../AvaTax-REST-V2-$i-SDK/ 
     
	git add .
   
	# $1 is the version passed in and name of branch
    git commit -m "$1 update"
    git push upstream $1

done

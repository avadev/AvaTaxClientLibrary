declare -a sdks=("DotNet" "PHP" "JS" "Python" "Ruby")
# get to right directory level
cd ../
for i in "${sdks[@]}"; do
     cd ../AvaTax-REST-V2-$i-SDK/ 
     
     # $1 is the version passed in and name of branch
     git commit -m "$1 update"
     git push origin $1
done

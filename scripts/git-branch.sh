declare -a sdks=("DotNet" "PHP" "JS" "Python" "Ruby")
# get to right directory level
cd ../
     
for i in "${sdks[@]}"; do
     cd ../AvaTax-REST-V2-$i-SDK/ 
     
     # $1 is the version pass in and name of new branch
     git checkout -b $1    
done


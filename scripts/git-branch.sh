# if you want to skip or add SDKs, modify this array
declare -a sdks=("DotNet" "PHP" "JS" "Python" "Ruby")

# get to right directory level from scripts folder
cd ../
 
# loop through all SDKs    
for i in "${sdks[@]}"; do
    cd ../AvaTax-REST-V2-$i-SDK/ 
    
	# do this in case you have some weird vs folders or something
	git stash
	
	# make sure master is up to date with upstream
	git checkout master
	git pull upstream master

    # $1 is the version pass in and name of new branch
	# makes new branch based on upstream
    git checkout -b $1

done
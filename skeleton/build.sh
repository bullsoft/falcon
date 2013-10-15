#!/bin/bash
#please modify this script adapting to your module!

#remove tmp php dir
#rm -r php-*


#need encode or not ( 1 or 0)  NEED CHANGE
NEED=0

STACK_PATH=`pwd`

# module dir
SCRIPT_PATH=`dirname $0`

# enter script path
cd $SCRIPT_PATH

#source file folder NEED CHANGE
SF_SRC=`pwd|awk -F"/" '{print $NF}'`

#delete build.sh
rm -f build.sh

#delete upgrades
rm -rf upgrades

#delete output folder
rm -rf output

#just out of global dir
#cd ..
#delete svn folder
find . -name ".svn" | xargs -i rm -rf {}

#delete CVS folders
find . -name CVS|xargs -i rm -rf {}

tar cvzf $SF_SRC".tar.gz" ./* > /dev/null
RETVAL=$?
[ $RETVAL -gt 0 ] && echo "tar failed!" && exit $RETVAL

mkdir -p output
RETVAL=$?
[ $RETVAL -gt 0 ] && echo "create output folder failed!"  && exit $RETVAL

mv $SF_SRC".tar.gz" output
RETVAL=$?
[ $RETVAL -gt 0 ] && echo "mv files failed!" && exit $RETVAL
[ ! -e "output/"$SF_SRC".tar.gz" ] && echo "build failed!" && exit 1 
echo "build success!"
cd $STACK_PATH
exit 0

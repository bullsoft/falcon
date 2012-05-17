#!/bin/bash
#please modify this script adapting to your module!
BULL="framework"
BULL_SRC=$BULL"_src"

cd ..
mkdir ./$BULL_SRC
cp -r ./$BULL/* ./$BULL_SRC/
cd ./$BULL_SRC

#need encode or not ( 1 or 0)  NEED CHANGE
NEED=0

#delete git folder
rm -rf ".git"

#delete build.sh
rm -f build.sh
#rm test dir
rm -rf Tool/Test

#rm tmp 
rm -rf Framework/Tmp/session/*
rm -rf Framework/Tmp/cache/*
rm -rf Framework/Tmp/log/*

cd ..

tar cvzf $BULL_SRC".tar.gz" $BULL_SRC > /dev/null
RETVAL=$?
[ $RETVAL -gt 0 ] && echo "tar failed!" && exit $RETVAL

rm -rf ./$BULL_SRC

mkdir $BULL"/output"
mv $BULL_SRC".tar.gz" $BULL"/output/"
RETVAL=$?
[ $RETVAL -gt 0 ] && echo "mv files failed!" && exit $RETVAL
[ ! -e $BULL"/output/"$BULL_SRC".tar.gz" ] && echo "build failed!" && exit 1 

echo "build success!"
exit 0

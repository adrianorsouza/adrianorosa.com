#!/bin/bash

# Site build script
#
# @date: 2015-05-10 21:20
# @author: Adriano Rosa <http://adrianorosa.com>
# @usage: ./build.sh

WEBROOT=`pwd`

jekyll build
git checkout gh-pages
cp -r _site/ $WEBROOT
open /Applications/GitHub.app
# git add .
# git commit -m "site build"
# git push origin gh-pages
# git checkout master
# git push origin master

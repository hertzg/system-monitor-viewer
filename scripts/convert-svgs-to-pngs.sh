#!/bin/bash
cd `dirname $BASH_SOURCE`
cd ../images
convert -background transparent favicon.svg favicon.png
cd ..
convert images/favicon.png favicon.ico

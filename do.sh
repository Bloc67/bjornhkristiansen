#!/bin/bash
cd ext/jpg
for fil in *.jpg; do
    if [ ! -f "../jpg_thumb/$fil" ]; then
        ffmpeg -hide_banner -loglevel error -i $fil -vf scale=600:-1 "../jpg_thumb/$fil"
        echo $fil
    else
        echo "$fil eksisterer!"
    fi
done

#!/bin/bash
recpath=/var/spool/asterisk/monitor/
lame -V 1 $recpath$1.wav $recpath$1.mp3 && rm -frv $recpath$1.wav

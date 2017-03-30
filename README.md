# ns

sudo apt-get install espeak

http://espeak.sourceforge.net/commands.html

https://github.com/markondej/fm_transmitter

SSML https://www.w3.org/TR/speech-synthesis/

sox

Create a wav with TTS and broadcast:
$espeak -wespeak.wav -s125 -ven+m3 -k120 -m "1, 2, 3, 4, 5<break time='2s'/> 1, 2, 3, 4, 5 <break time='2s'/> This is a test of the emergency broadcasting system. <break time='1s'/>"
$sudo ./fm_transmitter -f 90.1 -r espeak.wav

From test.txt:
1, 2, 3, 4, 5<break time='2s'/> 1, 2, 3, 4, 5<break time='2s'/> This is a test of the emergency broadcasting system<break time='1s'/>
$espeak -wespeak.wav -s125 -ven+m3 -k120 -m -ftest.txt
$sudo ./fm_transmitter -f 90.1 -r espeak.wav

Convert MP3 and broadcast
sox lincoln.mp3 -r 22050 -c 1 -b 16 -t wav - | sudo ./fm_transmitter -f 100.0 -


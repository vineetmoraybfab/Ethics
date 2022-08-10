#!/data/data/com.termux/files/usr/bin/sh

termux-wake-lock
. $PREFIX/etc/profile


#/system/bin/sh iptables -t nat -A OUTPUT -o lo -p tcp --dport  REDIRECT --to-port 8080
/system/xbin/su -c 'iptables -t nat -A OUTPUT -o lo -p tcp --dport 80 -j REDIRECT --to-port 8080'

/system/xbin/su -c 'echo 1 > /data/data/com.termux/files/home/.booted'

#am start -n com.android.settings/.TetherSettings && /system/xbin/su -c 'input keyevent 20' && /system/xbin/su -c 'input keyevent 66' && /system/xbin/su -c 'input keyevent 66'


am startservice --user 0 -n com.termux/com.termux.app.RunCommandService \
-a com.termux.RUN_COMMAND \
--es com.termux.RUN_COMMAND_PATH '/data/data/com.termux/files/usr/bin/login' \
--ez com.termux.RUN_COMMAND_BACKGROUND 'false' 

/system/xbin/su -c 'input swipe 200 500 200 0' && /system/xbin/su -c 'input text 0007' && /system/xbin/su -c 'input keyevent 66'

am start -n com.android.settings/.TetherSettings && /system/xbin/su -c 'input keyevent 66' && /system/xbin/su -c 'input keyevent 66'

/system/xbin/su -c 'input keyevent 3'

/system/xbin/su -c 'input keyevent 26'

#am start -n com.android.settings/.TetherSettings && /system/xbin/su -c 'input keyevent 20' && /system/xbin/su -c 'input keyevent 66' && /system/xbin/su -c 'input keyevent 66'


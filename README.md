Intellect telephony based on YII2

INSTALATION:

1. install composer
https://getcomposer.org/download/

2. install fxp/composer-asset-plugin in global
composer global require "fxp/composer-asset-plugin:^1.3.1"

3. git clone https://github.com/predeinay/itwww.git

4. composer.phar install

5. php init

6. modify db connection

7. goto yoursite/itwww/backend/web/

8. cp dialplans
extensions_custom.conf
extensions_itwww.conf
extensions_override_freepbx.conf

9. chmod +x yoursite/itwww/asterisk

10. add to sudoers for run shell scripts from asterisk/www-data users
asterisk ALL=NOPASSWD: ALL
www-data ALL=NOPASSWD: /usr/sbin/asterisk



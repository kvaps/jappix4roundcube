jappix4roundcube
================

My version Jappix plugin for roundcube

### Changes

Added features:
* Autologin (uses roundcube creditionals)
* Enable / Disable jappix mini
* Show jappix in iframe
* Changed skin and icon now looks good

### Installation

Go to your plugins derictory

    cd /usr/share/roundcubemail/plugins/

Clone this repo

    git clone https://github.com/kvaps/jappix4roundcube jappix4roundcube

If you want, clone the main jappix project into this plugin folder

    git clone https://github.com/jappix/jappix jappix4roundcube/jappix/

    cd jappix4roundcube/

You can customize the jappix design for the standard roundcube larry-skin using my patch 

    mkdir -p jappix/store/backgrounds/ && cp ../../skins/larry/images/linen.jpg jappix/store/backgrounds/linen.jpg
    patch -p0 <larry-skin.patch

Сhange owner for jappix folder, recursive

    chown www-data:www-data jappix -R

Create new config.inc.php from config.inc.php.dist

    cp config.inc.php.dist config.inc.php

Edit jappix4roundcube/config.inc.php

### Original author

Forked from : http://code.google.com/p/jappix4roundcube/

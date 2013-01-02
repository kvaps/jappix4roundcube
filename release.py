import tarfile
import googlecode_upload
import shutil
import zipfile
import urllib
import sys

def excluded_files(myfile):
    _return = False
    if myfile.find('.git') > -1:
        _return = True
    if myfile.find('jappix4roundcube-plugin-externaljappix') > -1:
        _return = True
    if myfile.find('.bak') > -1:
        _return = True
    if myfile.find('.pyc') > -1:
        _return = True
    if myfile.find('.zip') > -1:
        _return = True
    if myfile.find('jappix4roundcube-plugin-jappixembedded') > -1:
        _return = True
    return _return

def main():
    
    version = input('Version?')
    user = ''#input('User?')
    password = ''#input('password?')

    project_name='jappix4roundcube'
    print('0')
    labels=['Featured','Type-Installer','OpSys-All']
    summary_emb='jappix4roundcube plugin v'+version+', with embedded jappix'
    summary_ext='jappix4roundcube plugin v'+version+', without embedded jappix'
    jappix='jappix-0.9.1-nemesis-alpha-1.zip'
    #description_emb='Including '+jappix
    #description_ext='If you already have a jappix installed or wan\'t to use a public one'

    print('Getting wiki file')
    urllib.urlretrieve('http://code.google.com/p/jappix4roundcube/wiki/README?show=content','README.html')
    urllib.urlretrieve('http://code.google.com/p/jappix4roundcube/wiki/CHANGELOG?show=content','CHANGELOG.html')
    
    print('Archive dev config')
    shutil.copy('config.inc.php','config.inc.php.bak')
    print('Archive dev appix dir')
    shutil.move('jappix','jappix.bak')
    print('Extracting '+jappix+' to jappix dir')
    
    

    print('Remplace dev config by config.inc.php.external.dist')
    shutil.copy('config.inc.php.external.dist','config.inc.php')
    print('Making jappix4roundcube-plugin-externaljappix-v'+version+'.tar')

    tar_ext = tarfile.open('jappix4roundcube-plugin-externaljappix-v'+version+'.tar', 'w')
    tar_ext.add('.',exclude=excluded_files)
    tar_ext.close()

    sourceZip = zipfile.ZipFile(jappix, 'r')
    sourceZip.extractall()
    sourceZip.close()

    print('Remplace dev config by config.inc.php.embedded.dist')
    shutil.copy('config.inc.php.embedded.dist','config.inc.php')
    print('Making jappix4roundcube-plugin-jappixembedded-v'+version+'.tar')
    tar_emb = tarfile.open('jappix4roundcube-plugin-jappixembedded-v'+version+'.tar', 'w')
    tar_emb.add('.',exclude=excluded_files)
    tar_emb.close()
    
    print('Removing extracted jappix')
    shutil.rmtree('jappix')

    print('Putting bak archived jappix')
    shutil.move('jappix.bak','jappix')

    print('Putting bak config file')
    shutil.move('config.inc.php.bak','config.inc.php')

    print('Sending file jappix4roundcube-plugin-jappixembedded-v'+version+'.tar google code')
    status, reason, url = googlecode_upload.upload_find_auth('jappix4roundcube-plugin-jappixembedded-v'+version+'.tar',
                                                             project_name,
                                                             summary_emb,
                                                             #description_emb,
                                                             labels,
                                                             user,
                                                             password)
    if url:
        print('The file was uploaded successfully.')
        print('URL: %s' % url)
    else:
        print('An error occurred. Your file was not uploaded.')
        print('Google Code upload server said: %s (%s)' % (reason, status))
    
    print('Sending file jappix4roundcube-plugin-embeddedjappix-v'+version+'.tar google code')
    status, reason, url = googlecode_upload.upload_find_auth('jappix4roundcube-plugin-externaljappix-v'+version+'.tar',
                                                             project_name,
                                                             summary_ext,
                                                             #description_ext,
                                                             labels,
                                                             user,
                                                             password)
    if url:
        print('The file was uploaded successfully.')
        print('URL: %s' % url)
    else:
        print('An error occurred. Your file was not uploaded.')
        print('Google Code upload server said: %s (%s)' % (reason, status))


if __name__ == '__main__':
    sys.exit(main())

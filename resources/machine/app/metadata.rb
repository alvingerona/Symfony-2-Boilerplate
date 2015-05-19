name                'app'
maintainer          'Youwe'
license             'Apache 2.0'
description         'Installs and configures symfony2 projects'
long_description    'Installs and configures symfony2 projects'
version             '0.0.1'

# supports
%w{ ubuntu debian centos }.each do |os|
    supports os
end

# dependencies
%w{ acl apt curl git nodejs apache2 php mysql }.each do |dependency|
 depends dependency
end

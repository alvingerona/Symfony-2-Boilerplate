#
# Cookbook Name:: app
# Recipe:: project
#

# get latest composer
bash 'Get latest composer' do
    user        'vagrant'
    cwd         node['app']['root_dir']
    code        "curl -sS https://getcomposer.org/installer | php"
end

# create mount directories to gain speed
%w{ app/cache app/logs web/bundles vendor }.each do |dir|
    directory node['app']['root_dir'] + '/' + dir do
        mode        00755
        recursive   true
        action      :create
    end

    bash 'Mount ' + dir do
        code    <<-EOH
            sudo mkdir -p #{node['app']['tmp_dir']}/#{dir}
            sudo cp -au #{node['app']['root_dir']}/#{dir}/* #{node['app']['tmp_dir']}/#{dir}
            sudo mount -o bind #{node['app']['tmp_dir']}/#{dir} #{node['app']['root_dir']}/#{dir}
            sudo setfacl -R -m u:"#{node['apache']['user']}":rwX -m u:"vagrant":rwX #{node['app']['tmp_dir']}/#{dir}
            sudo setfacl -dR -m u:"#{node['apache']['user']}":rwX -m u:"vagrant":rwX #{node['app']['tmp_dir']}/#{dir}
        EOH
    end
end

# install vendors
bash 'Composer install' do
    user        'vagrant'
    cwd         node['app']['root_dir']
    code        "php composer.phar install -o"
    environment ({'HOME' => '/home/vagrant'})
end

# project setup actions
bash "Propel build" do
    user        'vagrant'
    cwd         node['app']['root_dir']
    code        <<-EOH
        php app/console propel:model:build
        php app/console propel:sql:insert --force
        php app/console propel:fixtures:load
    EOH
end

# Copy vendors back for developer
bash 'Copying vendors' do
    code    <<-EOH
        sudo umount #{node['app']['root_dir']}/vendor
        sudo cp -au #{node['app']['tmp_dir']}/vendor/* #{node['app']['root_dir']}/vendor
        sudo mount -o bind #{node['app']['tmp_dir']}/vendor #{node['app']['root_dir']}/vendor
    EOH
end
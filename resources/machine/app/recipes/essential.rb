#
# Cookbook Name:: app
# Recipe:: default
#
# Installs essential requirements
#

# APT is required
include_recipe 'apt'

# ACL is required for extended file permission
include_recipe 'acl'

# CURL library is required
include_recipe 'curl'

# NODEJS client is required to install NPM packages like Bower
include_recipe 'nodejs'

# NPM package Bower is required
bash 'Install bower' do
    user    'root'
    code    'npm install -g bower'
end

# GIT client is required
include_recipe 'git'

# Install cachefilesd for better GIT performance on NFS filesystems
package 'cachefilesd' do
  action    :install
end

file '/etc/default/cachefilesd' do
  content   'RUN=yes'
  action    :create
  mode      0755
end

# Edit GIT settings for NFS performance
bash 'Edit GIT settings' do
    code    'git config --global core.preloadindex true'
end
# Run apt-get update to create the stamp file
execute "apt-get-update" do
  command "apt-get update"
  ignore_failure true
  not_if do ::File.exists?('/var/lib/apt/periodic/update-success-stamp') end
end

# For other recipes to call to force an update
execute "apt-get update" do
  command "apt-get update"
  ignore_failure true
  action :nothing
end

# provides /var/lib/apt/periodic/update-success-stamp on apt-get update
package "update-notifier-common" do
  notifies :run, resources(:execute => "apt-get-update"), :immediately
end

execute "apt-get-update-periodic" do
  command "apt-get update"
  ignore_failure true
  only_if do
    File.exists?('/var/lib/apt/periodic/update-success-stamp') &&
    File.mtime('/var/lib/apt/periodic/update-success-stamp') < Time.now - 86400
  end
end

# install the software we need
%w(
openjdk-6-jre-headless
curl
tmux
vim
emacs23-nox
git
libapache2-mod-php5
php5-cli
php5-curl
php5-sqlite
php5-intl
).each { | pkg | package pkg }


template "/etc/apache2/sites-enabled/vhost.conf" do
  user "root"
  mode "0644"
  source "vhost.conf.erb"
  notifies :reload, "service[apache2]"
end

service "apache2" do
  supports :restart => true, :reload => true, :status => true
  action [ :enable, :start ]
end

directory "/opt/jackrabbit" do
  owner "root"
  group "root"
end

remote_file "/opt/jackrabbit/jackrabbit.jar" do
  source "http://archive.apache.org/dist/jackrabbit/2.5.1/jackrabbit-standalone-2.5.1.jar"
  mode "0644"
  checksum "e65d2677a9514cf9f8cd216d6a331c2253fd37a2e8daab9a6ca928d602aa83b7"
end

template "/etc/init.d/jackrabbit" do
  mode "0755"
  source "jackrabbit.erb"
end

service "jackrabbit" do
  action :start
end

{ "/vagrant/app/config/parameters.yml.dist" =>  "/vagrant/app/config/parameters.yml",
  "/vagrant/app/config/phpcr_jackrabbit.yml.dist" => "/vagrant/app/config/phpcr.yml" }.each do | src, dest |
  file dest  do
    content IO.read(src)
  end
end

execute "date.timezone = UTC in php.ini?" do
  user "root"
  not_if "grep 'date.timezone = UTC' /etc/php5/cli/php.ini"
  command "echo -e '\ndate.timezone = UTC\n' >> /etc/php5/cli/php.ini"
end

bash "Running composer install and preparing the phpcr repository" do
  not_if "test -e /var/tmp/vendor/symfony/symfony/src/Symfony/Bundle/FrameworkBundle/Resources/public"
  user "vagrant"
  cwd "/vagrant"
  code <<-EOH
    set -e
    ln -sf /var/tmp/vendor
    curl -s https://getcomposer.org/installer | php
    COMPOSER_VENDOR_DIR="/var/tmp/vendor" php composer.phar install
    echo "Waiting for Jackrabbit:"
    while [[ -z `curl -s "http://localhost:8080"` ]] ; do sleep 1s; echo -n "."; done
    app/console doctrine:phpcr:workspace:create sandbox
    app/console doctrine:phpcr:register-system-node-types
    app/console -v doctrine:phpcr:fixtures:load
  EOH
end

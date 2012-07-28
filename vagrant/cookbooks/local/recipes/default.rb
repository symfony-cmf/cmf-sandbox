web_app "vhost" do
  template "vhost.conf.erb"
  notifies :reload, resources(:service => "apache2"), :delayed
end

# install the software we need
%w(
openjdk-6-jre-headless
curl
tmux
vim
emacs23-nox
git
php5-cli
php5-curl
php5-sqlite
php5-intl
).each { | pkg | package pkg }

directory "/opt/jackrabbit" do
  owner "root"
  group "root"
end

remote_file "/opt/jackrabbit/jackrabbit-standalone-2.4.2.jar" do
  source "http://apache.org/dist/jackrabbit/2.4.2/jackrabbit-standalone-2.4.2.jar"
  mode "0644"
  checksum "608b1a35897dc260b12c51f76819f96ae9d01d7fb943289754669ee396e49604"
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
    owner "vagrant"
    group "vagrant"
    content IO.read(src)
  end
end

execute "date.timezone = UTC in php.ini?" do
  user "root"
  not_if "grep 'date.timezone = UTC' /etc/php5/cli/php.ini"
  command "echo -e '\ndate.timezone = UTC\n' >> /etc/php5/cli/php.ini"
end

bash "Running composer install in separate vendor directory" do
  not_if "test -d /var/tmp/vendor"
  user "vagrant"
  code <<-EOH
    set -e
    mkdir -p /var/tmp/vendor
    cd /var/tmp
    cp /vagrant/composer.* /var/tmp
    curl -s https://getcomposer.org/installer | php
    php composer.phar install
  EOH
end

execute "Bind mount vendor folder" do
  not_if "mount|grep vendor|grep -v grep"
  user "root"
  command "mount --bind /var/tmp/vendor /vagrant/vendor"
end

bash "Preparing the phpcr repository" do
  not_if "test -L /vagrant/web/bundles/framework"
  user "vagrant"
  cwd "/vagrant"
  code <<-EOH
    set -e
    curl -s https://getcomposer.org/installer | php
    php composer.phar install
    echo "Waiting for Jackrabbit:"
    while [[ -z `curl -s "http://localhost:8080"` ]] ; do sleep 1s; echo -n "."; done
    app/console doctrine:phpcr:workspace:create sandbox
    app/console doctrine:phpcr:register-system-node-types
    app/console -v doctrine:phpcr:fixtures:load
  EOH
end


# begin
#   private_key = node['ssh_key']['private']
#   public_key = node['ssh_key']['public']

#   dir = "/home/vagrant/.ssh"

#   file "#{dir}/id_rsa" do
#     owner "vagrant"
#     group "vagrant"
#     mode "600"
#     content private_key
#   end

#   file "#{dir}/id_rsa.pub" do
#     owner "vagrant"
#     group "vagrant"
#     mode "644"
#     key = node['ssh_key']['public']
#     content public_key
#   end
# rescue
# end

# if FileTest.exists?("/vagrant/carlo.sql.gz")
#   bash "copy database" do
#     # dont if the db already exists
#     not_if("/usr/bin/mysql --defaults-file=/etc/mysql/debian.cnf -e'show databases' | grep carlo", :user => 'root')
#     user "root"
#     group "root"
#     code <<-EOH
#     mysql --defaults-file=/etc/mysql/debian.cnf -e 'create database carlo' && \
#     zcat /vagrant/carlo.sql.gz | mysql --defaults-file=/etc/mysql/debian.cnf --one-database carlo && \
#     mysql --defaults-file=/etc/mysql/debian.cnf -e "update carlo.core_config_data set value = 'http://carloag.lo:8080/shop/' where path like 'web/%secure/base_url'"
#   EOH
#   end
# else
#   log "No database dump found (carlo.sql.gz)"
# end

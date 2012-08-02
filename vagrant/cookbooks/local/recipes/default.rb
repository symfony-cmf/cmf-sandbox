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
    content IO.read(src)
  end
end

execute "date.timezone = UTC in php.ini?" do
  user "root"
  not_if "grep 'date.timezone = UTC' /etc/php5/cli/php.ini"
  command "echo -e '\ndate.timezone = UTC\n' >> /etc/php5/cli/php.ini"
end

bash "Running composer install in separate vendor directory" do
  not_if "test -f /var/tmp/vendor/autoload.php"
  user "vagrant"
  cwd "/var/tmp"
  code <<-EOH
    set -e
    mkdir -p vendor
    cp /vagrant/composer.* .
    curl -s https://getcomposer.org/installer | php
    php composer.phar install
  EOH
end

bash "Preparing the phpcr repository" do
  not_if "test -L /vagrant/web/bundles/framework"
  user "vagrant"
  cwd "/vagrant"
  code <<-EOH
    set -e
    ln -s /var/tmp/vendor
    curl -s https://getcomposer.org/installer | php
    php composer.phar install
    echo "Waiting for Jackrabbit:"
    while [[ -z `curl -s "http://localhost:8080"` ]] ; do sleep 1s; echo -n "."; done
    app/console doctrine:phpcr:workspace:create sandbox
    app/console doctrine:phpcr:register-system-node-types
    app/console -v doctrine:phpcr:fixtures:load
  EOH
end

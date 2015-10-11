# -*- mode: ruby -*-
# vi: set ft=ruby :

require 'yaml'

Vagrant.configure(2) do |cmf|
  config = get_validated_vm_config

  # Basic box settings
  cmf.vm.box      = "ubuntu/trusty64"
  cmf.vm.hostname = config['hostname']

  cmf.vm.synced_folder ".", "/var/www/cmf", :nfs => true

  # Custom virtualbox settings
  cmf.vm.provider "virtualbox" do |vb|
    # vb settings
    vb.name = config['name']

    # vb customizations
    vb.customize ['modifyvm', :id, '--cpus', config['cpus']]
    vb.customize ['modifyvm', :id, '--memory', config['memory']]
    vb.customize ["modifyvm", :id, "--natdnshostresolver1", "on"]
  end

  # Private network
  cmf.vm.network :private_network, :ip => config['ip']

  # Provisioners:
  #  1.: installs all mandatory puppet modules
  #  2.: setup of the machine (php, apache vhost, mysql/jackrabbit, parameter files)
  #  3.: setup of the application (phpcr setup using app/console commands)
  cmf.vm.provision :shell, path: "vagrant/puppet.sh"
  cmf.vm.provision :puppet do |puppet|
    puppet.manifests_path    = "vagrant/manifests"
    puppet.module_path       = ['vagrant/puppet']
    puppet.manifest_file     = 'site.pp'
    puppet.options           = ["--verbose"]
    puppet.hiera_config_path = 'vagrant/hiera.yaml'
  end

  cmf.vm.provision :shell, path: "vagrant/composer.sh"
end

def get_validated_vm_config()
  filename = 'vagrant/machine.yaml'

  unless File.exists?(filename)
    abort "File #{filename} does not exist!"
  end

  config = YAML::load(File.open(filename))
  unless config
    config = {}
  end

  if File.exists?('vagrant/local_machine.yaml')
    config = config.merge(YAML::load(File.open('vagrant/local_machine.yaml')))
  end

  if config['ip'].nil?
    abort "The configuration parameter 'ip' is necessary!"
  end

  if config['cpus'].nil?
    config['cpus'] = 1
  end

  if config['memory'].nil?
    config['memory'] = 1024
  end

  if config['name'].nil?
    config['name'] = 'Symfony-CMF VM'
  end

  if config['hostname'].nil?
    config['hostname'] = 'symfony-cmf'
  end

  return config
end

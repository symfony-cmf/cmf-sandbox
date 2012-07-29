require File.expand_path('../helpers', __FILE__)

describe 'apache2::mod_php5' do
  include Helpers::Apache

  it 'installs mod_php5' do
    mod_php_pkg = case node['platform']
      when 'debian', 'ubuntu' then 'libapache2-mod-php5'
      else 'php53'
    end
    package(mod_php_pkg).must_be_installed
  end

  it "deletes the stock php config on rhel distributions" do
    skip unless node.platform?("amazon", "redhat", "centos", "scientific")
    file("#{node['apache']['dir']}/conf.d/php.conf").wont_exist
  end

end

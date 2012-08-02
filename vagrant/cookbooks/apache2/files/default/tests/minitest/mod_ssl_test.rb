require File.expand_path('../helpers', __FILE__)

describe 'apache2::mod_ssl' do
  include Helpers::Apache

  it 'installs the mod_ssl package on RHEL distributions' do
    skip unless ["redhat", "centos", "scientific", "fedora", "amazon"].include? node.platform
    package("mod_ssl").must_be_installed
  end

  it 'does not store SSL config in conf.d' do
    file("#{node['apache']['dir']}/conf.d/ssl.conf").wont_exist
  end

  it "is configured to listen on port 443" do
    apache_configured_ports.must_include(443)
  end

end

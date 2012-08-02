require File.expand_path('../helpers', __FILE__)
require 'pathname'

describe 'apache2::mod_auth_openid' do
  include Helpers::Apache

  it "installs the opekele library" do
    lib_dir = Pathname.new(node['apache']['lib_dir']).dirname.to_s
    file("#{lib_dir}/libopkele.so").must_exist
  end

  it "does not add the module to httpd.conf" do
    httpd_config = File.read("#{node['apache']['dir']}/conf/httpd.conf")
    refute_match /^LoadModule authopenid_module /, httpd_config
  end

  it "creates a cache directory for the module" do
    directory(node['apache']['mod_auth_openid']['cache_dir']).must_exist.with(:owner, node['apache']['user'])
  end

  it "ensures the db file is writable by apache" do
    file(node['apache']['mod_auth_openid']['dblocation']).must_exist.with(:owner, node['apache']['user']).and(:mode, "644")
  end

end

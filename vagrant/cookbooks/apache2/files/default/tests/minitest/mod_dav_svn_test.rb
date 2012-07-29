require File.expand_path('../helpers', __FILE__)

describe 'apache2::mod_dav_svn' do
  include Helpers::Apache

  it 'installs mod_dav_svn' do
    mod_dav_svn = case node['platform']
      when "centos","redhat","scientific","fedora","suse","amazon"
        "mod_dav_svn"
      else
        "libapache2-svn"
      end
    package(mod_dav_svn).must_be_installed
  end

end

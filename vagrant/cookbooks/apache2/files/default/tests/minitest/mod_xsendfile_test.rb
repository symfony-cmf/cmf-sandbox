require File.expand_path('../helpers', __FILE__)

describe 'apache2::mod_xsendfile' do
  include Helpers::Apache

  it "installs mod_xsendfile" do
    sendfile_pkg = package(case node['platform']
      when "debian","ubuntu" then "libapache2-mod-xsendfile"
      else "mod_xsendfile"
    end)
    sendfile_pkg.must_be_installed
  end

end

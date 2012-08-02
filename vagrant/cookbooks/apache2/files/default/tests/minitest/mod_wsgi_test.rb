require File.expand_path('../helpers', __FILE__)

describe 'apache2::mod_wsgi' do
  include Helpers::Apache

  it "installs mod_wsgi" do
    wsgi_pkg = package(case node['platform']
      when "debian","ubuntu" then "libapache2-mod-wsgi"
      else "mod_wsgi"
    end)
    wsgi_pkg.must_be_installed
  end

end

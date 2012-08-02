require File.expand_path('../helpers', __FILE__)

describe 'apache2::mod_perl' do
  include Helpers::Apache

  it 'installs mod_perl' do
    mod_perl_pkg = case node['platform']
      when 'debian', 'ubuntu' then 'libapache2-mod-perl2'
      else 'mod_perl'
    end
    package(mod_perl_pkg).must_be_installed
  end

  it 'installs the apache request library' do
    req_pkg = case node['platform']
      when 'debian', 'ubuntu' then 'libapache2-request-perl'
      else 'perl-libapreq2'
    end
    package(req_pkg).must_be_installed
  end

end

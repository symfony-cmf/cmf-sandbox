require File.expand_path('../helpers', __FILE__)

describe 'apache2::mod_python' do
  include Helpers::Apache

  it 'installs mod_python' do
    mod_python_pkg = case node['platform']
      when 'debian', 'ubuntu' then 'libapache2-mod-python'
      else 'mod_python'
    end
    package(mod_python_pkg).must_be_installed
  end

end

require File.expand_path('../helpers', __FILE__)

# Many of the apache2 cookbook recipes do nothing more than install the module
# of the same name. Assert that any included recipe that claims to install a
# module actually does so.
%w{
  alias apreq2 auth_basic auth_digest authn_file authnz_ldap authopenid
  authz_default authz_groupfile authz_host authz_user autoindex cgi dav_fs dav
  dav_svn deflate dir env expires fcgid headers ldap log_config mime
  negotiation perl php5 proxy_ajp proxy_balancer proxy_connect proxy_http
  proxy python rewrite setenvif ssl status wsgi xsendfile
}.each do |expected_module|
  describe "apache2::mod_#{expected_module}" do
    include Helpers::Apache
    it "installs mod_#{expected_module}" do
      apache_enabled_modules.must_include expected_module
    end
  end
end

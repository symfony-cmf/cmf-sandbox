class cmf::application::symfony(
  $environment    = hiera('environment', 'dev'),
  $allow_override = undef,
  $front,
  $vhost_name     = 'cmf.dev'
) {
  $override = $allow_override ? {
    undef   => 'None',
    default => $allow_override
  }

  validate_string($override)
  validate_string($front)
  validate_string($vhost_name)

  class { '::apache': }

  ::apache::module { 'rewrite': }
  ::apache::vhost { $vhost_name:
    template                 => 'cmf/cmf_vhost.erb',
    directory_allow_override => $override,
    server_name              => $vhost_name
  }
}

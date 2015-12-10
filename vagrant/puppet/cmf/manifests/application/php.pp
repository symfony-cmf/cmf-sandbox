class cmf::application::php(
  $version     = '5.5',
  $use_apc     = undef,
  $use_xdebug  = undef,
  $environment = hiera('environment', 'dev')
) {
  validate_string($environment)

  case $version {
    '5.5':   { $ppaVersion = 'php5' }
    '5.6':   { $ppaVersion = 'php5-5.6' }
    default: {
      $supported = join(['5.5', '5.6'], ',')

      fail(
        "Invalid parameter for cmf::application::php::version given! The values ${$supported} are supported only!"
      )
    }
  }

  ::apt::ppa { "ppa:ondrej/${ppaVersion}": }

  exec { 'php::apt-get-upgrade':
    command => '/usr/bin/apt-get -y upgrade',
  }

  class { '::php':
    service => 'apache',
  }

  ::php::module { 'gd': }
  ::php::module { 'cli': }
  ::php::module { 'mysql': }
  ::php::module { 'curl': }
  ::php::module { 'intl': }
  ::php::module { 'mcrypt': }
  ::php::module { 'sqlite': }

  if $environment == 'dev' or $use_xdebug == true {
    ::php::module { 'xdebug': }
  }

  if $environment == 'prod' or $use_apc == true {
    ::php::module { 'apc':
      module_prefix => 'php-',
    }
  }

  class { '::composer':
    command_name => 'composer',
    auto_update  => true,
    require      => Package['php5'],
    target_dir   => '/usr/local/bin',
  }
}

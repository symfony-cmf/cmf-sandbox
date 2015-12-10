class cmf::application::parameters(
  $dist_template = undef,
  $credentials   = undef
) {
  if $credentials == undef {
    if $dist_template != undef {
      fail('The dist template requires the credentials hash!')
    }

    $db_credentials = {}
  }
  else {
    validate_hash($credentials)

    $db_credentials = $credentials
  }

  if $dist_template != undef {
    $host     = $credentials[host]
    $user     = $credentials[user]
    $password = $credentials[password]
    $dbname   = $credentials[db]

    $compiled_parameter_file = template($dist_template)

    file { '/var/www/cmf/app/config/parameters.yml':
      ensure  => present,
      content => $compiled_parameter_file,
    }
  }
  else {
    file { '/var/www/cmf/app/config/parameters.yml':
      ensure => present,
      source => '/var/www/cmf/app/config/parameters.yml.dist',
    }
  }
}

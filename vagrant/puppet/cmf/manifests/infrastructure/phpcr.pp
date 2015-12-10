class cmf::infrastructure::phpcr($mysql, $jack) {
  validate_bool($mysql)
  validate_bool($jack)

  if $mysql == true {
    if $jack == true {
      fail('$mysql AND $jack cannot be true!')
    }

    include ::cmf::infrastructure::phpcr::mysql_wrapper

    $db_hash         = {'db' => hiera('cmf::infrastructure::phpcr::mysql_wrapper::database_name', {})}
    $credential_hash = hiera('cmf::infrastructure::phpcr::mysql_wrapper::database_credentials', {})

    $type          = 'mysql'
    $yml_file      = 'phpcr_doctrine_dbal.yml.dist'
    $dist_template = 'cmf/parameters.erb'
    $credentials   = merge($db_hash, $credential_hash)
  }
  elsif $jack == true {
    include ::cmf::infrastructure::phpcr::jack_wrapper

    $type          = 'jack'
    $yml_file      = 'phpcr_jackrabbit.yml.dist'
    $dist_template = undef
    $credentials   = undef
  }
  else {
    fail('Either $mysql or $jack must be true!')
  }

  class { '::cmf::application::parameters':
    dist_template => $dist_template,
    credentials   => $credentials
  }

  file { '/etc/storage_type.txt':
    content => $type,
  }

  file { '/var/www/cmf/app/config/phpcr.yml':
    ensure => present,
    source => "/var/www/cmf/app/config/${yml_file}",
  }
}

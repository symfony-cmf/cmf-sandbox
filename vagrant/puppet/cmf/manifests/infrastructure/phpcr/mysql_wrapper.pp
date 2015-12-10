class cmf::infrastructure::phpcr::mysql_wrapper(
  $root_password,
  $database_name,
  $database_credentials
) {
  validate_string($root_password)
  validate_string($database_name)
  validate_hash($database_credentials)

  if $database_credentials[host] == undef {
    fail('Database host must not be null!')
  }

  if $database_credentials[user] == undef {
    fail('Database user  must not be null!')
  }

  if $database_credentials[password] == undef {
    fail('Database password must not be null!')
  }

  class { '::mysql::server':
    override_options => { 'root_password' => $root_password, },
  }

  ::mysql::db { $database_name:
    host     => $database_credentials[host],
    user     => $database_credentials[user],
    password => $database_credentials[password],
    grant    => ['ALL']
  }
}

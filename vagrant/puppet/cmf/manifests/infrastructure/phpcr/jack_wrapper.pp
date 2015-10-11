class cmf::infrastructure::phpcr::jack_wrapper($version) {
  validate_string($version)

  $target = "http://mirror.switch.ch/mirror/apache/dist/jackrabbit/${version}/jackrabbit-standalone-${version}.jar"
  $file   = "/var/www/cmf/jackrabbit-standalone-${version}.jar"

  class { '::java':
    distribution => 'jre',
  } ->
  exec { 'retrieve_jackrabbit_jar':
    command => "/usr/bin/wget ${target} -O ${file}",
    creates => $file,
  } ->
  file { $file:
    mode    => 0755,
    require => Exec['retrieve_jackrabbit_jar'],
  }
}

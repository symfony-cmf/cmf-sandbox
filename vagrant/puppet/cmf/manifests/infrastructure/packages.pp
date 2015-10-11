class cmf::infrastructure::packages($installs) {
  validate_array($installs)

  class { '::apt': }

  package { $installs:
    ensure => installed,
  }
}

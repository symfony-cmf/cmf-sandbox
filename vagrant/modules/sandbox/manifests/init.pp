class sandbox {
	package {
		["php5-cli", "php5-xdebug", "php5-sqlite", "php5-intl", "php5-curl", "apache2", "libapache2-mod-php5", "openjdk-6-jre"]:
			ensure => latest;
	}

	file {
		"/etc/apache2/sites-enabled/sandbox.lo":
			content => "<VirtualHost *:80>
		DocumentRoot /app/web

		<Directory /app/web>
				AllowOverride All
		</Directory>
</VirtualHost>",
			require => Package["apache2"],
			notify  => Service["apache2"],
	}

	file {
		"/etc/apache2/sites-enabled":
			recurse => true,
			purge => true,
			require => Package["apache2"],
			notify => Service["apache2"],
	}

	file {
		"/etc/php5/conf.d/01-settings.ini":
			content => 'date.timezone = "UTC"
short_open_tag = Off',
			require => Package["php5-cli"],
			notify => Service["apache2"],
	}

	service {
		"apache2":
			ensure => running,
			hasrestart => true,
			hasstatus => true,
			require => Package["apache2"],
	}

	class {
		"jackrabbit":
	}
}
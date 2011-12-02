class jackrabbit::package {
	file {
		"/opt/jackrabbit":
			ensure => directory,
			owner  => vagrant,
			group  => vagrant,
	}

	exec {
		"wget jackrabbit":
			unless => "/usr/bin/test -s /opt/jackrabbit/jackrabbit.jar",
			command => "/usr/bin/wget --no-check-certificate https://s3-eu-west-1.amazonaws.com/patched-jackrabbit/jackrabbit-standalone-2.2.8-jackalope-SNAPSHOT.jar -O /opt/jackrabbit/jackrabbit.jar",
			require => File["/opt/jackrabbit"],
	}

	file {
		"/etc/init.d/jackrabbit":
			mode    => 755,
			source  => "puppet:///modules/jackrabbit/initscript",
	}

	package {
		"curl":
			ensure => latest,
	}
}
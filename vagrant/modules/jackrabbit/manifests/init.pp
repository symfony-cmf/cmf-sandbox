class jackrabbit {
	class {
		"jackrabbit::package":
	}

	service {
		"jackrabbit":
			hasstatus  => true,
			hasrestart => false,
			ensure     => running,
			enable     => true,
			require    => [Class["jackrabbit::package"], Package["openjdk-6-jre"]],
			pattern    => "jackrabbit.jar"
	}
}
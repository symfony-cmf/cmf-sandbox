exec {
	"/usr/bin/apt-get update":
} -> class {
	"sandbox":
}

# workaround for stock lucid32 box
group {
	"puppet":
		ensure => present,
}
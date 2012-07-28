name 'apache2'
description 'apache2'
override_attributes "apache" => { :user => "vagrant", :group => "vagrant" }
run_list "recipe[apache2]", "recipe[apache2::mod_php5]"



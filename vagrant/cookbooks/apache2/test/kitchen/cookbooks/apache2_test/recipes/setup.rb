case node.platform
  when 'ubuntu'
    %w{libxml2 libxml2-dev libxslt1-dev}.each do |pkg|
      package pkg do
        action :install
      end
    end
  when 'centos'
    %w{gcc make ruby-devel libxml2 libxml2-devel libxslt libxslt-devel}.each do |pkg|
      package pkg do
        action :install
      end
    end
end

package "curl" do
  action :install
end

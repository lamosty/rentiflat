set :stage, :production

# Simple Role Syntax
# ==================
#role :app, %w{deploy@example.com}
#role :web, %w{deploy@example.com}
#role :db,  %w{deploy@example.com}

# Extended Server Syntax
# ======================
server '83.167.228.24', user: 'webvision', roles: %w{web app db}

set :deploy_to, -> { "/home/webvision/projects/#{fetch(:application)}" }

set :ssh_options, {:forward_agent => true}

set :wpcli_remote_url, "https://rentiflat.lamosty.com/"
set :wpcli_local_url, "http://dev.rentiflat.lamosty.com/"

# you can set custom ssh options
# it's possible to pass any option but you need to keep in mind that net/ssh understand limited list of options
# you can see them in [net/ssh documentation](http://net-ssh.github.io/net-ssh/classes/Net/SSH.html#method-c-start)
# set it globally
#  set :ssh_options, {
#    keys: %w(~/.ssh/id_rsa),
#    forward_agent: false,
#    auth_methods: %w(password)
#  }

fetch(:default_env).merge!(wp_env: :production)

namespace :permissions do
  task :chmod_uploads do
      on roles(:web) do
          execute "chmod -R 777 #{fetch(:deploy_to)}/shared/web/app/uploads"
      end
  end
end

after 'deploy:published', 'permissions:chmod_uploads'

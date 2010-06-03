require "sequel"

module Hector
  class ExpressionEngineIdentityAdapter
    attr_reader :config_filename, :config, :db
    
    def initialize(config_filename = nil)
      @config_filename ||= Hector.root.join("config/expression_engine.yml")
      load_config
      load_database
    end
    
    def authenticate(username, password)
      
    end
    
    def remember(username, password)
      
    end
    
    def forget(username)
      
    end
    
    def normalize(username)
      username
    end
    
    protected
      def load_config
        ensure_config_file_exists
        @config = YAML.load_file(config_filename) || {}
      end
      
      def load_database
        options = config["database"]
        adapter = options.delete("adapter")
        @db = Sequel.send(adapter, options)
      end
      
      def ensure_config_file_exists
        FileUtils.mkdir_p(File.dirname(config_filename))
        FileUtils.touch(config_filename)
      end
  end
end

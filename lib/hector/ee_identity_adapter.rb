require "digest/sha1"
require "yaml"

begin
  require "sequel"
rescue LoadError => e
  if require "rubygems"
    retry
  else
    raise e
  end
end


module Hector
  class ExpressionEngineIdentityAdapter
    attr_reader :filename, :config, :db
    
    def initialize(filename = nil)
      @filename = filename || Hector.root.join("config/expression_engine.yml")
      load_config
      load_database
    end
    
    def authenticate(username, password)
      yield identity(normalize(username)) == hash(password)
    end
    
    def remember(username, password)
      Hector.logger.warn "Hector cannot manage ExpressionEngine members"
      false
    end
    
    def forget(username)
      Hector.logger.warn "Hector cannot manage ExpressionEngine members"
      false
    end
    
    def normalize(username)
      username.strip.downcase
    end
    
    protected
      def load_config
        @config = YAML.load_file(filename)
      rescue => e
        Hector.logger.fatal "Could not load #{filename} (#{e.class.name})"
        exit 1
      end
      
      def load_database
        @db = Sequel.mysql2(config["database"])
      rescue => e
        Hector.logger.fatal "Could not connect to ExpressionEngine database: #{e.message} (#{e.class.name})"
        exit 1
      end
      
      def identity(username)
        query = { :hector_username => username }
        query[:group_id] = config["groups"] if config["groups"]
        (table(:members).select(:password).first(query) || {})[:password]
      end
      
      def table(name)
        db["#{(config["prefix"] || "exp")}_#{name}"]
      end
      
      def hash(password)
        Digest::SHA1.hexdigest(password)
      end
  end
end

require "yaml"

begin
  require "mysql2"
rescue LoadError => e
  if require "rubygems"
    retry
  else
    raise e
  end
end

module Hector
  class ExpressionEngineIdentityAdapter
    attr_reader :config, :database, :filename
    
    def initialize(filename = nil)
      @filename = filename || Hector.root.join("config/expression_engine.yml")
      load_config
      load_database
    end
    
    def authenticate(username, password)
      load_config
      yield normalize(identity(normalize(username))) == normalize(password)
    end
    
    def remember(username, password)
      Hector.logger.warn "Hector cannot manage ExpressionEngine members"
      false
    end
    
    def forget(username)
      Hector.logger.warn "Hector cannot manage ExpressionEngine members"
      false
    end
    
    protected
      def load_config
        @config = YAML.load_file(filename)
      rescue => e
        Hector.logger.fatal "#{e.class.name} while loading #{filename}: #{e.message}"
        exit 1
      end
      
      def load_database
        @database = Mysql2::Client.new(connection_params)
      rescue => e
        Hector.logger.fatal "#{e.class.name} while connecting to ExpressionEngine database: #{e.message}"
        exit 1
      end
      
      def connection_params
        config["database"].inject({}) do |hash, (key, value)|
          hash[key.to_sym] = value; hash
        end
      end
      
      def identity(username)
        sql = "SELECT hector_password FROM #{table(:members)} WHERE hector_username = '#{escape(username)}'"
        sql << " AND group_id IN (#{config["groups"].join(",")})" if config["groups"]
        (query(sql).first || {})["hector_password"]
      end
      
      def table(name)
        "#{config["prefix"] || "exp"}_#{name}"
      end
      
      def escape(input)
        database.escape(input)
      end
      
      def query(sql)
        Hector.logger.debug(sql.inspect)
        database.query(sql)
      end
      
      def normalize(input)
        input.to_s.strip.downcase if input
      end
  end
end

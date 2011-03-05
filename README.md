hector-identity-ee is an [ExpressionEngine](http://expressionengine.com/) 1.7 identity adapter for Hector.

### Installation and usage

Install hector-auth-ee with RubyGems and navigate to your server:

    $ gem install -r hector-identity-ee
    ...
    $ cd myserver.hect

Create `config/expression_engine.yml`:

    database:
      adapter: mysql
      host: mysql.myserver.com
      user: hector
      password: s3cr3t
      database: expressionengine

You can connect Hector to hector-identity-ee by modifying `init.rb` in your server's directory:

    require "hector/ee_identity_adapter"
    Hector::Identity.adapter = ExpressionEngineIdentityAdapter.new

### License <small>(MIT)</small>

<small>Copyright © 2011 Ross Paffett.</small>

<small>Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:</small>

<small>The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.</small>

<small>THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.</small>

hector-identity-ee is an [ExpressionEngine](http://expressionengine.com/) 2.x identity adapter for [Hector](http://github.com/sstephenson/hector).

### Installation and usage

Installing and configuring hector-identity-ee is simple.

#### ExpressionEngine

Clone hector-identity-ee to `system/expressionengine/third_party/hector`, then enable it by navigating to Add-Ons > Extensions.

The extension will generate safe usernames and passwords in the `exp_members.hector_username` and `exp_members.hector_password` columns for all existing and future members. A user should log in to Hector using his or her `hector_username` and `hector_password`.

You can display the currently logged in user's Hector credentials in a template using `{exp:hector:username}` and `{exp:hector:password}`. To display a particular member's Hector username or password, add a `member_id` parameter: `{exp:hector:username member_id="16"}`.

#### Hector

Install the [hector-identity-ee gem](http://rubygems.org/gems/hector-identity-ee) and navigate to your server:

    $ gem install hector-identity-ee
    ...
    $ cd myserver.hect

Create `config/expression_engine.yml`:

    database:
      host: mysql.myserver.com
      username: hector
      password: s3cr3t
      database: expression_engine
      prefix: exp # optional, defaults to "exp"

Optionally limit logins to members of specific groups by adding a `groups` key:

    groups:
      - 1 # Admins
      - 6 # Moderators

You can connect Hector to hector-identity-ee by modifying `init.rb` in your server's directory:

    require "hector/ee_identity_adapter"
    Hector::Identity.adapter = Hector::ExpressionEngineIdentityAdapter.new

### License <small>(MIT)</small>

<small>Copyright © 2011 Ross Paffett.</small>

<small>Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:</small>

<small>The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.</small>

<small>THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.</small>

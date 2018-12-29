#!/usr/bin/env bash

# 1. Clone complete SVN repository to separate directory
svn co https://plugins.svn.wordpress.org/$SVN_REPOSITORY ../svn


ls -las ./
pwd

# 2. Copy git repository contents to SNV trunk/ directory
cp -R ./* ../svn/trunk/

# 3. Switch to SVN repository
cd ../svn/trunk/

# 4. Move assets/ to SVN /assets/
mv ./assets/ ../assets/

# 5. Clean up unnecessary files
rm -rf .git/
rm -rf deploy/
rm .travis.yml

# 6. Go to SVN repository root
cd ../

# 8. Push SVN tag
svn ci  --message "Releasing" \
        --username $SVN_USERNAME \
        --password $SVN_PASSWORD \
        --non-interactive
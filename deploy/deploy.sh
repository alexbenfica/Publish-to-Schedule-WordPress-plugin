#!/usr/bin/env bash

# 1. Clone complete SVN repository to separate directory
svn co https://plugins.svn.wordpress.org/$SVN_REPOSITORY ../svn

# echo 'inside initial dir'
# pwd
# ls -las


# 2. Copy git repository contents to SNV trunk/ directory
cp -R ./* ../svn/trunk/

# 3. Switch to SVN repository
cd ../svn/trunk/

# echo 'inside SVN trunk'
# pwd
# ls -las

# 4. Move assets/ to SVN /assets/
mv ./assets/* ../assets/

# 5. Clean up unnecessary files
rm -rf .git/
rm -rf deploy/
rm -rf build/
rm -rf nbproject/
rm .travis.yml

echo 'SVN trunk - after removing files and folders'
pwd
ls -las

# 6. Go to SVN repository root
cd ../

echo 'SVN root'
pwd
ls -las

# 5. Clean up unnecessary files
svn delete deploy
svn delete build
svn delete nbproject

# 7. Add all new files
#svn add --force * --auto-props --parents --depth infinity -q

# 8. Push SVN tag
#svn ci --message "Releasing (Travis build: $TRAVIS_BUILD_ID )" --username $SVN_USERNAME --password $SVN_PASSWORD --non-interactive
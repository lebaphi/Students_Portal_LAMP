# Development
Development for Co Name

# REQUIREMENTS
1. Nodejs - https://nodejs.org/en/ ( check install success: `node -v`)
2. javascript-obfuscator - `npm install -g javascript-obfuscator`
3. Install Apache Tomcat at https://tomcat.apache.org

# INSTALLATION/UPDATE
## Manual
1. GIT PULL ORIGIN <BRANCH>
2. php production.build.php
3. mv files from production to www root folder
4. Create/update .env file in www root folder 

## Shell script
1. Copy `deploy.production.sh` to new server
2. Set value for `ROOT_DIR`: directory to checkout source code, `ARCHIVE_DIR`: directory to archive old production, `PRDS_DIR`: directory to deploy new production 
3. Open terminal and run `chmod +x deploy.production.sh`
4. `./deploy.production.sh`


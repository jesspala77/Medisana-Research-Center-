Add these GitHub repository secrets before enabling the deploy workflow:

- DEPLOY_HOST
- DEPLOY_USER
- DEPLOY_SSH_KEY
- DEPLOY_PORT (optional; default 22)
- DEPLOY_PATH

Example values:
- DEPLOY_HOST: your-server-ip-or-domain
- DEPLOY_USER: deploy
- DEPLOY_SSH_KEY: private SSH key content
- DEPLOY_PORT: 22
- DEPLOY_PATH: /var/www/synergia

Notes:
- The deploy user must be able to git pull in /var/www/synergia and run the deployment script.
- Your server must already have the Laravel app checked out, dependencies installed, and a valid .env file in place.
- The script expects the app folder to already exist and be ready for deployment.
